<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Daftar Produk yang Tersimpan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Daftar Produk Tersimpan</h4>
                    </div>
                    <div class="card-body">
                        @if (count($products) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" id="savedProductsTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 20%">Produk</th>
                                            <th style="width: 65%">Deskripsi & Gambar Produk</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    @if (count($product->categories) > 0)
                                                        <div class="product-categories">
                                                            @foreach ($product->categories as $category)
                                                                <div class="category-item mb-3 pb-2 border-bottom">
                                                                    <div class="fw-bold mb-1">{{ $category->description }}
                                                                    </div>

                                                                    @if (count($category->images) > 0)
                                                                        <div class="d-flex flex-wrap">
                                                                            @foreach ($category->images as $image)
                                                                                <div class="me-2 mb-2">
                                                                                    <img src="{{ asset('storage/' . $image->path) }}"
                                                                                        class="img-thumbnail"
                                                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="text-muted small">Tidak ada
                                                                            gambar</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Tidak ada deskripsi</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Kolom ini dibiarkan kosong atau dapat digunakan untuk aksi lainnya -->
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm delete-saved-product"
                                                        data-id="{{ $product->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Belum ada produk yang tersimpan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Tambah Produk Baru -->
                <div class="card">
                    <div class="card-header">
                        <h4>Product Description Section - Green Mart</h4>
                    </div>
                    <div class="card-body">
                        <form id="productForm" enctype="multipart/form-data">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="productTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 20%">Produk</th>
                                            <th style="width: 65%">Deskripsi & Gambar Produk</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productTableBody">
                                        <!-- Product rows will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-3" id="addProductBtnContainer">
                                <button type="button" class="btn btn-primary" id="addProductBtn">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </button>
                            </div>

                            <div class="alert alert-warning mt-3 d-none" id="maxProductAlert">
                                Anda Sudah Mencapai Maksimum Input
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success">Simpan Produk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Image Confirmation Modal -->
    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteImageModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda Yakin untuk Menghapus Gambar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #808080; color: white;"
                        data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="btn" style="background-color: #D22B2B; color: white;"
                        id="confirmDeleteImage">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates for dynamic content -->
    <template id="productRowTemplate">
        <tr class="product-row">
            <td class="product-number text-center"></td>
            <td>
                <input type="text" class="form-control product-name" name="products[0][name]" required>
            </td>
            <td class="categories-container">
                <!-- Categories will be added here dynamically -->
                <div class="text-center mt-2 add-category-btn-container">
                    <button type="button" class="btn btn-info btn-sm add-category">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="alert alert-warning mt-2 d-none max-category-alert" style="font-size: 12px; padding: 5px;">
                    Anda Sudah Mencapai Maksimum Input
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm delete-product mb-2">
                    <i class="fas fa-times"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm add-row-below">
                    <i class="fas fa-plus"></i>
                </button>
            </td>
        </tr>
    </template>

    <template id="categoryItemTemplate">
        <div class="category-item mb-3 border-bottom pb-2">
            <div class="input-group mb-2">
                <textarea class="form-control category-description" name="products[0][categories][0][description]"
                    placeholder="Deskripsi produk" required></textarea>
                <button type="button" class="btn btn-danger delete-category">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="image-upload-container mb-2">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-secondary upload-btn me-2">
                        <i class="fas fa-upload"></i>
                    </button>
                    <input type="file" class="d-none image-input" name="products[0][categories][0][images][]"
                        accept=".jpg,.jpeg,.png" multiple>
                    <div class="image-preview-container d-flex flex-wrap">
                        <!-- Image previews will be added here dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="imageUploadTemplate">
        <!-- Template tidak digunakan lagi, fungsi upload diintegrasikan dalam template kategori -->
    </template>

    <template id="imagePreviewTemplate">
        <div class="image-preview mb-2">
            <div class="d-flex align-items-center">
                <img src="" class="preview-img me-2" alt="Product Image"
                    style="width: 60px; height: 60px; object-fit: cover;">
                <button type="button" class="btn btn-danger btn-sm delete-image">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let productCount = 0;
            const MAX_PRODUCTS = 5;
            const MAX_CATEGORIES = 3;

            // Add Product
            $('#addProductBtn').on('click', function() {
                if (productCount >= MAX_PRODUCTS) {
                    $('#maxProductAlert').removeClass('d-none');
                    return;
                }

                addProduct();

                if (productCount >= MAX_PRODUCTS) {
                    $('#addProductBtnContainer').addClass('d-none');
                    $('#maxProductAlert').removeClass('d-none');
                }
            });

            // Add initial product
            addProduct();

            // Add Product function
            function addProduct() {
                const template = document.getElementById('productRowTemplate').content.cloneNode(true);
                const productRow = template.querySelector('.product-row');

                productCount++;

                // Update name attributes
                const productIndex = productCount - 1;
                productRow.querySelector('.product-name').setAttribute('name', `products[${productIndex}][name]`);
                productRow.querySelector('.product-number').textContent = productCount;

                // Add category button functionality
                const addCategoryBtn = productRow.querySelector('.add-category');
                addCategoryBtn.addEventListener('click', function() {
                    const categoriesContainer = this.closest('.categories-container');
                    const categoryCount = categoriesContainer.querySelectorAll('.category-item').length;

                    if (categoryCount >= MAX_CATEGORIES) {
                        categoriesContainer.querySelector('.max-category-alert').classList.remove('d-none');
                        return;
                    }

                    addCategory(categoriesContainer, productIndex, categoryCount);

                    if (categoryCount + 1 >= MAX_CATEGORIES) {
                        categoriesContainer.querySelector('.add-category-btn-container').classList.add(
                            'd-none');
                        categoriesContainer.querySelector('.max-category-alert').classList.remove('d-none');
                    }
                });

                // Add image upload container
                const imageContainer = productRow.querySelector('.image-container');
                //addImageUpload(imageContainer, productIndex, 0);

                // Delete product functionality
                productRow.querySelector('.delete-product').addEventListener('click', function() {
                    this.closest('.product-row').remove();
                    productCount--;
                    updateProductNumbers();

                    if (productCount < MAX_PRODUCTS) {
                        $('#addProductBtnContainer').removeClass('d-none');
                        $('#maxProductAlert').addClass('d-none');
                    }
                });

                // Delete saved product functionality
                $('.delete-saved-product').off('click').on('click', function() {
                    const productId = $(this).data('id');
                    const row = $(this).closest('tr');

                    if (confirm('Yakin ingin menghapus produk ini?')) {
                        $.ajax({
                            url: '/products/' + productId,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    row.remove();
                                    alert('Produk berhasil dihapus');

                                    // Reorder numbering
                                    $('#savedProductsTable tbody tr').each(function(index) {
                                        $(this).find('td:first').text(index + 1);
                                    });
                                }
                            },
                            error: function() {
                                alert('Gagal menghapus produk');
                            }
                        });
                    }
                });

                // Add row below functionality
                productRow.querySelector('.add-row-below').addEventListener('click', function() {
                    if (productCount >= MAX_PRODUCTS) {
                        $('#maxProductAlert').removeClass('d-none');
                        return;
                    }

                    const newRow = addProduct();
                    $(this).closest('.product-row').after(newRow);
                    updateProductNumbers();

                    if (productCount >= MAX_PRODUCTS) {
                        $('#addProductBtnContainer').addClass('d-none');
                        $('#maxProductAlert').removeClass('d-none');
                    }
                });

                $('#productTableBody').append(productRow);
                return productRow;
            }

            // Add Category function
            function addCategory(container, productIndex, categoryIndex) {
                const template = document.getElementById('categoryItemTemplate').content.cloneNode(true);
                const categoryItem = template.querySelector('.category-item');

                // Update name attributes
                categoryItem.querySelector('.category-description').setAttribute(
                    'name', `products[${productIndex}][categories][${categoryIndex}][description]`
                );

                categoryItem.querySelector('.image-input').setAttribute(
                    'name', `products[${productIndex}][categories][${categoryIndex}][images][]`
                );

                // Delete category functionality
                categoryItem.querySelector('.delete-category').addEventListener('click', function() {
                    const categoriesContainer = this.closest('.categories-container');
                    this.closest('.category-item').remove();

                    const remainingCategories = categoriesContainer.querySelectorAll('.category-item');

                    // Update name attributes for remaining categories
                    remainingCategories.forEach((cat, idx) => {
                        cat.querySelector('.category-description').setAttribute(
                            'name', `products[${productIndex}][categories][${idx}][description]`
                        );

                        cat.querySelector('.image-input').setAttribute(
                            'name', `products[${productIndex}][categories][${idx}][images][]`
                        );
                    });

                    if (remainingCategories.length < MAX_CATEGORIES) {
                        categoriesContainer.querySelector('.add-category-btn-container').classList.remove(
                            'd-none');
                        categoriesContainer.querySelector('.max-category-alert').classList.add('d-none');
                    }
                });

                // Image upload functionality
                const uploadBtn = categoryItem.querySelector('.upload-btn');
                const inputFile = categoryItem.querySelector('.image-input');
                const previewContainer = categoryItem.querySelector('.image-preview-container');

                uploadBtn.addEventListener('click', function() {
                    inputFile.click();
                });

                inputFile.addEventListener('change', function(event) {
                    if (this.files) {
                        Array.from(this.files).forEach(file => {
                            if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                                alert('Hanya file JPG, JPEG dan PNG yang diperbolehkan');
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewTemplate = document.getElementById(
                                    'imagePreviewTemplate').content.cloneNode(true);
                                const previewItem = previewTemplate.querySelector(
                                    '.image-preview');

                                previewItem.querySelector('.preview-img').src = e.target.result;

                                // Delete image preview functionality
                                previewItem.querySelector('.delete-image').addEventListener(
                                    'click',
                                    function() {
                                        const imageToDelete = this.closest(
                                            '.image-preview');

                                        $('#deleteImageModal').modal('show');

                                        $('#confirmDeleteImage').off('click').on('click',
                                            function() {
                                                imageToDelete.remove();
                                                $('#deleteImageModal').modal('hide');
                                            });
                                    });

                                previewContainer.appendChild(previewItem);
                            };

                            reader.readAsDataURL(file);
                        });
                    }
                });

                // Insert before the add button
                const addBtn = container.querySelector('.add-category-btn-container');
                container.insertBefore(categoryItem, addBtn);
            }

            // Add Image Upload function
            function addImageUpload(container, productIndex, categoryIndex) {
                const template = document.getElementById('imageUploadTemplate').content.cloneNode(true);
                const uploadContainer = template.querySelector('.image-upload-container');

                const uploadBtn = uploadContainer.querySelector('.upload-btn');
                const inputFile = uploadContainer.querySelector('.image-input');

                inputFile.setAttribute(
                    'name', `products[${productIndex}][images][]`
                );

                uploadBtn.addEventListener('click', function() {
                    inputFile.click();
                });

                inputFile.addEventListener('change', function(event) {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];

                        if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                            alert('Hanya file JPG, JPEG dan PNG yang diperbolehkan');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewTemplate = document.getElementById('imagePreviewTemplate')
                                .content.cloneNode(true);
                            const previewItem = previewTemplate.querySelector('.image-preview');

                            previewItem.querySelector('.preview-img').src = e.target.result;

                            // Delete image preview functionality
                            previewItem.querySelector('.delete-image').addEventListener('click',
                                function() {
                                    const imageToDelete = this.closest('.image-preview');

                                    $('#deleteImageModal').modal('show');

                                    $('#confirmDeleteImage').off('click').on('click', function() {
                                        imageToDelete.remove();
                                        $('#deleteImageModal').modal('hide');
                                    });
                                });

                            container.appendChild(previewItem);
                        };

                        reader.readAsDataURL(file);
                    }
                });

                //container.appendChild(uploadContainer);
            }

            // Update product numbers
            function updateProductNumbers() {
                $('.product-row').each(function(index) {
                    $(this).find('.product-number').text(index + 1);

                    // Update name attributes for this product
                    const productIndex = index;
                    const productName = $(this).find('.product-name');
                    productName.attr('name', `products[${productIndex}][name]`);

                    // Update categories
                    $(this).find('.category-item').each(function(catIndex) {
                        $(this).find('.category-description').attr(
                            'name',
                            `products[${productIndex}][categories][${catIndex}][description]`
                        );
                    });

                    // Update image inputs
                    $(this).find('.image-input').attr(
                        'name', `products[${productIndex}][images][]`
                    );
                });
            }

            // Submit form
            $('#productForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route('products.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Produk berhasil disimpan!');
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        alert('Error: ' + (response.message || 'Terjadi kesalahan'));
                        console.error(response.errors);
                    }
                });
            });
        });
    </script>
@endsection
