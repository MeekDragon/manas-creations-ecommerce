@extends('layouts.admin')
@section('title', $user->exists ? 'Edit Admin' : 'Create Admin')

@section('admin-content')
<div class="admin-page-title">{{ $user->exists ? 'Edit Admin Account' : 'Create Admin Account' }}</div>
<div class="admin-page-sub">Manage admin credentials and administrative privileges.</div>

<form method="POST" action="{{ $user->exists ? route('admin.admins.update', $user, false) : route('admin.admins.store', [], false) }}">
  @csrf
  @if($user->exists) @method('PUT') @endif

  <div class="admin-form-grid">
    <div class="admin-form-group">
      <label>Full Name</label>
      <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="e.g. Om Yadav" required>
      @error('name')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Email Address</label>
      <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="e.g. admin@manascreations.in" required>
      @error('email')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Mobile Number</label>
      <input type="text" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}" placeholder="e.g. 7058466881" required>
      @error('mobile')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="admin-form-group">
      <label>Password {{ $user->exists ? '(Leave blank to keep current)' : '' }}</label>
      <input type="password" name="password" placeholder="{{ $user->exists ? '••••••••' : 'Enter strong password' }}" {{ $user->exists ? '' : 'required' }} minlength="6">
      @error('password')<div class="field-error">{{ $message }}</div>@enderror
    </div>
  </div>

  <div style="display:flex;gap:12px;margin-top:20px">
    <button type="submit" class="btn-gold" style="padding:12px 32px">
      {{ $user->exists ? 'Update Admin' : 'Create Admin' }}
    </button>
    <a href="{{ route('admin.admins.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
