@extends('layouts.admin')
@section('title', 'Products Trash')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Products Trash</div>
  <a href="{{ route('admin.products') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">← Back to Products</a>
</div>
<div class="admin-page-sub">Restore or permanently delete products.</div>

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
            <div class="table-actions" style="display:flex;gap:8px;">
              <form action="{{ route('admin.products.restore', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline" style="padding:4px 10px;font-size:11px;color:#25d366;border-color:rgba(37,211,102,.3)">Restore</button>
              </form>
              <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" onsubmit="return confirm('Permanently delete this product?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-outline" style="padding:4px 10px;font-size:11px;color:#E24B4A;border-color:rgba(226,75,74,.3)">Force Delete</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center;color:var(--text-dim);padding:32px">Trash is empty.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
  // Handled via standard forms above
</script>
@endpush
