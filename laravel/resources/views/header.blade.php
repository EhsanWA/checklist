<header class="bg-sky-500 flex items-center p-0 drop-shadow-md">
    {{-- Sticky header met logo en navigatie. Hamburger menu voor mobiel, volledige nav voor tablet/desktop. --}}
    <a href="{{ url('/') }}" class="flex items-center gap-3 pl-3 pr-2 py-2">
        <img src="{{ asset('images/logo_marine.png') }}" alt="Koninklijke Marine Logo" class="h-14 md:h-16">
    </a>

    {{-- Dynamische navigatie: basis links (Home, Overzicht) + voorwaardelijk Beheer (PIN). Status classes bepalen styling. --}}
    @php
    $is = fn(...$names) => request()->routeIs($names) ? 'active' : '';
    $link =
    'inline-flex items-center gap-2 rounded-lg text-white/90 hover:text-white hover:bg-white/10 px-3 py-2 text-sm font-medium transition';
    $active = ' bg-white/15 text-white';
    @endphp

    <div class="ml-auto mr-2 sm:mr-3 md:mr-4 flex items-center gap-2">

        {{-- Primaire navigatie (zichtbaar vanaf tablet/sm) --}}
        <nav class="hidden sm:flex items-center gap-1">
            <a href="{{ url('/') }}" class="{{ $link }} {{ $is('/') ? $active : '' }}">
                <i class="fa-solid fa-house"></i><span>Home</span>
            </a>
            <a href="{{ route('reports.index') }}"
                class="{{ $link }} {{ $is('reports.index') ? $active : '' }}">
                <i class="fa-solid fa-table-list"></i><span>Overzicht</span>
            </a>
            @if (session('is_admin') === true)
            <a href="{{ route('admin.menu') }}"
                class="{{ $link }} {{ $is('admin.menu', 'reports.beheer', 'inspections.beheer') ? $active : '' }}">
                <i class="fa-solid fa-shield-halved"></i><span>Beheer</span>
            </a>
            @else
            <a href="{{ route('admin.login') }}"
                class="{{ $link }} {{ $is('admin.login') ? $active : '' }}">
                <i class="fa-solid fa-lock"></i><span>Beheer (PIN)</span>
            </a>
            @endif
        </nav>

        {{-- Uitloggen (alleen als admin, ook op tablet zichtbaar) --}}
        @if (session('is_admin') === true)
        <form action="{{ route('admin.logout') }}" method="POST" class="hidden sm:block">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-white/10 hover:bg-white/20 text-white px-3 py-2 text-sm font-medium transition">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Uitloggen</span>
            </button>
        </form>
        @endif

        {{-- Hamburger voor small screens (jouw eigen drawer/side menu) --}}
        <button type="button" onclick="toggleSidebar()"
            class="sm:hidden inline-flex items-center justify-center px-3 py-2">
            <i class="fa-solid fa-bars text-2xl" style="color:#ffffff;"></i>
        </button>
    </div>
</header>