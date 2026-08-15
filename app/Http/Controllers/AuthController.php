<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProcurementLaptopKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) return $this->redirectByRole(auth()->user());
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = auth()->user();

            if ($user->status === 'pending') {
                Auth::logout();
                return back()->with('error', 'Akun Anda sedang menunggu persetujuan admin.');
            }

            if ($user->status === 'rejected') {
                Auth::logout();
                return back()->with('error', 'Akun Anda telah ditolak oleh admin.');
            }

            $request->session()->regenerate();
            return $this->redirectByRole($user);
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function showRegister()
    {
        if (auth()->check()) return $this->redirectByRole(auth()->user());
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'required|string|max:20',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone.required'     => 'Nomor HP wajib diisi.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => 'customer',
            'status'   => 'active',
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Selamat datang, ' . $user->name . '! Akun Anda berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    // ─── Forgot & Reset Password ─────────────────────────────────────────────
    public function showForgotPassword()
    {
        if (auth()->check()) return $this->redirectByRole(auth()->user());
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.exists'   => 'Maaf, alamat email ini tidak terdaftar dalam sistem kami.',
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Maaf, alamat email ini tidak terdaftar dalam sistem kami.'])->withInput();
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email'      => $email,
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);

        try {
            Mail::send('emails.reset-password', [
                'resetUrl' => $resetUrl,
                'user'     => $user,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Reset Password Akun - Klinik Komputer');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info("Reset Password Link for {$user->email}: {$resetUrl}");
        }

        return back()->with('status', 'Email terverifikasi!')
                     ->with('resetUrl', $resetUrl);
    }

    public function showResetPassword(Request $request, $token)
    {
        if (auth()->check()) return $this->redirectByRole(auth()->user());

        $email = strtolower(trim($request->query('email', '')));
        $user = User::where('email', $email)->first();
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$user || !$record || !Hash::check($token, $record->token)) {
            return redirect()->route('password.request')->withErrors(['email' => 'Link reset password tidak valid atau email tidak terdaftar dalam sistem.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'              => 'required',
            'email'              => 'required|email|exists:users,email',
            'password'           => 'required|min:8|confirmed',
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.exists'       => 'Maaf, email ini tidak terdaftar.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $email = strtolower(trim($request->email));
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.'])->withInput();
        }

        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['email' => 'Link reset password telah kadaluarsa. Silakan ajukan kembali.'])->withInput();
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Maaf, akun tidak ditemukan.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')->with('success', 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru Anda.');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'customer'   => redirect()->route('home'),
            'cs'         => redirect()->route('dashboard.cs'),
            'teknisi'    => redirect()->route('dashboard.teknisi'),
            'produksi'   => redirect()->route('dashboard.produksi'),
            'superadmin' => redirect()->route('dashboard.admin'),
            default      => redirect('/'),
        };
    }

    // ─── Student Kit Activation ──────────────────────────────────────────────
    public function showActivationForm(Request $request)
    {
        $kit = null;
        $error = null;
        $memberId = strtoupper(trim($request->query('member_id')));

        if ($memberId) {
            $kit = ProcurementLaptopKit::with(['order', 'components'])->where('member_id', $memberId)->first();
            if (!$kit) {
                $error = 'Serial Number "' . $memberId . '" tidak ditemukan. Pastikan Anda memasukkan SN yang benar dan terdaftar di database pengadaan sekolah.';
            } elseif ($kit->status === 'activated') {
                $error = 'Serial Number "' . $memberId . '" sudah diaktivasi sebelumnya oleh ' . $kit->student_name . '.';
                $kit = null;
            }
        }

        return view('auth.activation', compact('kit', 'error', 'memberId'));
    }

    public function activateKit(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string|exists:procurement_laptop_kits,member_id',
            'name'      => 'nullable|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
            'phone'     => 'required|string|max:20',
        ], [
            'member_id.exists'   => 'Serial Number (SN) tidak valid atau belum terdaftar.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar. Silakan login atau gunakan email lain.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone.required'     => 'Nomor HP wajib diisi.',
        ]);

        $kit = ProcurementLaptopKit::where('member_id', $request->member_id)->firstOrFail();
        if ($kit->status === 'activated') {
            return back()->with('error', 'Serial Number (SN) ini sudah diaktivasi sebelumnya.');
        }

        $fullName = $kit->student_name ?: ($request->name ?: 'Member Pengadaan');

        try {
            DB::beginTransaction();

            // Create customer user
            $user = User::create([
                'name'     => $fullName,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'phone'    => $request->phone,
                'role'     => 'customer',
                'status'   => 'active',
            ]);

            // Update kit
            $kit->update([
                'customer_id'      => $user->id,
                'student_name'     => $fullName,
                'status'           => 'activated',
                'warranty_start'   => now(),
                'warranty_expires' => now()->addYear(), // 1 year warranty
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()->route('home')->with('success', 'Aktivasi Berhasil! Selamat datang ' . $user->name . '. Laptop Anda sekarang telah terdaftar.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses aktivasi: ' . $e->getMessage());
        }
    }

    public function registerRegularMember(Request $request)
    {
        $rules = [
            'nik'                 => 'required|numeric|digits_between:16,16|unique:users,nik',
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'phone'               => 'required|string|max:20',
            'password'            => 'required|min:8|confirmed',
            'membership_plan'     => 'required|string|in:Basic Priority,Silver Priority,Gold Priority,Platinum Priority',
        ];

        if ($request->membership_plan !== 'Platinum Priority') {
            $rules['registered_sn'] = 'required|string|max:255';
        }

        $messages = [
            'nik.required'                 => 'NIK (Nomor Induk Kependudukan) wajib diisi.',
            'nik.numeric'                  => 'NIK harus berupa angka.',
            'nik.digits_between'           => 'NIK harus terdiri dari 16 digit.',
            'nik.unique'                   => 'NIK ini sudah terdaftar sebagai member.',
            'name.required'                => 'Nama lengkap wajib diisi.',
            'email.required'               => 'Alamat email wajib diisi.',
            'email.unique'                 => 'Email sudah terdaftar. Silakan login atau gunakan email lain.',
            'phone.required'               => 'Nomor HP wajib diisi.',
            'password.required'            => 'Password wajib diisi.',
            'password.min'                 => 'Password minimal 8 karakter.',
            'password.confirmed'           => 'Konfirmasi password tidak cocok.',
            'membership_plan.required'     => 'Silakan pilih salah satu paket Priority Member terlebih dahulu.',
            'membership_plan.in'           => 'Pilihan paket member tidak valid.',
            'registered_sn.required'       => 'Serial Number (SN) perangkat wajib diisi untuk paket ' . $request->membership_plan . '.',
        ];

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            // Map plan details
            $plansMeta = [
                'Basic Priority'    => ['price' => 199000, 'duration' => 12],
                'Silver Priority'   => ['price' => 279000, 'duration' => 18],
                'Gold Priority'     => ['price' => 399000, 'duration' => 18],
                'Platinum Priority' => ['price' => 599000, 'duration' => 18],
            ];
            $selectedPlan = $request->membership_plan;
            $planInfo = $plansMeta[$selectedPlan] ?? ['price' => 399000, 'duration' => 18];

            // Ensure columns exist on procurement_laptop_kits table
            if (!Schema::hasColumn('procurement_laptop_kits', 'membership_plan')) {
                Schema::table('procurement_laptop_kits', function (Blueprint $table) {
                    $table->string('membership_plan')->nullable();
                    $table->decimal('membership_price', 12, 2)->nullable();
                    $table->integer('membership_duration')->nullable();
                });
            }

            // Create customer user
            $user = User::create([
                'nik'      => $request->nik,
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'phone'    => $request->phone,
                'role'     => 'customer',
                'status'   => 'pending',
            ]);

            // Create Membership record (member_id is NIK)
            ProcurementLaptopKit::create([
                'customer_id'          => $user->id,
                'member_id'            => $request->nik,
                'student_name'         => $user->name,
                'status'               => 'assembly', // Set to assembly/ready until approved/activated by CS
                'warranty_start'       => null,
                'warranty_expires'     => null,
                'is_regular'           => true,
                'axioo_serial_number'  => $request->membership_plan !== 'Platinum Priority' ? trim($request->registered_sn) : null,
                'purchase_store'       => null,
                'proof_of_purchase'    => null,
                'procurement_order_id' => null,
                'membership_plan'     => $selectedPlan,
                'membership_price'    => $planInfo['price'],
                'membership_duration' => $planInfo['duration'],
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('member_registered', true)
                ->with('member_plan', $selectedPlan)
                ->with('success', "Tim Customer Service Kami akan segera Menghubungi Anda dalam waktu 30-60 Menit untuk Konfirmasi Priority Member");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses pendaftaran member: ' . $e->getMessage());
        }
    }
}
