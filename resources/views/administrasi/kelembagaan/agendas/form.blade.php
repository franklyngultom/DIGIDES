<x-layouts.app :title="isset($agenda) ? 'Edit Agenda ' . $institution->singkatan : 'Jadwalkan Agenda ' . $institution->singkatan">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index', ['tab' => 'kelembagaan']) }}" class="hover:text-[#114443]">Kelembagaan</a>
                    <span>/</span>
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'agenda']) }}" class="hover:text-[#114443]">{{ $institution->singkatan }}</a>
                    <span>/</span>
                    <span class="text-[#0c3837]">{{ isset($agenda) ? 'Edit Agenda' : 'Form Agenda' }}</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    {{ isset($agenda) ? 'Edit Agenda ' . $institution->singkatan : 'Jadwalkan Agenda Rapat & Kerja ' . $institution->singkatan }}
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">{{ $institution->nama_lembaga }}</p>
            </div>
            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'agenda']) }}" class="px-4 py-2 bg-white border border-[#e1ede8] text-[#114443] font-bold text-xs rounded-2xl hover:bg-[#e2f0ed] transition-colors">
                Kembali
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form action="{{ isset($agenda) ? route('administrasi.kelembagaan.agendas.update', ['institution' => $institution->slug, 'agenda' => $agenda->id]) : route('administrasi.kelembagaan.agendas.store', ['institution' => $institution->slug]) }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($agenda))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Agenda -->
                    <div class="md:col-span-2">
                        <label for="nama_agenda" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nama Agenda / Pertemuan / Rapat <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="nama_agenda" name="nama_agenda" value="{{ old('nama_agenda', $agenda->nama_agenda ?? '') }}" placeholder="Contoh: Rapat Koordinasi Bulanan, Musyawarah Pleno" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nama_agenda') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Agenda -->
                    <div>
                        <label for="tanggal_agenda" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tanggal Pelaksanaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="tanggal_agenda" name="tanggal_agenda" value="{{ old('tanggal_agenda', isset($agenda->tanggal_agenda) ? $agenda->tanggal_agenda->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_agenda') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Waktu -->
                    <div>
                        <label for="waktu" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Waktu / Jam Pelaksanaan
                        </label>
                        <input type="text" id="waktu" name="waktu" value="{{ old('waktu', $agenda->waktu ?? '09:00 WIB') }}" placeholder="Contoh: 09:00 WIB s/d selesai" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Tempat -->
                    <div>
                        <label for="tempat" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tempat / Ruangan Pelaksanaan
                        </label>
                        <input type="text" id="tempat" name="tempat" value="{{ old('tempat', $agenda->tempat ?? '') }}" placeholder="Contoh: Aula Balai Desa, Ruang Rapat BPD" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Peserta -->
                    <div>
                        <label for="peserta" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Peserta / Undangan Hadir
                        </label>
                        <input type="text" id="peserta" name="peserta" value="{{ old('peserta', $agenda->peserta ?? '') }}" placeholder="Contoh: Seluruh Pengurus, Ketua RT/RW" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Status Agenda <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="rencana" {{ old('status', $agenda->status ?? '') == 'rencana' ? 'selected' : '' }}>Rencana Jadwal</option>
                            <option value="berlangsung" {{ old('status', $agenda->status ?? '') == 'berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
                            <option value="selesai" {{ old('status', $agenda->status ?? '') == 'selesai' ? 'selected' : '' }}>Telah Selesai</option>
                            <option value="dibatalkan" {{ old('status', $agenda->status ?? '') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tahun Agenda <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $agenda->tahun ?? date('Y')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Pembahasan -->
                    <div class="md:col-span-2">
                        <label for="pembahasan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Pokok Bahasan / Notula Singkat
                        </label>
                        <textarea id="pembahasan" name="pembahasan" rows="3" placeholder="Poin pembahasan atau hasil musyawarah..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('pembahasan', $agenda->pembahasan ?? '') }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'agenda']) }}" class="px-5 py-2.5 bg-[#f7faf9] text-[#64748b] hover:text-[#0c3837] font-bold text-xs rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors">
                        {{ isset($agenda) ? 'Simpan Perubahan' : 'Jadwalkan Agenda' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
