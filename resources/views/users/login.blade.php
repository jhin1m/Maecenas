@extends('users.layout.main')
@section('metadata')
    <title>{{ __('string.login') }} - {{env('APP_NAME')}}</title>
@endsection

@section('class') vi-vn site1 @endsection
@section('content')
<div class="container">
    <div id="ctl00_Breadcrumbs_pnlWrapper">
        <ul class="breadcrumb">
            <li> <a href="{{route('home')}}" class="itemcrumb active"> <span>{{ __('string.home') }}</span> </a> <meta> </li>
            <li> <a href="{{route('login')}}" class="selectedcrumb">{{ __('string.login') }}</a> </li>
        </ul>
    </div>
    <div class="row">
        <div id="ctl00_divCenter" class="full-width col-sm-12">
            <div id="ctl00_mainContent_pnlLogin">
                <div id="ctl00_mainContent_pnlStandardLogin" class="login-wrapper">
                    <div class="row">
                        <div class="col-sm-offset-3 col-sm-6">
                            <form id="form-login" class="user-page clearfix">
                                <h1 class="postname"> {{ __('string.login') }} </h1>
                                <div id="ctl00_mainContent_login1_LoginCtrl_pnlLContainer" class="signup-wrapper">
                                    <div class="form-group">
                                        <label for="email" class="control-label">Email</label>
                                        <input name="email" type="text" maxlength="100" id="email" tabindex="10" class="form-control" value="" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="control-label"> {{ __('string.password') }} </label>
                                        <input name="password" type="password" id="password" tabindex="10" class="form-control" placeholder="{{ __('string.password') }}" value="">
                                        <span class="remember-me hidden">
                                            <input id="ctl00_mainContent_login1_LoginCtrl_RememberMe" type="checkbox" name="ctl00$mainContent$login1$LoginCtrl$RememberMe" checked="checked" tabindex="10">
                                            <label for="ctl00_mainContent_login1_LoginCtrl_RememberMe">{{ __('string.remember_me') }}</label>
                                        </span>
                                    </div>
                                </div>
                                <div class="login-action">
                                    <div class="form-group">
                                        <a id="ctl00_mainContent_login1_LoginCtrl_lnkRegisterExtraLink" class="login-link" href="{{route('register')}}">{{ __('string.register') }}</a>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="{{ __('string.login') }}" id="ctl00_mainContent_login1_LoginCtrl_Login" tabindex="10" class="btn btn-primary">
                                    </div>
                                </div>
                             </form>
                             <div class="open-login mrb20">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('#form-login').submit(function(e){
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        $.ajax({
            url: "{{route('auth-login')}}",
            type: "POST",
            data: {
                email: email,
                password: password,
                _token: "{{csrf_token()}}"
            },
            success: function(data){
                if(data.status == 'success'){
                    window.location.href = "/";
                }else{
                    alert(data.message);
                }
            }
        });
    });
</script>
@endsection
