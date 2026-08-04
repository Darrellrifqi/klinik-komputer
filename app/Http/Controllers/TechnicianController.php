<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function index()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate');
        } catch (\Exception $e) {}

        $activeTickets = Ticket::with(['customer', 'creator'])
            ->whereNotIn('status', ['done', 'cancelled'])
            ->orderBy('created_at', 'asc')
            ->get();

        $doneTickets = Ticket::with(['customer', 'technician'])
            ->whereIn('status', ['done', 'cancelled'])
            ->orderBy('updated_at', 'desc')
            ->take(20)->get();

        return view('dashboard.teknisi.index', compact('activeTickets', 'doneTickets'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['action' => 'required|in:start_check,finish_check,set_menunggu_part,set_pengerjaan_unit,rma,done,cancel']);

        $oldStatus = $ticket->status;
        $notes = '';

        switch ($request->action) {
            case 'start_check':
                $request->validate([
                    'start_check_date' => 'required|date',
                    'pic_name'         => 'required|string|max:255',
                ]);
                $ticket->update([
                    'status'          => 'checking',
                    'start_check_date'=> $request->start_check_date,
                    'pic_name'        => $request->pic_name,
                    'assigned_to'     => auth()->id(),
                ]);
                $notes = "Mulai pengecekan. PJ: {$request->pic_name}. Tanggal: {$request->start_check_date}";
                break;

            case 'finish_check':
                $request->validate([
                    'components_issue' => 'required|string',
                    'cause'            => 'required|string',
                ]);
                $components = array_filter(array_map('trim', explode("\n", $request->components_issue)));
                $ticket->update([
                    'status'           => 'konfirmasi_user',
                    'components_issue' => $components,
                    'cause'            => $request->cause,
                ]);
                $notes = "Selesai pengecekan teknisi. Komponen: " . implode(', ', $components) . ". Penyebab: {$request->cause}";
                break;

            case 'set_menunggu_part':
                $ticket->update([
                    'status'     => 'proses_service',
                    'sub_status' => 'menunggu_part',
                ]);
                $notes = 'Teknisi memperbarui progres: Menunggu Part (Sparepart).';
                break;

            case 'set_pengerjaan_unit':
                $ticket->update([
                    'status'     => 'proses_service',
                    'sub_status' => 'pengerjaan_unit',
                ]);
                $notes = 'Teknisi memperbarui progres: Pengerjaan Unit.';
                break;

            case 'rma':
                $ticket->update([
                    'status'     => 'proses_service',
                    'sub_status' => 'klaim_garansi',
                ]);
                $notes = 'Unit masuk proses RMA (klaim garansi).';
                break;

            case 'done':
                $ticket->update([
                    'status'     => 'siap_diambil',
                    'sub_status' => null,
                ]);
                $notes = 'Unit selesai diperbaiki oleh teknisi dan status menjadi Siap Diambil.';
                break;

            case 'cancel':
                $ticket->update(['status' => 'cancelled']);
                $notes = 'Tiket dibatalkan oleh teknisi.';
                break;
        }

        TicketHistory::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $ticket->fresh()->status,
            'notes'      => $notes,
        ]);

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'technician', 'creator', 'histories.user']);
        return view('dashboard.teknisi.show', compact('ticket'));
    }
}
