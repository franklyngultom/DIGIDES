<table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
    <tr>
        <td style="width: 75px; text-align: center; vertical-align: middle;">
            @if(!empty($desa->logo_path) && file_exists(storage_path('app/public/' . $desa->logo_path)))
                <img src="{{ storage_path('app/public/' . $desa->logo_path) }}" style="width: 65px; height: auto;" alt="Logo">
            @else
                <div style="width: 65px; height: 65px; background: #114443; border-radius: 8px; color: #d4ed31; line-height: 65px; text-align: center; font-size: 24px; font-weight: bold; margin: auto;">
                    DS
                </div>
            @endif
        </td>
        <td style="text-align: center; vertical-align: middle; padding: 0 10px;">
            <div style="font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #333;">
                Pemerintah Kabupaten {{ $desa->kabupaten ?? 'Sukabumi' }}
            </div>
            <div style="font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #333;">
                Kecamatan {{ $desa->kecamatan ?? 'Cikole' }}
            </div>
            <div style="font-size: 16px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; color: #0c3837; margin: 2px 0;">
                Kantor Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}
            </div>
            <div style="font-size: 9px; color: #555; line-height: 1.3;">
                {{ $desa->alamat_kantor ?? 'Jl. Raya Desa Sukamaju No. 01' }}
                @if(!empty($desa->kode_pos)) - Kode Pos: {{ $desa->kode_pos }} @endif
            </div>
            <div style="font-size: 8.5px; color: #555; line-height: 1.3;">
                @if(!empty($desa->telepon_desa)) Telp: {{ $desa->telepon_desa }} | @endif
                @if(!empty($desa->email_desa)) Email: {{ $desa->email_desa }} | @endif
                @if(!empty($desa->website)) Website: {{ $desa->website }} @endif
            </div>
        </td>
    </tr>
</table>

<!-- Double Kop Line (Thick & Thin) -->
<div style="border-top: 2.5px solid #000; margin-top: 2px;"></div>
<div style="border-top: 1px solid #000; margin-top: 1.5px; margin-bottom: 16px;"></div>
