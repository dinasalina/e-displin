<aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
       class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700/60 transition-all duration-300 transform lg:translate-x-0 lg:static"
       :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    
    <!-- BRAND LOGO -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-100 dark:border-slate-700/60">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-500/20 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="flex flex-col whitespace-nowrap" x-show="sidebarOpen" x-transition>
                <span class="font-bold text-lg font-heading text-slate-900 dark:text-white">e-Disiplin</span>
                <span class="text-[10px] font-semibold tracking-wider text-indigo-600 dark:text-indigo-400 uppercase">Sistem Pengurusan Disiplin</span>
            </div>
        </a>
        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50">
            <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': !sidebarOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    <!-- MENU LINKS -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        
        <div class="px-3 mb-2" x-show="sidebarOpen">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modul Utama</span>
        </div>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-xs sm:text-sm {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/60' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100' }} transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span x-show="sidebarOpen" class="truncate">Dashboard Utama</span>
        </a>

        @can('disiplin.lapor')
        <!-- Borang Laporan Kes -->
        <a href="{{ route('disiplin.lapor.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs sm:text-sm {{ request()->routeIs('disiplin.lapor.*') ? 'bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100' }} transition-colors">
            <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-show="sidebarOpen" class="truncate font-bold text-red-600 dark:text-red-400">+ Lapor Kes Baharu</span>
        </a>
        @endcan

        @canany(['disiplin.lihat.sekolah', 'disiplin.lihat.kelas', 'disiplin.lihat.sendiri'])
        <!-- Rekod Kes Disiplin -->
        <a href="{{ route('disiplin.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs sm:text-sm {{ request()->routeIs('disiplin.index', 'disiplin.show') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/60' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100' }} transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="sidebarOpen" class="truncate">Senarai Kes Disiplin</span>
        </a>
        @endcanany

        @canany(['disiplin.eskalasi.pkhem', 'disiplin.eskalasi.pengetua', 'disiplin.semak'])
        <!-- Kelulusan Kes Berat -->
        <a href="{{ route('disiplin.eskalasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs sm:text-sm {{ request()->routeIs('disiplin.eskalasi.*') ? 'bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 border border-purple-100 dark:border-purple-900/60' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100' }} transition-colors">
            <svg class="w-5 h-5 shrink-0 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span x-show="sidebarOpen" class="truncate">Kelulusan Kes Berat</span>
        </a>
        @endcanany

        @canany(['sekolah.urus', 'pengguna.urus', 'kelas.urus', 'murid.urus', 'penjaga.urus'])
        <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-700/60" x-show="sidebarOpen">
            <span class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Master Data</span>
        </div>

        @can('sekolah.urus')
        <a href="{{ route('master.sekolah.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm {{ request()->routeIs('master.sekolah.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
            <span>🏫</span>
            <span x-show="sidebarOpen" class="truncate">Sekolah</span>
        </a>
        <a href="{{ route('master.tahun-akademik.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm {{ request()->routeIs('master.tahun-akademik.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
            <span>📅</span>
            <span x-show="sidebarOpen" class="truncate">Tahun Akademik</span>
        </a>
        @endcan

        @can('pengguna.urus')
        <a href="{{ route('master.pengguna.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm {{ request()->routeIs('master.pengguna.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
            <span>👥</span>
            <span x-show="sidebarOpen" class="truncate">Pengguna & Peranan</span>
        </a>
        @endcan

        @can('kelas.urus')
        <a href="{{ route('master.kelas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm {{ request()->routeIs('master.kelas.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
            <span>🏫</span>
            <span x-show="sidebarOpen" class="truncate">Kelas & Guru Kelas</span>
        </a>
        @endcan

        @can('murid.urus')
        <a href="{{ route('master.murid.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm {{ request()->routeIs('master.murid.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
            <span>👧</span>
            <span x-show="sidebarOpen" class="truncate">Murid</span>
        </a>
        @endcan

        @can('penjaga.urus')
        <a href="{{ route('master.penjaga.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm {{ request()->routeIs('master.penjaga.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
            <span>👨‍👩‍👧</span>
            <span x-show="sidebarOpen" class="truncate">Penjaga</span>
        </a>
        @endcan

        @can('sekolah.urus')
        <a href="{{ route('master.kategori-disiplin.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm {{ request()->routeIs('master.kategori-disiplin.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
            <span>⚠️</span>
            <span x-show="sidebarOpen" class="truncate">Kategori Disiplin</span>
        </a>
        @endcan
        @endcanany

    </div>

    <!-- USER CARD FOOTER -->
    <div class="p-3 border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
        <div class="flex items-center gap-3 p-2 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200/60 dark:border-slate-600/50 shadow-sm">
            <div class="w-9 h-9 rounded-lg bg-indigo-600 text-white font-extrabold text-sm flex items-center justify-center shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex flex-col min-w-0 flex-1" x-show="sidebarOpen">
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->jawatan ?? 'Pengguna Sistem' }}</span>
            </div>
        </div>
    </div>

</aside>
