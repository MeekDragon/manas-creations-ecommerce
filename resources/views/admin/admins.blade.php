@extends('layouts.admin')
@section('title', 'Admins')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Admins</div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.admins.create') }}" class="btn-gold" style="padding:10px 20px;font-size:13px">Create Admin</a>
    <a href="{{ route('admin.admins.trash') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">Trash ({{ $trashedCount }})</a>
  </div>
</div>
<div class="admin-page-sub">Manage administrator accounts and security credentials.</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr><th>Name</th><th>Email</th><th>Mobile</th><th>Verified</th><th>Role</th><th>Registered At</th><th>Actions</th></tr>
    </thead>
    <tbody>
      @forelse($users as $user)
        <tr>
          <td style="font-weight:500">{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->mobile ?: 'N/A' }}</td>
          <td>
            @if($user->hasVerifiedMobile() && $user->hasVerifiedEmail())
              <span class="badge" style="background:rgba(37,211,102,.1);color:#25d366">Both</span>
            @elseif($user->hasVerifiedMobile())
              <span class="badge" style="background:rgba(201,168,76,.15);color:var(--gold)">Mobile Only</span>
            @elseif($user->hasVerifiedEmail())
              <span class="badge" style="background:rgba(99,153,220,.1);color:#6399dc">Email Only</span>
            @else
              <span class="badge" style="background:rgba(226,75,74,.1);color:#E24B4A">None</span>
            @endif
          </td>
          <td>
            <span class="badge" style="background:rgba(201,168,76,.15);color:var(--gold)">Admin</span>
          </td>
          <td>{{ $user->created_at->format('M d, Y') }}</td>
          <td>
            <div style="display:flex;gap:8px">
              <a href="{{ route('admin.admins.edit', $user) }}" class="btn-outline" style="padding:4px 8px;font-size:11px">Edit</a>
              <form action="{{ route('admin.admins.destroy', $user) }}" method="POST" onsubmit="return confirm('Move this admin to trash?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-outline" style="padding:4px 8px;font-size:11px;color:#E24B4A;border-color:rgba(226,75,74,.3)">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;color:var(--text-dim);padding:32px">No administrative accounts found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
