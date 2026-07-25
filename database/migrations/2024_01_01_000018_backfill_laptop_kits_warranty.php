<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // Fetch all activated kits that have null warranty dates
        $kits = DB::table('procurement_laptop_kits')
            ->where('status', 'activated')
            ->whereNull('warranty_expires')
            ->get();

        foreach ($kits as $kit) {
            $activatedAt = $kit->updated_at ? Carbon::parse($kit->updated_at) : Carbon::parse($kit->created_at);
            
            DB::table('procurement_laptop_kits')
                ->where('id', $kit->id)
                ->update([
                    'warranty_start'   => $activatedAt,
                    'warranty_expires' => $activatedAt->copy()->addYear(),
                ]);
        }
    }

    public function down(): void
    {
        // No rollback needed for data backfill
    }
};
