<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Services\AirtableService;
use Illuminate\Console\Command;

class TestAirtableSync extends Command
{
    protected $signature = 'airtable:sync {ticket_id?} {--all : Sync all active tickets}';
    protected $description = 'Synchronize tickets from local database to Airtable';

    public function handle(AirtableService $airtableService)
    {
        $baseId = config('services.airtable.base_id') ?: env('AIRTABLE_BASE_ID');
        $this->info("🚀 Memulai sinkronisasi ke Airtable Base: {$baseId}");

        if ($this->option('all')) {
            $tickets = Ticket::latest()->limit(10)->get();
            $this->info("Mengirim " . $tickets->count() . " tiket terbaru ke Airtable...");
            
            $successCount = 0;
            foreach ($tickets as $t) {
                $res = $airtableService->syncTicket($t);
                if ($res && isset($res['id'])) {
                    $this->line("✅ [{$t->ticket_number}] {$t->customer_name} -> Masuk ke Airtable (ID: {$res['id']})");
                    $successCount++;
                } else {
                    $this->warn("⚠️ [{$t->ticket_number}] Gagal kirim ke Airtable.");
                }
            }
            $this->info("Selesai! {$successCount}/{$tickets->count()} tiket berhasil disinkronisasi ke Airtable.");
            return 0;
        }

        $ticketId = $this->argument('ticket_id');
        $ticket   = $ticketId ? Ticket::find($ticketId) : Ticket::latest()->first();

        if (!$ticket) {
            $ticket = new Ticket();
            $ticket->ticket_number      = 'SRV-TEST-' . date('Ymd-His');
            $ticket->customer_name      = 'Darrell (Test Sync Localhost)';
            $ticket->customer_phone     = '081390727420';
            $ticket->brand              = 'Axioo';
            $ticket->model              = 'Pongo 760';
            $ticket->status             = 'checking';
            $ticket->damage_description = 'Uji coba sinkronisasi Web KK ke Airtable';
        }

        $this->info("Mengirim tiket [{$ticket->ticket_number}]...");
        $res = $airtableService->syncTicket($ticket);

        if ($res && isset($res['id'])) {
            $this->info("🎉 SUKSES! Tiket [{$ticket->ticket_number}] berhasil masuk ke Airtable!");
            $this->info("Airtable Record ID: " . $res['id']);
            $this->info("Silakan cek browser Airtable Anda sekarang!");
        } else {
            $this->error("❌ Gagal mengirim ke Airtable. Pastikan koneksi internet aktif.");
        }

        return 0;
    }
}
