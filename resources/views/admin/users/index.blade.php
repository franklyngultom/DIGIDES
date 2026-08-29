<x-layouts.app>
    <x-slot:title>Manajemen Pengguna & Staf</x-slot:title>

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Manajemen Pengguna</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Manajemen Pengguna & Staf Desa</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Kelola akun staf internal, pembagian peran (RBAC), dan status keaktifan</p>
        </div>

        @can('user.create')
        <a href="{{ route('admin.users.create') }}" 
           class="px-5 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
            <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Pengguna Baru</span>
        </a>
        @endcan
    </div>

    <!-- Stat Highlights -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <x-card class="p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Total Pengguna</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ $stats['total'] }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Staf Aktif</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ $stats['active'] }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#0c3837] text-[#d4ed31] flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Admin Desa</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ $stats['admin_count'] }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#edf5f2] text-[#4fa394] flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Staff Desa</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ $stats['staff_count'] }}</span>
            </div>
        </x-card>
    </div>

    <!-- Filters & Table Section -->
    <x-card class="space-y-4">
        <!-- Search & Filter Bar -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-3 items-center justify-between">
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, no HP..." 
                           class="w-full pl-10 pr-4 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="role" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    <option value="">Semua Peran / Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ $roleFilter === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>

                <select name="status" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    <option value="">Semua Status</option>
                    <option value="1" {{ $statusFilter === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $statusFilter === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white rounded-full text-xs font-bold transition-all cursor-pointer">
                    Filter
                </button>

                @if($search || $roleFilter || $statusFilter !== null)
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-xs text-rose-600 hover:underline">
                    Reset
                </a>
                @endif
            </div>
        </form>

        <!-- User Table -->
        <div class="overflow-x-auto rounded-2xl border border-[#e1ede8]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f7faf9] text-[#64748b] font-bold uppercase border-b border-[#e1ede8]">
                    <tr>
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">Kontak & Email</th>
                        <th class="py-3.5 px-4">Peran (Role)</th>
                        <th class="py-3.5 px-4">Status Akun</th>
                        <th class="py-3.5 px-4">Login Terakhir</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8]">
                    @forelse ($users as $u)
                    <tr class="hover:bg-[#f7faf9] transition-colors">
                        <!-- Name & Avatar -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <img src="{{ $u->avatar_url }}" class="w-9 h-9 rounded-full object-cover border border-[#e1ede8]" alt="{{ $u->name }}">
                                <div>
                                    <span class="font-bold text-[#0c3837] block text-sm">{{ $u->name }}</span>
                                    <span class="text-[11px] text-[#64748b]">ID: #USR-{{ str_pad($u->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Contact & Email -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="font-medium text-[#0f172a] block">{{ $u->email }}</span>
                            <span class="text-[11px] text-[#64748b]">{{ $u->phone ?? 'Belum ada telepon' }}</span>
                        </td>

                        <!-- Role Badge -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($u->hasRole('Admin Desa'))
                                <x-badge variant="pine">Admin Desa</x-badge>
                            @elseif($u->hasRole('Staff Desa'))
                                <x-badge variant="emerald">Staff Desa</x-badge>
                            @else
                                <x-badge variant="slate">{{ $u->role_name }}</x-badge>
                            @endif
                        </td>

                        <!-- Status Toggle Switch -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.users.toggle-status', $u) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $u->is_active ? 'bg-[#10b981]' : 'bg-slate-300' }}"
                                            title="Klik untuk ubah status aktif/nonaktif">
                                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $u->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>
                                <span class="text-[11px] font-semibold {{ $u->is_active ? 'text-[#10b981]' : 'text-slate-400' }}">
                                    {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </td>

                        <!-- Last Login -->
                        <td class="py-3.5 px-4 whitespace-nowrap text-[#64748b]">
                            {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : 'Belum pernah' }}
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                @can('user.edit')
                                <a href="{{ route('admin.users.edit', $u) }}" 
                                   class="p-2 text-[#114443] hover:bg-[#e2f0ed] rounded-xl transition-colors" title="Edit Data & Hak Akses">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endcan

                                @can('user.delete')
                                @if($u->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus staf {{ $u->name }}? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Hapus Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[#64748b]">
                            Tidak ada data pengguna yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $users->links() }}
        </div>
    </x-card>
</x-layouts.app>
