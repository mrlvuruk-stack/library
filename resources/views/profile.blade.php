@extends('layouts.app')
@section('content')
<style>
    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        border: 1px solid #E2E8F0;
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        padding: 40px 30px;
        text-align: center;
        color: #ffffff;
        position: relative;
    }
    
    .avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 16px;
    }
    
    .profile-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.2);
        background: #f1f5f9;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    
    .profile-name {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .profile-role-badge {
        display: inline-block;
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #60A5FA;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .profile-body {
        padding: 40px;
    }
    
    .custom-file-upload {
        border: 2px dashed #CBD5E1;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #F8FAFC;
    }
    
    .custom-file-upload:hover {
        border-color: #2563EB;
        background: #EFF6FF;
    }
    
    .upload-icon {
        font-size: 24px;
        color: #64748B;
        margin-bottom: 8px;
    }
    
    .upload-text {
        font-size: 13px;
        font-weight: 500;
        color: #334155;
    }
    
    .upload-hint {
        font-size: 11px;
        color: #94A3B8;
        margin-top: 4px;
    }
    
    #avatar-file-input {
        display: none;
    }
    
    .btn-save-profile {
        background: #020617;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }
    
    .btn-save-profile:hover {
        background: #0f172a;
    }
</style>

<div id="admin-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="admin-heading">Librarian Profile</h2>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; margin-bottom: 24px;">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar-wrapper">
                            <img src="{{ $user->avatar ? asset('images/' . $user->avatar) : asset('images/avatar.png') }}" class="profile-avatar-large" id="profile-avatar-preview" alt="Avatar">
                        </div>
                        <h3 class="profile-name">{{ $user->name }}</h3>
                        <span class="profile-role-badge">Active Librarian</span>
                    </div>
                    
                    <div class="profile-body">
                        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required placeholder="Full Name">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Library ID / Username</label>
                                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required placeholder="Username">
                                        @error('username')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="email@example.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label>Profile Picture</label>
                                <label for="avatar-file-input" class="custom-file-upload">
                                    <div class="upload-icon">📷</div>
                                    <div class="upload-text" id="upload-status-text">Click to upload or drag & drop</div>
                                    <div class="upload-hint">PNG, JPG or JPEG (Max 2MB)</div>
                                </label>
                                <input type="file" name="avatar" id="avatar-file-input" accept="image/*">
                                @error('avatar')
                                    <div class="text-danger mt-2" style="font-size: 13px;"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn-save-profile">
                                    Save Profile Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('avatar-file-input');
        const preview = document.getElementById('profile-avatar-preview');
        const statusText = document.getElementById('upload-status-text');
        
        if (fileInput && preview && statusText) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Update preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                    
                    // Update label text
                    statusText.textContent = "Selected: " + file.name;
                }
            });
        }
    });
</script>
@endsection
