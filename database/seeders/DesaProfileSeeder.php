<?php

namespace Database\Seeders;

use App\Models\DesaProfile;
use Illuminate\Database\Seeder;

class DesaProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DesaProfile::updateOrCreate(
            ['id' => 1],
            [
                'nama_desa' => 'Desa Sukamaju',
                'kode_desa' => '3202112001',
                'kecamatan' => 'Cikole',
                'kabupaten' => 'Sukabumi',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '43113',
                'alamat_kantor' => 'Jl. Raya Sukamaju No. 01, Kec. Cikole, Kab. Sukabumi',
                'email_desa' => 'kontak@desa-sukamaju.id',
                'telepon_desa' => '0266-221144',
                'website' => 'https://desa-sukamaju.id',
                'logo_path' => null,
                'nama_kades' => 'H. Rahmat Hidayat, S.IP',
                'nip_kades' => '197508172005011003',
                'nik_kades' => '3202111708750001',
            ]
        );
    }
}
