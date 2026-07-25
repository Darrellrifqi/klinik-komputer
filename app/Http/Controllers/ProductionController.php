<?php

namespace App\Http\Controllers;

use App\Models\ProcurementOrder;
use App\Models\ProcurementLaptopKit;
use App\Models\ProcurementKitComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        // Get procurement orders that are active (pending, diproses, siap_kirim, etc.)
        $orders = ProcurementOrder::withCount('kits')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.produksi.index', compact('orders'));
    }

    public function scan(ProcurementOrder $order)
    {
        $order->load(['kits.components', 'kits.customer']);
        
        // Generate next member ID
        $nextMemberId = ProcurementLaptopKit::generateMemberId();

        return view('dashboard.produksi.scan', compact('order', 'nextMemberId'));
    }

    public function storeKit(Request $request, ProcurementOrder $order)
    {
        $request->validate([
            'member_id'      => 'required|string|unique:procurement_laptop_kits,member_id',
            'motherboard_sn' => 'required|string|max:100',
            'ram_sn'         => 'required|string|max:100',
            'ssd_sn'         => 'required|string|max:100',
            'screen_sn'      => 'required|string|max:100',
            'battery_sn'     => 'required|string|max:100',
        ], [
            'member_id.unique'        => 'ID Member sudah terdaftar.',
            'motherboard_sn.required' => 'SN Motherboard wajib diisi.',
            'ram_sn.required'         => 'SN RAM wajib diisi.',
            'ssd_sn.required'         => 'SN SSD wajib diisi.',
            'screen_sn.required'      => 'SN Screen wajib diisi.',
            'battery_sn.required'     => 'SN Battery wajib diisi.',
        ]);

        // Check if limit is reached
        $currentCount = $order->kits()->count();
        if ($currentCount >= $order->total_units) {
            return response()->json([
                'success' => false,
                'message' => 'Semua unit kit laptop untuk order ini sudah selesai di-scan.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $kit = ProcurementLaptopKit::create([
                'procurement_order_id' => $order->id,
                'member_id'            => $request->member_id,
                'status'               => 'assembly',
            ]);

            $components = [
                'Motherboard' => $request->motherboard_sn,
                'RAM'         => $request->ram_sn,
                'SSD'         => $request->ssd_sn,
                'Screen'      => $request->screen_sn,
                'Battery'     => $request->battery_sn,
            ];

            foreach ($components as $name => $sn) {
                ProcurementKitComponent::create([
                    'laptop_kit_id'  => $kit->id,
                    'component_name' => $name,
                    'serial_number'  => trim($sn),
                ]);
            }

            // If this was the last unit, auto update order status
            $newCount = $currentCount + 1;
            if ($newCount >= $order->total_units) {
                $order->update(['status' => 'siap_kirim']);
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Kit laptop berhasil dibungkus dengan ID Member: ' . $kit->member_id,
                'next_member'  => ProcurementLaptopKit::generateMemberId(),
                'progress'     => $newCount,
                'total'        => $order->total_units,
                'is_completed' => $newCount >= $order->total_units,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportKits(ProcurementOrder $order)
    {
        $order->load(['kits.components']);

        $filename = "Excel_SN_Pengadaan_" . $order->order_number . "_" . str_replace(' ', '_', $order->school_name) . ".xls";

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-type" content="text/html;charset=utf-8" />';
        $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Data SN Laptops</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; width: 100%; }';
        $html .= 'th { background-color: #2e7d32; color: #ffffff; font-weight: bold; text-align: center; padding: 8px; border: 1px solid #cccccc; }';
        $html .= 'td { padding: 6px 10px; border: 1px solid #cccccc; }';
        $html .= '.text-cell { vnd.ms-excel.numberformat:@; }'; // Treat as text to prevent dropping leading zeroes
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<h2>Data Serial Number Pengadaan - ' . htmlspecialchars($order->school_name) . '</h2>';
        $html .= '<p>Nomor Referensi: ' . htmlspecialchars($order->order_number) . ' | Model: ' . htmlspecialchars($order->axioo_model) . '</p>';
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>ID Member</th>';
        $html .= '<th>Model Laptop</th>';
        $html .= '<th>Motherboard SN</th>';
        $html .= '<th>RAM SN</th>';
        $html .= '<th>SSD SN</th>';
        $html .= '<th>Screen SN</th>';
        $html .= '<th>Battery SN</th>';
        $html .= '<th>Status Aktivasi</th>';
        $html .= '<th>Nama Siswa/Penerima</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($order->kits as $index => $kit) {
            $mb  = $kit->components->where('component_name', 'Motherboard')->first()?->serial_number ?? '—';
            $ram = $kit->components->where('component_name', 'RAM')->first()?->serial_number ?? '—';
            $ssd = $kit->components->where('component_name', 'SSD')->first()?->serial_number ?? '—';
            $scr = $kit->components->where('component_name', 'Screen')->first()?->serial_number ?? '—';
            $bat = $kit->components->where('component_name', 'Battery')->first()?->serial_number ?? '—';

            $html .= '<tr>';
            $html .= '<td style="text-align: center;">' . ($index + 1) . '</td>';
            $html .= '<td class="text-cell" style="font-weight: bold; color: #1b5e20;">' . htmlspecialchars($kit->member_id) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->axioo_model) . '</td>';
            $html .= '<td class="text-cell">' . htmlspecialchars($mb) . '</td>';
            $html .= '<td class="text-cell">' . htmlspecialchars($ram) . '</td>';
            $html .= '<td class="text-cell">' . htmlspecialchars($ssd) . '</td>';
            $html .= '<td class="text-cell">' . htmlspecialchars($scr) . '</td>';
            $html .= '<td class="text-cell">' . htmlspecialchars($bat) . '</td>';
            $html .= '<td style="text-align: center;">' . ($kit->status === 'activated' ? 'Aktif' : 'Belum Aktivasi') . '</td>';
            $html .= '<td>' . htmlspecialchars($kit->student_name ?? '—') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        return response($html, 200, [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }
}

