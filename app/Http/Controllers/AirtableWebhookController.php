<?php

namespace App\Http\Controllers;

use App\Services\AirtableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AirtableWebhookController extends Controller
{
    protected AirtableService $airtableService;

    public function __construct(AirtableService $airtableService)
    {
        $this->airtableService = $airtableService;
    }

    /**
     * Handle incoming webhook from Airtable
     */
    public function handle(Request $request)
    {
        Log::info('Airtable Webhook Received:', $request->all());

        $payload = $request->all();

        // Check if payload is nested in fields (standard Airtable webhook format)
        if (isset($payload['fields'])) {
            $payload = array_merge($payload, $payload['fields']);
        }

        $success = $this->airtableService->handleWebhook($payload);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket updated from Airtable successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Webhook received but ticket was not updated or not found'
        ], 200);
    }
}
