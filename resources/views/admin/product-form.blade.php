@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('admin-content')
<div class="admin-page-title">{{ isset($product) ? 'Edit Product' : 'Add Product' }}</div>
<div class="admin-page-sub">Fill in the details for your product.</div>

<form method="POST"
      action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      id="productForm">
  @csrf
  @if(isset($product)) @method('PUT') @endif

  <div class="admin-form-grid">
    <div class="admin-form-group">
      <label>Product Name</label>
      <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Custom Acrylic Keychain" required>
      @error('name')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Category</label>
      <select name="category_id" required>
        <option value="">Select Category...</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      @error('category')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>MRP (₹)</label>
      <input type="number" step="0.01" name="mrp" value="{{ old('mrp', $product->mrp ?? '') }}" placeholder="0.00" required>
      @error('mrp')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Discount (%)</label>
      <input type="number" name="discount" value="{{ old('discount', $product->discount ?? 0) }}" placeholder="0" min="0" max="100" required>
      @error('discount')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Stock Quantity</label>
      <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 10) }}" placeholder="10" min="0" required>
      @error('stock')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:20px;color:var(--text);font-size:14px;text-transform:none;letter-spacing:normal">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($product) ? $product->is_active : true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:var(--gold)">
        Product is Active (visible)
      </label>
    </div>

    <div class="admin-form-group">
      <label>Short Tagline</label>
      <input type="text" name="tagline" value="{{ old('tagline', $product->tagline ?? '') }}" placeholder="e.g. Custom engraved, bulk orders">
    </div>

    <div class="admin-form-group full">
      <label>Description</label>
      <textarea name="description" placeholder="Describe the product, materials, customization options...">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <!-- Existing images (edit mode) -->
    @if(isset($product) && !empty($product->images))
      <div class="admin-form-group full">
        <label>Current Images</label>
        <div class="img-preview-grid" id="existingImgGrid">
          @foreach($product->images as $i => $img)
            <div class="img-preview-item" id="existing-img-{{ $i }}">
              <img src="{{ str_starts_with($img,'http') ? $img : Storage::url($img) }}" loading="lazy">
              <input type="hidden" name="existing_images[]" value="{{ $img }}" id="existing-input-{{ $i }}">
              <button type="button" class="del-img" onclick="removeExistingImg({{ $i }})">✕</button>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- New image upload -->
    <div class="admin-form-group full">
      <label>{{ isset($product) ? 'Add More Images' : 'Product Images' }}</label>
      <div class="img-upload-area" id="imgUploadArea">
        <input type="file" accept="image/*" multiple id="imgFileInput" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%" onchange="handleImgUpload(event)">
        <svg width="32" height="32" fill="none" stroke="var(--text-dim)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:8px"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <p style="font-size:13px;color:var(--text-muted)">Click to upload images</p>
        <p style="font-size:11px;color:var(--text-dim);margin-top:4px">JPG, PNG, WEBP — Multiple files supported</p>
      </div>
      <div class="upload-progress hidden" id="uploadProgress">Uploading...</div>
      <div class="img-preview-grid" id="newImgGrid"></div>
      <!-- Hidden inputs for uploaded image paths -->
      <div id="newImgInputs"></div>
    </div>
  </div>

  <div style="display:flex;gap:12px">
    <button type="submit" class="btn-gold" style="padding:12px 32px" id="saveProdBtn">
      {{ isset($product) ? 'Update Product' : 'Save Product' }}
    </button>
    <a href="{{ route('admin.products') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection

@push('scripts')
<script>


let uploadedImages = []; // array of { path, url }

function removeExistingImg(idx) {
  document.getElementById(`existing-img-${idx}`)?.remove();
  // Removing the hidden input means the image won't be in existing_images[]
  document.getElementById(`existing-input-${idx}`)?.remove();
}

async function handleImgUpload(e) {
  const files = Array.from(e.target.files);
  if (!files.length) return;
  const prog = document.getElementById('uploadProgress');
  prog.classList.remove('hidden');
  prog.textContent = 'Uploading...';

  for (const file of files) {
    try {
      const fd = new FormData();
      fd.append('image', file);
      fd.append('_token', window.CSRF_TOKEN);
      const res = await fetch('{{ route("admin.products.upload-image") }}', { method: 'POST', body: fd });
      const data = await res.json();
      uploadedImages.push(data);
      renderNewImagePreviews();
    } catch(err) {
      showToast('Image upload failed.', 'error');
    }
  }

  prog.classList.add('hidden');
  e.target.value = '';
}

function renderNewImagePreviews() {
  document.getElementById('newImgGrid').innerHTML = uploadedImages.map((img, i) =>
    `<div class="img-preview-item"><img src="${img.url}" loading="lazy"><button type="button" class="del-img" onclick="removeNewImg(${i})">✕</button></div>`
  ).join('');

  document.getElementById('newImgInputs').innerHTML = uploadedImages.map(img =>
    `<input type="hidden" name="images_uploaded[]" value="${img.path}">`
  ).join('');
}

function removeNewImg(idx) {
  uploadedImages.splice(idx, 1);
  renderNewImagePreviews();
}
</script>
@endpush
