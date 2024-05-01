@extends('layout.client')

@section('template_title')
    {{ 'Quên mật khẩu' }}
@endsection

@section('content')
    <div id="login-signup-form" class="py-5">
        <div class="row justify-content-center">
            <div class="col-sm-offset-3 col-sm-6">
                <div id="forgot-password-form">
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="control-label">Nhập địa chỉ email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" autocomplete="username" placeholder="example@email.com" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Xác nhận</button>
                        </div>
                    </form>
                </div>
                @if(session('status'))
                    <div class="alert alert-success mt-3">
                        {{ session('status') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <div class="text-muted mt-4">
                    {{ 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.' }}
                </div>
            </div>
        </div>
    </div>
@endsection
