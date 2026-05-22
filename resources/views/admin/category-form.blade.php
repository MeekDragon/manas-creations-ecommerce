@extends('layouts.admin')
@section('title', isset($category) ? 'Edit Category' : 'Add Category')

@section('admin-content')
<div class="admin-page-title">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</div>
<div class="admin-page-sub">Manage category details.</div>

<form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
  @csrf
  @if(isset($category)) @method('PUT') @endif

  <div class="admin-form-grid">
    <div class="admin-form-group">
      <label>Category Name</label>
      <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Trophies" required>
      @error('name')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Icon (Emoji)</label>
      <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="e.g. 🏆">
      @error('icon')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Sort Order</label>
      <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
      <div style="font-size:11px;color:var(--text-dim);margin-top:2px">Lower numbers appear first.</div>
    </div>

    <div class="admin-form-group" style="justify-content:center">
      <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:20px;color:var(--text);font-size:14px;text-transform:none;letter-spacing:normal">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($category) ? $category->is_active : true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:var(--gold)">
        Category is Active (visible to users)
      </label>
    </div>

    <div class="admin-form-group full">
      <label>Description (Optional)</label>
      <textarea name="description" placeholder="Brief description of this category...">{{ old('description', $category->description ?? '') }}</textarea>
      @error('description')<div class="field-error">{{ $message }}</div>@enderror
    </div>
  </div>

  <div style="display:flex;gap:12px;margin-top:20px">
    <button type="submit" class="btn-gold" style="padding:12px 32px">
      {{ isset($category) ? 'Update Category' : 'Save Category' }}
    </button>
    <a href="{{ route('admin.categories') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
