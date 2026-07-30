<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProcurementLaptopKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        $request->validate([
            'nik'                 => 'required|numeric|digits_between:16,16|unique:users,nik',
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'phone'               => 'required|string|max:20',
            'password'            => 'required|min:8|confirmed',
        ], [
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
        ]);

        try {
            DB::beginTransaction();

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
                'warranty_expires'     => null, // Set to null as CS checks manually
                'is_regular'           => true,
                'axioo_serial_number'  => null,
                'purchase_store'       => null,
                'proof_of_purchase'    => null,
                'procurement_order_id' => null,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi Member Mandiri berhasil diajukan! Akun Anda berstatus PENDING. Silakan lakukan pembayaran terlebih dahulu di kantor. CS kami akan memverifikasi dan mengaktifkan akun Anda setelah pembayaran selesai.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses pendaftaran member: ' . $e->getMessage());
        }
    }
}
