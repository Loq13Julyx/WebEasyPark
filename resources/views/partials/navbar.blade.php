<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a class="logo mx-auto d-flex align-items-center justify-content-center">
            <img src="{{ asset('images/logo-parkir1.png') }}" alt="Logo" style="max-height: 125px;">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                    @if (Auth::user()->image)
                        <img src="{{ asset('storage/' . Auth::user()->image) }}" class="rounded-circle"
                            style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <i class="bi bi-person-circle text-secondary"
                            style="font-size: 1.8rem; width: 40px; height: 40px;"></i>
                    @endif

                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        {{ Auth::user()->name }}
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li class="dropdown-header">
                        <h6>{{ Auth::user()->name }}</h6>
                        <span>{{ Auth::user()->role->name ?? 'User' }}</span>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <button id="logoutButton" type="button" class="dropdown-item d-flex align-items-center">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar</span>
                        </button>

                        <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>

</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const logoutBtn = document.getElementById("logoutButton");
        const logoutForm = document.getElementById("logoutForm");

        logoutBtn?.addEventListener("click", function() {
            Swal.fire({
                title: "Apakah Anda yakin ingin keluar?",
                text: "Anda akan logout dari sistem.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, logout!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    logoutForm.submit();
                }
            });
        });
    });
</script>
