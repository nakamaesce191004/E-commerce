<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Admin & Customer)
        $admin = User::create([
            'name' => 'Admin EquipRent',
            'email' => 'admin@equiprent.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'HQ EquipRent, Kebayoran Baru, Jakarta Selatan',
            'profile_photo' => null
        ]);

        $customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@equiprent.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'phone' => '089876543210',
            'address' => 'Jl. Dago No. 102, Coblong, Kota Bandung',
            'profile_photo' => null
        ]);

        // 2. Seed Categories
        $categoriesData = [
            ['name' => 'Kamera', 'slug' => 'kamera', 'type' => 'camera', 'icon' => 'Camera'],
            ['name' => 'Lensa', 'slug' => 'lensa', 'type' => 'camera', 'icon' => 'Disc'],
            ['name' => 'Drone', 'slug' => 'drone', 'type' => 'camera', 'icon' => 'Plane'],
            ['name' => 'Tripod & Grip', 'slug' => 'tripod', 'type' => 'camera', 'icon' => 'Compass'],
            ['name' => 'Lighting & Flash', 'slug' => 'lighting', 'type' => 'camera', 'icon' => 'Sun'],
            ['name' => 'Tenda Camping', 'slug' => 'tenda', 'type' => 'camping', 'icon' => 'Tent'],
            ['name' => 'Carrier Bag', 'slug' => 'carrier', 'type' => 'camping', 'icon' => 'Briefcase'],
            ['name' => 'Sleeping Bag', 'slug' => 'sleeping-bag', 'type' => 'camping', 'icon' => 'Moon'],
            ['name' => 'Cooking Outdoor Set', 'slug' => 'cooking-set', 'type' => 'camping', 'icon' => 'Flame'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Seed Products
        $productsData = [
            // Kamera
            [
                'category_slug' => 'kamera',
                'name' => 'Sony Alpha 7 IV (Body Only)',
                'slug' => 'sony-a7iv-body',
                'description' => 'Kamera mirrorless hybrid full-frame canggih dengan sensor Exmor R 33MP. Sangat ideal untuk fotografer profesional dan videografer yang membutuhkan kualitas gambar murni serta autofokus real-time pelacakan mata yang sangat cepat.',
                'specifications' => [
                    'Sensor' => '33MP Full-Frame Exmor R CMOS',
                    'Prosesor' => 'BIONZ XR',
                    'Video' => '4K 60p 10-bit 4:2:2',
                    'Autofokus' => '759 Titik Phase-Detection AF',
                    'ISO Range' => '100 - 51200 (Expanded: 50 - 204800)',
                    'Berat' => '658 gram'
                ],
                'price_per_day' => 350000.00,
                'rating' => 4.9,
                'status' => 'available',
                'thumbnail' => 'sony_a7iv_thumb.jpg',
                'gallery' => ['sony_a7iv_1.jpg', 'sony_a7iv_2.jpg']
            ],
            [
                'category_slug' => 'kamera',
                'name' => 'Fujifilm X-T5 (Body Only)',
                'slug' => 'fujifilm-xt5-body',
                'description' => 'Membawa desain retro klasik berpadu dengan performa modern. Dilengkapi sensor X-Trans CMOS 5 HR 40.2MP dan stabilisasi gambar 5-axis. Menghasilkan detail warna Fujifilm yang khas dan legendaris.',
                'specifications' => [
                    'Sensor' => '40.2MP APS-C X-Trans CMOS 5 HR',
                    'Prosesor' => 'X-Processor 5',
                    'Video' => '6.2K 30p, 4K 60p 10-bit',
                    'Stabilization' => '7.0 stop In-Body Image Stabilization',
                    'ISO Range' => '125 - 12800',
                    'Berat' => '557 gram'
                ],
                'price_per_day' => 280000.00,
                'rating' => 4.8,
                'status' => 'available',
                'thumbnail' => 'fuji_xt5_thumb.jpg',
                'gallery' => ['fuji_xt5_1.jpg', 'fuji_xt5_2.jpg']
            ],

            // Lensa
            [
                'category_slug' => 'lensa',
                'name' => 'Sony FE 24-70mm f/2.8 GM II',
                'slug' => 'sony-fe-24-70mm-f28-gm-ii',
                'description' => 'Lensa zoom standar teringan dan terkecil di kelasnya. Seri G Master generasi kedua memberikan ketajaman menakjubkan di seluruh rentang zoom dan bokeh melingkar f/2.8 yang sangat menawan.',
                'specifications' => [
                    'Focal Length' => '24 - 70mm',
                    'Aperture' => 'f/2.8 - f/22',
                    'Diameter Filter' => '82mm',
                    'Elemen Lensa' => '20 Elemen dalam 15 Grup',
                    'Fokus Minimum' => '21 cm',
                    'Berat' => '695 gram'
                ],
                'price_per_day' => 250000.00,
                'rating' => 4.9,
                'status' => 'available',
                'thumbnail' => 'sony_24_70_gm2_thumb.jpg',
                'gallery' => ['sony_24_70_gm2_1.jpg']
            ],
            [
                'category_slug' => 'lensa',
                'name' => 'Canon RF 50mm f/1.2L USM',
                'slug' => 'canon-rf-50mm-f12l-usm',
                'description' => 'Lensa prime standar profesional seri L yang legendaris untuk kamera mirrorless EOS R. Aperture f/1.2 memberikan performa low-light yang luar biasa dan pemisahan subjek dengan latar belakang (bokeh) yang sangat dramatis.',
                'specifications' => [
                    'Focal Length' => '50mm',
                    'Aperture' => 'f/1.2 - f/16',
                    'Diameter Filter' => '95mm',
                    'Motor Fokus' => 'Ring-type USM',
                    'Berat' => '950 gram'
                ],
                'price_per_day' => 220000.00,
                'rating' => 4.7,
                'status' => 'available',
                'thumbnail' => 'canon_rf_50_thumb.jpg',
                'gallery' => ['canon_rf_50_1.jpg']
            ],

            // Drone
            [
                'category_slug' => 'drone',
                'name' => 'DJI Mavic 3 Pro Fly More Combo',
                'slug' => 'dji-mavic-3-pro-combo',
                'description' => 'Drone kelas sinematik dengan sistem tiga kamera Hasselblad. Menawarkan kualitas sensor 4/3 CMOS, optical zoom 3x dan 7x, serta transmisi video O3+ sejauh 15 km dengan waktu terbang maksimal 43 menit.',
                'specifications' => [
                    'Kamera Utama' => 'Hasselblad 20MP 4/3 CMOS',
                    'Kamera Medium' => '70mm 48MP 1/1.3-inch CMOS',
                    'Kamera Tele' => '166mm 12MP 1/2-inch CMOS',
                    'Resolusi Video' => '5.1K 50p, 4K 120p D-Log',
                    'Waktu Terbang' => 'Hingga 43 menit',
                    'Berat' => '958 gram'
                ],
                'price_per_day' => 450000.00,
                'rating' => 4.9,
                'status' => 'available',
                'thumbnail' => 'dji_mavic_3_thumb.jpg',
                'gallery' => ['dji_mavic_3_1.jpg', 'dji_mavic_3_2.jpg']
            ],

            // Tripod
            [
                'category_slug' => 'tripod',
                'name' => 'Manfrotto Befree Advanced Carbon Fiber',
                'slug' => 'manfrotto-befree-carbon',
                'description' => 'Tripod travel premium berbahan serat karbon yang super ringan namun sangat kokoh. Didesain untuk fotografer hobi maupun profesional yang sering bepergian dan membutuhkan stabilitas ekstra di luar ruangan.',
                'specifications' => [
                    'Bahan' => 'Serat Karbon (Carbon Fiber)',
                    'Tinggi Maksimal' => '150 cm',
                    'Beban Maksimal' => '8 kg',
                    'Panjang Terlipat' => '40 cm',
                    'Jenis Head' => 'Ball Head dengan QR Plate',
                    'Berat Tripod' => '1.25 kg'
                ],
                'price_per_day' => 60000.00,
                'rating' => 4.6,
                'status' => 'available',
                'thumbnail' => 'manfrotto_befree_thumb.jpg',
                'gallery' => []
            ],

            // Lighting
            [
                'category_slug' => 'lighting',
                'name' => 'Godox AD200Pro Pocket Flash',
                'slug' => 'godox-ad200pro-flash',
                'description' => 'Lampu studio mini bertenaga 200Ws yang sangat portabel. Menggunakan sistem nirkabel Godox 2.4G X-System yang kompatibel dengan berbagai brand kamera besar. Sempurna untuk strobist luar ruangan.',
                'specifications' => [
                    'Kekuatan Lampu' => '200 Ws',
                    'Waktu Recyle' => '0.01 - 1.8 detik',
                    'Kapasitas Baterai' => '500 kali Full Power Flashes',
                    'Mode Flash' => 'TTL, Manual, Multi-Flash',
                    'Berat' => '590 gram (tanpa head & baterai)'
                ],
                'price_per_day' => 90000.00,
                'rating' => 4.7,
                'status' => 'available',
                'thumbnail' => 'godox_ad200pro_thumb.jpg',
                'gallery' => []
            ],

            // Tenda Camping
            [
                'category_slug' => 'tenda',
                'name' => 'Naturehike Mongar 2 (2 Person Tent)',
                'slug' => 'naturehike-mongar-2-person',
                'description' => 'Tenda dome ultralight berkapasitas 2 orang dengan struktur double layer. Menggunakan bahan 20D Nylon Silicone yang tahan badai angin dan hujan lebat dengan indeks waterproof PU 4000mm.',
                'specifications' => [
                    'Kapasitas' => '2 Orang',
                    'Bahan Outer' => '20D Nylon Silicone (Waterproof PU4000mm)',
                    'Bahan Tiang' => '7001 Aluminum Alloy',
                    'Dimensi' => '210 x (60+135+60) x 100 cm',
                    'Berat Total' => '1.8 kg (Ultralight)'
                ],
                'price_per_day' => 75000.00,
                'rating' => 4.8,
                'status' => 'available',
                'thumbnail' => 'naturehike_mongar_thumb.jpg',
                'gallery' => ['naturehike_mongar_1.jpg', 'naturehike_mongar_2.jpg']
            ],
            [
                'category_slug' => 'tenda',
                'name' => 'Consina Magnum 4 (4 Person Tent)',
                'slug' => 'consina-magnum-4-person',
                'description' => 'Tenda andalan keluarga berkapasitas 4-5 orang dengan teras (vestibule) luas di bagian depan untuk memasak atau menaruh barang bawaan. Awet, andal, dan sangat mudah dirakit.',
                'specifications' => [
                    'Kapasitas' => '4 - 5 Orang',
                    'Bahan Flysheet' => '190T Polyester PU 2000mm',
                    'Bahan Inner' => 'Breathable Polyester',
                    'Dimensi' => '(100+220) x 240 x 135 cm',
                    'Berat Total' => '3.8 kg'
                ],
                'price_per_day' => 60000.00,
                'rating' => 4.6,
                'status' => 'available',
                'thumbnail' => 'consina_magnum_thumb.jpg',
                'gallery' => ['consina_magnum_1.jpg']
            ],

            // Carrier Bag
            [
                'category_slug' => 'carrier',
                'name' => 'Deuter Aircontact Lite 50+10 Litre',
                'slug' => 'deuter-aircontact-lite-50',
                'description' => 'Ransel gunung legendaris asal Jerman dengan sistem ventilasi punggung Aircontact yang sangat empuk dan meminimalkan keringat. Kapasitas 50 liter yang dapat diekspansi hingga 60 liter, cocok untuk hiking 3-4 hari.',
                'specifications' => [
                    'Kapasitas' => '50 + 10 Litre',
                    'Bahan Utama' => '600D Polyester / 100D PA High Tenacity',
                    'Sistem Punggung' => 'Aircontact Lite Backsystem',
                    'Dimensi' => '80 x 30 x 26 cm',
                    'Berat Tas' => '1.75 kg'
                ],
                'price_per_day' => 50000.00,
                'rating' => 4.9,
                'status' => 'available',
                'thumbnail' => 'deuter_aircontact_thumb.jpg',
                'gallery' => ['deuter_aircontact_1.jpg']
            ],
            [
                'category_slug' => 'carrier',
                'name' => 'Osprey Atmos AG 65 Litre',
                'slug' => 'osprey-atmos-ag-65',
                'description' => 'Menghadirkan kenyamanan luar biasa dengan fitur suspensi jaring 3D Anti-Gravity (AG) yang membentang dari punggung hingga ke sabuk pinggang. Beban berat terasa jauh lebih ringan dan seimbang.',
                'specifications' => [
                    'Kapasitas' => '65 Litre',
                    'Fitur Utama' => 'Anti-Gravity Suspended Mesh Backsystem',
                    'Material' => '210D Nylon Honey Comb',
                    'Raincover' => 'Sudah Termasuk (Included)',
                    'Berat Tas' => '2.1 kg'
                ],
                'price_per_day' => 70000.00,
                'rating' => 4.9,
                'status' => 'available',
                'thumbnail' => 'osprey_atmos_thumb.jpg',
                'gallery' => ['osprey_atmos_1.jpg']
            ],

            // Sleeping Bag
            [
                'category_slug' => 'sleeping-bag',
                'name' => 'Deuter Orbit +5 Degree Sleeping Bag',
                'slug' => 'deuter-orbit-sleeping-bag',
                'description' => 'Sleeping bag tipe mummy yang hangat dengan isolasi serat sintetis berkualitas tinggi. Cocok untuk suhu dingin di puncak gunung Indonesia dengan kehangatan optimal dan packing yang ringkas.',
                'specifications' => [
                    'Tipe' => 'Mummy Sleeping Bag',
                    'Suhu Nyaman' => '5 Derajat Celcius',
                    'Suhu Limit' => '0 Derajat Celcius',
                    'Bahan Pengisi' => 'High-Loft Synthetic Hollowfibre',
                    'Panjang Maksimal' => 'Hingga Tinggi Badan 185 cm',
                    'Berat' => '1.05 kg'
                ],
                'price_per_day' => 20000.00,
                'rating' => 4.5,
                'status' => 'available',
                'thumbnail' => 'deuter_orbit_sb_thumb.jpg',
                'gallery' => []
            ],

            // Cooking Outdoor Set
            [
                'category_slug' => 'cooking-set',
                'name' => 'Alocs Camping Cooking Set (4-5 Person)',
                'slug' => 'alocs-cooking-set-outdoor',
                'description' => 'Paket panci dan penggorengan outdoor lengkap berbahan Anodized Aluminum yang anti karat, anti lengket, dan sangat cepat menghantarkan panas. Sangat ringkas karena semua panci dapat ditumpuk menjadi satu wadah.',
                'specifications' => [
                    'Kapasitas' => '4 - 5 Orang',
                    'Bahan Panci' => 'Hard Anodized Aluminum',
                    'Isi Paket' => '2 Panci, 1 Wajan, 1 Ketel, 4 Mangkuk, 1 Spons',
                    'Kompatibilitas' => 'Kompor Portable & Kompor Windproof',
                    'Berat Total' => '1.1 kg'
                ],
                'price_per_day' => 25000.00,
                'rating' => 4.7,
                'status' => 'available',
                'thumbnail' => 'alocs_cookset_thumb.jpg',
                'gallery' => []
            ],
        ];

        foreach ($productsData as $prod) {
            $catSlug = $prod['category_slug'];
            unset($prod['category_slug']);

            $prod['stock'] = $prod['stock'] ?? 5;
            $prod['denda_per_day'] = $prod['denda_per_day'] ?? (round($prod['price_per_day'] * 0.20 / 5000) * 5000); // 20% of price rounded to nearest 5000

            $category = $categories[$catSlug];
            $product = $category->products()->create($prod);

            // 4. Seed Reviews for this product
            Review::create([
                'user_id' => $customer->id,
                'product_id' => $product->id,
                'rating' => rand(4, 5),
                'comment' => 'Barangnya dalam kondisi sangat bagus, bersih, dan performanya mantap saat dibawa kemarin. Pelayanan toko juga sangat ramah dan responsif!'
            ]);
        }
    }
}
