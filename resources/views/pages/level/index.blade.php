@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cấp độ'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Cấp độ</h6>
                            <button type="button" data-type="add" class="text-white btn btn-add rounded font-weight-bold bg-success" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                Thêm mới
                            </button>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-2">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Cấp độ</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kinh nghiệm</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Link ảnh</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Mẫu</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($levels as $item)
                                        <tr>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">{{$item->level}}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-secondary">{{$item->experience}}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-success">{{$item->image}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong style="background-image: url({{$item->image}}); color:transparent; -webkit-background-clip: text; background-position: center;">Admin</strong>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <button data-level="{{$item->level}}" data-image="{{$item->image}}" data-type="edit" data-id="{{$item->id}}" data-experience="{{$item->experience}}" style="background: #7c69ef;border: none" class="text-white btn-edit px-2 py-1 rounded font-weight-bold" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                                        Sửa
                                                    </button>
                                                    <button data-level="{{$item->level}}" data-id="{{$item->id}}" type="button" style="border: none" class="text-white px-2 btn-delete py-1 rounded font-weight-bold bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-notification">
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
                                              <label for="level" class="col-form-label">Tên:</label>
                                              <input type="text" class="form-control" value="" id="level">
                                            </div>
                                            <div class="form-group">
                                              <label for="experience" class="col-form-label">Số kinh nghiệm:</label>
                                              <input type="text" class="form-control" value="" id="experience">
                                            </div>
                                            <div class="form-group">
                                                <label for="image" class="col-form-label">Link ảnh:</label>
                                                <input type="text" class="form-control" value="" id="image">
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
    <script>
        let id = null;
        let type = null;
        $('.btn-delete').click(function(){
            id = $(this).data('id');
            $('.container-text').text('Bạn có chắc chắn muốn xóa cấp độ ' + $(this).data('level') + ' không?' );
        })
        $('.btn-submit').click(function(){
            $.ajax({
                url: '{{route('admin.level.destroy', ['level' => 'id'])}}',
                method: 'DELETE',
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                success: function(){
                    alert('Xóa thành công');
                    window.location.reload();
                }
            })
        })
        $('.btn-add').click(function(){
            $('#labelModal').text('Thêm mới cấp độ');
            $('.btn-submit-abc').text('Thêm mới');
            $('#level').val('');
            $('#experience').val('');
            $('#image').val('');
            type = 'add';
        })
        $('.btn-edit').click(function(){
            id = $(this).data('id');
            $('#labelModal').text('Sửa cấp độ');
            $('.btn-submit-abc').text('Sửa');
            $('#level').val($(this).data('level'));
            $('#experience').val($(this).data('experience'));
            $('#image').val($(this).data('image'));
            type = 'edit';
        });
        $('.btn-submit-abc').click(function(){
            let level = $('#level').val();
            let experience = $('#experience').val();
            let image = $('#image').val();
            if(!level || !experience || !image){
                alert('Vui lòng nhập đầy đủ thông tin');
                return;
            }
            if(type == 'add'){
                $.ajax({
                    url: '{{route('admin.level.store')}}',
                    method: 'POST',
                    data: {
                        level: level,
                        experience: experience,
                        image: image,
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
            }else{
                $.ajax({
                    url: '{{route('admin.level.update', ["level" => 'id'])}}',
                    method: 'PUT',
                    data: {
                        level: level,
                        experience: experience,
                        image: image,
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
            }
        })
    </script>
@endpush
