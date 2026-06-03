<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventFaq;
use App\Models\EventLineup;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin ──────────────────────────────────────────────
        $admin = User::create([
            'nama_lengkap' => 'Admin TicketIn', 'no_hp' => '081200000000',
            'email'        => 'admin@ticketin.id',
            'password'     => Hash::make('password'),
            'role'         => 'admin',
            'status_akun'  => 'active',
            'eo_verified'  => false,
        ]);

        // ── 2. Pengelola (EO) ─────────────────────────────────────
        $eo1 = User::create([
            'nama_lengkap' => 'Budi Santoso', 'no_hp' => '081200000001',
            'email'        => 'eo@ticketin.id',
            'password'     => Hash::make('password'),
            'role'         => 'pengelola',
            'status_akun'  => 'active',
            'eo_verified'  => true,
        ]);

        $eo2 = User::create([
            'nama_lengkap' => 'Sari Dewi Production', 'no_hp' => '081200000002',
            'email'        => 'sari@ticketin.id',
            'password'     => Hash::make('password'),
            'role'         => 'pengelola',
            'status_akun'  => 'active',
            'eo_verified'  => true,
        ]);

        // ── 3. User biasa ─────────────────────────────────────────
        User::create([
            'nama_lengkap' => 'Andi Pratama', 'no_hp' => '081200000003',
            'email'        => 'user@ticketin.id',
            'password'     => Hash::make('password'),
            'role'         => 'user',
        ]);

        // ── 4. Events ─────────────────────────────────────────────
        $events = [
            [
                'pengelola_id'  => $eo1->id,
                'judul'         => 'Java Jazz Festival 2025',
                'kategori'      => 'Konser',
                'lokasi_kota'   => 'Jakarta',
                'venue'         => 'Jakarta International Expo',
                'tanggal_waktu' => now()->addDays(30),
                'deskripsi'     => "Java Jazz Festival kembali hadir dengan line-up artis jazz kelas dunia!\n\nNikmati tiga hari penuh musik jazz dari berbagai genre: smooth jazz, fusion, blues, dan R&B. Lebih dari 100 penampil dari 20 negara akan memeriahkan panggung di Jakarta International Expo.\n\nJangan lewatkan momen terbaik ini bersama orang-orang tersayang!",
                'status'        => 'published',
                'gambar_cover'  => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=1200&q=80',
                'lineups'       => [
                    ['nama' => 'Incognito', 'is_headliner' => true],
                    ['nama' => 'Hiromi Uehara', 'is_headliner' => true],
                    ['nama' => 'Maliq & D\'Essentials', 'is_headliner' => false],
                    ['nama' => 'Tompi', 'is_headliner' => false],
                ],
                'tickets'       => [
                    ['nama_kategori' => 'Festival Pass 3 Hari', 'harga' => 1500000, 'kuota' => 500],
                    ['nama_kategori' => 'VIP Pass', 'harga' => 3500000, 'kuota' => 100],
                    ['nama_kategori' => 'VVIP Package', 'harga' => 7500000, 'kuota' => 30],
                ],
                'faqs'          => [
                    ['pertanyaan' => 'Apakah tiket bisa direfund?', 'jawaban' => 'Tiket tidak dapat di-refund, namun bisa dipindahtangankan dengan menghubungi CS kami.'],
                    ['pertanyaan' => 'Apakah ada dress code?', 'jawaban' => 'Tidak ada dress code khusus. Smart casual dianjurkan.'],
                ],
            ],
            [
                'pengelola_id'  => $eo1->id,
                'judul'         => 'Djakarta Warehouse Project 2025',
                'kategori'      => 'Festival',
                'lokasi_kota'   => 'Jakarta',
                'venue'         => 'Jakarta International Expo Hall B&C',
                'tanggal_waktu' => now()->addDays(45),
                'deskripsi'     => "DWP kembali! Festival elektronik terbesar di Asia Tenggara hadir lagi dengan DJ-DJ kelas dunia.\n\nSiapkan diri kamu untuk malam panjang penuh energi, beats, dan cahaya laser spektakuler. Multi-stage dengan genre berbeda: techno, house, trance, dan drum & bass.",
                'status'        => 'published',
                'gambar_cover'  => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&q=80',
                'lineups'       => [
                    ['nama' => 'Martin Garrix', 'is_headliner' => true],
                    ['nama' => 'Tiësto', 'is_headliner' => true],
                    ['nama' => 'DJ Snake', 'is_headliner' => false],
                    ['nama' => 'Alesso', 'is_headliner' => false],
                ],
                'tickets'       => [
                    ['nama_kategori' => 'Regular', 'harga' => 850000, 'kuota' => 2000],
                    ['nama_kategori' => 'VIP', 'harga' => 2000000, 'kuota' => 300],
                    ['nama_kategori' => 'Backstage Pass', 'harga' => 5000000, 'kuota' => 50],
                ],
                'faqs'          => [
                    ['pertanyaan' => 'Berapa batas usia minimum?', 'jawaban' => 'Minimum usia 17 tahun dengan membawa identitas resmi.'],
                    ['pertanyaan' => 'Boleh bawa makanan dari luar?', 'jawaban' => 'Tidak diperbolehkan membawa makanan dan minuman dari luar venue.'],
                ],
            ],
            [
                'pengelola_id'  => $eo2->id,
                'judul'         => 'TEDx Surabaya 2025: Redefine',
                'kategori'      => 'Seminar',
                'lokasi_kota'   => 'Surabaya',
                'venue'         => 'Ciputra World Surabaya Convention Hall',
                'tanggal_waktu' => now()->addDays(15),
                'deskripsi'     => "TEDx Surabaya kembali hadir dengan tema 'Redefine' — mendefinisikan ulang cara kita melihat dunia.\n\nDengarkan cerita inspiratif dari para innovator, entrepreneur, dan pemikir terbaik Indonesia. Sesi networking eksklusif setelah acara.",
                'status'        => 'published',
                'gambar_cover'  => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=1200&q=80',
                'lineups'       => [
                    ['nama' => 'Sandiaga Uno', 'is_headliner' => true],
                    ['nama' => 'Nadiem Makarim', 'is_headliner' => false],
                    ['nama' => 'Shinta Kamdani', 'is_headliner' => false],
                ],
                'tickets'       => [
                    ['nama_kategori' => 'Mahasiswa', 'harga' => 150000, 'kuota' => 200],
                    ['nama_kategori' => 'Umum', 'harga' => 350000, 'kuota' => 300],
                    ['nama_kategori' => 'Premium (Termasuk Dinner)', 'harga' => 850000, 'kuota' => 50],
                ],
                'faqs'          => [
                    ['pertanyaan' => 'Apakah ada sertifikat?', 'jawaban' => 'Ya, semua peserta mendapat e-sertifikat dalam 3 hari kerja setelah event.'],
                    ['pertanyaan' => 'Apakah acara direkam?', 'jawaban' => 'Sebagian sesi akan direkam dan diunggah ke YouTube TEDx Surabaya.'],
                ],
            ],
            [
                'pengelola_id'  => $eo2->id,
                'judul'         => 'Workshop UI/UX Design Intensif',
                'kategori'      => 'Workshop',
                'lokasi_kota'   => 'Bandung',
                'venue'         => 'Co-Working Space Bandung Creative Hub',
                'tanggal_waktu' => now()->addDays(7),
                'deskripsi'     => "Workshop intensif 2 hari untuk belajar UI/UX Design dari praktisi industri.\n\nMateri: User Research, Wireframing, Prototyping di Figma, Usability Testing, dan Portfolio Building. Cocok untuk pemula dan yang ingin switch career.",
                'status'        => 'published',
                'gambar_cover'  => 'https://images.unsplash.com/photo-1587440871875-191322ee64b0?w=1200&q=80',
                'lineups'       => [
                    ['nama' => 'Reza Pratama (Senior UX, Gojek)', 'is_headliner' => true],
                    ['nama' => 'Ayu Lestari (Product Designer, Tokopedia)', 'is_headliner' => false],
                ],
                'tickets'       => [
                    ['nama_kategori' => 'Early Bird', 'harga' => 450000, 'kuota' => 20],
                    ['nama_kategori' => 'Regular', 'harga' => 650000, 'kuota' => 30],
                ],
                'faqs'          => [
                    ['pertanyaan' => 'Apakah perlu laptop?', 'jawaban' => 'Ya, wajib membawa laptop dengan Figma sudah terinstall (akun gratis cukup).'],
                    ['pertanyaan' => 'Apakah ada materi yang dibagikan?', 'jawaban' => 'Ya, semua materi slide dan recording akan dibagikan ke peserta setelah workshop.'],
                ],
            ],
            [
                'pengelola_id'  => $eo1->id,
                'judul'         => 'Surabaya Marathon 2025',
                'kategori'      => 'Olahraga',
                'lokasi_kota'   => 'Surabaya',
                'venue'         => 'Jalan Darmo — Tugu Pahlawan',
                'tanggal_waktu' => now()->addDays(20),
                'deskripsi'     => "Surabaya Marathon hadir kembali! Rasakan sensasi berlari melintasi jantung kota Surabaya melewati ikon-ikon bersejarah.\n\nKategori: Full Marathon (42km), Half Marathon (21km), 10K Run, dan 5K Fun Run. Finisher medal dan kaos eksklusif untuk semua finisher.",
                'status'        => 'published',
                'gambar_cover'  => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=1200&q=80',
                'lineups'       => [],
                'tickets'       => [
                    ['nama_kategori' => '5K Fun Run', 'harga' => 150000, 'kuota' => 1000],
                    ['nama_kategori' => '10K Run', 'harga' => 250000, 'kuota' => 800],
                    ['nama_kategori' => 'Half Marathon 21K', 'harga' => 450000, 'kuota' => 500],
                    ['nama_kategori' => 'Full Marathon 42K', 'harga' => 650000, 'kuota' => 200],
                ],
                'faqs'          => [
                    ['pertanyaan' => 'Kapan kit collection?', 'jawaban' => 'Kit collection H-2 dan H-1 di Grand City Mall Surabaya, pukul 10.00-21.00 WIB.'],
                    ['pertanyaan' => 'Apakah ada batas waktu finish?', 'jawaban' => 'Full Marathon batas waktu 7 jam, Half Marathon 4 jam, 10K 2 jam, 5K tidak ada batas waktu.'],
                ],
            ],
            [
                'pengelola_id'  => $eo2->id,
                'judul'         => 'Pameran Seni Kontemporer Nusantara',
                'kategori'      => 'Seni',
                'lokasi_kota'   => 'Yogyakarta',
                'venue'         => 'Jogja National Museum',
                'tanggal_waktu' => now()->addDays(10),
                'deskripsi'     => "Pameran seni kontemporer terbesar di Yogyakarta menampilkan karya 50 seniman terpilih dari seluruh Indonesia.\n\nInstalasi seni, lukisan, patung, hingga digital art yang mengeksplorasi identitas dan budaya Nusantara di era modern. Dipamerkan selama 2 minggu.",
                'status'        => 'published',
                'gambar_cover'  => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=1200&q=80',
                'lineups'       => [],
                'tickets'       => [
                    ['nama_kategori' => 'Tiket Masuk', 'harga' => 75000, 'kuota' => 500],
                    ['nama_kategori' => 'Guided Tour (termasuk tiket)', 'harga' => 150000, 'kuota' => 80],
                ],
                'faqs'          => [
                    ['pertanyaan' => 'Apakah boleh memotret?', 'jawaban' => 'Diperbolehkan untuk keperluan pribadi. Dilarang menggunakan flash.'],
                    ['pertanyaan' => 'Apakah ada workshop?', 'jawaban' => 'Ada workshop setiap akhir pekan bersama seniman. Cek jadwal di Instagram kami.'],
                ],
            ],
        ];

        foreach ($events as $eventData) {
            $lineups  = $eventData['lineups'];
            $tickets  = $eventData['tickets'];
            $faqs     = $eventData['faqs'];

            unset($eventData['lineups'], $eventData['tickets'], $eventData['faqs']);

            $event = Event::create($eventData);

            foreach ($tickets as $ticket) {
                TicketCategory::create(array_merge($ticket, ['event_id' => $event->id]));
            }

            foreach ($lineups as $lineup) {
                EventLineup::create(array_merge($lineup, ['event_id' => $event->id]));
            }

            foreach ($faqs as $faq) {
                EventFaq::create(array_merge($faq, ['event_id' => $event->id]));
            }
        }

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('   Admin   : admin@ticketin.id / password');
        $this->command->info('   EO      : eo@ticketin.id / password');
        $this->command->info('   User    : user@ticketin.id / password');
    }
}
