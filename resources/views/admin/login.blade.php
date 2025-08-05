@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin: 40px auto;">
    <h2 class="mb-4 text-center">تسجيل دخول الأدمن</h2>
    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <button type="submit" class="btn btn-primary w-100">دخول</button>
    </form>
</div>
@endsection
