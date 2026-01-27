@extends('users.layout.main')
@section('metadata')
    <title>Sửa Thông Tin Cá Nhân</title>
    <meta property="og:title" content="Sửa Thông Tin Cá Nhân">
    <meta name="robots" content="nofollow, noindex">
@endsection
@section('content')
    <div id="main-content" class="container">
        <div class="page-breadcrumb">
            <span class="item"><a href="/">Trang chủ</a></span>
            <span class="item breadcrumb_last" aria-current="page">Cá nhân</span>
        </div>
        <ul class="nav nav-tabs nav-account" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="" class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" role="tab" aria-controls="general" aria-selected="true">Thông tin</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{route('showHistory')}}" class="nav-link">Đang đọc</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{route('showFollow')}}" class="nav-link" id="mg-save-tab">Đã lưu</a>
            </li>
        </ul>

        <div class="tab-content" id="TopFollow">
            <div class="tab-pane fade active show" id="general" role="tabpanel">
                <h3 class="m-title title">
                    Thông tin chung
                </h3>

                <form id="form-prf" class="user-page">
                    <div class="avatar-user position-relative">
                        <div id="avatar-temp-edit" class="avatar-temp user-avatar-img" style="background-image: url(&quot;{{$user->avatar}}&quot;); background-size: cover; background-position: center center; background-repeat: no-repeat;"></div>
                        <span class="color position-absolute ">
                            <input type="file" id="changeAvatar" name="uploadAvatar" accept=".jpg,.jpeg,.gif,.png">
                            <span class="icon-edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M17.4752 0.833984H15.0252C13.9668 0.833984 13.3335 1.46732 13.3335 2.52565V4.97565C13.3335 6.03398 13.9668 6.66732 15.0252 6.66732H17.4752C18.5335 6.66732 19.1668 6.03398 19.1668 4.97565V2.52565C19.1668 1.46732 18.5335 0.833984 17.4752 0.833984ZM15.8418 5.47565C15.8168 5.50065 15.7585 5.53398 15.7168 5.53398L14.8502 5.65898C14.8252 5.66732 14.7918 5.66732 14.7668 5.66732C14.6418 5.66732 14.5335 5.62565 14.4585 5.54232C14.3585 5.44232 14.3168 5.30065 14.3418 5.15065L14.4668 4.28398C14.4752 4.24232 14.5002 4.18398 14.5252 4.15898L15.9418 2.74232C15.9668 2.80065 15.9918 2.86732 16.0168 2.93398C16.0502 3.00065 16.0835 3.05898 16.1168 3.11732C16.1418 3.16732 16.1752 3.21732 16.2085 3.25065C16.2418 3.30065 16.2752 3.35065 16.3002 3.37565C16.3168 3.40065 16.3252 3.40898 16.3335 3.41732C16.4085 3.50898 16.4918 3.59232 16.5668 3.65065C16.5835 3.66732 16.6002 3.68398 16.6085 3.68398C16.6502 3.71732 16.7002 3.75898 16.7335 3.78398C16.7835 3.81732 16.8252 3.85065 16.8752 3.87565C16.9335 3.90898 17.0002 3.94232 17.0668 3.97565C17.1335 4.00898 17.2002 4.03398 17.2585 4.05065L15.8418 5.47565ZM17.8335 3.48398L17.5668 3.75065C17.5502 3.77565 17.5252 3.78398 17.5002 3.78398C17.4918 3.78398 17.4835 3.78398 17.4752 3.78398C16.8752 3.60898 16.4002 3.13398 16.2252 2.53398C16.2168 2.50065 16.2252 2.46732 16.2502 2.44232L16.5252 2.16732C16.9752 1.71732 17.4002 1.72565 17.8418 2.16732C18.0668 2.39232 18.1752 2.60898 18.1752 2.82565C18.1668 3.04232 18.0585 3.25898 17.8335 3.48398Z" fill="white"></path>
                                    <path d="M7.49994 8.65026C8.5953 8.65026 9.48327 7.76229 9.48327 6.66693C9.48327 5.57156 8.5953 4.68359 7.49994 4.68359C6.40457 4.68359 5.5166 5.57156 5.5166 6.66693C5.5166 7.76229 6.40457 8.65026 7.49994 8.65026Z" fill="white"></path>
                                    <path d="M17.4748 6.66602H17.0832V10.5077L16.9748 10.416C16.3248 9.85768 15.2748 9.85768 14.6248 10.416L11.1582 13.391C10.5082 13.9493 9.45817 13.9493 8.80817 13.391L8.52484 13.1577C7.93317 12.641 6.9915 12.591 6.32484 13.041L3.20817 15.1327C3.02484 14.666 2.9165 14.1243 2.9165 13.491V6.50768C2.9165 4.15768 4.15817 2.91602 6.50817 2.91602H13.3332V2.52435C13.3332 2.19102 13.3915 1.90768 13.5248 1.66602H6.50817C3.47484 1.66602 1.6665 3.47435 1.6665 6.50768V13.491C1.6665 14.3994 1.82484 15.191 2.13317 15.8577C2.84984 17.441 4.38317 18.3327 6.50817 18.3327H13.4915C16.5248 18.3327 18.3332 16.5244 18.3332 13.491V6.47435C18.0915 6.60768 17.8082 6.66602 17.4748 6.66602Z" fill="white"></path>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="info-user">
                        <div class="info-user-item">
                            <input name="avatar" type="text" class="d-none" value="">
                            <div>
                                <label for="txtEmail" class="form-label">Địa chỉ email</label>
                                <input name="txtEmail" type="text" value="{{$user->email}}" maxlength="100" id="txtEmail" disabled="disabled" tabindex="10" class="disabled form-control">
                            </div>
                            <div>
                                <label for="userName" class="form-label">Tên hiển thị</label>
                                <input name="name" type="text" value="{{$user->name}}" maxlength="100" id="userName" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>

                <button type="submit" id="save-prf" form="form-prf" class="btn">
                    Lưu thay đổi
                </button>

                <div class="sugguest-set pt-5 sidebar">
                    <h3 class="m-title title">
                        Đổi mật khẩu
                    </h3>
                    <form class="info-user user-page">
                        <div class="info-user-item">
                            <div>
                                <label for="password" class="form-label">Mật khẩu cũ</label>
                                <input name="password" type="password" value="" maxlength="100" id="password" tabindex="10" class="form-control">
                            </div>
                            <div>
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <input name="new_password" type="password" value="" maxlength="100" id="new_password" tabindex="10" class="form-control">
                            </div>
                            <div>
                                <label for="new_password_confirm" class="form-label">Xác nhận mật khẩu mới</label>
                                <input name="new_password_confirm" type="password" value="" maxlength="100" id="new_password_confirm" tabindex="10" class="form-control">
                            </div>
                        </div>
                    </form>

                    <button id="save-pass" form="form-pass" class="btn">
                        Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $('#save-prf').click(function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append('avatar', $('#changeAvatar')[0].files[0]);
            formData.append('name', $('#userName').val());
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('updateProfile') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alertNoti(data.message);
                    window.location.reload();
                },
                error: function() {
                    alertNoti('Có lỗi xảy ra, vui lòng thử lại sau');
                }
            });
        });

        $('#save-pass').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('updatePassword') }}',
                type: 'POST',
                data: {
                    old_password: $('input[name=password]').val(),
                    new_password: $('input[name=new_password]').val(),
                    new_password_confirm: $('input[name=new_password_confirm]').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function(data) {
                    alertNoti(data.message);
                    if (data.status == "success") {
                        window.location.reload();
                    }
                },
                error: function() {
                    alertNoti('Có lỗi xảy ra, vui lòng thử lại sau');
                }
            });
        });

        $('#changeAvatar').change(function() {
            var file = this.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alertNoti('File quá lớn. Vui lòng chọn file nhỏ hơn 5MB');
                    $(this).val('');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    alertNoti('Vui lòng chọn file ảnh');
                    $(this).val('');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatar-temp-edit').attr('style', 'background-image: url(' + e.target.result + '); background-size: cover; background-position: center center; background-repeat: no-repeat;');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
