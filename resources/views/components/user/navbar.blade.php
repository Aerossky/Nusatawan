@props(['currentPage' => ''])

@php
    $isHeroPage = in_array($currentPage, ['user.home', 'user.destinations.index']);
    $menus = [
        ['route' => 'user.home',                'name' => 'user.home',                'label' => 'Beranda'],
        ['route' => 'user.destinations.index',  'name' => 'user.destinations.index',  'label' => 'Destinasi'],
        ['route' => 'user.itinerary.index',     'name' => 'user.itinerary.index',     'label' => 'Rencana Perjalanan'],
        ['route' => 'user.about',               'name' => 'user.about',               'label' => 'Tentang Kami'],
    ];
@endphp

<nav id="navbar" data-page="{{ $currentPage }}"
    class="fixed w-full z-50 top-0 transition-all duration-300 {{ $isHeroPage ? 'bg-linear-to-b from-black/40 to-transparent' : 'bg-white shadow-sm' }}">

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

        {{-- Brand --}}
        <a href="{{ route('user.home') }}" class="shrink-0">
            <span id="brand-text"
                class="text-xl font-bold tracking-tight transition-colors duration-300 {{ $isHeroPage ? 'text-white' : 'text-gray-900' }}">
                Nusatawan.
            </span>
        </a>

        {{-- Desktop Nav Links --}}
        <ul class="hidden md:flex items-center gap-1" id="nav-links-desktop">
            @foreach ($menus as $menu)
            <li>
                <a href="{{ route($menu['route']) }}"
                    data-page="{{ $menu['name'] }}"
                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200
                    {{ $currentPage === $menu['name']
                        ? ($isHeroPage ? 'text-white font-semibold' : 'text-blue-600 font-semibold')
                        : ($isHeroPage ? 'text-white/80 hover:text-white' : 'text-gray-600 hover:text-blue-600')
                    }}">
                    {{ $menu['label'] }}
                </a>
            </li>
            @endforeach
        </ul>

        {{-- Right Side --}}
        <div class="flex items-center gap-2 shrink-0">
            @auth
                {{-- Profile Dropdown --}}
                <div class="relative">
                    <button id="user-menu-button" type="button"
                        class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-1">
                        <span class="sr-only">Buka menu pengguna</span>
                        @if (Auth::user()->image)
                            <img class="w-8 h-8 rounded-full object-cover border-2 border-white/60"
                                src="{{ asset('storage/' . Auth::user()->image) }}"
                                alt="{{ Auth::user()->name }}">
                        @else
                            <img class="w-8 h-8 rounded-full object-cover border-2 border-white/60"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=100"
                                alt="{{ Auth::user()->name }}">
                        @endif
                    </button>

                    <div id="user-dropdown"
                        class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <ul class="py-1">
                            <li>
                                <a href="{{ route('user.profile.show') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    Profil
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.itinerary.index') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    Rencana Perjalanan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.destination-favorite.index') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    Favorit
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.destination-submission.index') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    Pengajuan Saya
                                </a>
                            </li>
                            <li class="border-t border-gray-100">
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                {{-- Guest: Masuk (desktop only) --}}
                <a href="{{ route('auth.login') }}" id="login-link"
                    class="hidden md:inline-flex text-sm font-medium px-3 py-2 transition-colors duration-200
                    {{ $isHeroPage ? 'text-white hover:text-white/70' : 'text-gray-600 hover:text-blue-600' }}">
                    Masuk
                </a>
                {{-- Guest: Daftar --}}
                <a href="{{ route('auth.register') }}" id="register-button"
                    class="inline-flex text-sm font-medium px-4 py-2 rounded-full transition-all duration-200
                    {{ $isHeroPage
                        ? 'border border-white/80 text-white hover:bg-white hover:text-blue-700'
                        : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                    Daftar
                </a>
            @endauth

            {{-- Hamburger --}}
            <button id="hamburger-menu" type="button"
                class="md:hidden p-2 rounded-lg transition-colors duration-200
                {{ $isHeroPage ? 'text-white hover:bg-white/10' : 'text-gray-700 hover:bg-gray-100' }}"
                aria-label="Toggle menu">
                <span id="ham-top" class="block w-5 h-0.5 bg-current transition-all duration-300 origin-center"></span>
                <span id="ham-mid" class="block w-5 h-0.5 bg-current transition-all duration-300 mt-1.5"></span>
                <span id="ham-bot" class="block w-5 h-0.5 bg-current transition-all duration-300 mt-1.5 origin-center"></span>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden">
        <div class="bg-white border-t border-gray-100 px-4 pt-2 pb-4">
            @foreach ($menus as $menu)
            <a href="{{ route($menu['route']) }}"
                data-page="{{ $menu['name'] }}"
                class="flex items-center py-3 text-sm font-medium border-b border-gray-50 last:border-0 transition-colors duration-200
                {{ $currentPage === $menu['name'] ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                {{ $menu['label'] }}
            </a>
            @endforeach

            @guest
            <div class="flex gap-3 pt-3">
                <a href="{{ route('auth.login') }}"
                    class="flex-1 text-center py-2.5 border border-gray-200 rounded-lg text-gray-700 text-sm font-medium hover:border-blue-300 hover:text-blue-600 transition-colors duration-200">
                    Masuk
                </a>
                <a href="{{ route('auth.register') }}"
                    class="flex-1 text-center py-2.5 bg-blue-600 rounded-lg text-white text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                    Daftar
                </a>
            </div>
            @endguest
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const navbar      = document.getElementById('navbar');
    const brandText   = document.getElementById('brand-text');
    const hamburger   = document.getElementById('hamburger-menu');
    const mobileMenu  = document.getElementById('mobile-menu');
    const hamTop      = document.getElementById('ham-top');
    const hamMid      = document.getElementById('ham-mid');
    const hamBot      = document.getElementById('ham-bot');
    const desktopLinks = document.querySelectorAll('#nav-links-desktop a');
    const loginLink   = document.getElementById('login-link');
    const registerBtn = document.getElementById('register-button');
    const userBtn     = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    const currentPage = navbar.dataset.page;
    const isHeroPage  = ['user.home', 'user.destinations.index'].includes(currentPage);
    let menuOpen = false;

    // ── State setter ──────────────────────────────────────────────────────────
    function setNavbarState(solid) {
        if (solid) {
            navbar.classList.add('bg-white', 'shadow-sm');
            navbar.classList.remove('bg-linear-to-b', 'from-black/40', 'to-transparent');
            brandText.className = 'text-xl font-bold tracking-tight transition-colors duration-300 text-gray-900';
            hamburger.className = 'md:hidden p-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-100';
            desktopLinks.forEach(link => {
                const active = link.dataset.page === currentPage;
                link.className = 'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 ' +
                    (active ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600');
            });
            if (loginLink)   loginLink.className   = 'hidden md:inline-flex text-sm font-medium px-3 py-2 transition-colors duration-200 text-gray-600 hover:text-blue-600';
            if (registerBtn) registerBtn.className = 'inline-flex text-sm font-medium px-4 py-2 rounded-full transition-all duration-200 bg-blue-600 text-white hover:bg-blue-700';
        } else {
            navbar.classList.add('bg-linear-to-b', 'from-black/40', 'to-transparent');
            navbar.classList.remove('bg-white', 'shadow-sm');
            brandText.className = 'text-xl font-bold tracking-tight transition-colors duration-300 text-white';
            hamburger.className = 'md:hidden p-2 rounded-lg transition-colors duration-200 text-white hover:bg-white/10';
            desktopLinks.forEach(link => {
                const active = link.dataset.page === currentPage;
                link.className = 'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 ' +
                    (active ? 'text-white font-semibold' : 'text-white/80 hover:text-white');
            });
            if (loginLink)   loginLink.className   = 'hidden md:inline-flex text-sm font-medium px-3 py-2 transition-colors duration-200 text-white hover:text-white/70';
            if (registerBtn) registerBtn.className = 'inline-flex text-sm font-medium px-4 py-2 rounded-full transition-all duration-200 border border-white/80 text-white hover:bg-white hover:text-blue-700';
        }
    }

    // Initial state
    setNavbarState(!isHeroPage);

    // Scroll listener (hero pages only)
    if (isHeroPage) {
        window.addEventListener('scroll', function () {
            setNavbarState(window.scrollY > 50 || menuOpen);
        }, { passive: true });
    }

    // ── Hamburger toggle ──────────────────────────────────────────────────────
    function openMenu() {
        menuOpen = true;
        mobileMenu.classList.remove('hidden');
        hamTop.style.transform = 'translateY(8px) rotate(45deg)';
        hamMid.style.opacity   = '0';
        hamBot.style.transform = 'translateY(-8px) rotate(-45deg)';
        if (isHeroPage) setNavbarState(true);
    }

    function closeMenu() {
        menuOpen = false;
        mobileMenu.classList.add('hidden');
        hamTop.style.transform = '';
        hamMid.style.opacity   = '';
        hamBot.style.transform = '';
        if (isHeroPage) setNavbarState(window.scrollY > 50);
    }

    hamburger.addEventListener('click', function () {
        menuOpen ? closeMenu() : openMenu();
    });

    document.addEventListener('click', function (e) {
        if (menuOpen && !hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
            closeMenu();
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768 && menuOpen) closeMenu();
    });

    // ── Profile dropdown ──────────────────────────────────────────────────────
    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
});
</script>
