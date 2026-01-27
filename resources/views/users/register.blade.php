@extends('users.layout.main')
@section('metadata')
    <title>{{ __('string.register') }} - {{env('APP_NAME')}}</title>
@endsection

@section('class') vi-vn site1 @endsection
@section('content')
<div class="container">
    <div id="ctl00_Breadcrumbs_pnlWrapper">
        <ul class="breadcrumb">
            <li> <a href="{{route('home')}}" class="itemcrumb active"> <span>{{ __('string.home') }}</span> </a> <meta> </li>
            <li> <a href="{{route('register')}}" class="selectedcrumb">{{ __('string.register') }}</a> </li>
        </ul>
    </div>
    <div class="row">
        <div id="ctl00_divCenter" class="full-width col-sm-12">
            <div id="ctl00_mainContent_pnlLogin">
                <div id="ctl00_mainContent_pnlStandardLogin" class="login-wrapper">
                    <div class="row">
                        <div class="col-sm-offset-3 col-sm-6">
                            <form id="form-signup" class="user-page clearfix">
                                 <h1 class="postname"> {{ __('string.register') }} </h1>
                                 <div id="ctl00_mainContent_login1_LoginCtrl_pnlLContainer" class="signup-wrapper">
                                    <div class="form-group">
                                        <label for="name" class="control-label">Name</label>
                                        <input type="text" maxlength="100" id="name" name="name" value="" tabindex="10" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="control-label">Email</label>
                                        <input type="text" maxlength="100" id="email" name="email" value="" tabindex="10" class="form-control" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="password">{{ __('string.password') }}</label>
                                        <input type="password" autocomplete="off" id="password" name="password" value="" tabindex="10" class="form-control" placeholder="{{ __('string.password') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="cf_password">{{ __('string.confirm_password') }}</label>
                                        <input type="password" autocomplete="off" id="cf_password" name="cf_password" value="" tabindex="10" class="form-control" placeholder="{{ __('string.confirm_password') }}">
                                    </div>
                                </div>
                                <div class="login-action">
                                    <div class="form-group">
                                        <a id="ctl00_mainContent_login1_LoginCtrl_lnkRegisterExtraLink" class="login-link" href="{{route('login')}}">{{ __('string.login') }}</a>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="{{ __('string.register') }}" id="ctl00_mainContent_login1_LoginCtrl_Login" tabindex="10" class="btn btn-primary">
                                    </div>
                                    <div class="open-login mrt20 mrb20">
                                        <div class="form-group">
                                            <a class="btn login-google" href="{{route('loginGoogle')}}">
                                                <i class="fa fa-google-plus" style="display: flex;align-items:center; justify-content:center;">
                                                    <svg fill="#000000" height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 210 210" xml:space="preserve">
                                                        <path d="M0,105C0,47.103,47.103,0,105,0c23.383,0,45.515,7.523,64.004,21.756l-24.4,31.696C133.172,44.652,119.477,40,105,40 c-35.841,0-65,29.159-65,65s29.159,65,65,65c28.867,0,53.398-18.913,61.852-45H105V85h105v20c0,57.897-47.103,105-105,105S0,162.897,0,105z" fill="#ffffff"></path>
                                                    </svg>
                                                </i>
                                                <span>{{ __('string.login_google') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </f>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$('#form-signup').submit(function(e){
    e.preventDefault();
    var name = $('#name').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var cf_password = $('#cf_password').val();
    if(name == '' || email == '' || password == '' || cf_password == ''){
        alert('Vui lòng nhập đầy đủ thông tin');
        return;
    }
    if(password != cf_password){
        alert('Mật khẩu không khớp');
        return;
    }
    $.ajax({
        url: '{{route('auth-register')}}',
        type: 'POST',
        data: {
            name: name,
            email: email,
            password: password,
            cf_password: cf_password,
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            if(data.status == 'success'){
                alert('Đăng ký thành công');
                window.location.href = '{{route('login')}}';
            }else{
                alert(data.message);
            }
        }, error: function(){
            alert('Đăng ký thất bại');
        }
    });
});
</script>
@endsection
