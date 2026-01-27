@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Người dùng'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Người dùng</h6>
                            <button type="button" data-type="add" class="text-white btn btn-add rounded font-weight-bold bg-success" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                Thêm mới
                            </button>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-2">
                                <table id="myTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Ảnh</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tên</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Email</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kinh nghiệm</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Vai trò</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $item)
                                        <tr>
                                            <td class="align-middle text-center text-sm">
                                                <img src="{{$item->avatar}}" loading="lazy" class="avatar" alt="">
                                            </td>
                                            <td class="align-middle text-center">
                                                @if ($item->level->level == 'Cấp 0')
                                                <strong>{{$item->name}}</strong>
                                                @else
                                                <strong style="background-image: url({{$item->level->image}}); color:transparent; -webkit-background-clip: text; background-position: center;">{{$item->name}}</strong>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$item->email}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$item->exp}}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">{{$item->role == 1 ? 'Admin' : 'User'}}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <a style="background: #7c69ef;border: none" class="text-white btn-edit px-2 py-1 rounded font-weight-bold" href="{{route('admin.user.edit', ['user' => $item->id])}}">
                                                        Sửa
                                                    </a>
                                                    <button data-name="{{$item->name}}" data-id="{{$item->id}}" type="button" style="border: none" class="text-white px-2 btn-delete py-1 rounded font-weight-bold bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-notification">
                                                        Xóa
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
                                    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h6 class="modal-title" id="modal-title-notification">Xác nhận xóa</h6>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="py-3 text-center">
                                            <i class="ni ni-bell-55 ni-3x"></i>
                                            <h4 class="text-gradient text-danger container-text mt-4"></h4>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-white btn-submit">Chắc chắn</button>
                                          <button type="button" class="btn btn-link ml-auto" style="color: #5e72e4" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="labelModal"></h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <form>
                                            <div class="form-group">
                                              <label for="name" class="col-form-label">Tên:</label>
                                              <input type="text" class="form-control" value="" id="name">
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="col-form-label">Email:</label>
                                                <input type="email" class="form-control" value="" id="email">
                                            </div>
                                            <div class="form-group">
                                                <label for="password" class="col-form-label">Mật khẩu:</label>
                                                <input type="text" class="form-control" value="" id="password">
                                            </div>
                                            <div class="form-group">
                                                <label for="exp" class="form-control-label">Kinh nghiệm:</label>
                                                <input class="form-control" type="number" value="" id="exp">
                                            </div>
                                            <div class="form-group">
                                                <label for="role" class="form-control-label">Vai trò:</label>
                                                <select id="role" class="form-control">
                                                    <option value="0">User</option>
                                                    <option value="1">Admin</option>
                                                </select>
                                            </div>
                                          </form>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Đóng</button>
                                          <button type="button" class="btn bg-gradient-primary btn-submit-abc"></button>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
        let id = null;
        $(document).on('click', '.btn-delete', function() {
            id = null;
            $('.container-text').text('');
            id = $(this).data('id');
            $('.container-text').text('Bạn có chắc chắn muốn xóa người dùng ' + $(this).data('name') + ' không?' );
        });
        $('.btn-submit').click(function(){
            $.ajax({
                url: '{{route('admin.user.destroy', ["user" => 'id'])}}',
                method: 'DELETE',
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                success: function(data){
                    if(data.status == "success"){
                        alert(data.message);
                        window.location.reload();
                    }else{
                        alert(data.message);
                    }
                }
            })
        })
        $('.btn-add').click(function(){
            $('#labelModal').text('Thêm mới người dùng');
            $('.btn-submit-abc').text('Thêm mới');
        })
        $('.btn-submit-abc').click(function(){
            let name = $('#name').val();
            let email = $('#email').val();
            let exp = $('#exp').val();
            let role = $('#role').val();
            let password = $('#password').val();
            if(!name || !email || !exp || !role || !password){
                alert('Vui lòng nhập đầy đủ thông tin');
                return;
            }
            $.ajax({
                url: '{{route('admin.user.store')}}',
                method: 'POST',
                data: {
                    name: name,
                    email: email,
                    exp: exp,
                    role: role,
                    password: password,
                    _token: '{{csrf_token()}}'
                },
                success: function(data){
                    if(data.status == "success"){
                        alert(data.message);
                        window.location.reload();
                    }else{
                        alert(data.message);
                    }
                }
            })

        })
    </script>
@endpush
