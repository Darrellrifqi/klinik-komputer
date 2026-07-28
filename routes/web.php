<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HeroSlideController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PklController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ───────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/service/track', [ServiceController::class, 'track'])->name('service.track');

// ─── Internship & PKL Public Routes ──────────────────────────────────────────
Route::get('/internship', [PklController::class, 'index'])->name('pkl.index');
Route::post('/internship/apply', [PklController::class, 'storeApplication'])->name('internship.apply');
Route::get('/pkl', function() { return redirect()->route('pkl.index'); });
Route::get('/pkl/daftar', [PklController::class, 'create'])->name('pkl.create');
Route::post('/pkl/daftar', [PklController::class, 'store'])->name('pkl.store');

// ─── Protected Booking Routes ────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/service', [ServiceController::class, 'booking'])->name('service.booking');
    Route::post('/service', [ServiceController::class, 'store'])->name('service.store');
});

// ─── Procurement Routes ───────────────────────────────────────────────────────
Route::get('/pengadaan', [ProcurementController::class, 'index'])->name('procurement.index');
Route::post('/pengadaan', [ProcurementController::class, 'store'])->name('procurement.store');
Route::get('/pengadaan/sukses/{orderNumber}', [ProcurementController::class, 'success'])->name('procurement.success');

// ─── Auth Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Dashboard Redirect ───────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    if (!auth()->check()) return redirect()->route('login');
    return match (auth()->user()->role) {
        'customer'   => redirect()->route('dashboard.customer'),
        'cs'         => redirect()->route('dashboard.cs'),
        'teknisi'    => redirect()->route('dashboard.teknisi'),
        'produksi'   => redirect()->route('dashboard.produksi'),
        'superadmin' => redirect()->route('dashboard.admin'),
        default      => redirect()->route('home'),
    };
})->name('dashboard')->middleware('auth');

// ─── Customer Dashboard ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])->prefix('dashboard/customer')->group(function () {
    Route::get('/', [TicketController::class, 'customerIndex'])->name('dashboard.customer');
    Route::get('/chat', [ChatController::class, 'indexCustomer'])->name('dashboard.customer.chat');
    Route::post('/chat', [ChatController::class, 'storeCustomer'])->name('dashboard.customer.chat.store');
});

// ─── CS Dashboard ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:cs'])->prefix('dashboard/cs')->group(function () {
    Route::get('/', [TicketController::class, 'index'])->name('dashboard.cs');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('dashboard.cs.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('dashboard.cs.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('dashboard.cs.show');
    Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateTicketStatus'])->name('dashboard.cs.tickets.update_status');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('dashboard.cs.tickets.destroy');
    Route::get('/procurement/{order}', [TicketController::class, 'showProcurement'])->name('dashboard.cs.procurement.show');
    Route::post('/procurement/{order}/update', [TicketController::class, 'updateProcurement'])->name('dashboard.cs.procurement.update');

    // Chat
    Route::get('/chat', [ChatController::class, 'indexCS'])->name('dashboard.cs.chat');
    Route::get('/chat/{chat}', [ChatController::class, 'showCS'])->name('dashboard.cs.chat.show');
    Route::post('/chat/{chat}', [ChatController::class, 'storeCS'])->name('dashboard.cs.chat.store');

    // CS User Management
    Route::get('/users', [AdminController::class, 'users'])->name('cs.users');
    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('cs.users.approve');
    Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('cs.users.reject');
    Route::get('/regular-members', [AdminController::class, 'regularMembers'])->name('cs.regular-members');
});

// ─── Teknisi Dashboard ────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:teknisi'])->prefix('dashboard/teknisi')->group(function () {
    Route::get('/', [TechnicianController::class, 'index'])->name('dashboard.teknisi');
    Route::post('/tickets/{ticket}/update', [TechnicianController::class, 'updateStatus'])->name('dashboard.teknisi.update');
    Route::get('/tickets/{ticket}', [TechnicianController::class, 'show'])->name('dashboard.teknisi.show');
});

// ─── Super Admin Dashboard ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:superadmin'])->prefix('dashboard/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard.admin');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
    Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Ticket management
    Route::get('/tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');

    // Product management
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::post('/products/{product}/toggle', [AdminController::class, 'toggleProduct'])->name('admin.products.toggle');

    // Procurement management
    Route::get('/procurement', [AdminController::class, 'procurementOrders'])->name('admin.procurement');
    Route::get('/procurement/export', [AdminController::class, 'exportProcurements'])->name('admin.procurement.export');
    Route::get('/procurement/{order}', [AdminController::class, 'procurementDetail'])->name('admin.procurement.show');
    Route::post('/procurement/{order}/status', [AdminController::class, 'updateProcurementStatus'])->name('admin.procurement.status');

    // Dedicated Procurement Products (Excel Import)
    Route::get('/procurement-products', [AdminController::class, 'procurementProducts'])->name('admin.procurement-products');
    Route::post('/procurement-products/import', [AdminController::class, 'importProcurementProducts'])->name('admin.procurement-products.import');
    Route::delete('/procurement-products/clear', [AdminController::class, 'clearProcurementProducts'])->name('admin.procurement-products.clear');
    Route::delete('/procurement-products/{product}', [AdminController::class, 'destroyProcurementProduct'])->name('admin.procurement-products.destroy');

    // Hero Slide management
    Route::get('/hero', [HeroSlideController::class, 'index'])->name('admin.hero');
    Route::get('/hero/create', [HeroSlideController::class, 'create'])->name('admin.hero.create');

    // Page Banner management
    Route::get('/banners', [AdminController::class, 'banners'])->name('admin.banners');
    Route::post('/banners', [AdminController::class, 'updateBanners'])->name('admin.banners.update');
    Route::post('/hero', [HeroSlideController::class, 'store'])->name('admin.hero.store');
    Route::get('/hero/{hero}/edit', [HeroSlideController::class, 'edit'])->name('admin.hero.edit');
    Route::put('/hero/{hero}', [HeroSlideController::class, 'update'])->name('admin.hero.update');
    Route::delete('/hero/{hero}', [HeroSlideController::class, 'destroy'])->name('admin.hero.destroy');

    // PKL management
    Route::get('/acp-schools', [AdminController::class, 'acpSchools'])->name('admin.acp.schools');
    Route::post('/acp-schools', [AdminController::class, 'storeAcpSchool'])->name('admin.acp.schools.store');
    Route::post('/acp-schools/import', [AdminController::class, 'importAcpSchools'])->name('admin.acp.schools.import');
    Route::delete('/acp-schools/delete-all', [AdminController::class, 'deleteAllAcpSchools'])->name('admin.acp.schools.delete_all');
    Route::delete('/acp-schools/{school}', [AdminController::class, 'destroyAcpSchool'])->name('admin.acp.schools.destroy');

    // SN Pengadaan management
    Route::get('/procurement-kits', [AdminController::class, 'procurementKits'])->name('admin.procurement-kits');
    Route::post('/procurement-kits/import', [AdminController::class, 'importProcurementKits'])->name('admin.procurement-kits.import');
    Route::delete('/procurement-kits/delete-all', [AdminController::class, 'deleteAllProcurementKits'])->name('admin.procurement-kits.delete_all');
    Route::delete('/procurement-kits/{kit}', [AdminController::class, 'destroyProcurementKit'])->name('admin.procurement-kits.destroy');
    Route::get('/regular-members', [AdminController::class, 'regularMembers'])->name('admin.regular-members');

    Route::get('/pkl/tokens', [AdminController::class, 'pklTokens'])->name('admin.pkl.tokens');
    Route::post('/pkl/tokens', [AdminController::class, 'storePklToken'])->name('admin.pkl.tokens.store');
    Route::post('/pkl/tokens/{token}/toggle', [AdminController::class, 'togglePklToken'])->name('admin.pkl.tokens.toggle');
    Route::delete('/pkl/tokens/{token}', [AdminController::class, 'destroyPklToken'])->name('admin.pkl.tokens.destroy');

    Route::get('/pkl/students', [AdminController::class, 'pklStudents'])->name('admin.pkl.students');
    Route::post('/pkl/students/{student}/approve', [AdminController::class, 'approvePklStudent'])->name('admin.pkl.students.approve');
    Route::post('/pkl/students/{student}/reject', [AdminController::class, 'rejectPklStudent'])->name('admin.pkl.students.reject');
    Route::delete('/pkl/students/{student}', [AdminController::class, 'destroyPklStudent'])->name('admin.pkl.students.destroy');
});

// ─── Production Dashboard ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:produksi'])->prefix('dashboard/produksi')->group(function () {
    Route::get('/', [ProductionController::class, 'index'])->name('dashboard.produksi');
    Route::get('/scan/{order}', [ProductionController::class, 'scan'])->name('dashboard.produksi.scan');
    Route::post('/scan/{order}/store', [ProductionController::class, 'storeKit'])->name('dashboard.produksi.scan.store');
});

// ─── Student Kit Activation Routes ──────────────────────────────────────────
Route::get('/aktivasi-kit', [AuthController::class, 'showActivationForm'])->name('kit.activation');
Route::post('/aktivasi-kit', [AuthController::class, 'activateKit'])->name('kit.activate.submit');
Route::post('/aktivasi-kit-mandiri', [AuthController::class, 'registerRegularMember'])->name('kit.activate.regular');

Route::get('/procurement/{order}/export-kits', [ProductionController::class, 'exportKits'])->name('procurement.export-kits')->middleware('auth');
Route::get('/chat/api/{chat}/messages', [ChatController::class, 'getMessages'])->name('chat.api.messages')->middleware('auth');




