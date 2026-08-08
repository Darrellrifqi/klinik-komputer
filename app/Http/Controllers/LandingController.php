<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ticket;
use App\Models\HeroSlide;

class LandingController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->take(3)->get();
        
        // Ambil tiket antrian aktif (dari Menunggu Unit sampai Proses Service)
        $activeQueue = Ticket::with('technician')
            ->whereNotIn('status', ['done', 'siap_diambil', 'sudah_diambil', 'taken', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $heroSlides = \App\Models\HeroSlide::where('is_active', true)->orderBy('order_index')->get();

        return view('landing.index', compact('featuredProducts', 'activeQueue', 'heroSlides'));
    }

}

