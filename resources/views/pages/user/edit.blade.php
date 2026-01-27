@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cá nhân'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{$user->avatar}}" alt="profile_image" class="w-100 avatar border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{$user->name}}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{$user->role == 1 ? 'Quản trị viên' : 'Người dùng'}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form role="form" method="POST" action={{ route('admin.user.update', ['user' => $user->id]) }}>
                        @csrf
                        @method('PUT')
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Chỉnh sửa</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Lưu</button>
                                <a href="{{route('admin.user.index')}}" class="btn btn-secondary btn-sm mx-2" >Quay về</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Thông tin người dùng</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Tên</label>
                                        <input class="form-control" id="name" type="text" name="name" value="{{$user->name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">Email</label>
                                        <input class="form-control" type="email" id="email" name="email" value="{{$user->email}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exp" class="form-control-label">Kinh nghiệm</label>
                                        <input class="form-control" type="number" id="exp" name="exp" value="{{$user->exp}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role" class="form-control-label">Vai trò</label>
                                        <select name="role" id="role" class="form-control">
                                            <option {{$user->role == 0 ? 'selected' : ''}} value="0">Người dùng</option>
                                            <option {{$user->role == 1 ? 'selected' : ''}} value="1">Quản trị viên</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
