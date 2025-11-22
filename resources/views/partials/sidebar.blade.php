@php
    $role = Auth::user()->role->name ?? null;
@endphp

@if (in_array($role, ['admin', 'officer', 'user']))
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

            {{-- ================= ADMIN ================= --}}
            @if ($role === 'admin')
                {{-- Dashboard Admin --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Manajemen User --}}
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#officer-nav" role="button"
                        aria-expanded="false" aria-controls="officer-nav">
                        <i class="bi bi-person-badge"></i>
                        <span>Manajemen Petugas</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>

                    <ul id="officer-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.officers.index') }}"
                                class="{{ request()->routeIs('admin.officers.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i> Data Petugas
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Manajemen Parkir --}}
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#parking-nav" role="button"
                        aria-expanded="false" aria-controls="parking-nav">
                        <i class="bi bi-car-front"></i>
                        <span>Manajemen Parkir</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="parking-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.parking-areas.index') }}"
                                class="{{ request()->routeIs('admin.parking-areas.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i> Area Parkir
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.parking-slots.index') }}"
                                class="{{ request()->routeIs('admin.parking-slots.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i> Slot Parkir
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.vehicle-types.index') }}"
                                class="{{ request()->routeIs('admin.vehicle-types.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i> Tipe Kendaraan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.tarifs.index') }}"
                                class="{{ request()->routeIs('admin.tarifs.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i> Tarif Parkir
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.parking-records.index') }}"
                                class="{{ request()->routeIs('admin.parking-records.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i> Parkir Record
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ================= OFFICER ================= --}}
            @elseif($role === 'officer')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('officer/dashboard') ? 'active' : '' }}"
                        href="{{ route('officer.dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Keluar Parkir --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('officer/parking-exit*') ? 'active' : '' }}"
                        href="{{ route('officer.parking-exit.index') }}">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar Parkir</span>
                    </a>
                </li>

                {{-- ================= USER ================= --}}
            @elseif($role === 'user')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('user/recommendations*') ? 'active' : '' }}"
                        href="{{ route('user.recommendations.index') }}">
                        <i class="bi bi-car-front"></i>
                        <span>Rekomendasi Parkir</span>
                    </a>
                </li>
            @endif

        </ul>
    </aside>
@endif
