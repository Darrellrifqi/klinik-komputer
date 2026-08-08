<?php

namespace App\Http\Controllers;

use App\Models\ProcurementOrder;
use App\Models\ProcurementLaptopKit;
use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // CS Dashboard - Tiket Servis
    public function index()
    {
        // Cleanup leftover sub_status for tickets that are done, ready for pickup, picked up, or not in active sub-status stages
        Ticket::whereNotIn('status', ['konfirmasi_user', 'checked', 'proses_service', 'rma', 'in_service'])
            ->whereNotNull('sub_status')
            ->update(['sub_status' => null]);

        $allTickets = Ticket::with(['customer', 'laptopKit', 'technician', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedTickets = [
            'waiting' => [
                'title' => 'Menunggu Unit (Booking Online)',
                'color' => '#eab308',
                'tickets' => $allTickets->where('status', 'waiting')
            ],
            'unit_received' => [
                'title' => 'Antrian Servis',
                'color' => '#0284c7',
                'tickets' => $allTickets->where('status', 'unit_received')
            ],
            'checking' => [
                'title' => 'Pengecekan Teknisi',
                'color' => '#3b82f6',
                'tickets' => $allTickets->where('status', 'checking')
            ],
            'konfirmasi_user' => [
                'title' => 'Konfirmasi User (Pembelian Part / Garansi)',
                'color' => '#8b5cf6',
                'tickets' => $allTickets->whereIn('status', ['konfirmasi_user', 'checked'])
            ],
            'proses_service' => [
                'title' => 'Proses Service / Pengerjaan Unit',
                'color' => '#64748b',
                'tickets' => $allTickets->whereIn('status', ['proses_service', 'rma', 'in_service'])
            ],
            'siap_diambil' => [
                'title' => 'Selesai Servis & Siap Diambil',
                'color' => '#0d9488',
                'tickets' => $allTickets->whereIn('status', ['done', 'siap_diambil'])
            ],
        ];

        return view('dashboard.cs.index', compact('groupedTickets', 'allTickets'));
    }

    // CS Dashboard - History Servis (Sudah Diambil & Dibatalkan)
    public function history(\Illuminate\Http\Request $request)
    {
        $query = Ticket::with(['customer', 'laptopKit', 'technician', 'creator'])
            ->whereIn('status', ['sudah_diambil', 'taken', 'cancelled']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('airtable_service_number', 'like', "%{$search}%");
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

        return view('dashboard.cs.history', compact('tickets', 'stats'));
    }

    // CS Dashboard - Tiket Pengadaan Sekolah
    public function procurementIndex()
    {
        $procurementOrders = ProcurementOrder::orderBy('created_at', 'desc')
            ->paginate(15);

        $procurementStats = [
            'total'      => ProcurementOrder::count(),
            'pending'    => ProcurementOrder::whereIn('status', ['pending', 'diproses'])->count(),
            'dibayar'    => ProcurementOrder::where('status', 'dibayar')->count(),
            'dibatalkan' => ProcurementOrder::where('status', 'dibatalkan')->count(),
        ];

        return view('dashboard.cs.procurement_index', compact('procurementOrders', 'procurementStats'));
    }

    public function create()
    {
        return view('dashboard.cs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'      => 'required|string|max:255',
            'customer_phone'     => 'required|string|max:20',
            'unit_type'          => 'required|in:laptop,desktop,printer,other',
            'brand'              => 'required|string|max:100',
            'model'              => 'required|string|max:100',
            'damage_description' => 'required|string|min:5',
            'member_id_input'    => 'nullable|string',
            'is_tune_up'         => 'nullable|boolean',
            'is_os_install'      => 'nullable|boolean',
        ]);

        $customerId = null;
        $kitId      = null;

        if ($request->filled('member_id_input')) {
            $input = trim($request->member_id_input);

            // 1. Try NIK → Member Umum
            $regularUser = \App\Models\User::where('nik', $input)->where('role', 'customer')->first();
            if ($regularUser) {
                $customerId = $regularUser->id;
            } else {
                // 2. Try SN / member_id → Member Pengadaan
                $kit = ProcurementLaptopKit::where('member_id', $input)->first();
                if ($kit) {
                    $kitId      = $kit->id;
                    $customerId = $kit->customer_id;
                }
            }
        }

        $ticket = Ticket::create([
            'ticket_number'      => Ticket::generateTicketNumber(),
            'queue_number'       => Ticket::generateQueueNumber(),
            'customer_name'      => $request->customer_name,
            'customer_phone'     => $request->customer_phone,
            'unit_type'          => $request->unit_type,
            'brand'              => $request->brand,
            'model'              => $request->model,
            'damage_description' => $request->damage_description,
            'status'             => 'waiting',
            'notes'              => $request->notes,
            'created_by'         => auth()->id(),
            'laptop_kit_id'      => $kitId,
            'customer_id'        => $customerId,
            'is_tune_up'         => $request->has('is_tune_up') ? (bool)$request->is_tune_up : false,
            'is_os_install'      => $request->has('is_os_install') ? (bool)$request->is_os_install : false,
        ]);

        TicketHistory::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => auth()->id(),
            'old_status' => null,
            'new_status' => 'waiting',
            'notes'      => 'Tiket dibuat oleh CS: ' . auth()->user()->name,
        ]);

        return redirect()->route('dashboard.cs')->with('success', "Tiket {$ticket->ticket_number} berhasil dibuat. Nomor antrian: #{$ticket->queue_number}");
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'technician', 'creator', 'histories.user']);
        return view('dashboard.cs.show', compact('ticket'));
    }

    public function showProcurement(ProcurementOrder $order)
    {
        $order->load(['kits.components', 'kits.customer']);
        return view('dashboard.cs.procurement-show', compact('order'));
    }

    public function updateProcurement(Request $request, ProcurementOrder $order)
    {
        $request->validate([
            'status'             => 'required|in:pending,diproses,siap_kirim,konfirmasi_harga,menunggu_pembayaran,dibayar,diproses_pengiriman,selesai,dibatalkan',
            'cs_notes'           => 'nullable|string|max:500',
            'substep_penawaran'  => 'nullable|boolean',
            'substep_invoice'    => 'nullable|boolean',
            'substep_pembayaran' => 'nullable|boolean',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
        ]);

        $newSubstepPenawaran = $request->has('substep_penawaran') || ($order->quoted_price > 0);
        $newSubstepInvoice   = $request->has('substep_invoice');
        $newSubstepPembayaran= $request->has('substep_pembayaran');

        // Stage progression check: Block moving to next stages if any sub-step is missing
        $nextStages = ['siap_kirim', 'diproses_pengiriman', 'selesai'];
        if (in_array($request->status, $nextStages)) {
            if (!$newSubstepPenawaran || !$newSubstepInvoice || !$newSubstepPembayaran) {
                $missing = [];
                if (!$newSubstepPenawaran) $missing[] = 'Penawaran';
                if (!$newSubstepInvoice)   $missing[] = 'Proses Invoice';
                if (!$newSubstepPembayaran)$missing[] = 'Pembayaran';

                return back()->withErrors([
                    'status' => 'Pengadaan belum dapat dipindahkan ke tahap selanjutnya karena sub-tahap Unit Diproses belum lengkap: ' . implode(', ', $missing) . '.'
                ])->withInput();
            }
        }

        $order->update([
            'status'             => $request->status,
            'substep_penawaran'  => $newSubstepPenawaran,
            'substep_invoice'    => $newSubstepInvoice,
            'substep_pembayaran' => $newSubstepPembayaran,
            // Append CS notes to admin_notes so admin can see the history
            'admin_notes'        => $order->admin_notes
                ? $order->admin_notes . "\n[CS - " . now()->format('d M Y H:i') . "] " . ($request->cs_notes ?? 'Status diperbarui oleh CS.')
                : '[CS - ' . now()->format('d M Y H:i') . '] ' . ($request->cs_notes ?? 'Status diperbarui oleh CS.'),
        ]);

        return back()->with('success', 'Status pengadaan berhasil diperbarui menjadi: ' . $order->fresh()->status_label);
    }

    public function updateTicketStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status'                  => 'required|in:waiting,unit_received,checking,checked,konfirmasi_user,proses_service,rma,done,siap_diambil,sudah_diambil,taken,cancelled',
            'sub_status'              => 'nullable|string|in:pembelian_part,klaim_garansi,menunggu_part,pengerjaan_unit',
            'airtable_service_number' => 'nullable|string|max:100',
            'pic_name'                => 'nullable|string|max:255',
            'start_check_date'        => 'nullable|date',
            'components_issue'        => 'nullable|string',
            'cause'                   => 'nullable|string',
            'estimated_cost'          => 'nullable|numeric|min:0',
            'notes'                   => 'nullable|string|max:500',
            'is_tune_up'              => 'nullable|boolean',
            'is_os_install'           => 'nullable|boolean',
            'member_id_input'         => 'nullable|string',
        ], [
            'status.required'  => 'Status tiket wajib dipilih.',
            'status.in'        => 'Pilihan status tidak valid.',
        ]);

        // Ensure sub_status column exists in database
        if (!\Illuminate\Support\Facades\Schema::hasColumn('tickets', 'sub_status')) {
            try {
                \Illuminate\Support\Facades\Schema::table('tickets', function ($table) {
                    $table->string('sub_status')->nullable()->after('status');
                });
            } catch (\Exception $e) {
                try {
                    \Illuminate\Support\Facades\Artisan::call('migrate');
                } catch (\Exception $ex) {}
            }
        }

        $oldStatus = $ticket->status;
        $activeProcessingStatuses = ['konfirmasi_user', 'checked', 'proses_service', 'rma', 'in_service'];
        $newSubStatus = in_array($request->status, $activeProcessingStatuses) ? $request->sub_status : null;

        $updateData = [
            'status'        => $request->status,
            'sub_status'    => $newSubStatus,
            'is_tune_up'    => $request->has('is_tune_up') ? (bool)$request->is_tune_up : false,
            'is_os_install' => $request->has('is_os_install') ? (bool)$request->is_os_install : false,
        ];

        if ($request->filled('member_id_input')) {
            $input = trim($request->member_id_input);

            // 1. Try NIK → Member Umum
            $regularUser = \App\Models\User::where('nik', $input)->where('role', 'customer')->first();
            if ($regularUser) {
                $updateData['customer_id']  = $regularUser->id;
                $updateData['laptop_kit_id'] = null; // Umum: no specific kit
            } else {
                // 2. Try SN → Member Pengadaan
                $kit = ProcurementLaptopKit::where('member_id', $input)->first();
                if ($kit) {
                    $updateData['laptop_kit_id'] = $kit->id;
                    $updateData['customer_id']   = $kit->customer_id;
                }
            }
        }

        if ($request->has('airtable_service_number')) {
            $updateData['airtable_service_number'] = $request->airtable_service_number;
        }

        if ($request->filled('pic_name')) {
            $updateData['pic_name'] = $request->pic_name;
        }
        if ($request->filled('start_check_date')) {
            $updateData['start_check_date'] = $request->start_check_date;
        }
        if ($request->filled('components_issue')) {
            $components = array_filter(array_map('trim', explode("\n", $request->components_issue)));
            $updateData['components_issue'] = array_values($components);
        }
        if ($request->filled('cause')) {
            $updateData['cause'] = $request->cause;
        }
        if ($request->filled('estimated_cost')) {
            $updateData['estimated_cost'] = $request->estimated_cost;
        }

        $ticket->update($updateData);

        TicketHistory::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'notes'      => $request->notes ?: 'Status diperbarui oleh CS: ' . auth()->user()->name,
        ]);

        return back()->with('success', 'Status tracking tiket berhasil diperbarui menjadi: ' . $ticket->fresh()->status_label);
    }

    // Customer Dashboard
    public function customerIndex()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate');
        } catch (\Exception $e) {
            // Ignore if already migrated
        }

        $user    = auth()->user();
        $tickets = Ticket::where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $kits = ProcurementLaptopKit::with(['order', 'components'])
            ->where('customer_id', $user->id)
            ->get();

        // Tune-up quota is per-user (total across all units), within their membership year window
        $tuneUpPeriod    = $user->tuneUpPeriod();
        $tuneUpCount     = $user->tuneUpCount();
        $tuneUpRemaining = $user->tuneUpRemaining();
        $tuneUpResetDate = $tuneUpPeriod['end'];

        return view('dashboard.customer.index', compact(
            'tickets', 'kits',
            'tuneUpCount', 'tuneUpRemaining', 'tuneUpResetDate'
        ));
    }

    public function destroy(Ticket $ticket)
    {
        $ticketNumber = $ticket->ticket_number;
        $ticket->histories()->delete();
        $ticket->delete();

        return redirect()->route('dashboard.cs')->with('success', "Tiket {$ticketNumber} berhasil dihapus secara permanen dari database.");
    }
}
