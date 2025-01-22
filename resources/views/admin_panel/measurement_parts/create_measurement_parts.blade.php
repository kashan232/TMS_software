@include('main_includes.header_include')

<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper">

    <!--**********************************
        Nav header start
    ***********************************-->

    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')
    <!--**********************************
        Content body start
    ***********************************-->

    <div class="content-body rightside-event">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Measurement Part</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Select Category</label>
                                            <select name="category" class="form-control" required>
                                                <option value="" selected disabled>Select One</option>
                                                @foreach($ClothTypes as $type)
                                                <option value="{{ $type->cloth_type_name }}">
                                                    {{ $type->cloth_type_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Measurement Name</label>
                                            <input type="text" class="form-control" placeholder="Measurement Name">
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">Description</label>
                                            <textarea name="Description" id="Description" class="form-control" placeholder="Description"></textarea>
                                        </div>

                                        <!-- Image upload section -->
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Select Image</label>
                                            <input type="file" id="imageInput" class="form-control" accept="image/*">
                                        </div>

                                        <!-- Image preview section -->
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Image Preview</label>
                                            <div id="imagePreview" class="image-preview-box" style="display:none;">
                                                <img id="previewImage" src="" alt="Selected Image" class="img-fluid">
                                                <button type="button" id="cancelImage" class="btn btn-danger mt-2">Cancel</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')

    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cancelImage').addEventListener('click', function() {
            document.getElementById('imageInput').value = ''; // Clear the file input
            document.getElementById('imagePreview').style.display = 'none'; // Hide the preview div
        });
    </script>

    <style>
        .image-preview-box {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .image-preview-box img {
            max-width: 100%;
            max-height: 200px;
        }
        .btn-danger {
            width: 100%;
            padding: 5px;
            font-size: 14px;
        }
    </style>
</div>

