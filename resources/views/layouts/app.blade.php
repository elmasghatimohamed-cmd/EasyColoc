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
                --primary-color: #6366f1;
                --primary-dark: #4f46e5;
                --primary-light: #818cf8;
                --secondary-color: #8b5cf6;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --danger-color: #ef4444;
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
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
                background: var(--gray-50);
                color: var(--gray-900);
                line-height: 1.6;
                font-size: 14px;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            /* Layout Container */
            .app-container {
                display: flex;
                min-height: 100vh;
                background: var(--gray-50);
            }

            /* Sidebar Navigation */
            .sidebar {
                width: 260px;
                background: white;
                border-right: 1px solid var(--gray-200);
                box-shadow: var(--shadow-sm);
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
                background: var(--gray-100);
            }

            .sidebar::-webkit-scrollbar-thumb {
                background: var(--gray-300);
                border-radius: 3px;
            }

            .sidebar::-webkit-scrollbar-thumb:hover {
                background: var(--gray-400);
            }

            .sidebar-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--gray-200);
                background: white;
            }

            .sidebar-logo {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--primary-color);
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .sidebar-nav {
                flex: 1;
                padding: 1rem 0;
                display: flex;
                flex-direction: column;
            }

            .nav-item {
                display: block;
                padding: 0.75rem 1.5rem;
                color: var(--gray-600);
                text-decoration: none;
                font-weight: 500;
                transition: all 0.2s ease;
                border-left: 3px solid transparent;
                position: relative;
            }

            .nav-item:hover {
                background: var(--gray-50);
                color: var(--primary-color);
                border-left-color: var(--primary-color);
            }

            .nav-item.active {
                background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
                color: var(--primary-color);
                border-left-color: var(--primary-color);
                font-weight: 600;
            }

            .nav-item i {
                width: 20px;
                margin-right: 0.75rem;
                text-align: center;
            }

            /* Main Content Area */
            .main-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                background: var(--gray-50);
                min-height: 100vh;
            }

            .top-bar {
                background: white;
                border-bottom: 1px solid var(--gray-200);
                padding: 1rem 2rem;
                box-shadow: var(--shadow-sm);
                display: flex;
                align-items: center;
                justify-content: space-between;
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
                border-radius: 12px;
                box-shadow: var(--shadow);
                border: 1px solid var(--gray-200);
                transition: all 0.2s ease;
                overflow: hidden;
            }

            .card:hover {
                box-shadow: var(--shadow-md);
                transform: translateY(-1px);
            }

            .card-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--gray-200);
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
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: var(--shadow);
                border: 1px solid var(--gray-200);
                transition: all 0.2s ease;
            }

            .stat-card:hover {
                box-shadow: var(--shadow-md);
                transform: translateY(-2px);
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
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

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                box-shadow: var(--shadow-sm);
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
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
                border-radius: 8px;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                background: white;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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
                background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
                color: white;
                border: 1px solid var(--gray-700);
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
                color: var(--gray-500);
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
                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                    @php
                        $activeColocation = auth()->user()->colocations()->wherePivot('left_at', null)->first();
                    @endphp
                    @if($activeColocation)
                        <a href="{{ route('colocations.show', $activeColocation) }}" 
                           class="nav-item {{ request()->routeIs('colocations.*') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            Colocations
                        </a>
                    @endif
                    @if(auth()->user()->is_global_admin)
                        <a href="{{ route('admin.stats') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fas fa-shield-alt"></i>
                            Admin
                        </a>
                    @endif
                    <a href="{{ route('profile.show') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                    
                    <!-- Spacer -->
                    <div class="flex-1"></div>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-gray-200" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
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
                    @if (isset($header))
                        <h1 class="page-title">{{ $header }}</h1>
                    @else
                        <h1 class="page-title">Tableau de bord</h1>
                    @endif
                    
                    <div class="user-menu">
                        <span class="text-muted">{{ auth()->user()->name }}</span>
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
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
