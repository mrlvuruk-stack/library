@extends('layouts.app')
@section('content')
    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">Add Student</h2>
                </div>
                <div class="offset-md-7 col-md-2">
                    <a class="add-new" href="{{ route('students') }}">All Students</a>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-3 col-md-6">
                    <form class="yourform" action="{{ route('student.store') }}" method="post" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" class="form-control" placeholder="Student Name" name="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" placeholder="Address" name="address"
                                value="{{ old('address') }}" required>
                            @error('address')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="male" selected>Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('gender')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <input type="text" class="form-control" placeholder="Class" name="class"
                                value="{{ old('class') }}" required>
                            @error('class')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Branch</label>
                            <input type="text" class="form-control" placeholder="Branch" name="branch"
                                value="{{ old('branch') }}" required>
                            @error('branch')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Student Category</label>
                            <select name="category" class="form-control" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>General</option>
                                <option value="OBC" {{ old('category') == 'OBC' ? 'selected' : '' }}>OBC</option>
                                <option value="SC" {{ old('category') == 'SC' ? 'selected' : '' }}>SC</option>
                                <option value="ST" {{ old('category') == 'ST' ? 'selected' : '' }}>ST</option>
                                <option value="Regular" {{ old('category') == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Scholarship" {{ old('category') == 'Scholarship' ? 'selected' : '' }}>Scholarship</option>
                            </select>
                            @error('category')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Student Photo (Crop Option Available)</label>
                            <input type="file" id="upload-image" class="form-control" accept="image/*" style="padding-top: 5px;">
                            <input type="hidden" name="photo" id="cropped-image-data" value="{{ old('photo') }}">
                            <div class="mt-3 text-center">
                                <div class="d-inline-block position-relative">
                                    <img id="avatar-preview" src="{{ asset('images/avatar.png') }}" 
                                         class="rounded-circle border shadow-sm" 
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                            @error('photo')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" class="form-control" placeholder="Age" name="age"
                                value="{{ old('age') }}" required>
                            @error('age')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="phone" class="form-control" placeholder="Phone" name="phone"
                                value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <input type="submit" name="save" class="btn btn-danger" value="save">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropper-modal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>Crop Profile Image</h3>
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
                            aspectRatio: 1,
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
