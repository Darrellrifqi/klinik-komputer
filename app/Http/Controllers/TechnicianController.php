<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function index()
    {
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
        $request->validate(['action' => 'required|in:start_check,finish_check,rma,done,cancel']);

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
                    'estimated_cost'   => 'nullable|numeric|min:0',
                ]);
                $components = array_filter(array_map('trim', explode("\n", $request->components_issue)));
                $ticket->update([
                    'status'           => 'checked',
                    'components_issue' => $components,
                    'cause'            => $request->cause,
                    'estimated_cost'   => $request->estimated_cost,
                ]);
                $notes = "Selesai pengecekan. Komponen: " . implode(', ', $components) . ". Estimasi: Rp " . number_format($request->estimated_cost ?? 0, 0, ',', '.');
                break;

            case 'rma':
                $ticket->update(['status' => 'rma']);
                $notes = 'Unit masuk proses RMA (klaim garansi).';
                break;

            case 'done':
                $ticket->update(['status' => 'done']);
                $notes = 'Unit selesai diperbaiki dan siap diambil.';
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

        return redirect()->route('dashboard.teknisi')->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'technician', 'creator', 'histories.user']);
        return view('dashboard.teknisi.show', compact('ticket'));
    }
}
