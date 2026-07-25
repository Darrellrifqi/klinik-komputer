<?php

namespace Database\Seeders;

use App\Models\AcpPartnerSchool;
use Illuminate\Database\Seeder;

class AcpPartnerSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            ['name' => 'SMK Negeri 1 Surabaya', 'npsn' => '20532581', 'city' => 'Surabaya', 'province' => 'Jawa Timur'],
            ['name' => 'SMK Axioo Malang', 'npsn' => '20533012', 'city' => 'Malang', 'province' => 'Jawa Timur'],
            ['name' => 'SMK Negeri 2 Semarang', 'npsn' => '20328901', 'city' => 'Semarang', 'province' => 'Jawa Tengah'],
            ['name' => 'SMK Negeri 5 Bandung', 'npsn' => '20219804', 'city' => 'Bandung', 'province' => 'Jawa Barat'],
            ['name' => 'SMK Kristen 1 Surakarta', 'npsn' => '20327711', 'city' => 'Surakarta', 'province' => 'Jawa Tengah'],
            ['name' => 'SMK Negeri 1 Malang', 'npsn' => '20533802', 'city' => 'Malang', 'province' => 'Jawa Timur'],
            ['name' => 'SMK Negeri 2 Yogyakarta', 'npsn' => '20403309', 'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            ['name' => 'SMK Telkom Malang', 'npsn' => '20533990', 'city' => 'Malang', 'province' => 'Jawa Timur'],
            ['name' => 'SMK Negeri 1 Jakarta', 'npsn' => '20100101', 'city' => 'Jakarta Pusat', 'province' => 'DKI Jakarta'],
            ['name' => 'SMK Negeri 26 Jakarta', 'npsn' => '20100260', 'city' => 'Jakarta Timur', 'province' => 'DKI Jakarta'],
        ];

        foreach ($schools as $school) {
            AcpPartnerSchool::updateOrCreate(['name' => $school['name']], $school);
        }
    }
}
