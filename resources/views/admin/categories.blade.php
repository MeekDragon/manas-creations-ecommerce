@extends('layouts.admin')
@section('title', 'Categories')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Categories</div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.categories.trash') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">Trash ({{ $trashedCount }})</a>
    <a href="{{ route('admin.categories.create') }}" class="btn-gold" style="padding:10px 20px;font-size:13px">+ Add Category</a>
  </div>
</div>
<div class="admin-page-sub">Manage your product categories.</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr><th>Icon</th><th>Name</th><th>Slug</th><th>Products</th><th>Status</th><th>Sort</th><th>Actions</th></tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
        <tr id="cat-row-{{ $category->id }}">
          <td style="font-size:18px">{{ $category->icon ?: '—' }}</td>
          <td style="font-weight:500">{{ $category->name }}</td>
          <td style="color:var(--text-dim)">{{ $category->slug }}</td>
          <td><span class="badge" style="background:rgba(201,168,76,.12);color:var(--gold)">{{ $category->products_count }}</span></td>
          <td>
            @if($category->is_active)
              <span class="badge badge-resolved">Active</span>
            @else
              <span class="badge badge-pending">Inactive</span>
            @endif
          </td>
          <td>{{ $category->sort_order }}</td>
          <td>
            <div class="table-actions">
              <a href="{{ route('admin.categories.edit', $category) }}" class="icon-btn" title="Edit">✏</a>
              <button class="icon-btn del" onclick="deleteCategory({{ $category->id }})" title="Move to Trash">🗑</button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;color:var(--text-dim);padding:32px">No categories yet. <a href="{{ route('admin.categories.create') }}" style="color:var(--gold)">Add your first category!</a></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
async function deleteCategory(id) {
  if (!confirm('Move this category to trash?')) return;
  try {
    const res = await fetch(`/admin/categories/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (res.ok) {
      document.getElementById(`cat-row-${id}`)?.remove();
      showToast('Category moved to trash.');
      setTimeout(() => window.location.reload(), 1000); // Reload to update counts
    } else {
      showToast(data.error || 'Delete failed.', 'error');
    }
  } catch(e) { showToast('Request failed.', 'error'); }
}
</script>
@endpush
