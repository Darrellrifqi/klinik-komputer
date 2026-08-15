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

        $allTickets = Ticket::with(['customer', 'laptopKit', 'creator', 'technician'])
            ->orderBy('created_at', 'asc')
            ->get();

        $groupedTickets = [
            'unit_received' => [
                'title' => 'Antrian Servis (Siap Cek Perangkat)',
                'color' => '#0284c7',
                'tickets' => $allTickets->where('status', 'unit_received')
            ],
            'checking' => [
                'title' => 'Pengecekan Teknisi',
                'color' => '#3b82f6',
                'tickets' => $allTickets->where('status', 'checking')
            ],
            'konfirmasi_user' => [
                'title' => 'Konfirmasi User (Menunggu CS/Customer)',
                'color' => '#8b5cf6',
                'tickets' => $allTickets->whereIn('status', ['konfirmasi_user', 'checked'])
            ],
            'menunggu_part' => [
                'title' => 'Menunggu Part',
                'color' => '#d97706',
                'tickets' => $allTickets->where('status', 'menunggu_part')
            ],
            'proses_service' => [
                'title' => 'Proses Service / Pengerjaan Unit',
                'color' => '#64748b',
                'tickets' => $allTickets->whereIn('status', ['proses_service', 'rma', 'in_service'])
            ],
            'done' => [
                'title' => 'Selesai Servis & Siap Diambil',
                'color' => '#0d9488',
                'tickets' => $allTickets->whereIn('status', ['done', 'siap_diambil'])
            ],
        ];

        return view('dashboard.teknisi.index', compact('groupedTickets', 'allTickets'));
    }

    // Teknisi Dashboard - History Servis (Sudah Diambil & Dibatalkan)
    public function history(Request $request)
    {
        $query = Ticket::with(['customer', 'laptopKit', 'creator', 'technician'])
            ->whereIn('status', ['sudah_diambil', 'taken', 'cancelled']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('damage_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'sudah_diambil') {
                $query->whereIn('status', ['sudah_diambil', 'taken']);
            } elseif ($request->status === 'cancelled') {
                $query->where('status', 'cancelled');
            }
        }

        $tickets = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total'         => Ticket::whereIn('status', ['sudah_diambil', 'taken', 'cancelled'])->count(),
            'sudah_diambil' => Ticket::whereIn('status', ['sudah_diambil', 'taken'])->count(),
            'cancelled'     => Ticket::where('status', 'cancelled')->count(),
        ];

        return view('dashboard.teknisi.history', compact('tickets', 'stats'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['action' => 'required|in:start_check,finish_check,set_menunggu_part,set_pengerjaan_unit,rma,done,cancel']);

        $oldStatus = $ticket->status;
        $notes = '';

        switch ($request->action) {
            case 'start_check':
                if ($ticket->status === 'waiting') {
                    return redirect()->back()->with('error', 'Pemeriksaan perangkat belum dapat dimulai karena unit belum diserahkan oleh customer ke kantor (Status masih Menunggu Unit).');
                }
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
                    'status'     => 'menunggu_part',
                    'sub_status' => null,
                ]);
                $notes = 'Teknisi memperbarui status: Menunggu Part (Sparepart).';
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
