@extends('layouts.admin')
@section('title', 'Category Trash')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Category Trash</div>
  <a href="{{ route('admin.categories') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">← Back to Active</a>
</div>
<div class="admin-page-sub">Restore deleted categories or permanently remove them.</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr><th>Name</th><th>Products</th><th>Deleted At</th><th>Actions</th></tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
        <tr id="cat-row-{{ $category->id }}">
          <td style="font-weight:500;color:var(--text-muted)">{{ $category->name }}</td>
          <td><span class="badge" style="background:rgba(255,255,255,.05)">{{ $category->products_count }}</span></td>
          <td style="color:var(--text-dim)">{{ $category->deleted_at->format('d M Y') }}</td>
          <td>
            <div class="table-actions">
              <button class="icon-btn" onclick="restoreCategory({{ $category->id }})" title="Restore" style="color:#25D366;border-color:rgba(37,211,102,.2)">↺</button>
              <button class="icon-btn del" onclick="forceDeleteCategory({{ $category->id }})" title="Permanent Delete">🗑</button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" style="text-align:center;color:var(--text-dim);padding:32px">Trash is empty.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
async function restoreCategory(id) {
  if (!confirm('Restore this category?')) return;
  try {
    const res = await fetch(`/admin/categories/${id}/restore`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
    });
    if (res.ok) {
      document.getElementById(`cat-row-${id}`)?.remove();
      showToast('Category restored.');
      setTimeout(() => window.location.reload(), 1000);
    } else {
      showToast('Restore failed.', 'error');
    }
  } catch(e) { showToast('Request failed.', 'error'); }
}

async function forceDeleteCategory(id) {
  if (!confirm('PERMANENTLY delete this category? This cannot be undone!')) return;
  try {
    const res = await fetch(`/admin/categories/${id}/force-delete`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (res.ok) {
      document.getElementById(`cat-row-${id}`)?.remove();
      showToast('Category permanently deleted.');
    } else {
      showToast(data.error || 'Delete failed.', 'error');
    }
  } catch(e) { showToast('Request failed.', 'error'); }
}
</script>
@endpush
