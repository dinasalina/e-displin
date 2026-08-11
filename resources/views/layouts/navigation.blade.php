<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                        e-Disiplin
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ __('Dashboard') }}
                    </a>

                    @can('disiplin.lapor')
                    <a href="{{ route('disiplin.lapor.create') }}" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-md text-xs font-bold hover:bg-red-700 transition">
                        + Lapor Kes
                    </a>
                    @endcan

                    @canany(['disiplin.lihat.sekolah', 'disiplin.lihat.kelas', 'disiplin.lihat.sendiri'])
                    <a href="{{ route('disiplin.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('disiplin.index', 'disiplin.show') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Senarai Kes Disiplin
                    </a>
                    @endcanany

                    @canany(['disiplin.eskalasi.pkhem', 'disiplin.eskalasi.pengetua', 'disiplin.semak'])
                    <a href="{{ route('disiplin.eskalasi.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('disiplin.eskalasi.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Kelulusan Kes Berat
                    </a>
                    @endcanany

                    @canany(['sekolah.urus', 'pengguna.urus', 'kelas.urus', 'murid.urus', 'penjaga.urus'])
                    <!-- Master Data Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = ! open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <span>Master Data</span>
                            <svg class="ms-1 fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="absolute left-0 z-50 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" style="display: none;">
                            @can('sekolah.urus')
                            <a href="{{ route('master.sekolah.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sekolah</a>
                            <a href="{{ route('master.tahun-akademik.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tahun Akademik</a>
                            @endcan
                            @can('pengguna.urus')
                            <a href="{{ route('master.pengguna.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengguna & Peranan</a>
                            @endcan
                            @can('kelas.urus')
                            <a href="{{ route('master.kelas.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kelas & Guru Kelas</a>
                            @endcan
                            @can('murid.urus')
                            <a href="{{ route('master.murid.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Murid</a>
                            @endcan
                            @can('penjaga.urus')
                            <a href="{{ route('master.penjaga.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Penjaga</a>
                            @endcan
                            @can('sekolah.urus')
                            <a href="{{ route('master.kategori-disiplin.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kategori Disiplin</a>
                            @endcan
                        </div>
                    </div>
                    @endcanany
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <div @click="open = ! open">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                         style="display: none;"
                         @click="open = false">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            {{ __('Profile') }}
                        </a>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                {{ __('Dashboard') }}
            </a>
            @can('disiplin.lapor')
            <a href="{{ route('disiplin.lapor.create') }}" class="block w-full ps-3 pe-4 py-2 text-start text-base font-bold text-red-600 hover:bg-gray-50">+ Lapor Kes Disiplin</a>
            @endcan
            <a href="{{ route('disiplin.index') }}" class="block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-gray-600 hover:bg-gray-50">Senarai Kes Disiplin</a>
            <a href="{{ route('disiplin.eskalasi.index') }}" class="block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-gray-600 hover:bg-gray-50">Kelulusan Kes Berat</a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition duration-150 ease-in-out">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
