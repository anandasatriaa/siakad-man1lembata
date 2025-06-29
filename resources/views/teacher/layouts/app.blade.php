<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">

    {{-- DATATABLE --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .sidebar-wrapper .sidebar-header img {
            height: 3rem !important;
        }

        .sidebar-wrapper .sidebar-header .logo {
            font-size: 1.25rem !important;
            display: flex;
            align-items: center;
        }
    </style>

    @stack('css')
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo d-flex align-items-center">
                            <a href="{{ route('teacher.dashboard.index') }}">
                                <img src="{{ asset('assets/images/logo/logo-lembata.png') }}" alt="Logo">
                            </a>
                            <div class="ms-2 lh-sm">
                                <strong>MAN 1</strong><br>LEMBATA
                            </div>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        {{-- Dashboard --}}
                        <li class="sidebar-item {{ request()->routeIs('teacher.dashboard.index') ? 'active' : '' }}">
                            <a href="{{ route('teacher.dashboard.index') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- Pengumuman --}}
                        <li
                            class="sidebar-item {{ request()->routeIs('teacher.announcement.index') ? 'active' : '' }}">
                            <a href="{{ route('teacher.announcement.index') }}" class="sidebar-link">
                                <i class="bi bi-megaphone-fill"></i>
                                <span>Pengumuman</span>
                            </a>
                        </li>

                        {{-- Upload Materi --}}
                        <li class="sidebar-item {{ request()->routeIs('teacher.material.index') ? 'active' : '' }}">
                            <a href="{{ route('teacher.material.index') }}" class="sidebar-link">
                                <i class="bi bi-upload"></i>
                                <span>Upload Materi</span>
                            </a>
                        </li>

                        {{-- Kelas --}}
                        <li class="sidebar-item {{ request()->routeIs('teacher.class.index') ? 'active' : '' }}">
                            <a href="{{ route('teacher.class.index') }}" class="sidebar-link">
                                <i class="bi bi-people-fill"></i>
                                <span>Kelas</span>
                            </a>
                        </li>

                        {{-- Input Nilai --}}
                        <li class="sidebar-item {{ request()->routeIs('teacher.grade.index') ? 'active' : '' }}">
                            <a href="{{ route('teacher.grade.index') }}" class="sidebar-link">
                                <i class="bi bi-pencil-fill"></i>
                                <span>Input Nilai</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main" class="layout-navbar">
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">

                            </ul>
                            @php
                                $user = Auth::user();
                                $levels = [
                                    1 => 'Admin',
                                    2 => 'Kesiswaan',
                                    3 => 'Guru',
                                    4 => 'Siswa/Siswi',
                                ];
                            @endphp
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{ $user->name }}</h6>
                                            <p class="mb-0 text-sm text-gray-600">
                                                {{ $levels[$user->level] ?? 'Tidak Diketahui' }}</p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{ asset('assets/images/faces/1.jpg') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                    style="min-width: 11rem">
                                    <li>
                                        <h6 class="dropdown-header">Hello, {{ $user->name }}!</h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <div id="main-content">
                @yield('content')

                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2021 &copy; Mazer</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                    href="http://ahmadsaugi.com">A. Saugi</a></p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- DATATABLE --}}
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('js')
</body>

</html>
