<?php

namespace App\Http\Controllers;

use App\Models\ProcurementOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function index(Request $request)
    {
        $products       = Product::active()->get();
        $retailProducts = \App\Models\ProcurementProduct::retail()->where('is_active', true)->get();
        $tkdnProducts   = \App\Models\ProcurementProduct::tkdn()->where('is_active', true)->get();

        // Tracking via ?track=PRC-...
        $trackedOrder = null;
        $trackError   = null;
        if ($request->filled('track')) {
            $trackedOrder = ProcurementOrder::where('order_number', strtoupper(trim($request->track)))->first();
            if (!$trackedOrder) {
                $trackError = 'Nomor referensi "' . $request->track . '" tidak ditemukan. Pastikan nomor sudah benar.';
            }
        }

        return view('procurement.index', compact('products', 'retailProducts', 'tkdnProducts', 'trackedOrder', 'trackError'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name'     => 'required|string|max:255',
            'school_address'  => 'required|string|max:500',
            'school_city'     => 'required|string|max:100',
            'school_type'     => 'required|in:sd,smp,sma,smk,perguruan_tinggi,instansi_lain',
            'pic_name'        => 'required|string|max:100',
            'pic_position'    => 'required|string|max:100',
            'pic_phone'       => 'required|string|max:20',
            'pic_email'       => 'required|email|max:255',
            'notes'           => 'nullable|string|max:1000',
        ], [
            'school_name.required'   => 'Nama sekolah wajib diisi.',
            'school_address.required'=> 'Alamat sekolah wajib diisi.',
            'school_city.required'   => 'Kota wajib diisi.',
            'school_type.required'   => 'Jenis institusi wajib dipilih.',
            'pic_name.required'      => 'Nama PIC wajib diisi.',
            'pic_position.required'  => 'Jabatan PIC wajib diisi.',
            'pic_phone.required'     => 'Nomor WhatsApp wajib diisi.',
            'pic_email.required'     => 'Alamat email wajib diisi.',
            'pic_email.email'        => 'Format email tidak valid.',
        ]);

        $items = [];
        $totalUnits = 0;
        $modelsSummary = [];
        $primarySeries = 'hype';

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $row) {
                $model = trim($row['model'] ?? '');
                $units = (int) ($row['units'] ?? 0);
                if ($model !== '' && $units > 0) {
                    $items[] = [
                        'model' => $model,
                        'units' => $units,
                    ];
                    $totalUnits += $units;
                    $modelsSummary[] = "{$model} ({$units} unit)";
                    if (stripos($model, 'pongo') !== false) {
                        $primarySeries = 'pongo';
                    }
                }
            }
        }

        // Fallback for single model input
        if (empty($items) && $request->filled('axioo_model')) {
            $model = trim($request->axioo_model);
            $units = (int) $request->input('total_units', 1);
            $items[] = ['model' => $model, 'units' => $units];
            $totalUnits = $units;
            $modelsSummary[] = "{$model} ({$units} unit)";
            $primarySeries = strtolower($request->input('axioo_series', 'hype'));
        }

        if (empty($items) || $totalUnits < 1) {
            return back()->withInput()->withErrors(['items' => 'Minimal pilih 1 unit laptop dengan jumlah unit yang valid.']);
        }

        $order = ProcurementOrder::create([
            ...$validated,
            'usage_purpose'=> 'pembelajaran',
            'axioo_series' => $primarySeries,
            'axioo_model'  => implode(', ', $modelsSummary),
            'items'        => $items,
            'total_units'  => $totalUnits,
            'is_tkdn'      => $request->boolean('is_tkdn'),
            'order_number' => ProcurementOrder::generateOrderNumber(),
            'status'       => 'pending',
        ]);

        return redirect()->route('procurement.success', $order->order_number);
    }

    public function success(string $orderNumber)
    {
        $order = ProcurementOrder::where('order_number', $orderNumber)->firstOrFail();
        return view('procurement.success', compact('order'));
    }

    public function track(Request $request)
    {
        // Pre-fill from success page link (?order_number=PRC-...)
        if ($request->filled('order_number')) {
            $order = ProcurementOrder::where('order_number', strtoupper(trim($request->order_number)))->first();
            return view('procurement.track', compact('order'));
        }
        return view('procurement.track');
    }

    public function trackResult(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ], [
            'order_number.required' => 'Nomor referensi pengajuan wajib diisi.',
        ]);

        $order = ProcurementOrder::where('order_number', strtoupper(trim($request->order_number)))
            ->first();

        if (!$order) {
            return back()
                ->withInput()
                ->withErrors(['order_number' => 'Nomor referensi "' . $request->order_number . '" tidak ditemukan. Pastikan nomor sudah benar.']);
        }

        return view('procurement.track', compact('order'));
    }
}
