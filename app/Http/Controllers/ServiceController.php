<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function booking()
    {
        // Only Member Pengadaan (is_regular = false) has a specific registered unit (SN-based)
        // Member Umum can bring any laptop so no dropdown needed
        $registeredKits = collect();
        if (auth()->check()) {
            $registeredKits = \App\Models\ProcurementLaptopKit::where('customer_id', auth()->id())
                ->where('is_regular', false) // only school/pengadaan kits
                ->get();
        }
        return view('service.booking', compact('registeredKits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_phone'    => 'required|string|max:20',
            'unit_type'         => 'required|in:laptop,desktop,printer,other',
            'brand'             => 'required|string|max:100',
            'model'             => 'required|string|max:100',
            'damage_description'=> 'required|string|min:10',
            'dropoff_schedule'  => 'required|string|max:255',
            'laptop_kit_id'     => 'nullable|exists:procurement_laptop_kits,id',
            'is_tune_up'        => 'nullable|boolean',
        ], [
            'customer_name.required'      => 'Nama wajib diisi.',
            'customer_phone.required'     => 'Nomor HP wajib diisi.',
            'unit_type.required'          => 'Tipe unit wajib dipilih.',
            'brand.required'              => 'Merek wajib diisi.',
            'model.required'              => 'Seri/model unit wajib diisi.',
            'damage_description.required' => 'Deskripsi kerusakan wajib diisi.',
            'damage_description.min'      => 'Deskripsi kerusakan minimal 10 karakter.',
            'dropoff_schedule.required'   => 'Jadwal penyerahan unit ke kantor wajib diisi.',
        ]);

        $ticket = Ticket::create([
            'ticket_number'      => Ticket::generateTicketNumber(),
            'queue_number'       => Ticket::generateQueueNumber(),
            'customer_id'        => auth()->id(),
            'laptop_kit_id'      => $request->laptop_kit_id,
            'is_tune_up'         => $request->has('is_tune_up') ? (bool)$request->is_tune_up : false,
            'customer_name'      => $request->customer_name,
            'customer_phone'     => $request->customer_phone,
            'unit_type'          => $request->unit_type,
            'brand'              => $request->brand,
            'model'              => $request->model,
            'damage_description' => $request->damage_description,
            'dropoff_schedule'   => $request->dropoff_schedule,
            'status'             => 'waiting',
            'created_by'         => auth()->id(),
        ]);

        return view('service.success', compact('ticket'));
    }

    public function track(Request $request)
    {
        $ticket = null;
        $ticketNumber = trim($request->ticket_number);
        $trackError = null;

        if ($ticketNumber) {
            // First check if searched string matches airtable_service_number
            $ticket = Ticket::with(['histories.user', 'technician'])
                ->where('airtable_service_number', $ticketNumber)
                ->first();

            // If not found by airtable_service_number, check by original ticket_number
            if (!$ticket) {
                $candidate = Ticket::with(['histories.user', 'technician'])
                    ->where('ticket_number', $ticketNumber)
                    ->first();

                if ($candidate) {
                    if (!empty($candidate->airtable_service_number)) {
                        // Ticket has already been assigned an Airtable Service Number!
                        // Block tracking via original ticket_number and inform the customer
                        $trackError = "Nomor booking '{$ticketNumber}' telah diperbarui ke Nomor Tiket Servis resmi: {$candidate->airtable_service_number}. Silakan gunakan Nomor Tiket Servis tersebut untuk melacak.";
                    } else {
                        $ticket = $candidate;
                    }
                }
            }
        }

        return view('service.track', compact('ticket', 'ticketNumber', 'trackError'));
    }
}
