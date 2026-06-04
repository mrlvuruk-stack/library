@extends('layouts.app')
@section('content')
    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="admin-heading">Library Profile & Settings</h2>
                </div>
            </div>
            
            @if(session('success'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; margin-bottom: 24px;">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border" style="border-radius: 12px; background: #fff; overflow: hidden; margin-bottom: 30px;">
                        <div class="card-header" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); padding: 25px; color: white;">
                            <h4 style="margin: 0; font-weight: 700;">Edit Profile & Rules</h4>
                        </div>
                        <div class="card-body" style="background: white; padding: 30px; opacity: 1;">
                            <form action="{{ route('settings') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <!-- Left Column: General Library Profile -->
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 600; color: #374151;">Library Name (पुस्तकालय का नाम)</label>
                                                    <input type="text" class="form-control" name="library_name" value="{{ $data->library_name ?? 'Acadivio Library' }}" required>
                                                    @error('library_name')
                                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 600; color: #374151;">Email Address (ईमेल)</label>
                                                    <input type="email" class="form-control" name="email" value="{{ $data->email ?? 'contact@acadivio.com' }}" required>
                                                    @error('email')
                                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 600; color: #374151;">Contact Phone (फोन)</label>
                                                    <input type="text" class="form-control" name="phone" value="{{ $data->phone ?? '1234567890' }}" required>
                                                    @error('phone')
                                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 600; color: #374151;">Library Address (पता)</label>
                                                    <input type="text" class="form-control" name="address" value="{{ $data->address ?? '123 Central St' }}" required>
                                                    @error('address')
                                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 15px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 600; color: #374151;">Return Days Limit (किताब लौटाने के दिन)</label>
                                                    <input type="number" class="form-control" name="return_days" value="{{ $data->return_days }}" required>
                                                    @error('return_days')
                                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 600; color: #374151;">Fine per Day (in Rs.) (प्रतिदिन का जुर्माना)</label>
                                                    <input type="number" class="form-control" name="fine" value="{{ $data->fine }}" required>
                                                    @error('fine')
                                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column: Logo Upload with Cropper -->
                                    <div class="col-md-4 border-left">
                                        <div class="form-group text-center" style="padding-left: 20px;">
                                            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 15px;">Library Logo</label>
                                            
                                            <div class="mb-3">
                                                <img id="avatar-preview" src="{{ ($data && $data->logo) ? asset('images/' . $data->logo) : asset('images/logo.png') }}" 
                                                     class="border shadow-sm" 
                                                     style="width: 140px; height: 140px; object-fit: contain; background: #f8fafc; padding: 10px; border-radius: 12px;">
                                            </div>

                                            <label for="upload-image" class="btn btn-primary" style="cursor: pointer; border-radius: 8px; font-size: 13px; background: #2563EB; border: none;">
                                                Change Logo
                                            </label>
                                            <input type="file" id="upload-image" accept="image/*" style="display: none;">
                                            <input type="hidden" name="logo" id="cropped-image-data">
                                            <span class="d-block text-muted" style="font-size: 11px; margin-top: 5px;">Supports PNG, JPG, JPEG</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4" style="border-top: 1px solid #E2E8F0; padding-top: 20px;">
                                    <div class="col-md-12 text-right">
                                        <input type="submit" class="btn btn-danger" value="Save Profile Changes" style="padding: 12px 30px; font-weight: 700; border-radius: 8px;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropper-modal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>Crop Library Logo</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="custom-modal-body">
                <div class="img-container">
                    <img id="cropper-image" src="" style="max-width: 100%; max-height: 400px; display: block;">
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" id="crop-cancel-btn" class="btn btn-secondary">Cancel</button>
                <button type="button" id="crop-btn" class="btn btn-danger">Crop & Save</button>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let cropper;
            const uploadImage = document.getElementById('upload-image');
            const cropperModal = document.getElementById('cropper-modal');
            const cropperImage = document.getElementById('cropper-image');
            const avatarPreview = document.getElementById('avatar-preview');
            const croppedImageData = document.getElementById('cropped-image-data');
            const closeModal = document.querySelector('.close-modal');
            const cropCancelBtn = document.getElementById('crop-cancel-btn');
            const cropBtn = document.getElementById('crop-btn');

            uploadImage.addEventListener('change', function (e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        cropperImage.src = event.target.result;
                        cropperModal.style.display = 'flex';
                        
                        if (cropper) {
                            cropper.destroy();
                        }
                        
                        cropper = new Cropper(cropperImage, {
                            aspectRatio: 1, // Square crop for brand logo
                            viewMode: 1,
                            background: false
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            function closeCropper() {
                cropperModal.style.display = 'none';
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                uploadImage.value = '';
            }

            closeModal.addEventListener('click', closeCropper);
            cropCancelBtn.addEventListener('click', closeCropper);

            cropBtn.addEventListener('click', function () {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        width: 250,
                        height: 250
                    });
                    
                    const dataURL = canvas.toDataURL('image/png');
                    avatarPreview.src = dataURL;
                    croppedImageData.value = dataURL;
                    
                    cropperModal.style.display = 'none';
                    cropper.destroy();
                    cropper = null;
                }
            });
        });
    </script>
@endsection
