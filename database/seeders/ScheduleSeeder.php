<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schedule::count() > 0) {
            return;
        }

        $schedules = [
            [
                'title' => 'Pelayanan Surat Keterangan Usaha (SKU)',
                'description' => '3 Berkas pemohon walk-in desk',
                'tag' => 'Persuratan',
                'time' => '09:30 WIB',
                'pic' => 'Staff Pelayanan',
                'order' => 1,
            ],
            [
                'title' => 'Verifikasi Duplikasi NIK Kependudukan',
                'description' => 'Sinkronisasi data RT 02 / RW 01',
                'tag' => 'Kependudukan',
                'time' => '11:00 WIB',
                'pic' => 'Admin Desa',
                'order' => 2,
            ],
            [
                'title' => 'Cetak Rekap Buku Ekspedisi & Agenda',
                'description' => 'Penutupan buku register bulan berjalan',
                'tag' => 'Administrasi',
                'time' => '14:00 WIB',
                'pic' => 'Sekretariat',
                'order' => 3,
            ],
            [
                'title' => 'Monitoring Proyek Fisik RKP Desa',
                'description' => 'Inspeksi pembangunan posyandu Dusun 2',
                'tag' => 'Pembangunan',
                'time' => '15:30 WIB',
                'pic' => 'TPK Desa',
                'order' => 4,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
