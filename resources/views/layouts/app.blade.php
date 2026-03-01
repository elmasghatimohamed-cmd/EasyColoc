<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EasyColoc') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        
        <style>
            :root {
                --primary-color: #3E97FF;
                --primary-dark: #2884EF;
                --primary-light: #6FB1FF;
                --secondary-color: #7239EA;
                --success-color: #50CD89;
                --warning-color: #FFC700;
                --danger-color: #F1416C;
                --gray-50: #f5f8fa;
                --gray-100: #eff2f5;
                --gray-200: #e4e6ef;
                --gray-300: #d8dbe6;
                --gray-400: #b5b5c3;
                --gray-500: #a1a5b7;
                --gray-600: #7e8299;
                --gray-700: #5e6278;
                --gray-800: #3f4254;
                --gray-900: #181c32;

                --sidebar-bg: #ffffff;
                --sidebar-bg-2: #ffffff;
                --sidebar-border: rgba(15, 23, 42, 0.06);
                --sidebar-text: #64748b;
                --sidebar-text-active: #0f172a;
                --sidebar-hover: rgba(15, 23, 42, 0.04);
                --sidebar-active: rgba(62, 151, 255, 0.10);
                --topbar-bg: #ffffff;
                --content-bg: #f1f5f9;
                --sidebar-card-bg: #ffffff;
                --sidebar-card-border: rgba(15, 23, 42, 0.08);
                --radius-lg: 14px;
                --radius-md: 12px;
                --ring: 0 0 0 3px rgba(62, 151, 255, 0.16);
                --shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.06);
                --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            * {
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background: var(--content-bg);
                color: var(--gray-900);
                line-height: 1.6;
                font-size: 14px;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            .page-shell {
                padding: 1.25rem;
            }

            .bg-primary { background: var(--primary-color); }
            .text-primary { color: var(--primary-color); }
            .flex-1 { flex: 1; }

            /* Layout Container */
            .app-container {
                display: flex;
                min-height: 100vh;
                background: var(--content-bg);
            }

            /* Sidebar Navigation */
            .sidebar {
                width: 260px;
                background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--sidebar-bg-2) 100%);
                border-right: 1px solid var(--sidebar-border);
                box-shadow: 0 6px 22px rgba(15, 23, 42, 0.04);
                display: flex;
                flex-direction: column;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }

            /* Custom scrollbar for sidebar */
            .sidebar::-webkit-scrollbar {
                width: 6px;
            }

            .sidebar::-webkit-scrollbar-track {
                background: rgba(15, 23, 42, 0.04);
            }

            .sidebar::-webkit-scrollbar-thumb {
                background: rgba(15, 23, 42, 0.12);
                border-radius: 3px;
            }

            .sidebar::-webkit-scrollbar-thumb:hover {
                background: rgba(15, 23, 42, 0.18);
            }

            .sidebar-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--sidebar-border);
                background: transparent;
            }

            .sidebar-logo {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--gray-900);
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .sidebar-user {
                padding: 1rem 1.5rem;
                border-bottom: 1px solid var(--sidebar-border);
            }

            .sidebar-user-card {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem;
                border-radius: var(--radius-md);
                background: var(--sidebar-card-bg);
                border: 1px solid var(--sidebar-card-border);
                box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            }

            .sidebar-user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                background: rgba(62, 151, 255, 0.12);
                color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 0.875rem;
                flex: 0 0 auto;
            }

            .sidebar-user-name {
                color: var(--gray-900);
                font-weight: 600;
                font-size: 0.875rem;
                line-height: 1.2;
            }

            .sidebar-user-subtitle {
                color: var(--sidebar-text);
                font-size: 0.75rem;
                margin-top: 0.1rem;
            }

            .sidebar-section-label {
                padding: 0.75rem 1.5rem 0.25rem;
                font-size: 0.6875rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: rgba(15, 23, 42, 0.45);
                font-weight: 700;
            }

            .sidebar-logo i {
                color: var(--primary-color);
            }

            .sidebar-nav {
                flex: 1;
                padding: 1rem 0;
                display: flex;
                flex-direction: column;
            }

            .nav-item {
                display: block;
                padding: 0.7rem 1.25rem;
                color: var(--sidebar-text);
                text-decoration: none;
                font-weight: 500;
                transition: all 0.2s ease;
                border-left: 3px solid transparent;
                position: relative;
                margin: 0.15rem 0.75rem;
                border-radius: 12px;
            }

            .nav-item .nav-icon {
                width: 20px;
                margin-right: 0.75rem;
                text-align: center;
                opacity: 0.9;
                display: inline-block;
            }

            .nav-item:hover {
                background: var(--sidebar-hover);
                color: var(--sidebar-text-active);
                border-left-color: transparent;
            }

            .nav-item.active {
                background: var(--sidebar-active);
                color: var(--sidebar-text-active);
                border-left-color: transparent;
                font-weight: 600;
            }

            .nav-item i {
                width: 20px;
                margin-right: 0.75rem;
                text-align: center;
                opacity: 0.9;
            }

            /* Main Content Area */
            .main-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                background: var(--content-bg);
                min-height: 100vh;
            }

            .top-bar {
                background: var(--topbar-bg);
                border-bottom: 1px solid var(--gray-200);
                padding: 1rem 2rem;
                box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
            }

            .topbar-left {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                min-width: 0;
            }

            .topbar-search {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                background: #ffffff;
                border: 1px solid rgba(15, 23, 42, 0.08);
                border-radius: 12px;
                padding: 0.5rem 0.75rem;
                min-width: 280px;
                max-width: 520px;
                width: 40vw;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
            }

            .topbar-search i {
                color: var(--gray-600);
                font-size: 0.875rem;
            }

            .topbar-search input {
                border: none;
                outline: none;
                background: transparent;
                font-size: 0.875rem;
                width: 100%;
                color: var(--gray-900);
            }

            .topbar-search:focus-within {
                border-color: rgba(62, 151, 255, 0.45);
                box-shadow: var(--ring);
            }

            .topbar-right {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex: 0 0 auto;
            }

            .page-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--gray-900);
                margin: 0;
            }

            .user-menu {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--primary-color);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 0.875rem;
            }

            .content-area {
                flex: 1;
                padding: 2rem;
                overflow-y: auto;
            }

            /* Professional Cards */
            .card {
                background: white;
                border-radius: var(--radius-lg);
                box-shadow: var(--shadow-soft);
                border: 1px solid rgba(15, 23, 42, 0.06);
                transition: all 0.2s ease;
                overflow: hidden;
            }

            .card:hover {
                box-shadow: var(--shadow-md);
                transform: translateY(-1px);
            }

            .card-header {
                padding: 1.5rem;
                border-bottom: 1px solid rgba(15, 23, 42, 0.06);
                background: white;
            }

            .card-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: var(--gray-900);
                margin: 0;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-footer {
                padding: 1rem 1.5rem;
                border-top: 1px solid var(--gray-200);
                background: var(--gray-50);
            }

            /* Stats Cards */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: white;
                border-radius: var(--radius-lg);
                padding: 1.5rem;
                box-shadow: var(--shadow-soft);
                border: 1px solid rgba(15, 23, 42, 0.06);
                transition: all 0.2s ease;
            }

            .stat-card:hover {
                box-shadow: var(--shadow-md);
                transform: translateY(-2px);
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .stat-icon.primary {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
                color: white;
            }

            .stat-icon.success {
                background: linear-gradient(135deg, var(--success-color), #34d399);
                color: white;
            }

            .stat-value {
                font-size: 2rem;
                font-weight: 700;
                color: var(--gray-900);
                margin-bottom: 0.25rem;
            }

            .stat-label {
                font-size: 0.875rem;
                color: var(--gray-500);
                font-weight: 500;
            }

            /* Professional Buttons */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                font-size: 0.875rem;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                gap: 0.5rem;
                white-space: nowrap;
            }

            .btn-primary,
            .btn-secondary,
            .btn-success,
            .btn-danger {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                font-size: 0.875rem;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                gap: 0.5rem;
                white-space: nowrap;
            }

            .btn-primary {
                background: var(--primary-color);
                color: white;
                box-shadow: var(--shadow-sm);
            }

            .btn-primary:hover {
                background: var(--primary-dark);
                box-shadow: var(--shadow-md);
                transform: translateY(-1px);
            }

            .btn-secondary {
                background: white;
                color: var(--gray-700);
                border: 1px solid var(--gray-300);
            }

            .btn-secondary:hover {
                background: var(--gray-50);
                border-color: var(--gray-400);
            }

            .btn-success {
                background: var(--success-color);
                color: white;
            }

            .btn-success:hover {
                background: #059669;
            }

            .btn-danger {
                background: var(--danger-color);
                color: white;
            }

            .btn-danger:hover {
                background: #dc2626;
            }

            .btn-sm {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }

            .btn-lg {
                padding: 1rem 2rem;
                font-size: 1rem;
            }

            /* Form Elements */
            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-label {
                display: block;
                font-weight: 600;
                color: var(--gray-700);
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
            }

            .form-control {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid var(--gray-300);
                border-radius: 12px;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                background: white;
            }

            .input-modern {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid var(--gray-300);
                border-radius: 12px;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                background: white;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: var(--ring);
            }

            .input-modern:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: var(--ring);
            }

            .form-control::placeholder {
                color: var(--gray-400);
            }

            /* Tables */
            .table {
                width: 100%;
                border-collapse: collapse;
                background: white;
            }

            .table th {
                text-align: left;
                padding: 1rem;
                font-weight: 600;
                color: var(--gray-700);
                border-bottom: 2px solid var(--gray-200);
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .table td {
                padding: 1rem;
                border-bottom: 1px solid var(--gray-100);
            }

            .table tbody tr:hover {
                background: var(--gray-50);
            }

            /* Dark Theme Cards */
            .dark-card {
                background: linear-gradient(135deg, #2B2B40, #1E1E2D);
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            .dark-card .card-title {
                color: white;
            }

            .dark-card .card-header {
                border-bottom-color: var(--gray-700);
                background: transparent;
            }

            .dark-card .card-body {
                background: transparent;
            }

            /* Utility Classes */
            .text-muted {
                color: var(--gray-600);
            }

            .text-center {
                text-align: center;
            }

            .mb-0 { margin-bottom: 0; }
            .mb-1 { margin-bottom: 0.25rem; }
            .mb-2 { margin-bottom: 0.5rem; }
            .mb-3 { margin-bottom: 0.75rem; }
            .mb-4 { margin-bottom: 1rem; }
            .mb-5 { margin-bottom: 1.25rem; }

            .mt-0 { margin-top: 0; }
            .mt-1 { margin-top: 0.25rem; }
            .mt-2 { margin-top: 0.5rem; }
            .mt-3 { margin-top: 0.75rem; }
            .mt-4 { margin-top: 1rem; }

            .flex { display: flex; }
            .items-center { align-items: center; }
            .justify-between { justify-content: space-between; }
            .gap-2 { gap: 0.5rem; }
            .gap-4 { gap: 1rem; }

            .grid { display: grid; }
            .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
            .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

            .table-responsive {
                width: 100%;
                overflow-x: auto;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .sidebar {
                    position: fixed;
                    left: -260px;
                    top: 0;
                    height: 100vh;
                    z-index: 1000;
                    transition: left 0.3s ease;
                    width: 280px;
                }

                .sidebar.open {
                    left: 0;
                }

                .main-content {
                    margin-left: 0;
                }

                .content-area {
                    padding: 1rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .topbar-search {
                    display: none;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="app-container">
            <!-- Sidebar Navigation -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <div class="sidebar-logo">
                        <i class="fas fa-home"></i>
                        <span>EasyColoc</span>
                    </div>
                </div>
                <div class="sidebar-user">
                    <div class="sidebar-user-card">
                        <div class="sidebar-user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                            <div class="sidebar-user-subtitle">Account</div>
                        </div>
                    </div>
                </div>
                <nav class="sidebar-nav">
                    <div class="sidebar-section-label">Dashboards</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        Dashboard
                    </a>
                    @php
                        $activeColocation = auth()->user()->colocations()->wherePivot('left_at', null)->first();
                    @endphp
                    @if($activeColocation)
                        <div class="sidebar-section-label">Pages</div>
                        <a href="{{ route('colocations.show', $activeColocation) }}" 
                           class="nav-item {{ request()->routeIs('colocations.*') ? 'active' : '' }}">
                            <i class="fas fa-home nav-icon"></i>
                            Colocations
                        </a>
                    @endif
                    @if(auth()->user()->is_global_admin)
                        <a href="{{ route('admin.stats') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fas fa-shield-alt nav-icon"></i>
                            Admin
                        </a>
                    @endif
                    <a href="{{ route('profile.show') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user nav-icon"></i>
                        Profile
                    </a>
                    
                    <!-- Spacer -->
                    <div class="flex-1"></div>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t" style="border-color: var(--sidebar-border)" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
                        @csrf
                        <button type="submit" class="nav-item w-full text-left hover:bg-red-50 hover:text-red-600 hover:border-red-600 group">
                            <i class="fas fa-sign-out-alt"></i>
                            Déconnexion
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Top Bar -->
                <header class="top-bar">
                    <div class="topbar-left">
                        @hasSection('header')
                            @yield('header')
                        @else
                            @if (isset($header))
                                <h1 class="page-title">{{ $header }}</h1>
                            @else
                                <h1 class="page-title">Tableau de bord</h1>
                            @endif
                        @endif
                    </div>

                    <div class="topbar-right">
                        <div class="user-menu">
                            <span class="text-muted">{{ auth()->user()->name }}</span>
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="content-area">
                    @yield('content')
                </div>
            </main>
        </div>

        @stack('modals')
        @livewireScripts

        <script>
            // Mobile sidebar toggle
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.querySelector('.sidebar');
                const toggleBtn = document.createElement('button');
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.className = 'mobile-sidebar-toggle';
                toggleBtn.style.cssText = `
                    position: fixed;
                    top: 1rem;
                    left: 1rem;
                    z-index: 1001;
                    background: var(--primary-color);
                    color: white;
                    border: none;
                    border-radius: 8px;
                    padding: 0.75rem;
                    cursor: pointer;
                    display: none;
                `;

                document.body.appendChild(toggleBtn);

                function checkScreenSize() {
                    if (window.innerWidth <= 768) {
                        toggleBtn.style.display = 'block';
                    } else {
                        toggleBtn.style.display = 'none';
                        sidebar.classList.remove('open');
                    }
                }

                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                });

                window.addEventListener('resize', checkScreenSize);
                checkScreenSize();
            });
        </script>
    </body>
</html>
