@extends('layouts.admin')
@section('title', 'Users Trash')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Users Trash</div>
  <a href="{{ route('admin.users') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">← Back to Users</a>
</div>
<div class="admin-page-sub">Restore or permanently delete user accounts.</div>

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
            <div style="display:flex;gap:8px;">
              <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline" style="padding:4px 10px;font-size:11px;color:#25d366;border-color:rgba(37,211,102,.3)">Restore</button>
              </form>
              <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this user?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-outline" style="padding:4px 10px;font-size:11px;color:#E24B4A;border-color:rgba(226,75,74,.3)">Force Delete</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;color:var(--text-dim);padding:32px">Trash is empty.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
