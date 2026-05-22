@extends('layouts.admin')
@section('title', 'Dashboard')

@section('admin-content')
<div class="admin-page-title">Dashboard</div>
<div class="admin-page-sub">Welcome back! Here's your business overview.</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card">
    <div><div class="stat-label">Total Products</div><div class="stat-value">{{ $totalProducts }}</div></div>
    <div class="stat-icon" style="background:rgba(201,168,76,.1)">📦</div>
  </div>
  <div class="stat-card">
    <div><div class="stat-label">Total Inquiries</div><div class="stat-value">{{ $totalInquiries }}</div></div>
    <div class="stat-icon" style="background:rgba(37,211,102,.1)">💬</div>
  </div>
  <div class="stat-card">
    <div><div class="stat-label">Pending Inquiries</div><div class="stat-value">{{ $pendingInquiries }}</div></div>
    <div class="stat-icon" style="background:rgba(239,166,50,.1)">🕐</div>
  </div>
  <div class="stat-card">
    <div><div class="stat-label">Categories</div><div class="stat-value">{{ $totalCategories }}</div></div>
    <div class="stat-icon" style="background:rgba(99,153,220,.1)">📊</div>
  </div>
</div>

<!-- Row -->
<div class="dash-row">
  <div class="dash-card">
    <div class="dash-card-title">Products by Category</div>
    @forelse($productsByCategory as $row)
      <div class="cat-item-dash">
        <span>{{ $row->name }}</span>
        <span class="cat-item-count">{{ $row->products_count }}</span>
      </div>
    @empty
      <div style="color:var(--text-dim);font-size:13px">No categories yet</div>
    @endforelse
  </div>

  <div class="dash-card">
    <div class="dash-card-title">Quick Actions</div>
    <div class="quick-action-grid">
      <a href="{{ route('admin.inquiries') }}" class="quick-action">
        <div class="quick-action-icon">💬</div>
        <div class="quick-action-label">View Inquiries</div>
        <div class="quick-action-sub">{{ $pendingInquiries }} pending</div>
      </a>
      <a href="{{ route('admin.products') }}" class="quick-action">
        <div class="quick-action-icon">📦</div>
        <div class="quick-action-label">Manage Products</div>
        <div class="quick-action-sub">{{ $totalProducts }} products</div>
      </a>
      <a href="{{ route('home') }}" target="_blank" class="quick-action">
        <div class="quick-action-icon">📈</div>
        <div class="quick-action-label">View Website</div>
        <div class="quick-action-sub">See live site</div>
      </a>
    </div>
  </div>
</div>

<!-- Recent Inquiries -->
<div class="dash-card">
  <div class="dash-card-title">Recent Inquiries</div>
  @if($recentInquiries->count())
    <table class="admin-table">
      <thead><tr><th>Name</th><th>Category</th><th>Date</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($recentInquiries as $inq)
          <tr>
            <td>{{ $inq->name }}</td>
            <td>{{ $inq->category }}</td>
            <td>{{ $inq->created_at->format('d M Y') }}</td>
            <td><span class="badge badge-{{ strtolower($inq->status) }}">{{ $inq->status }}</span></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <div style="color:var(--text-dim);font-size:13px;padding:16px 0">No inquiries yet.</div>
  @endif
</div>
@endsection
