<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="{{ route('air.index') }}">
            <img src="{{ asset('images/logo_artetis.jpeg') }}"
                 alt="Logo"
                 height="60"
                 class="d-inline-block align-text-top">
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarText"
                aria-controls="navbarText"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">

            {{-- MENU HANYA UNTUK SELAIN WARGA --}}
            @if(auth()->user()->role !== 'warga')
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('air.index') ? 'active fw-semibold' : '' }}"
                            href="{{ route('air.index') }}">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('air.create') ? 'active fw-semibold' : '' }}"
                            href="{{ route('air.create') }}">
                            Tambah Data
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.index') ? 'active fw-semibold' : '' }}"
                            href="{{ route('users.index') }}">
                            Users
                        </a>
                    </li>

                </ul>
            @endif

            {{-- RIGHT SIDE --}}
            <div class="ms-auto d-flex align-items-center gap-3">

                <span class="text-muted">
                    {{ auth()->user()->username }}
                </span>

                <form method="POST" action="/logout" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>
