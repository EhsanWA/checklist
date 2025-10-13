<header class="bg-sky-500 flex items-center p-0 drop-shadow-md">
    <div>
        <img src="{{ asset('images/logo_marine.png') }}" alt="Koninklijke Marine Logo" class="h-20">
    </div>

    <div class="ml-auto mr-4 flex items-center gap-3">
        {{-- Alleen tonen als admin is ingelogd --}}
        @if (session('is_admin') === true)
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-white/10 hover:bg-white/20 text-white px-3 py-2 text-sm font-medium transition">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Uitloggen</span>
                </button>
            </form>
        @endif

        <i onclick="toggleSidebar()" class="fa-solid fa-bars text-3xl cursor-pointer" style="color: #ffffff;"></i>
    </div>
</header>
