<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AirtableService
{
    protected ?string $apiKey;
    protected ?string $baseId;
    protected string $tableName;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey    = config('services.airtable.key') ?: env('AIRTABLE_API_KEY');
        $this->baseId    = config('services.airtable.base_id') ?: env('AIRTABLE_BASE_ID');
        $this->tableName = config('services.airtable.table_name') ?: env('AIRTABLE_TABLE_NAME', 'Tickets');
        $this->baseUrl   = "https://api.airtable.com/v0/{$this->baseId}/" . rawurlencode($this->tableName);
    }

    /**
     * Helper to get HTTP client with Bearer token
     */
    protected function client()
    {
        return Http::withToken($this->apiKey)
            ->timeout(12)
            ->acceptJson();
    }

    /**
     * Normalize status to match Airtable options
     */
    protected function mapStatusToAirtable(?string $status): string
    {
        return match ($status) {
            'waiting', 'booking_online'             => 'waiting',
            'checking', 'antrian_servis', 'checked' => 'checking',
            'in_service', 'rma', 'proses_service'   => 'in_service',
            'done', 'siap_diambil'                  => 'done',
            'sudah_diambil', 'taken'                => 'sudah_diambil',
            default                                 => $status ?: 'waiting',
        };
    }

    /**
     * Create or update record in Airtable for a given Ticket
     */
    public function syncTicket(Ticket $ticket): ?array
    {
        if (empty($this->apiKey) || empty($this->baseId)) {
            Log::warning('Airtable Sync: Credentials not configured in .env or config/services.php');
            return null;
        }

        try {
            $device = trim(($ticket->brand ?? '') . ' ' . ($ticket->model ?? ''));
            if (empty($device)) {
                $device = '-';
            }

            // Build base payload with standard fields
            $fields = [
                'Customer Name'    => (string) ($ticket->customer_name ?: ($ticket->customer?->name ?: 'Customer')),
                'Phone'            => (string) ($ticket->customer_phone ?: ($ticket->customer?->phone ?: '-')),
                'Unit Device'      => (string) $device,
                'Status'           => (string) $this->mapStatusToAirtable($ticket->status),
                'Technician Notes' => (string) ($ticket->technician_notes ?: ($ticket->damage_description ?: '-')),
            ];

            // 1. First attempt to find existing record or check column name (Ticket Number vs Tickets Number)
            $ticketNumber = (string) $ticket->ticket_number;
            
            // Try searching with 'Tickets Number' first (from current screenshot), then 'Ticket Number'
            $searchFormula = rawurlencode("OR({Tickets Number} = '{$ticketNumber}', {Ticket Number} = '{$ticketNumber}')");
            $searchRes = $this->client()->get("{$this->baseUrl}?filterByFormula={$searchFormula}&maxRecords=1");

            $recordId = null;
            $primaryKey = 'Tickets Number'; // Default to match current user's Airtable column

            if ($searchRes->successful()) {
                $records = $searchRes->json('records') ?? [];
                if (!empty($records)) {
                    $recordId = $records[0]['id'];
                    // Detect which column was used in existing record
                    if (isset($records[0]['fields']['Ticket Number'])) {
                        $primaryKey = 'Ticket Number';
                    }
                }
            }

            $fields[$primaryKey] = $ticketNumber;

            if ($recordId) {
                // Update existing record (PATCH)
                $updateRes = $this->client()->patch("{$this->baseUrl}/{$recordId}", [
                    'fields'   => $fields,
                    'typecast' => true
                ]);

                if ($updateRes->successful()) {
                    Log::info("Airtable: Updated record [{$recordId}] for Ticket {$ticketNumber}");
                    return $updateRes->json();
                } else {
                    Log::error("Airtable Update Error: " . $updateRes->body());
                }
            } else {
                // Create new record (POST)
                $createRes = $this->client()->post($this->baseUrl, [
                    'fields'   => $fields,
                    'typecast' => true
                ]);

                // If failed with unknown field, fallback with 'Ticket Number' (singular)
                if ($createRes->failed() && str_contains($createRes->body(), 'UNKNOWN_FIELD_NAME')) {
                    unset($fields['Tickets Number']);
                    $fields['Ticket Number'] = $ticketNumber;

                    $createRes = $this->client()->post($this->baseUrl, [
                        'fields'   => $fields,
                        'typecast' => true
                    ]);
                }

                if ($createRes->successful()) {
                    Log::info("Airtable: Created new record for Ticket {$ticketNumber}");
                    return $createRes->json();
                } else {
                    Log::error("Airtable Create Error: " . $createRes->body());
                }
            }
        } catch (\Throwable $e) {
            Log::error("Airtable Sync Exception: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Process incoming Webhook from Airtable (Airtable -> Web)
     */
    public function handleWebhook(array $payload): bool
    {
        Log::info('Airtable Incoming Webhook Payload:', $payload);

        $ticketNumber = $payload['ticket_number'] 
            ?? $payload['Ticket Number'] 
            ?? $payload['Tickets Number'] 
            ?? null;

        $status = $payload['status'] 
            ?? $payload['Status'] 
            ?? null;

        $notes = $payload['technician_notes'] 
            ?? $payload['Technician Notes'] 
            ?? null;

        if (!$ticketNumber) {
            Log::warning('Airtable Webhook: Missing Ticket Number in payload.');
            return false;
        }

        $ticket = Ticket::where('ticket_number', $ticketNumber)
            ->orWhere('airtable_service_number', $ticketNumber)
            ->first();

        if (!$ticket) {
            Log::warning("Airtable Webhook: Ticket {$ticketNumber} not found in database.");
            return false;
        }

        $updateData = [];

        if ($status) {
            $updateData['status'] = $this->mapStatusFromAirtable($status);
        }

        if ($notes !== null) {
            $updateData['technician_notes'] = $notes;
        }

        if (!empty($updateData)) {
            $ticket->update($updateData);
            Log::info("Airtable Webhook: Ticket {$ticket->ticket_number} updated successfully.", $updateData);
            return true;
        }

        return false;
    }

    /**
     * Map Airtable status back to database Ticket status
     */
    protected function mapStatusFromAirtable(string $airtableStatus): string
    {
        return match (strtolower(trim($airtableStatus))) {
            'waiting'       => 'waiting',
            'checking'      => 'checking',
            'in_service'    => 'proses_service',
            'done'          => 'done',
            'sudah_diambil' => 'sudah_diambil',
            default         => $airtableStatus,
        };
    }
}
