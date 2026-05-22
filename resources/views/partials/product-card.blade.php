@php
  $imgs    = $product->images ?? [];
  $mainImg = count($imgs) > 0 ? $imgs[0] : null;
  $wa      = urlencode("Hi, I'm interested in: {$product->name}");
  $desc    = $product->description ?? '';
  $shortDesc = mb_strlen($desc) > 60 ? mb_substr($desc, 0, 60) . '…' : $desc;
  
  $variants = collect($product->variants ?? []);
  $minPrice = $variants->min('price');
  $minVariant = $variants->where('price', $minPrice)->first();
  $minMrp = $minVariant?->mrp;
  $discount = $minVariant?->discount ?: 0;
@endphp
<div class="product-card" onclick="openProductModal({{ $product->id }})">
  <div class="product-img-wrap" style="position:relative">
    @if($mainImg)
      <img src="{{ str_starts_with($mainImg,'http') ? $mainImg : Storage::url($mainImg) }}" alt="{{ $product->name }}" loading="lazy">
    @else
      <div class="product-img-placeholder">
        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <span style="font-size:12px">No image yet</span>
      </div>
    @endif
    @if($variants->isEmpty() || $variants->sum('stock') <= 0)
      <div style="position:absolute;top:10px;right:10px;background:rgba(226,75,74,0.9);color:#fff;font-size:11px;font-weight:700;padding:4px 8px;border-radius:4px;letter-spacing:1px;text-transform:uppercase;">Out of Stock</div>
    @endif
  </div>
  <div class="product-body">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px">
      <span class="product-badge" style="margin-bottom:0">{{ $product->category->name ?? '' }}</span>
      <div style="display:flex; align-items:center; gap:4px; font-size:12px; font-weight:600; color:var(--text); background:rgba(255,255,255,0.05); padding:2px 6px; border-radius:4px;">
        {{ number_format($product->rating, 1) }} <span style="color:#f59e0b">★</span> 
        <span style="color:var(--text-dim); font-weight:400; padding-left:2px; border-left:1px solid rgba(255,255,255,0.1)">{{ $product->reviews_count }}</span>
      </div>
    </div>
    <div class="product-name" style="font-size:16px;">{{ $product->name }}</div>
    @if($product->tagline)
      <div class="product-tagline" style="font-size:11px; color:var(--text-muted); font-weight:500; margin-top:2px;">{{ $product->tagline }}</div>
    @endif
    

    
    <div class="product-desc" style="font-size:12px; margin-top:4px;">{{ $shortDesc }}</div>
    <div class="product-actions">
      <button class="btn-enquire" onclick="event.stopPropagation();openProductModal({{ $product->id }})">View Details</button>
      <a href="https://wa.me/918928202040?text={{ $wa }}" target="_blank" class="btn-whatsapp" onclick="event.stopPropagation()">
        @include('partials.wa-icon', ['size'=>16])
      </a>
    </div>
  </div>
</div>
