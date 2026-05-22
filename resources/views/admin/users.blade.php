@extends('layouts.admin')
@section('title', 'Users')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Users</div>
  <a href="{{ route('admin.users.trash') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">Trash ({{ $trashedCount }})</a>
</div>
<div class="admin-page-sub">Manage registered users and their roles.</div>

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
            @if($user->is_admin)
              <span class="badge" style="background:rgba(201,168,76,.15);color:var(--gold)">Admin</span>
            @else
              <span class="badge" style="background:rgba(255,255,255,.05);color:var(--text-muted)">Customer</span>
            @endif
          </td>
          <td>{{ $user->created_at->format('M d, Y') }}</td>
          <td>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user permanently?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-outline" style="padding:4px 8px;font-size:11px;color:#E24B4A;border-color:rgba(226,75,74,.3)">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" style="text-align:center;color:var(--text-dim);padding:32px">No users found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
