<!DOCTYPE html>
<html lang="id" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="color" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>

    @php
        $breadcrumbTitle = trim($__env->yieldContent('title'));
    @endphp
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    
    <!-- Choices JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('template/Solic/dist/assets') }}/js/main.js"></script>
    
    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('template/Solic/dist/assets') }}/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('template/Solic/dist/assets') }}/css/styles.min.css" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="{{ asset('template/Solic/dist/assets') }}/css/icons.css" rel="stylesheet" >

    <!-- Node Waves Css -->
    <link href="{{ asset('template/Solic/dist/assets') }}/libs/node-waves/waves.min.css" rel="stylesheet" > 

    <!-- Simplebar Css -->
    <link href="{{ asset('template/Solic/dist/assets') }}/libs/simplebar/simplebar.min.css" rel="stylesheet" >
    
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('template/Solic/dist/assets') }}/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('template/Solic/dist/assets') }}/libs/@simonwep/pickr/themes/nano.min.css">

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('template/Solic/dist/assets') }}/libs/choices.js/public/assets/styles/choices.min.css">

    <!-- DataTables Css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

    <!-- Custom Style untuk Header dan Sidebar -->
    <style>
        /* CSS Variables untuk warna konsisten */
        :root {
            --primary-color: #f25920 !important;
            --primary-rgb: 242, 89, 32 !important;
            --primary: #f25920 !important;
        }
        
        /* Header Styling - Semua Variasi dengan Selector Kuat */
        header.app-header,
        .app-header,
        .app-header::before,
        .app-header::after,
        .main-header-container,
        .main-header-container::before,
        .main-header-container::after,
        .header-content-left,
        .header-content-left::before,
        .header-content-right,
        .header-content-right::before,
        .header-element,
        .header-link,
        .horizontal-logo,
        body .app-header,
        body .main-header-container {
            background: #f25920 !important;
            background-color: #f25920 !important;
        }

        [class*="header"]:not([class*="dropdown"]):not([class*="menu"]) {
            background: #f25920 !important;
            background-color: #f25920 !important;
        }
        
        /* Sidebar Styling - Semua Variasi dengan Selector Kuat */
        aside.app-sidebar,
        .app-sidebar,
        .app-sidebar::before,
        .app-sidebar::after,
        .main-sidebar-header,
        .main-sidebar-header::before,
        .main-sidebar-header::after,
        .main-sidebar,
        .main-sidebar::before,
        .main-sidebar::after,
        .main-menu-container,
        .main-menu-container::before,
        .main-menu-container::after,
        nav.main-menu-container,
        .main-menu,
        .slide,
        .slide__category,
        .side-menu__item,
        body .app-sidebar,
        body .main-sidebar,
        body .main-sidebar-header {
            background: #f25920 !important;
            background-color: #f25920 !important;
        }
        
        /* Override semua background sidebar dengan selector lebih kuat */
        [class*="sidebar"]:not([class*="dropdown"]),
        [class*="side-menu"],
        [class*="slide"]:not([class*="menu"]) {
            background: #f25920 !important;
            background-color: #f25920 !important;
        }
        
        /* Menu Item Active State */
        .side-menu__item.active,
        .side-menu__item[aria-expanded="true"],
        .slide.active > .side-menu__item,
        .slide.has-sub.active > .side-menu__item,
        .slide.active .side-menu__item {
            background-color: rgba(255, 255, 255, 0.15) !important;
            background: rgba(255, 255, 255, 0.15) !important;
        }
        
        /* Menu Item Hover */
        .side-menu__item:hover,
        .slide:hover > .side-menu__item {
            background-color: rgba(255, 255, 255, 0.1) !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Submenu */
        .slide-menu,
        .slide-menu::before,
        .slide-menu::after,
        .child1,
        .mega-menu,
        .slide-menu.child1,
        .slide-menu.mega-menu {
            background: #f25920 !important;
            background-color: #f25920 !important;
        }
        
        /* Override warna primary dari template dengan selector lebih kuat */
        body .border-primary {
            border-color: #f25920 !important;
        }
        body .text-primary {
            color: #f25920 !important;
        }
        body .bg-primary {
            background-color: #f25920 !important;
        }
        /* Teks PUTIH pada elemen dengan background orange - agar terbaca */
        body .btn-primary,
        body .btn.bg-primary,
        body .badge.bg-primary,
        body .badge[class*="primary"] {
            color: #ffffff !important;
        }
        
        /* Badge dan Notification */
        .badge.bg-primary,
        .header-icon-badge,
        .pulse-danger {
            background-color: #f25920 !important;
            background: #f25920 !important;
            color: #ffffff !important;
        }
        
        /* Link di header */
        .header-link,
        .header-link-icon,
        .header-link i {
            color: #ffffff !important;
        }
        
        /* Teks dan icon di sidebar - pastikan putih di atas orange */
        .app-sidebar .side-menu__label,
        .app-sidebar .side-menu__item,
        .app-sidebar .category-name,
        .app-sidebar .side-menu__angle,
        .app-sidebar a.side-menu__item,
        .app-sidebar .slide-menu a {
            color: #ffffff !important;
        }
        .app-sidebar .side-menu__icon,
        .app-sidebar svg.side-menu__icon {
            fill: #ffffff !important;
            color: #ffffff !important;
        }

        .app-sidebar .menu-notif {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            margin-left: auto;
            border-radius: 999px;
            background: #ffffff !important;
            color: #f25920 !important;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
        }

        .app-sidebar .slide.has-sub > .side-menu__item .menu-notif {
            margin-right: 0.35rem;
        }

        .app-sidebar .slide-menu .slide {
            display: flex;
            align-items: center;
        }

        .app-sidebar .slide-menu .menu-notif {
            margin-left: 0.5rem;
            margin-right: 0.75rem;
        }
        .app-sidebar .slide-left svg,
        .app-sidebar .slide-right svg {
            fill: #ffffff !important;
        }
        
        /* Card Header Styling - Background Putih saja, teks tetap default */
        .card-header,
        .card .card-header,
        .card.custom-card .card-header,
        body .card-header,
        body .card .card-header,
        body .card.custom-card .card-header {
            background-color: #ffffff !important;
            background: #ffffff !important;
        }
        
        /* Override inline styles jika ada - pastikan teks putih pada background orange */
        [style*="background"][style*="orange"],
        [style*="background-color"][style*="orange"],
        [style*="background"][style*="#ff6"],
        [style*="background-color"][style*="#ff6"],
        [style*="background-color"][style*="f25920"],
        [style*="background"][style*="f25920"] {
            background-color: #f25920 !important;
            background: #f25920 !important;
        }
        .modal-header[style*="f25920"] .modal-title,
        .modal-header[style*="f25920"] h1,
        .modal-header[style*="f25920"] h2,
        .modal-header[style*="f25920"] h3,
        .modal-header[style*="f25920"] h4,
        .modal-header[style*="f25920"] h5 {
            color: #ffffff !important;
        }
        
        /* Pastikan tidak ada warna oranye lain yang muncul */
        * {
            --bs-primary: #f25920 !important;
            --bs-primary-rgb: 242, 89, 32 !important;
        }

        /* Page header di area oranye */
        .page-header-breadcrumb {
            position: relative;
            z-index: 1;
            padding-bottom: 2rem;
        }

        .page-header-breadcrumb .page-subtitle {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 36rem;
        }

        .page-header-breadcrumb .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .page-header-breadcrumb .breadcrumb-item a:hover {
            color: #fff !important;
        }

        .page-header-breadcrumb .breadcrumb-item.active {
            color: #fff !important;
        }

        .page-header-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.55) !important;
        }

        .page-header-meta {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.85);
        }
    </style>

<link rel="stylesheet" href="{{ asset('template/Solic/dist/assets') }}/libs/jsvectormap/css/jsvectormap.min.css">

<link rel="stylesheet" href="{{ asset('template/Solic/dist/assets') }}/libs/swiper/swiper-bundle.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

</head>

<body>

    <!-- Loader -->
    <div id="loader" >
        <img src="{{ asset('template/Solic/dist/assets') }}/images/media/loader.svg" alt="">
    </div>
    <!-- Loader -->

    <div class="page">
         <!-- app-header -->
         <header class="app-header">

            <!-- Start::main-header-container -->
            <div class="main-header-container container-fluid">

                <!-- Start::header-content-left -->
                <div class="header-content-left">

                    <!-- Start::header-element -->
                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="index.html" class="header-logo">
                                <img src="{{ asset('template/Solic/dist/assets') }}/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
                                <img src="{{ asset('template/Solic/dist/assets') }}/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
                                <img src="{{ asset('template/Solic/dist/assets') }}/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                                <img src="{{ asset('template/Solic/dist/assets') }}/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
                                <img src="{{ asset('template/Solic/dist/assets') }}/images/brand-logos/desktop-white.png" alt="logo" class="desktop-white">
                                <img src="{{ asset('template/Solic/dist/assets') }}/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
                            </a>
                        </div>
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element">
                        <!-- Start::header-link -->
                        <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                        <!-- End::header-link -->
                    </div>
                    <!-- End::header-element -->

                    @auth
                    <div class="header-element d-none d-lg-flex align-items-center ms-2">
                        <span class=" fs-14 text-white fw-semibold">Selamat datang, {{ Auth::user()->nama }}</span>
                    </div>
                    @endauth

                </div>
                <!-- End::header-content-left -->

                <!-- Start::header-content-right -->
                <div class="header-content-right">

                    <!-- Start::header-element -->
                    <div class="header-element d-flex align-items-center me-3">
                        <div class="d-flex align-items-center text-white gap-3">
                            <span class="fs-14 text-white" id="header-date"></span>
                            <span class="fs-16 fw-semibold text-white" id="header-time"></span>
                        </div>
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    @auth
                    <div class="header-element">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div>
                                <div class="me-sm-2 me-0">
                                    @if(Auth::user()->foto_url)
                                        <img src="{{ Auth::user()->foto_url }}" alt="User" width="32" height="32" class="rounded-circle" style="object-fit: cover;">
                                    @else
                                        <img src="{{ asset('default.jpg') }}" alt="User" width="32" height="32" class="rounded-circle">
                                    @endif
                                </div>
                            </div>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <div class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown  border-transparent dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                            <div class="main-header-profile header-img p-3 border-bottom text-center">
                                <p class="fw-semibold mb-0 lh-1 fs-16">{{ Auth::user()->nama }}</p>
                                <span class="op-7 fw-normal d-block fs-11">{{ Auth::user()->roles->first()?->name ?? 'User' }}</span>
                            </div>
                            <a class="dropdown-item d-flex" href="{{ route('profile.index') }}"><i class="fe fe-user fs-18 me-2 op-7"></i>Profile</a>
                            <a class="dropdown-item d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"><i class="fe fe-log-out fs-18 me-2 op-7"></i>Logout</a>
                            <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                    @endauth  
                    <!-- End::header-element -->

                </div>
                <!-- End::header-content-right -->

            </div>
            <!-- End::main-header-container -->

        </header>
        <!-- /app-header -->
        <!-- Start::app-sidebar -->
        <aside class="app-sidebar sticky" id="sidebar">

            <!-- Start::main-sidebar-header -->
            <div class="main-sidebar-header">
                <a href="{{ route('dashboard') }}" class="header-logo">
                    <img src="{{ asset('icons.png') }}" alt="VEKTOR Logo" class="desktop-logo" style="width: 160px; height: 120px; object-fit: contain;">
                    <img src="{{ asset('icons.png') }}" alt="VEKTOR Logo" class="toggle-logo" style="width: 160px; height: 120px; object-fit: contain;">
                    <img src="{{ asset('icons.png') }}" alt="VEKTOR Logo" class="desktop-dark" style="width: 160px; height: 120px; object-fit: contain;">
                    <img src="{{ asset('icons.png') }}" alt="VEKTOR Logo" class="toggle-dark" style="width: 160px; height: 120px; object-fit: contain;">
                    <img src="{{ asset('icons.png') }}" alt="VEKTOR Logo" class="desktop-white" style="width: 160px; height: 120px; object-fit: contain;">
                    <img src="{{ asset('icons.png') }}" alt="VEKTOR Logo" class="toggle-white" style="width: 160px; height: 120px; object-fit: contain;">
                </a>
            </div>
            <!-- End::main-sidebar-header -->

            <!-- Start::main-sidebar -->
            <div class="main-sidebar" id="sidebar-scroll">

                <!-- Start::nav -->
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
                    </div>
                    <ul class="main-menu">
                        <!-- Start::slide__category -->
                        <li class="slide__category" ><span class="category-name text-white">Menu Utama</span></li>
                        <!-- End::slide__category -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('dashboard') }}" class="side-menu__item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586l6-6z"></path></svg>
                                <span class="side-menu__label text-white">Dashboard</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        @can('view-aset')
                        <li class="slide">
                            <a href="{{ route('aset.index') }}" class="side-menu__item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M17 6h2v2h-2zm0 4h2v2h-2zm-4-4h2v2h-2zm0 4h2v2h-2zM9 6h2v2H9zm0 4h2v2H9zm-4 0h2v2H5zm0-4h2v2H5zm12 8H5c-1.103 0-2-.897-2-2V6c0-1.103.897-2 2-2h12c1.103 0 2 .897 2 2v6c0 1.103-.897 2-2 2zm0-8H5v6h12V6z"></path></svg>
                                <span class="side-menu__label text-white">Data Aset</span>
                            </a>
                        </li>
                        @endcan

                        @can('view-pengajuan')
                        <li class="slide">
                            <a href="{{ route('pengajuan.list') }}" class="side-menu__item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M19.903 8.586a.997.997 0 0 0-.196-.293l-6-6a.997.997 0 0 0-.293-.196c-.03-.014-.062-.022-.094-.033a.991.991 0 0 0-.259-.051C13.04 2.011 13.021 2 13 2H6c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2V9c0-.021-.011-.04-.013-.062a.952.952 0 0 0-.051-.259c-.01-.032-.019-.063-.033-.093zM16.586 8H14V5.414L16.586 8zM6 20V4h6v5a1 1 0 0 0 1 1h5l.002 10H6z"></path></svg>
                                <span class="side-menu__label text-white">Peminjaman</span>
                                @hasanyrole('Admin|Security')
                                @if(($peminjamanDecisionCount ?? 0) > 0)
                                    <span class="menu-notif">{{ $peminjamanDecisionCount > 99 ? '99+' : $peminjamanDecisionCount }}</span>
                                @endif
                                @endhasanyrole
                            </a>
                        </li>
                        @endcan

                        @can('view-approval')
                        <li class="slide">
                            <a href="{{ route('approval.index') }}" class="side-menu__item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z"></path></svg>
                                <span class="side-menu__label text-white">Approval</span>
                                @hasanyrole('Admin|PIC')
                                @if(($approvalPendingCount ?? 0) > 0)
                                    <span class="menu-notif">{{ $approvalPendingCount > 99 ? '99+' : $approvalPendingCount }}</span>
                                @endif
                                @endhasanyrole
                            </a>
                        </li>
                        @endcan
                        
                        <!-- Start::slide__category -->
                        <li class="slide__category"><span class="category-name text-white">Akun</span></li>
                        <!-- End::slide__category -->
                        
                        @can('view-user')
                        <li class="slide">
                            <a href="{{ route('user') }}" class="side-menu__item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                                <span class="side-menu__label text-white">Data User</span>
                            </a>
                        </li>
                        @endcan

                        @role('Admin')
                        <li class="slide">
                            <a href="{{ route('access-management.index') }}" class="side-menu__item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"></path></svg>
                                <span class="side-menu__label text-white">Akses User</span>
                            </a>
                        </li>
                        @endrole

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="side-menu__item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3z"></path><path d="M20 3h-9c-1.103 0-2 .897-2 2v4h2V5h9v14h-9v-4h-2v4c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2z"></path></svg>
                                <span class="side-menu__label text-white">Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        <!-- End::slide -->

                    </ul>
                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
                </nav>
                <!-- End::nav -->

            </div>
            <!-- End::main-sidebar -->

        </aside>
        <!-- End::app-sidebar -->

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="page-header-breadcrumb text-fixed-white">
                    <div class="d-md-flex d-block align-items-start justify-content-between gap-3">
                        <div class="page-header-left">
                            <h1 class="page-title fw-semibold fs-22 mb-0 text-fixed-white">{{ $breadcrumbTitle }}</h1>
                            @hasSection('page-subtitle')
                                <p class="page-subtitle mb-0 mt-1">@yield('page-subtitle')</p>
                            @endif
                        </div>
                        <div class="page-header-right ms-md-auto">
                            <nav>
                                <ol class="breadcrumb mb-0 justify-content-md-end">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}" class="text-fixed-white">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active text-fixed-white" aria-current="page">
                                        {{ $breadcrumbTitle }}
                                    </li>
                                </ol>
                            </nav>
                            @hasSection('page-actions')
                                <div class="page-header-actions d-flex align-items-center gap-3 flex-wrap justify-content-md-end mt-2">
                                    @yield('page-actions')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Page Header Close -->
                 @yield('content')


            </div>
        </div>
        <!-- End::app-content -->

        <!-- Footer Start -->
        <!-- <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);" class="text-dark fw-medium">BBT</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);">
                        <span class="fw-medium text-primary text-decoration-underline">Spruko</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer> -->
        <!-- Footer End -->

    </div>

    
    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top -->

    <!-- Popper JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/@popperjs/core/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Defaultmenu JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/js/defaultmenu.min.js"></script>

    <!-- Node Waves JS-->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/node-waves/waves.min.js"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/js/sticky.js"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('template/Solic/dist/assets') }}/js/simplebar.js"></script>

    <!-- Color Picker JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/@simonwep/pickr/pickr.es5.min.js"></script>

  

    <!-- JSVector Maps JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/jsvectormap/js/jsvectormap.min.js"></script>

    <!-- JSVector Maps MapsJS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/jsvectormap/maps/world-merc.js"></script>

    <!-- Apex Charts JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Swiper JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/libs/swiper/swiper-bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/js/index.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('template/Solic/dist/assets') }}/js/custom.js"></script>

     <!-- jQuery JS -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <!-- Datatables Cdn -->
     <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

  <!-- Internal Datatables JS -->
  <script src="{{ asset('template/Solic/dist/assets') }}/js/datatables.js"></script>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateElement = document.getElementById('header-date');
            const timeElement = document.getElementById('header-time');

            if (!(dateElement && timeElement)) {
                return;
            }

            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jumat", 'Sabtu'];
            const dateFormatter = new Intl.DateTimeFormat('id-ID', {
                year: 'numeric',
                month: 'short',
                day: '2-digit'
            });

            const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });

            const updateDateTime = () => {
                const now = new Date();
                const formattedDate = dateFormatter.format(now);
                dateElement.textContent = `${dayNames[now.getDay()]}, ${formattedDate}`;
                timeElement.textContent = timeFormatter.format(now);
            };

            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Flash Messages -->
    @if (session('success') || session('error'))
    @php
        $swalType = session('success') ? 'success' : 'error';
        $swalMessage = session('success') ?? session('error');
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const type = "{{ $swalType }}";
            const message = <?php echo json_encode($swalMessage, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Berhasil' : 'Terjadi Kesalahan',
                text: message,
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'swal2-border-radius'
                }
            });
        });
    </script>
    @endif

    @auth
    {{-- Notifikasi Real-Time: polling setiap 15 detik --}}
    <script>
    window.addEventListener('load', function () {
        var POLL_INTERVAL = 15000;
        var CSRF_TOKEN    = '{{ csrf_token() }}';
        var UNREAD_URL    = '{{ route("notifications.unread") }}';

        console.log('[NOTIF] Sistem notifikasi aktif. Polling tiap 15 detik.');

        // Buat Toast SweetAlert (fallback ke console jika Swal belum load)
        function makeToast() {
            if (typeof Swal === 'undefined') return null;
            return Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 7000,
                timerProgressBar: true,
                didOpen: function(toast) {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }

        function markRead(id) {
            fetch('/notifications/' + id + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).catch(function() {});
        }

        function showNotification(notif) {
            var Toast = makeToast();
            var icon  = 'info';
            var bg    = '#fff7ed';
            var color = '#c2410c';
            var iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c2410c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';

            if (notif.type === 'pengajuan_diverifikasi') {
                var status = notif.data && notif.data.status ? notif.data.status.toLowerCase() : '';
                if (status === 'approved' || status === 'approve') {
                    icon  = 'success';
                    bg    = '#f0fdf4';
                    color = '#15803d';
                    iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="16 9 10.5 15.5 8 13"></polyline></svg>';
                } else {
                    icon  = 'error';
                    bg    = '#fef2f2';
                    color = '#b91c1c';
                    iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
                }
            }

            console.log('[NOTIF] Tampilkan notifikasi:', notif.title, notif.message);

            if (Toast) {
                Toast.fire({
                    icon: icon,
                    iconHtml: iconSvg,
                    title: '<span style="color:' + color + ';font-weight:700;font-size:0.95rem">' + notif.title + '</span>',
                    html: '<span style="color:#374151;font-size:0.85rem">' + notif.message + '</span>',
                    background: bg,
                    iconColor: color,
                });
            } else {
                // Fallback jika SweetAlert belum load
                console.warn('[NOTIF] SweetAlert belum tersedia, tampilkan fallback.');
                var div = document.createElement('div');
                div.setAttribute('style',
                    'position:fixed;top:20px;right:20px;z-index:99999;' +
                    'background:' + bg + ';border-left:4px solid ' + color + ';' +
                    'padding:14px 18px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,.15);' +
                    'max-width:320px;font-family:sans-serif;cursor:pointer;'
                );
                div.innerHTML = '<strong style="color:' + color + ';display:block;margin-bottom:4px">' + notif.title + '</strong>' +
                    '<span style="color:#374151;font-size:0.85rem">' + notif.message + '</span>';
                div.onclick = function(){ document.body.removeChild(div); };
                document.body.appendChild(div);
                setTimeout(function(){ if(document.body.contains(div)) document.body.removeChild(div); }, 7000);
            }

            markRead(notif.id);
        }

        function pollNotifications() {
            fetch(UNREAD_URL, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
            })
            .then(function(res) {
                if (!res.ok) { return []; }
                return res.json();
            })
            .then(function(notifications) {
                notifications.forEach(function(notif) {
                    showNotification(notif);
                });
            })
            .catch(function() {});
        }

        // Polling pertama setelah 2 detik, lalu setiap 15 detik
        setTimeout(pollNotifications, 2000);
        setInterval(pollNotifications, POLL_INTERVAL);
    });
    </script>
    @endauth

    <!-- Reset Direction to LTR -->
    <script>
        // Reset direction ke LTR secara default
        (function() {
            // Hapus semua setting direction dari localStorage
            const keysToRemove = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key && (key.toLowerCase().includes('direction') || key.toLowerCase().includes('rtl') || key.toLowerCase().includes('ltr'))) {
                    keysToRemove.push(key);
                }
            }
            keysToRemove.forEach(key => localStorage.removeItem(key));
            
            // Hapus juga dari sessionStorage
            const sessionKeysToRemove = [];
            for (let i = 0; i < sessionStorage.length; i++) {
                const key = sessionStorage.key(i);
                if (key && (key.toLowerCase().includes('direction') || key.toLowerCase().includes('rtl') || key.toLowerCase().includes('ltr'))) {
                    sessionKeysToRemove.push(key);
                }
            }
            sessionKeysToRemove.forEach(key => sessionStorage.removeItem(key));
        })();
        
        // document.addEventListener('DOMContentLoaded', function() {
        //     // Pastikan HTML tag selalu LTR
        //     const html = document.documentElement;
        //     html.setAttribute('dir', 'ltr');
        //     html.removeAttribute('dir-rtl');
        //     html.classList.remove('rtl');
        //     html.classList.add('ltr');
            
        //     // Pastikan body selalu LTR
        //     document.body.classList.remove('rtl'); 
        //     document.body.classList.add('ltr');
            
        //     // Reset radio button ke LTR
        //     const ltrRadio = document.getElementById('switcher-ltr');
        //     const rtlRadio = document.getElementById('switcher-rtl');
        //     if (ltrRadio) {
        //         ltrRadio.checked = true;
        //         ltrRadio.disabled = false;
        //     }
        //     if (rtlRadio) {
        //         rtlRadio.checked = false;
        //         rtlRadio.disabled = true;
        //     }
            
        //     // Force reload CSS jika diperlukan
        //     const links = document.querySelectorAll('link[rel="stylesheet"]');
        //     links.forEach(link => {
        //         if (link.href.includes('rtl')) {
        //             link.disabled = true;
        //         }
        //     });
        // });
        
        
        // Monitor perubahan attribute dir
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'dir') {
                    const dir = document.documentElement.getAttribute('dir');
                    if (dir === 'rtl') {
                        document.documentElement.setAttribute('dir', 'ltr');
                        document.body.classList.remove('rtl');
                        document.body.classList.add('ltr');
                    }
                }
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['dir']
            });
        });
    </script>
    
    <!-- Force Override Warna Header dan Sidebar -->
    <style>
        /* Final Override - Pastikan semua elemen menggunakan #f25920 */
        html body header.app-header,
        html body .app-header,
        html body .header-content-left,
        html body .header-content-right,
        html body aside.app-sidebar,
        html body .app-sidebar,
        html body .main-sidebar-header,
        html body .main-sidebar,
        html body .main-menu-container,
        html body nav.main-menu-container,
        html body .main-menu,
        html body .slide,
        html body .slide__category,
        html body .side-menu__item,
        html body .slide-menu,
        html body .slide-menu.child1,
        html body .slide-menu.mega-menu {
            background: #f25920 !important;
            background-color: #f25920 !important;
        }
        
        /* Override dengan JavaScript untuk memastikan */
        html body [class*="header"]:not([class*="dropdown-menu"]):not([class*="dropdown-item"]):not([class*="card-header"]),
        html body [class*="sidebar"]:not([class*="dropdown-menu"]):not([class*="dropdown-item"]),
        html body [class*="side-menu"]:not([class*="dropdown-menu"]):not([class*="dropdown-item"]) {
            background: #f25920 !important;
            background-color: #f25920 !important;
        }
        
        /* Pastikan card-header tetap putih */
        html body .card-header,
        html body .card .card-header,
        html body .card.custom-card .card-header {
            background: #ffffff !important;
            background-color: #ffffff !important;
        }

        /* Fix SweetAlert2 Toast Icon distortion & template line overflow */
        .swal2-toast .swal2-icon {
            border: none !important;
            background: transparent !important;
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            min-height: 32px !important;
            margin: 0 12px 0 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: visible !important;
            box-shadow: none !important;
            transform: none !important;
        }
        .swal2-toast .swal2-icon .swal2-icon-content {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .swal2-toast .swal2-icon::before,
        .swal2-toast .swal2-icon::after,
        .swal2-toast .swal2-icon .swal2-x-mark,
        .swal2-toast .swal2-icon .swal2-success-ring,
        .swal2-toast .swal2-icon .swal2-success-fix,
        .swal2-toast .swal2-icon [class^='swal2-success-line'] {
            display: none !important;
        }
    </style>
    
    <script>
        // Force update warna setelah semua CSS dimuat
        document.addEventListener('DOMContentLoaded', function() {
            function forceColorUpdate() {
                const targetColor = '#f25920';
                
                // Update Header (kecuali card-header)
                const headerElements = document.querySelectorAll('.app-header, .main-header-container, .header-content-left, .header-content-right, [class*="header"]:not([class*="dropdown-menu"]):not([class*="card-header"])');
                headerElements.forEach(el => {
                    if (el && !el.closest('.dropdown-menu') && !el.classList.contains('card-header')) {
                        el.style.setProperty('background-color', targetColor, 'important');
                        el.style.setProperty('background', targetColor, 'important');
                    }
                });
                
                // Update Sidebar
                const sidebarElements = document.querySelectorAll('.app-sidebar, .main-sidebar-header, .main-sidebar, .main-menu-container, [class*="sidebar"], [class*="side-menu"], [class*="slide"]:not([class*="menu"]:not([class*="side-menu"]))');
                sidebarElements.forEach(el => {
                    if (el && !el.closest('.dropdown-menu') && !el.classList.contains('menu-notif')) {
                        el.style.setProperty('background-color', targetColor, 'important');
                        el.style.setProperty('background', targetColor, 'important');
                    }
                });
                
                // Pastikan card-header tetap putih
                const cardHeaders = document.querySelectorAll('.card-header, .card .card-header, .card.custom-card .card-header');
                cardHeaders.forEach(el => {
                    if (el) {
                        el.style.setProperty('background-color', '#ffffff', 'important');
                        el.style.setProperty('background', '#ffffff', 'important');
                    }
                });
            }
            
            // Jalankan segera
            forceColorUpdate();
            
            // Jalankan lagi setelah delay kecil untuk memastikan
            setTimeout(forceColorUpdate, 100);
            setTimeout(forceColorUpdate, 500);
            
            // Monitor perubahan DOM
            const observer = new MutationObserver(function(mutations) {
                forceColorUpdate();
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['class', 'style']
            });
        });
    </script>
    
</body>

</html>