@extends('layouts.admin')
@section('title', 'Products')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Products</div>
  <div>
    <a href="{{ route('admin.products.trash') }}" class="btn-outline" style="padding:10px 20px;font-size:13px;margin-right:8px">Trash ({{ $trashedCount }})</a>
    <a href="{{ route('admin.products.create') }}" class="btn-gold" style="padding:10px 20px;font-size:13px">+ Add Product</a>
  </div>
</div>
<div class="admin-page-sub">Manage your product catalog.</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr><th>Image</th><th>Name</th><th>Category</th><th>Base Price</th><th>Variants</th><th>Actions</th></tr>
    </thead>
    <tbody>
      @forelse($products as $product)
        <tr id="prod-row-{{ $product->id }}">
          <td>
            <div style="width:52px;height:42px;border-radius:6px;overflow:hidden;background:var(--dark4)">
              @if($product->primary_image)
                <img src="{{ str_starts_with($product->primary_image,'http') ? $product->primary_image : Storage::url($product->primary_image) }}"
                     style="width:100%;height:100%;object-fit:cover" loading="lazy">
              @endif
            </div>
          </td>
          <td style="font-weight:500">{{ $product->name }}</td>
          <td><span class="badge" style="background:rgba(201,168,76,.12);color:var(--gold)">{{ $product->category->name ?? '—' }}</span></td>
          <td>
            @if($product->variants->count() > 0)
              ₹{{ number_format($product->variants->min('price')) }}
            @else
              <span style="color:var(--text-dim)">N/A</span>
            @endif
          </td>
          <td>
            <span class="badge" style="background:rgba(201,168,76,.12);color:var(--gold)">{{ $product->variants->count() }} sizes</span>
          </td>
          <td>
            <div class="table-actions">
              <a href="{{ route('admin.products.edit', $product) }}" class="icon-btn" title="Edit">✏</a>
              <button class="icon-btn del" onclick="deleteProduct({{ $product->id }})" title="Delete">🗑</button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" style="text-align:center;color:var(--text-dim);padding:32px">No products yet. <a href="{{ route('admin.products.create') }}" style="color:var(--gold)">Add your first product!</a></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
async function deleteProduct(id) {
  if (!confirm('Delete this product?')) return;
  try {
    await fetch(`/admin/products/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
    });
    document.getElementById(`prod-row-${id}`)?.remove();
    showToast('Product deleted.');
  } catch(e) { showToast('Delete failed.', 'error'); }
}
</script>
@endpush
