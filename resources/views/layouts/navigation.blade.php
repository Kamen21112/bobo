<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <svg class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="font-bold text-slate-700 text-base tracking-tight">АвтоСервиз</span>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Начало
                    </x-nav-link>

                    @if(Auth::user()->role !== 'client')
                        <x-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')">
                            Автомобили
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('repairs.index')" :active="request()->routeIs('repairs.*')">
                        Ремонти
                    </x-nav-link>

                    <x-nav-link :href="route('service_requests.index')" :active="request()->routeIs('service_requests.*')">
                        Заявки
                    </x-nav-link>

                    @if(Auth::user()->role === 'client')
                        <x-nav-link :href="route('my_cars.index')" :active="request()->routeIs('my_cars.*')">
                            Моите Коли
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->role !== 'client')
                        <x-nav-link :href="route('parts.index')" :active="request()->routeIs('parts.*')">
                            Склад
                        </x-nav-link>

                        <x-nav-link :href="route('part_requests.index')" :active="request()->routeIs('part_requests.*')">
                            Заявки части
                        </x-nav-link>
                    @endif

                    {{-- Доставчици и Служители – само admin --}}
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                            Доставчици
                        </x-nav-link>

                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                            Служители
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-800 transition">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            {{ Auth::user()->name }}
                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs text-slate-500">Влязъл като</p>
                            <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-blue-500 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">Профил</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                Изход
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-100 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Начало</x-responsive-nav-link>

            @if(Auth::user()->role !== 'client')
                <x-responsive-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')">Автомобили</x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('repairs.index')" :active="request()->routeIs('repairs.*')">Ремонти</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('service_requests.index')" :active="request()->routeIs('service_requests.*')">Заявки</x-responsive-nav-link>

            @if(Auth::user()->role === 'client')
                <x-responsive-nav-link :href="route('my_cars.index')" :active="request()->routeIs('my_cars.*')">Моите Коли</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role !== 'client')
                <x-responsive-nav-link :href="route('parts.index')" :active="request()->routeIs('parts.*')">Склад</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('part_requests.index')" :active="request()->routeIs('part_requests.*')">Заявки части</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">Доставчици</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">Служители</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-3 border-t border-slate-200">
            <div class="px-4 mb-2">
                <div class="font-medium text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>
            <x-responsive-nav-link :href="route('profile.edit')">Профил</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    Изход
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
