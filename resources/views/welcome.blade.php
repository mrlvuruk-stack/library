@extends('layouts.guest')
@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        background: radial-gradient(circle at 50% 50%, #ffffff 0%, #e2e8f0 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }
    
    .login-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        border-top: 4px solid #2563EB;
        width: 100%;
        max-width: 440px;
        padding: 40px;
        text-align: center;
    }
    
    .logo-container {
        margin-bottom: 24px;
        display: flex;
        justify-content: center;
    }
    
    .logo-container img {
        height: 60px;
        object-fit: contain;
    }
    
    .welcome-title {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 8px;
    }
    
    .welcome-subtitle {
        font-size: 14px;
        color: #64748B;
        margin-bottom: 32px;
    }
    
    .form-group {
        text-align: left;
        margin-bottom: 20px;
    }
    
    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
        display: inline-block;
    }
    
    .label-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    
    .label-wrapper label {
        margin-bottom: 0;
    }
    
    .forgot-link {
        font-size: 12px;
        color: #2563EB;
        text-decoration: none;
        font-weight: 500;
    }
    
    .forgot-link:hover {
        text-decoration: underline;
    }
    
    .form-input {
        width: 100%;
        padding: 10px 14px;
        font-size: 14px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        color: #1E293B;
        transition: all 0.2s;
    }
    
    .form-input::placeholder {
        color: #94A3B8;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    
    .btn-signin {
        width: 100%;
        background: #020617;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
        margin-top: 24px;
    }
    
    .btn-signin:hover {
        background: #0f172a;
    }
    
    .btn-signin svg {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }
    
    .request-access {
        font-size: 13px;
        color: #64748B;
        margin-top: 24px;
    }
    
    .request-access a {
        color: #2563EB;
        text-decoration: none;
        font-weight: 600;
    }
    
    .request-access a:hover {
        text-decoration: underline;
    }
    
    /* Footer styling */
    .login-footer {
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
    }
    
    .footer-left {
        font-weight: 600;
        color: #0F172A;
    }
    
    .footer-links {
        display: flex;
        gap: 20px;
    }
    
    .footer-links a {
        color: #64748B;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .footer-links a:hover {
        color: #0F172A;
    }
    
    .footer-right {
        color: #64748B;
    }
    
    /* Error display */
    .alert-danger {
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 13px;
        margin-bottom: 20px;
        text-align: left;
        border: 1px solid #FECACA;
        background: #FEF2F2;
        color: #DC2626;
    }
</style>

<div class="login-container">
    <div class="login-card">
        @php
            $settings = \App\Models\settings::latest()->first();
            $logoUrl = ($settings && $settings->logo) ? asset('images/' . $settings->logo) : asset('images/logo.png');
            $libraryName = $settings->library_name ?? 'Acadivio Library Management';
        @endphp
        <div class="logo-container">
            <img src="{{ $logoUrl }}" alt="Logo">
        </div>
        
        <h2 class="welcome-title">Welcome Back</h2>
        <p class="welcome-subtitle">Sign in to access the library management console.</p>
        
        @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        
        <form action="{{ route('login') }}" method="post">
            @csrf
            
            <div class="form-group">
                <label for="username">Library ID / Email</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="Enter your ID or email" value="{{ old('username') }}" required autofocus autocomplete="username">
            </div>
            
            <div class="form-group">
                <div class="label-wrapper">
                    <label for="password">Password</label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn-signin">
                Sign In
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>
        
        <div class="request-access">
            New librarian? <a href="#">Request Access</a>
        </div>
    </div>
</div>

<footer class="login-footer">
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
        &copy; {{ now()->format("Y") }} {{ $libraryName }}. All rights reserved.
    </div>
</footer>
@endsection
