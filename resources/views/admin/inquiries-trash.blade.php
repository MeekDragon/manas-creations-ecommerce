@extends('layouts.admin')
@section('title', 'Inquiries Trash')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Inquiries Trash</div>
  <a href="{{ route('admin.inquiries') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">← Back to Inquiries</a>
</div>
<div class="admin-page-sub">Restore or permanently delete customer inquiries.</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>#</th><th>Name</th><th>Email</th><th>Mobile</th><th>Category</th>
        <th>Date</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($inquiries as $i => $inq)
        <tr id="inq-row-{{ $inq->id }}">
          <td style="color:var(--text-dim)">{{ $i + 1 }}</td>
          <td style="font-weight:500">{{ $inq->name }}</td>
          <td>{{ $inq->user?->email ?? '-' }}</td>
          <td>{{ $inq->contact }}</td>
          <td>{{ $inq->category }}</td>
          <td style="color:var(--text-muted)">{{ $inq->created_at->format('d M Y') }}</td>
          <td><span class="badge badge-{{ strtolower($inq->status) }}" id="status-badge-{{ $inq->id }}">{{ $inq->status }}</span></td>
          <td>
            <div style="display:flex;gap:8px;">
              <form action="{{ route('admin.inquiries.restore', $inq->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline" style="padding:4px 10px;font-size:11px;color:#25d366;border-color:rgba(37,211,102,.3)">Restore</button>
              </form>
              <form action="{{ route('admin.inquiries.force-delete', $inq->id) }}" method="POST" onsubmit="return confirm('Permanently delete this inquiry?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-outline" style="padding:4px 10px;font-size:11px;color:#E24B4A;border-color:rgba(226,75,74,.3)">Force Delete</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align:center;color:var(--text-dim);padding:32px">Trash is empty.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- Inquiry Detail Modal -->
<div id="inqModal" class="hidden" style="position:fixed;inset:0;z-index:300;background:rgba(0,0,0,.7);display:flex;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)">
  <div style="background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);padding:32px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <span style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700">Inquiry Details</span>
      <button onclick="document.getElementById('inqModal').classList.add('hidden')" style="background:none;border:none;color:var(--text-muted);font-size:20px;cursor:pointer">✕</button>
    </div>
    <div id="inqModalBody"></div>
    <div id="inqModalActions" style="display:flex;gap:12px;margin-top:20px"></div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Handled via forms
</script>
@endpush
