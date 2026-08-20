<?php

namespace App\Http\Controllers;

use App\Models\PklPeriod;
use App\Models\PklStudent;
use Illuminate\Http\Request;

use App\Models\AcpPartnerSchool;
use App\Models\InternshipApplication;

class PklController extends Controller
{
    /**
     * Display public Internship & PKL page.
     */
    public function index()
    {
        // Auto copy banner image from artifacts to public directory on access
        $sourcePath = 'C:\\Users\\ASUS\\.gemini\\antigravity-ide\\brain\\5029ac20-c989-400f-9893-7dc37afb58b5\\media__1784514715793.png';
        $destPath = public_path('images/pkl-banner.png');
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            if (!is_dir(dirname($destPath))) {
                mkdir(dirname($destPath), 0755, true);
            }
            copy($sourcePath, $destPath);
        }

        // Auto copy favicon image from artifacts to public directory on access
        $favSource = 'C:\\Users\\ASUS\\.gemini\\antigravity-ide\\brain\\5029ac20-c989-400f-9893-7dc37afb58b5\\media__1784517265058.png';
        $favDest = public_path('favicon-kk.png');
        if (file_exists($favSource) && !file_exists($favDest)) {
            copy($favSource, $favDest);
        }

        // Auto seed sample ACP partner schools if table is empty
        if (AcpPartnerSchool::count() === 0) {
            (new \Database\Seeders\AcpPartnerSchoolSeeder())->run();
        }

        // Load active ACP Partner Schools for the application dropdown
        $acpSchools = AcpPartnerSchool::where('is_active', true)->orderBy('name', 'asc')->get();

        // Load approved students sorted by year and quarter descending (newest first on the left)
        $students = PklStudent::with(['period'])
            ->where('status', 'approved')
            ->join('pkl_periods', 'pkl_students.pkl_period_id', '=', 'pkl_periods.id')
            ->orderBy('pkl_periods.year', 'desc')
            ->orderBy('pkl_periods.quarter', 'desc')
            ->orderBy('pkl_students.id', 'desc')
            ->select('pkl_students.*')
            ->get();

        // Get unique years that have approved students (newest first)
        $years = PklPeriod::whereHas('students', function ($q) {
            $q->where('status', 'approved');
        })->orderBy('year', 'desc')->distinct()->pluck('year');

        return view('pkl.index', compact('students', 'years', 'acpSchools'));
    }

    /**
     * Show registration form for PKL students.
     */
    public function create()
    {
        $divisions = \App\Models\PklDivision::getActiveDivisions();
        return view('pkl.create', compact('divisions'));
    }

    /**
     * Submit PKL student registration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'name' => 'required|string|max:100',
            'school' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'testimonial' => 'nullable|string|max:400',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096', // Max 4MB
        ], [
            'token.required' => 'Token registrasi PKL wajib diisi.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'school.required' => 'Asal sekolah/universitas wajib diisi.',
            'division.required' => 'Divisi magang wajib diisi.',
            'testimonial.max' => 'Kesan dan pesan maksimal 400 huruf (termasuk spasi).',
            'start_date.required' => 'Tanggal mulai PKL wajib diisi.',
            'end_date.required' => 'Tanggal selesai PKL wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'photo.required' => 'Foto profil wajib diunggah.',
        ]);

        // Validate token
        $period = PklPeriod::where('token', $request->token)->where('is_active', true)->first();
        if (!$period) {
            return back()->withErrors(['token' => 'Token registrasi PKL tidak valid atau sudah dinonaktifkan.'])->withInput();
        }

        // Store photo profile
        $photoPath = $request->file('photo')->store('pkl/avatars', 'public');

        // Create student application
        PklStudent::create([
            'pkl_period_id' => $period->id,
            'name' => $request->name,
            'school' => $request->school,
            'division' => $request->division,
            'testimonial' => $request->testimonial,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('pkl.index')->with('success', 'Pendaftaran berhasil dikirim! Menunggu persetujuan admin.');
    }

    /**
     * Submit Internship Application from ACP Partner Schools.
     */
    public function storeApplication(Request $request)
    {
        $request->validate([
            'acp_partner_school_id' => 'required|exists:acp_partner_schools,id',
            'contact_person'         => 'required|string|max:100',
            'phone'                  => 'required|string|max:30',
            'email'                  => 'nullable|email|max:100',
            'student_count'          => 'required|integer|min:1|max:100',
            'start_date'             => 'required|date',
            'end_date'               => 'required|date|after_or_equal:start_date',
            'proposal_file'          => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'acp_partner_school_id.required' => 'Silakan pilih sekolah mitra Axioo Class Program (ACP).',
            'acp_partner_school_id.exists'   => 'Sekolah yang dipilih tidak terdaftar sebagai Mitra ACP.',
            'contact_person.required'         => 'Nama penanggung jawab/pembimbing wajib diisi.',
            'phone.required'                  => 'Nomor WhatsApp/Telepon wajib diisi.',
            'student_count.required'          => 'Jumlah kuota siswa wajib diisi.',
            'start_date.required'             => 'Estimasi tanggal mulai wajib diisi.',
            'end_date.required'               => 'Estimasi tanggal selesai wajib diisi.',
        ]);

        $school = AcpPartnerSchool::findOrFail($request->acp_partner_school_id);

        $proposalPath = null;
        if ($request->hasFile('proposal_file')) {
            $proposalPath = $request->file('proposal_file')->store('internship/proposals', 'public');
        }

        InternshipApplication::create([
            'acp_partner_school_id' => $school->id,
            'school_name'           => $school->name,
            'contact_person'        => $request->contact_person,
            'phone'                 => $request->phone,
            'email'                 => $request->email,
            'student_count'         => $request->student_count,
            'start_date'            => $request->start_date,
            'end_date'              => $request->end_date,
            'proposal_file'         => $proposalPath,
            'status'                => 'pending',
            'notes'                 => $request->notes,
        ]);

        return back()->with('success_application', "Pengajuan Internship untuk sekolah '{$school->name}' berhasil dikirim! Tim Klinik Komputer akan segera menghubungi Anda.");
    }
}
