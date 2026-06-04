@php
    $settings = \App\Models\settings::latest()->first();
    $logoUrl = ($settings && $settings->logo) ? asset('images/' . $settings->logo) : asset('images/logo.png');
    $libraryName = $settings->library_name ?? 'Acadivio Library Management';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>{{ $libraryName }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom stlylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }} ">
</head>

<body>
    <!-- Custom Style Overrides -->
    <style>
        body {
            background-color: #F8FAFC !important;
            font-family: 'Inter', sans-serif !important;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Modern unified navbar */
        .unified-navbar {
            background: #ffffff;
            border-bottom: 1px solid #E2E8F0;
            padding: 0 40px;
            height: 72px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .nav-brand-logo {
            height: 32px;
            width: 32px;
            object-fit: contain;
        }
        
        .nav-brand-text {
            font-size: 16px;
            font-weight: 700;
            color: #0F172A;
        }
        
        .nav-brand:hover {
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            height: 100%;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .nav-links li {
            height: 100%;
            display: flex;
            align-items: center;
        }
        
        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            color: #64748B;
            text-decoration: none;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 4px;
            position: relative;
            transition: color 0.2s;
        }
        
        .nav-links a:hover {
            color: #0F172A;
            text-decoration: none;
        }
        
        .nav-links a.active {
            color: #0F172A;
            font-weight: 600;
            text-decoration: none;
        }
        
        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: #0F172A;
            border-radius: 2px;
        }
        
        .nav-user {
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
        }
        
        .nav-logout-btn {
            border: 1px solid #EF4444;
            color: #EF4444;
            background: transparent;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .nav-logout-btn:hover {
            background: #EF4444;
            color: #ffffff;
            text-decoration: none;
        }
        
        .nav-avatar-btn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            outline: none;
        }
        
        .nav-avatar-btn:focus {
            outline: none;
        }
        
        .nav-avatar {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #E2E8F0;
            transition: border-color 0.2s;
        }
        
        .nav-avatar:hover {
            border-color: #CBD5E1;
        }
        
        /* User dropdown */
        .user-dropdown-menu {
            position: absolute;
            top: 50px;
            right: 0;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
            width: 200px;
            display: none;
            flex-direction: column;
            padding: 6px;
            z-index: 1100;
        }
        
        .user-dropdown-menu a, .user-dropdown-menu button {
            padding: 10px 12px;
            font-size: 14px;
            color: #334155;
            text-decoration: none;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: left;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .user-dropdown-menu a:hover, .user-dropdown-menu button:hover {
            background: #F1F5F9;
            color: #0F172A;
            text-decoration: none;
        }
        
        /* Global layout wrap */
        .app-main-content {
            flex: 1;
        }
        
        /* Footer redesign */
        .app-footer {
            background: #ffffff;
            border-top: 1px solid #E2E8F0;
            padding: 24px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 13px;
            color: #64748B;
            margin-top: auto;
        }
        
        .app-footer .footer-left {
            font-weight: 600;
            color: #0F172A;
        }
        
        .app-footer .footer-links {
            display: flex;
            gap: 20px;
        }
        
        .app-footer .footer-links a {
            color: #64748B;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .app-footer .footer-links a:hover {
            color: #0F172A;
            text-decoration: none;
        }
        
        /* Hide legacy layout containers */
        #header, #menubar, #footer {
            display: none !important;
        }
    </style>

    <!-- Unified Premium Navbar -->
    <nav class="unified-navbar">
        <a href="{{ route('dashboard') }}" class="nav-brand">
            <img src="{{ $logoUrl }}" class="nav-brand-logo" alt="Logo">
            <span class="nav-brand-text">{{ $libraryName }}</span>
        </a>
        
        <ul class="nav-links">
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('authors') }}" class="{{ request()->routeIs('authors*') ? 'active' : '' }}">Authors</a></li>
            <li><a href="{{ route('publishers') }}" class="{{ request()->routeIs('publishers*') ? 'active' : '' }}">Publishers</a></li>
            <li><a href="{{ route('categories') }}" class="{{ request()->routeIs('categories*') ? 'active' : '' }}">Categories</a></li>
            <li><a href="{{ route('books') }}" class="{{ request()->routeIs('books*') ? 'active' : '' }}">Books</a></li>
            <li><a href="{{ route('students') }}" class="{{ request()->routeIs('students*') ? 'active' : '' }}">Reg Students</a></li>
            <li><a href="{{ route('book_issued') }}" class="{{ request()->routeIs('book_issued*') || request()->routeIs('book_issue*') ? 'active' : '' }}">Book Issue</a></li>
            <li><a href="{{ route('reports') }}" class="{{ request()->routeIs('reports*') ? 'active' : '' }}">Reports</a></li>
            <li><a href="{{ route('settings') }}" class="{{ request()->routeIs('settings*') ? 'active' : '' }}">Settings</a></li>
        </ul>
        
        <div class="nav-user">
            <button class="nav-logout-btn" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                Log Out
            </button>
            <button class="nav-avatar-btn" id="avatarBtn">
                <img src="{{ auth()->user()->avatar ? asset('images/' . auth()->user()->avatar) : asset('images/avatar.png') }}" class="nav-avatar" alt="Avatar">
            </button>
            <div class="user-dropdown-menu" id="userDropdown">
                <div style="padding: 10px 12px; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px solid #F1F5F9; margin-bottom: 4px;">
                    Hi {{ auth()->user()->name }}
                </div>
                <a href="{{ route('profile.view') }}">My Profile</a>
                <a href="{{ route('change_password_view') }}">Change Password</a>
                <button onclick="document.getElementById('logoutForm').submit()">Log Out</button>
            </div>
            <form method="post" id="logoutForm" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="app-main-content">
        @yield('content')
    </div>

    <!-- Redesigned Footer -->
    <footer class="app-footer">
        <div class="footer-left">
            {{ $libraryName }}
        </div>
        <div class="footer-links">
            <a href="#">Help</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Support</a>
        </div>
        <div class="footer-right">
            &copy; {{ now()->format("Y") }} Acadivio Library Management. All rights reserved.
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- User dropdown toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarBtn = document.getElementById('avatarBtn');
            const userDropdown = document.getElementById('userDropdown');
            
            if (avatarBtn && userDropdown) {
                avatarBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (userDropdown.style.display === 'flex') {
                        userDropdown.style.display = 'none';
                    } else {
                        userDropdown.style.display = 'flex';
                    }
                });
                
                document.addEventListener('click', function () {
                    userDropdown.style.display = 'none';
                });
                
                userDropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>

</html>
