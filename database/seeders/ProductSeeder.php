<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'         => 'Axioo Hype 1',
                'series'       => 'hype',
                'processor'    => 'Intel Celeron N100 (4 Core, up to 3.4 GHz)',
                'ram'          => '4GB DDR4 (Upgradeable up to 8GB)',
                'storage'      => '128GB SSD M.2 NVMe',
                'gpu'          => 'Intel UHD Graphics',
                'display'      => '14 inci FHD (1920x1080) IPS Anti-glare',
                'battery'      => '38Wh, hingga 8 jam',
                'weight'       => '1.5 kg',
                'connectivity' => 'Wi-Fi 5, Bluetooth 5.0, USB-A x2, USB-C, HDMI',
                'price'        => 3499000,
                'description'  => 'Laptop entry-level yang sempurna untuk pelajar. Desain ringan dengan performa cukup untuk kegiatan belajar, mengetik, browsing, dan video call.',
                'features'     => ['Cocok untuk Pelajar', 'Ringan & Portabel', 'Baterai Tahan Lama', 'Mudah di-Upgrade'],
                'is_active'    => true,
            ],
            [
                'name'         => 'Axioo Hype 5',
                'series'       => 'hype',
                'processor'    => 'Intel Core i5-1235U (10 Core, up to 4.4 GHz)',
                'ram'          => '8GB DDR4 (Upgradeable up to 32GB)',
                'storage'      => '512GB SSD M.2 NVMe PCIe Gen4',
                'gpu'          => 'Intel Iris Xe Graphics',
                'display'      => '14 inci FHD+ (1920x1200) IPS, 60Hz',
                'battery'      => '56Wh, hingga 10 jam',
                'weight'       => '1.4 kg',
                'connectivity' => 'Wi-Fi 6, Bluetooth 5.2, USB-A x2, USB-C, HDMI 2.0, MicroSD',
                'price'        => 6499000,
                'description'  => 'Laptop produktivitas dengan performa seimbang. Ideal untuk mahasiswa, pekerja kantoran, dan multitasking sehari-hari.',
                'features'     => ['Layar 16:10 Lebih Luas', 'Wi-Fi 6 Super Cepat', 'Performa Seimbang', 'Desain Slim & Premium'],
                'is_active'    => true,
            ],
            [
                'name'         => 'Axioo Hype 7',
                'series'       => 'hype',
                'processor'    => 'AMD Ryzen 7 7735HS (8 Core, up to 4.75 GHz)',
                'ram'          => '16GB DDR5 (Upgradeable up to 64GB)',
                'storage'      => '512GB SSD M.2 NVMe PCIe Gen4',
                'gpu'          => 'AMD Radeon 680M Integrated',
                'display'      => '14 inci 2K (2560x1600) IPS, 90Hz',
                'battery'      => '70Wh, hingga 12 jam',
                'weight'       => '1.35 kg',
                'connectivity' => 'Wi-Fi 6E, Bluetooth 5.3, USB-A x2, USB-C (Thunderbolt 4), HDMI 2.1',
                'price'        => 9999000,
                'description'  => 'Performa tinggi dalam balutan desain tipis. Cocok untuk kreator konten, programmer, dan profesional yang butuh tenaga ekstra.',
                'features'     => ['Layar 2K 90Hz', 'AMD Ryzen 7 Bertenaga', 'Wi-Fi 6E', 'Thunderbolt 4'],
                'is_active'    => true,
            ],
            [
                'name'         => 'Axioo Hype R5',
                'series'       => 'hype',
                'processor'    => 'AMD Ryzen 5 7530U (6 Core, up to 4.5 GHz)',
                'ram'          => '8GB LPDDR5 (Onboard)',
                'storage'      => '256GB SSD M.2 NVMe',
                'gpu'          => 'AMD Radeon Vega 7',
                'display'      => '13.3 inci FHD (1920x1080) IPS Touchscreen',
                'battery'      => '50Wh, hingga 11 jam',
                'weight'       => '0.99 kg (di bawah 1 kg!)',
                'connectivity' => 'Wi-Fi 6, Bluetooth 5.1, USB-A, USB-C x2, HDMI',
                'price'        => 8499000,
                'description'  => 'Laptop ultra-ringan di bawah 1 kg dengan layar sentuh! Solusi terbaik bagi profesional yang selalu mobile dan butuh portabilitas maksimal.',
                'features'     => ['Ultra-ringan < 1kg', 'Layar Touchscreen', 'Desain Premium Tipis', 'Baterai Super Tahan Lama'],
                'is_active'    => true,
            ],
            [
                'name'         => 'Axioo Pongo 755',
                'series'       => 'pongo',
                'processor'    => 'Intel Core i7-13700H (14 Core, up to 5.0 GHz)',
                'ram'          => '16GB DDR5 (Upgradeable up to 64GB)',
                'storage'      => '512GB SSD M.2 NVMe PCIe Gen4',
                'gpu'          => 'NVIDIA GeForce RTX 4060 8GB GDDR6',
                'display'      => '15.6 inci FHD (1920x1080) IPS, 144Hz',
                'battery'      => '90Wh, hingga 6 jam (normal use)',
                'weight'       => '2.1 kg',
                'connectivity' => 'Wi-Fi 6, Bluetooth 5.2, USB-A x3, USB-C, HDMI 2.1, RJ-45',
                'price'        => 16999000,
                'description'  => 'Laptop gaming bertenaga dengan GPU RTX 4060. Mampu menjalankan game AAA dan aplikasi berat dengan lancar.',
                'features'     => ['RTX 4060 8GB', 'Layar 144Hz Anti-ghosting', 'Cooling System Canggih', 'RGB Keyboard'],
                'is_active'    => true,
            ],
            [
                'name'         => 'Axioo Pongo Monster X',
                'series'       => 'pongo',
                'processor'    => 'Intel Core Ultra 9 275HX (24 Core, up to 5.4 GHz)',
                'ram'          => '32GB DDR5 (Upgradeable up to 192GB)',
                'storage'      => '1TB SSD M.2 NVMe PCIe Gen5',
                'gpu'          => 'NVIDIA GeForce RTX 5090 16GB GDDR7',
                'display'      => '17.3 inci QHD (2560x1440) Mini-LED, 240Hz',
                'battery'      => '99Wh, Charger 330W',
                'weight'       => '3.2 kg',
                'connectivity' => 'Wi-Fi 6E, Bluetooth 5.3, USB-A x4, USB-C x2 (Thunderbolt 5), HDMI 2.1, SD Card',
                'price'        => 49999000,
                'description'  => 'Laptop flagship tertinggi dari Axioo. Performa monster yang ditujukan untuk gamer profesional, streamer, dan kreator konten level atas.',
                'features'     => ['RTX 5090 Flagship', 'Layar QHD Mini-LED 240Hz', 'Core Ultra 9', 'Thunderbolt 5'],
                'is_active'    => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}
