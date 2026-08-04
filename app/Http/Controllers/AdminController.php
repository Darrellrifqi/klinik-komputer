<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProcurementOrder;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PklPeriod;
use App\Models\PklStudent;

class AdminController extends Controller
{
    // Dashboard overview
    public function index()
    {
        $stats = [
            'total_users'         => User::whereNot('role', 'superadmin')->count(),
            'pending_users'       => User::where('status', 'pending')->count(),
            'total_tickets'       => Ticket::count(),
            'booking_tickets'     => Ticket::where('status', 'waiting')->count(),
            'active_tickets'      => Ticket::whereIn('status', ['checking', 'checked', 'konfirmasi_user', 'rma', 'proses_service'])->count(),
            'done_tickets'        => Ticket::whereIn('status', ['done', 'siap_diambil'])->count(),
            'taken_tickets'       => Ticket::whereIn('status', ['sudah_diambil', 'taken'])->count(),
            'total_products'      => Product::count(),
            
            // Procurement Stats
            'total_procurement'     => ProcurementOrder::count(),
            'pending_procurement'   => ProcurementOrder::where('status', 'pending')->count(),
            'processing_procurement'=> ProcurementOrder::whereIn('status', ['diproses', 'konfirmasi_harga', 'menunggu_pembayaran', 'siap_kirim', 'diproses_pengiriman'])->count(),
            'completed_procurement' => ProcurementOrder::whereIn('status', ['dibayar', 'selesai'])->count(),
            'cancelled_procurement' => ProcurementOrder::where('status', 'dibatalkan')->count(),

            // Internship & PKL Stats
            'total_internship_apps'   => \App\Models\InternshipApplication::count(),
            'pending_internship_apps' => \App\Models\InternshipApplication::where('status', 'pending')->count(),
            'approved_internship_apps'=> \App\Models\InternshipApplication::where('status', 'approved')->count(),
            'total_pkl_students'      => \App\Models\PklStudent::count(),
        ];

        // Daily Trend Data (Last 7 Days)
        $chartDates = [];
        $serviceTrend = [];
        $procurementTrend = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->toDateString();
            $chartDates[] = $date->format('d M');

            $serviceTrend[] = Ticket::whereDate('created_at', $dateStr)->count();
            $procurementTrend[] = ProcurementOrder::whereDate('created_at', $dateStr)->count();
        }

        // Status Distribution
        $statusDistribution = [
            'waiting'    => Ticket::where('status', 'waiting')->count(),
            'checking'   => Ticket::where('status', 'checking')->count(),
            'confirmed'  => Ticket::whereIn('status', ['checked', 'konfirmasi_user'])->count(),
            'in_service' => Ticket::whereIn('status', ['rma', 'proses_service'])->count(),
            'done'          => Ticket::whereIn('status', ['done', 'siap_diambil'])->count(),
            'sudah_diambil' => Ticket::whereIn('status', ['sudah_diambil', 'taken'])->count(),
        ];

        return view('dashboard.superadmin.index', compact(
            'stats',
            'chartDates',
            'serviceTrend',
            'procurementTrend',
            'statusDistribution'
        ));
    }

    // User management
    public function users(Request $request)
    {
        $query = User::whereNot('role', 'superadmin');
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('status', $request->status);
        $users = $query->with('laptopKits')->latest()->paginate(20);
        return view('dashboard.superadmin.users', compact('users'));
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', "Akun {$user->name} berhasil disetujui.");
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', "Akun {$user->name} telah ditolak.");
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:customer,cs,teknisi,produksi,superadmin',
        ]);

        $user->update([
            'role'   => $request->role,
            'status' => 'active',
        ]);

        return back()->with('success', "Role pengguna {$user->name} berhasil diubah menjadi " . strtoupper($request->role) . ".");
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'role'     => 'required|in:customer,cs,teknisi,produksi,superadmin',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email ini sudah terdaftar di sistem.',
            'phone.required'     => 'Nomor HP/WhatsApp wajib diisi.',
            'role.required'      => 'Role pengguna wajib dipilih.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status'   => 'active',
        ]);

        return back()->with('success', "Akun {$user->name} dengan role " . strtoupper($user->role) . " berhasil dibuat!");
    }

    // Ticket management
    public function tickets(Request $request)
    {
        $query = Ticket::with(['customer', 'technician', 'creator']);
        if ($request->filled('status')) $query->where('status', $request->status);
        $tickets = $query->latest()->paginate(20);
        return view('dashboard.superadmin.tickets', compact('tickets'));
    }

    // Product management (CRUD)
    public function products()
    {
        $products = Product::orderBy('series')->orderBy('name')->paginate(15);
        return view('dashboard.superadmin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('dashboard.superadmin.products.create');
    }

    public function storeProduct(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate');
        } catch (\Exception $e) {}

        $request->validate([
            'name'          => 'required|string|max:255',
            'series'        => 'required|in:hype,pongo',
            'processor'     => 'required|string|max:255',
            'ram'           => 'required|string|max:100',
            'storage'       => 'required|string|max:100',
            'display'       => 'required|string|max:255',
            'price'         => 'nullable|numeric|min:0',
            'tokopedia_url' => 'nullable|url|max:500',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'images'        => 'nullable|array',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $imagePaths[] = $file->store('products', 'public');
                }
            }
        } elseif ($request->hasFile('image')) {
            $imagePaths[] = $request->file('image')->store('products', 'public');
        }

        $imagePath = !empty($imagePaths) ? json_encode(array_values($imagePaths)) : null;

        $features = array_filter(array_map('trim', explode("\n", $request->features ?? '')));

        Product::create([
            'name'          => $request->name,
            'series'        => $request->series,
            'processor'     => $request->processor,
            'ram'           => $request->ram,
            'storage'       => $request->storage,
            'gpu'           => $request->gpu,
            'display'       => $request->display,
            'battery'       => $request->battery,
            'weight'        => $request->weight,
            'connectivity'  => $request->connectivity,
            'price'         => $request->price,
            'tokopedia_url' => $request->tokopedia_url,
            'image_path'    => $imagePath,
            'description'   => $request->description,
            'features'      => $features ?: null,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function editProduct(Product $product)
    {
        return view('dashboard.superadmin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'series'        => 'required|in:hype,pongo',
            'processor'     => 'required|string|max:255',
            'ram'           => 'required|string|max:100',
            'storage'       => 'required|string|max:100',
            'display'       => 'required|string|max:255',
            'price'         => 'nullable|numeric|min:0',
            'tokopedia_url' => 'nullable|url|max:500',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'images'        => 'nullable|array',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $currentImages = $product->images; // array of image relative paths

        // Process removals
        if ($request->has('remove_images')) {
            $removeList = (array) $request->remove_images;
            $currentImages = array_values(array_filter($currentImages, function($imgPath) use ($removeList) {
                if (in_array($imgPath, $removeList)) {
                    Storage::disk('public')->delete($imgPath);
                    return false;
                }
                return true;
            }));
        }

        // Process new uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $currentImages[] = $file->store('products', 'public');
                }
            }
        } elseif ($request->hasFile('image')) {
            $currentImages[] = $request->file('image')->store('products', 'public');
        }

        $imagePathValue = !empty($currentImages) ? json_encode(array_values($currentImages)) : null;

        $features = array_filter(array_map('trim', explode("\n", $request->features ?? '')));

        $product->update([
            'name'          => $request->name,
            'series'        => $request->series,
            'processor'     => $request->processor,
            'ram'           => $request->ram,
            'storage'       => $request->storage,
            'gpu'           => $request->gpu,
            'display'       => $request->display,
            'battery'       => $request->battery,
            'weight'        => $request->weight,
            'connectivity'  => $request->connectivity,
            'price'         => $request->price,
            'tokopedia_url' => $request->tokopedia_url,
            'image_path'    => $imagePathValue,
            'description'   => $request->description,
            'features'      => $features ?: null,
            'is_active'     => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->image_path) Storage::disk('public')->delete($product->image_path);
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleProduct(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil {$status}.");
    }

    // ─── Procurement Management ───────────────────────────────────────────────
    public function procurementOrders(Request $request)
    {
        $activeQuery = ProcurementOrder::where('status', '!=', 'dibatalkan')->latest();
        $cancelledQuery = ProcurementOrder::where('status', 'dibatalkan')->latest();

        if ($request->filled('school_type')) {
            $activeQuery->where('school_type', $request->school_type);
            $cancelledQuery->where('school_type', $request->school_type);
        }

        // Handle specific status filter (if any status is chosen, it overrides the split)
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'dibatalkan') {
                $activeOrders = collect();
                $cancelledOrders = ProcurementOrder::where('status', 'dibatalkan')->latest();
                if ($request->filled('school_type')) $cancelledOrders->where('school_type', $request->school_type);
                $cancelledOrders = $cancelledOrders->paginate(15, ['*'], 'cancelled_page');
            } else {
                $activeOrders = ProcurementOrder::where('status', $status)->latest();
                if ($request->filled('school_type')) $activeOrders->where('school_type', $request->school_type);
                $activeOrders = $activeOrders->paginate(15, ['*'], 'active_page');
                $cancelledOrders = collect();
            }
        } else {
            $activeOrders = $activeQuery->paginate(15, ['*'], 'active_page');
            $cancelledOrders = $cancelledQuery->paginate(15, ['*'], 'cancelled_page');
        }

        $stats = [
            'total'      => ProcurementOrder::count(),
            'pending'    => ProcurementOrder::where('status', 'pending')->count(),
            'dibayar'    => ProcurementOrder::where('status', 'dibayar')->count(),
            'dibatalkan' => ProcurementOrder::where('status', 'dibatalkan')->count(),
        ];

        return view('dashboard.superadmin.procurement.index', compact('activeOrders', 'cancelledOrders', 'stats'));
    }

    public function exportProcurements()
    {
        $orders = ProcurementOrder::orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="daftar_pengadaan_' . now()->format('Ymd_His') . '.xls"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use($orders) {
            $output = fopen('php://output', 'w');
            
            // Start HTML Excel template with Excel-specific XML namespace declarations
            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            $html .= '<head>';
            $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Daftar Pengadaan</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
            $html .= '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
            $html .= '<style>';
            $html .= 'table { border-collapse: collapse; }';
            $html .= 'th { background-color: #22c55e; color: #ffffff; font-weight: bold; border: 1px solid #cccccc; padding: 8px 12px; }';
            $html .= 'td { border: 1px solid #cccccc; padding: 6px 10px; font-size: 11pt; }';
            $html .= '.text { mso-number-format:"\@"; }'; // Tell Excel to treat the cell strictly as TEXT
            $html .= '</style>';
            $html .= '</head>';
            $html .= '<body>';
            $html .= '<table>';
            
            // Header row
            $html .= '<tr>';
            foreach ([
                'Nomor Order', 'Sekolah / Institusi', 'Tipe Sekolah', 'Alamat', 'Kota', 
                'Nama PIC', 'Jabatan PIC', 'No. WhatsApp PIC', 'Email PIC', 
                'Seri Laptop', 'Model Laptop', 'Jumlah Unit', 'Tujuan Penggunaan', 
                'Catatan Tambahan', 'Harga Penawaran', 'Status', 'Tanggal Pengajuan'
            ] as $head) {
                $html .= '<th>' . htmlspecialchars($head) . '</th>';
            }
            $html .= '</tr>';

            // Data rows
            foreach ($orders as $order) {
                $html .= '<tr>';
                $html .= '<td class="text">' . htmlspecialchars($order->order_number) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->school_name) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->school_type_label) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->school_address) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->school_city) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->pic_name) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->pic_position) . '</td>';
                $html .= '<td class="text">' . htmlspecialchars($order->pic_phone) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->pic_email) . '</td>';
                $html .= '<td>' . htmlspecialchars(strtoupper($order->axioo_series)) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->axioo_model) . '</td>';
                $html .= '<td>' . (int)$order->total_units . '</td>';
                $html .= '<td>' . htmlspecialchars($order->usage_purpose_label) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->notes ?: '-') . '</td>';
                $html .= '<td>' . ($order->quoted_price ? (double)$order->quoted_price : 0) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->status_label) . '</td>';
                $html .= '<td>' . htmlspecialchars($order->created_at->format('d M Y H:i')) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table>';
            $html .= '</body>';
            $html .= '</html>';

            fwrite($output, $html);
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function procurementDetail(ProcurementOrder $order)
    {
        $order->load(['kits.components', 'kits.customer']);
        return view('dashboard.superadmin.procurement.show', compact('order'));
    }

    public function updateProcurementStatus(Request $request, ProcurementOrder $order)
    {
        $request->validate([
            'status'             => 'required|in:pending,diproses,siap_kirim,konfirmasi_harga,menunggu_pembayaran,dibayar,diproses_pengiriman,selesai,dibatalkan',
            'quoted_price'       => 'nullable|numeric|min:0',
            'admin_notes'        => 'nullable|string|max:1000',
            'substep_penawaran'  => 'nullable|boolean',
            'substep_invoice'    => 'nullable|boolean',
            'substep_pembayaran' => 'nullable|boolean',
        ]);

        $newSubstepPenawaran = $request->has('substep_penawaran') || ($request->quoted_price > 0);
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
            'quoted_price'       => $request->quoted_price,
            'admin_notes'        => $request->admin_notes,
            'substep_penawaran'  => $newSubstepPenawaran,
            'substep_invoice'    => $newSubstepInvoice,
            'substep_pembayaran' => $newSubstepPembayaran,
        ]);

        return back()->with('success', 'Status pengadaan berhasil diperbarui.');
    }

    // ─── ACP Partner Schools Management ────────────────────────────────────
    public function acpSchools(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\AcpPartnerSchool::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('npsn', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('province', 'like', '%' . $search . '%');
        }

        $schools = $query->latest()->paginate(20)->withQueryString();
        return view('dashboard.superadmin.acp_schools.index', compact('schools', 'search'));
    }

    public function deleteAllAcpSchools()
    {
        \App\Models\AcpPartnerSchool::query()->delete();
        return back()->with('success', 'Semua data sekolah mitra ACP berhasil dihapus.');
    }

    public function storeAcpSchool(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|unique:acp_partner_schools,name|max:255',
            'npsn'     => 'nullable|string|max:50',
            'city'     => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama sekolah mitra ACP wajib diisi.',
            'name.unique'   => 'Sekolah dengan nama ini sudah terdaftar dalam sistem.',
        ]);

        \App\Models\AcpPartnerSchool::create([
            'name'     => $request->name,
            'npsn'     => $request->npsn,
            'city'     => $request->city,
            'province' => $request->province,
            'is_active'=> true,
        ]);

        return back()->with('success', "Sekolah '{$request->name}' berhasil ditambahkan ke daftar Mitra ACP.");
    }

    public function destroyAcpSchool(\App\Models\AcpPartnerSchool $school)
    {
        $name = $school->name;
        $school->delete();
        return back()->with('success', "Sekolah '{$name}' berhasil dihapus dari daftar Mitra ACP.");
    }

    public function importAcpSchools(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'File Excel/CSV wajib dipilih.',
        ]);

        $file = $request->file('file');
        $ext  = $file->getClientOriginalExtension();
        $path = $file->getRealPath();

        $rows = $this->extractRowsFromFile($path, $ext);

        if (empty($rows)) {
            return back()->with('error', 'Gagal membaca file Excel. Pastikan format file sesuai (.xlsx atau .csv).');
        }

        $header = null;
        $countImported = 0;

        foreach ($rows as $row) {
            if (empty($row) || !isset($row[0])) {
                continue;
            }

            $firstCell = trim($row[0]);
            if ($firstCell === '') {
                continue;
            }

            // Skip header row
            if (!$header) {
                $lowerFirst = strtolower($firstCell);
                if (str_contains($lowerFirst, 'nama') || str_contains($lowerFirst, 'institusi') || str_contains($lowerFirst, 'school')) {
                    $header = $row;
                    continue;
                }
            }

            // Extract values
            $name     = $firstCell;
            $npsn     = isset($row[1]) ? trim($row[1]) : null;
            $city     = isset($row[2]) ? trim($row[2]) : null;
            $province = isset($row[3]) ? trim($row[3]) : null;

            // Save to database
            \App\Models\AcpPartnerSchool::updateOrCreate(
                ['name' => $name],
                [
                    'npsn'      => $npsn ?: null,
                    'city'      => $city ?: null,
                    'province'  => $province ?: null,
                    'is_active' => true
                ]
            );

            $countImported++;
        }

        return back()->with('success', "Berhasil mengimpor {$countImported} Sekolah Mitra ACP dari file Excel.");
    }

    // ─── SN Pengadaan (Laptop Kits) Management & Excel Import ───────────────
    public function procurementKits(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate');
        } catch (\Exception $e) {
            // Ignore if already migrated
        }

        $search = $request->input('search');
        $query = \App\Models\ProcurementLaptopKit::where('is_regular', false);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('member_id', 'like', '%' . $search . '%')
                  ->orWhere('student_name', 'like', '%' . $search . '%')
                  ->orWhere('institution', 'like', '%' . $search . '%')
                  ->orWhere('unit_model', 'like', '%' . $search . '%');
            });
        }

        $kits = $query->latest()->paginate(20)->withQueryString();
        return view('dashboard.superadmin.procurement_kits.index', compact('kits', 'search'));
    }

    public function regularMembers(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\ProcurementLaptopKit::with('customer')->where('is_regular', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('member_id', 'like', '%' . $search . '%')
                  ->orWhere('student_name', 'like', '%' . $search . '%')
                  ->orWhere('axioo_serial_number', 'like', '%' . $search . '%');
            });
        }

        $kits = $query->latest()->paginate(20)->withQueryString();
        return view('dashboard.superadmin.procurement_kits.regular', compact('kits', 'search'));
    }

    public function downloadProcurementKitTemplate()
    {
        $filename = "template_import_sn_pengadaan.csv";
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Row
            fputcsv($file, [
                'No',
                'Nama Lengkap Siswa',
                'Instansi / Sekolah',
                'Tahun Pengadaan',
                'Model Unit (Laptop)',
                'Unit Serial Number',
                'Valid Until'
            ]);

            // Sample Rows
            fputcsv($file, [
                '1',
                'Ahmad Fauzi',
                'SMK Negeri 1 Bandung',
                '2026',
                'AXIOO HYPE 3 G12',
                'SN-AXIOO-2026-001',
                "'2029-12-31"
            ]);

            fputcsv($file, [
                '2',
                'Budi Santoso',
                'SMK Negeri 1 Bandung',
                '2026',
                'AXIOO PONGO 760 V2',
                'SN-AXIOO-2026-002',
                "'2029-12-31"
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importProcurementKits(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'File Excel/CSV wajib dipilih.',
        ]);

        $file = $request->file('file');
        $ext  = $file->getClientOriginalExtension();
        $path = $file->getRealPath();

        $rows = $this->extractRowsFromFile($path, $ext);

        if (empty($rows)) {
            return back()->with('error', 'Gagal membaca file Excel. Pastikan format file sesuai (.xlsx atau .csv).');
        }

        $header = null;
        $countImported = 0;

        foreach ($rows as $row) {
            if (empty($row) || !isset($row[0])) {
                continue;
            }

            // Column A: # (0)
            // Column B: Name (1)
            // Column C: Institution (2)
            // Column D: Year (3)
            // Column E: Unit (4)
            // Column F: Unit Serial Number (5)
            // Column G: Valid Until (6)
            $colB = isset($row[1]) ? trim($row[1]) : '';
            $colC = isset($row[2]) ? trim($row[2]) : '';
            $colD = isset($row[3]) ? trim($row[3]) : '';
            $colE = isset($row[4]) ? trim($row[4]) : '';
            $colF = isset($row[5]) ? trim($row[5]) : '';
            $colG = isset($row[6]) ? trim($row[6]) : '';

            if (!$header) {
                $lowerName = strtolower($colB);
                if ($lowerName === 'name' || $lowerName === 'nama' || str_contains($lowerName, 'nama') || $colF === 'Unit Serial Number') {
                    $header = $row;
                    continue;
                }
            }

            if ($colF === '') {
                continue;
            }

            // Parse Valid Until date if provided
            $warrantyExpires = null;
            $rawDate = ltrim($colG, "'` ");
            if (!empty($rawDate)) {
                try {
                    $warrantyExpires = \Carbon\Carbon::parse($rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $warrantyExpires = null;
                }
            }

            \App\Models\ProcurementLaptopKit::updateOrCreate(
                ['member_id' => $colF],
                [
                    'student_name'         => $colB ?: null,
                    'institution'          => $colC ?: null,
                    'year'                 => $colD ?: null,
                    'unit_model'           => $colE ?: null,
                    'is_regular'           => false,
                    'status'               => 'assembly',
                    'warranty_start'       => null,
                    'warranty_expires'     => $warrantyExpires,
                    'procurement_order_id' => null,
                ]
            );

            $countImported++;
        }

        return back()->with('success', "Berhasil mengimpor {$countImported} Serial Number Unit Pengadaan.");
    }

    public function deleteAllProcurementKits()
    {
        \App\Models\ProcurementLaptopKit::where('is_regular', false)->delete();
        return back()->with('success', 'Semua data Serial Number Unit Pengadaan berhasil dihapus.');
    }

    public function destroyProcurementKit(\App\Models\ProcurementLaptopKit $kit)
    {
        $kit->delete();
        return back()->with('success', "Serial Number {$kit->member_id} berhasil dihapus.");
    }

    // ─── PKL Token Management ────────────────────────────────────────────────
    public function pklTokens()
    {
        $tokens = PklPeriod::withCount('students')->latest()->paginate(15);
        return view('dashboard.superadmin.pkl.tokens', compact('tokens'));
    }

    public function storePklToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|unique:pkl_periods,token|max:50',
            'quarter' => 'required|string|in:Q1,Q2,Q3,Q4',
            'year' => 'required|integer|min:2020|max:2050',
        ]);

        PklPeriod::create([
            'token' => $request->token,
            'quarter' => $request->quarter,
            'year' => $request->year,
            'is_active' => true,
        ]);

        return back()->with('success', 'Token registrasi PKL berhasil dibuat.');
    }

    public function togglePklToken(PklPeriod $token)
    {
        $token->update([
            'is_active' => !$token->is_active
        ]);
        return back()->with('success', 'Status keaktifan token berhasil diubah.');
    }

    public function destroyPklToken(PklPeriod $token)
    {
        $token->delete();
        return back()->with('success', 'Token registrasi PKL berhasil dihapus.');
    }

    // ─── PKL Student Management ──────────────────────────────────────────────
    public function pklStudents(Request $request)
    {
        $query = PklStudent::with(['period']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending'); // Default show pending
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('school', 'like', '%' . $request->search . '%')
                  ->orWhere('division', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->latest()->paginate(15);
        return view('dashboard.superadmin.pkl.students', compact('students'));
    }

    public function approvePklStudent(PklStudent $student)
    {
        $student->update(['status' => 'approved']);
        return back()->with('success', 'Pendaftaran siswa PKL berhasil disetujui.');
    }

    public function rejectPklStudent(PklStudent $student)
    {
        $student->update(['status' => 'rejected']);
        return back()->with('success', 'Pendaftaran siswa PKL telah ditolak.');
    }

    public function destroyPklStudent(PklStudent $student)
    {
        // Delete photo
        if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $student->delete();
        return back()->with('success', 'Data siswa PKL berhasil dihapus.');
    }

    // ─── Internship Applications ──────────────────────────────────────────────
    public function internshipApplications(Request $request)
    {
        $query = \App\Models\InternshipApplication::query();

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('school_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15)->withQueryString();
        return view('dashboard.superadmin.pkl.applications', compact('applications'));
    }

    public function approveInternshipApplication(\App\Models\InternshipApplication $application)
    {
        $application->update(['status' => 'approved']);
        return back()->with('success', 'Pengajuan internship berhasil disetujui.');
    }

    public function rejectInternshipApplication(\App\Models\InternshipApplication $application)
    {
        $application->update(['status' => 'rejected']);
        return back()->with('success', 'Pengajuan internship telah ditolak.');
    }

    public function destroyInternshipApplication(\App\Models\InternshipApplication $application)
    {
        if ($application->proposal_file && \Illuminate\Support\Facades\Storage::disk('public')->exists($application->proposal_file)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($application->proposal_file);
        }
        $application->delete();
        return back()->with('success', 'Data pengajuan internship berhasil dihapus.');
    }

    public function banners()
    {
        return view('dashboard.superadmin.banners');
    }

    public function updateBanners(Request $request)
    {
        $request->validate([
            'procurement_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'pkl_banner'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'products_banner'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($request->hasFile('procurement_banner')) {
            $file = $request->file('procurement_banner');
            $file->move(public_path('images'), 'procurement-hero.jpg');
        }

        if ($request->hasFile('pkl_banner')) {
            $file = $request->file('pkl_banner');
            $file->move(public_path('images'), 'pkl-hero.jpg');
        }

        if ($request->hasFile('products_banner')) {
            $file = $request->file('products_banner');
            $file->move(public_path('images'), 'products-hero.jpg');
        }

        if ($request->input('reset_procurement') === '1') {
            $path = public_path('images/procurement-hero.jpg');
            if (file_exists($path)) {
                unlink($path);
            }
        }

        if ($request->input('reset_pkl') === '1') {
            $path = public_path('images/pkl-hero.jpg');
            if (file_exists($path)) {
                unlink($path);
            }
        }

        if ($request->input('reset_products') === '1') {
            $path = public_path('images/products-hero.jpg');
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return back()->with('success', 'Banner halaman berhasil diperbarui.');
    }

    // ─── Procurement Products Management & Excel/CSV Import ─────────────────
    public function procurementProducts(Request $request)
    {
        $activeCategory = $request->input('category', 'retail');
        $query = \App\Models\ProcurementProduct::where('category', $activeCategory);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('product_name', 'like', "%{$s}%")
                  ->orWhere('cpu', 'like', "%{$s}%")
                  ->orWhere('ram', 'like', "%{$s}%")
                  ->orWhere('storage', 'like', "%{$s}%")
                  ->orWhere('os', 'like', "%{$s}%");
            });
        }
        $products    = $query->latest()->paginate(25);
        $retailCount = \App\Models\ProcurementProduct::retail()->count();
        $tkdnCount   = \App\Models\ProcurementProduct::tkdn()->count();

        return view('dashboard.superadmin.procurement_products.index', compact('products', 'activeCategory', 'retailCount', 'tkdnCount'));
    }

    public function importProcurementProducts(Request $request)
    {
        $request->validate([
            'file'     => 'required|file|max:10240',
            'category' => 'required|in:retail,tkdn',
        ], [
            'file.required'     => 'File Excel/CSV wajib dipilih.',
            'category.required' => 'Kategori katalog wajib dipilih.',
        ]);

        $category = $request->input('category', 'retail');
        $file     = $request->file('file');
        $ext      = $file->getClientOriginalExtension();
        $path     = $file->getRealPath();

        $rows = $this->extractRowsFromFile($path, $ext);

        if (empty($rows)) {
            return back()->with('error', 'Gagal membaca isi file Excel. Pastikan file tidak kosong dan berformat .xlsx atau .csv.');
        }

        $header = null;
        $countImported = 0;

        $lastProductName = '';
        $lastColor = '';

        foreach ($rows as $row) {
            // Read header row
            if (!$header) {
                $firstCol = strtolower(trim(str_replace("\EF\BB\BF", '', $row[0] ?? '')));
                if (str_contains($firstCol, 'product') || str_contains($firstCol, 'nama')) {
                    $header = $row;
                    continue;
                }
            }

            // Extract columns by position
            $pName   = trim($row[0] ?? '');
            $color   = trim($row[1] ?? '');
            $size    = trim($row[2] ?? '');
            $cpu     = trim($row[3] ?? '');
            $ram     = trim($row[4] ?? '');
            $storage = trim($row[5] ?? '');
            $warranty= trim($row[6] ?? '');
            $os      = trim($row[7] ?? '');
            $rawPrice= trim($row[8] ?? '');
            $status  = trim($row[9] ?? 'READY');

            // Merged cells logic (carry over product name and color from previous row if blank)
            if ($pName !== '') {
                $lastProductName = $pName;
            } else {
                $pName = $lastProductName;
            }

            if ($color !== '') {
                $lastColor = $color;
            } else {
                $color = $lastColor;
            }

            if (empty($pName) || empty($cpu)) {
                continue; // Skip if no product name or cpu
            }

            // Parse price integer: remove all non-digits (handles 3,899,000 or 3.899.000)
            $cleanPriceStr = preg_replace('/[^0-9]/', '', $rawPrice);
            $cleanPrice = (float) $cleanPriceStr;

            \App\Models\ProcurementProduct::create([
                'product_name' => $pName,
                'color'        => $color,
                'size'         => $size,
                'cpu'          => $cpu,
                'ram'          => $ram,
                'storage'      => $storage,
                'warranty'     => $warranty,
                'os'           => $os,
                'srp_price'    => $cleanPrice > 0 ? $cleanPrice : null,
                'status_stock' => $status ?: 'READY',
                'category'     => $category,
                'is_active'    => true,
            ]);

            $countImported++;
        }

        $categoryLabel = strtoupper($category);
        return back()->with('success', "Berhasil mengimpor {$countImported} unit produk pengadaan ({$categoryLabel}) dari file Excel.");
    }

    private function extractRowsFromFile(string $path, string $extension): array
    {
        $rows = [];

        // Check if XLSX zip archive
        if (in_array(strtolower($extension), ['xlsx', 'xls']) && class_exists('\ZipArchive')) {
            $zip = new \ZipArchive();
            if ($zip->open($path) === true) {
                // 1. Load shared strings
                $sharedStrings = [];
                $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
                if ($stringsXml) {
                    $xml = @simplexml_load_string($stringsXml);
                    if ($xml) {
                        foreach ($xml->si as $val) {
                            if (isset($val->t)) {
                                $sharedStrings[] = (string) $val->t;
                            } else if (isset($val->r)) {
                                $t = '';
                                foreach ($val->r as $r) {
                                    $t .= (string) $r->t;
                                }
                                $sharedStrings[] = $t;
                            } else {
                                $sharedStrings[] = '';
                            }
                        }
                    }
                }

                // 2. Loop over ALL sheets/tabs (sheet1.xml, sheet2.xml, etc.)
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    if ($stat && preg_match('#xl/worksheets/sheet\d+\.xml$#i', $stat['name'])) {
                        $sheetXml = $zip->getFromName($stat['name']);
                        if ($sheetXml) {
                            $xml = @simplexml_load_string($sheetXml);
                            if ($xml && isset($xml->sheetData->row)) {
                                foreach ($xml->sheetData->row as $r) {
                                    $rowCells = array_fill(0, 12, ''); // Pre-fill up to 12 columns
                                    foreach ($r->c as $c) {
                                        $cellRef = (string) $c['r']; // e.g. "I5"
                                        $colIdx  = $this->colLetterToIndex($cellRef);
                                        $type    = (string) $c['t'];
                                        $val     = (string) $c->v;
                                        if ($type === 's' && isset($sharedStrings[(int)$val])) {
                                            $cellVal = $sharedStrings[(int)$val];
                                        } else {
                                            $cellVal = $val;
                                        }
                                        if ($colIdx >= 0 && $colIdx < 12) {
                                            $rowCells[$colIdx] = $cellVal;
                                        }
                                    }
                                    if (!empty(array_filter($rowCells, fn($v) => trim((string)$v) !== ''))) {
                                        $rows[] = $rowCells;
                                    }
                                }
                            }
                        }
                    }
                }

                $zip->close();
                if (!empty($rows)) {
                    return $rows;
                }
            }
        }

        // Fallback to CSV / Text parsing
        $handle = @fopen($path, 'r');
        if ($handle) {
            while (($data = fgetcsv($handle, 3000, ',')) !== false) {
                if (count($data) == 1 && str_contains($data[0], ';')) {
                    $data = str_getcsv($data[0], ';');
                }
                if (!empty(array_filter($data, fn($v) => trim((string)$v) !== ''))) {
                    $rows[] = $data;
                }
            }
            fclose($handle);
        }

        return $rows;
    }

    private function colLetterToIndex(string $cellRef): int
    {
        $colStr = strtoupper(preg_replace('/[^A-Z]/', '', $cellRef));
        if (empty($colStr)) return -1;
        $len = strlen($colStr);
        $idx = 0;
        for ($i = 0; $i < $len; $i++) {
            $idx = $idx * 26 + (ord($colStr[$i]) - 64);
        }
        return $idx - 1; // 0-indexed
    }

    public function destroyProcurementProduct(\App\Models\ProcurementProduct $product)
    {
        $product->delete();
        return back()->with('success', 'Produk pengadaan berhasil dihapus.');
    }

    public function clearProcurementProducts(Request $request)
    {
        $cat = $request->input('category');
        if (in_array($cat, ['retail', 'tkdn'])) {
            \App\Models\ProcurementProduct::where('category', $cat)->delete();
            $label = strtoupper($cat);
            return back()->with('success', "Seluruh data katalog pengadaan kategori {$label} berhasil dikosongkan.");
        }

        \App\Models\ProcurementProduct::truncate();
        return back()->with('success', 'Seluruh data katalog pengadaan berhasil dikosongkan.');
    }
}
