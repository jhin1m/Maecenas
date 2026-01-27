@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tác giả'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Tác giả</h6>
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
                                                Tác giả</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Slug</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($authors as $item)
                                        <tr>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">{{$item->name}}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="">
                                                        <div>
                                                            <span style="color: #7c69ef;">{{$item->slug}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <button data-name="{{$item->name}}" data-type="edit" data-id="{{$item->id}}" data-slug="{{$item->slug}}" style="background: #7c69ef;border: none" class="text-white btn-edit px-2 py-1 rounded font-weight-bold" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                                        Sửa
                                                    </button>
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
                                              <label for="slug" class="col-form-label">Slug:</label>
                                              <input type="text" class="form-control" value="" id="slug">
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
        let type = null;
        $(document).on('click', '.btn-delete', function() {
            id = null;
            $('.container-text').text('');
            id = $(this).data('id');
            $('.container-text').text('Bạn có chắc chắn muốn xóa tác giả ' + $(this).data('name') + ' không?' );
        });
        $('.btn-submit').click(function(){
            $.ajax({
                url: '{{route('admin.author.destroy', ["author" => 'id'])}}',
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
            $('#labelModal').text('Thêm mới tác giả');
            $('.btn-submit-abc').text('Thêm mới');
            $('#name').val('');
            $('#slug').val('');
            type = 'add';
        })
        $(document).on('click', '.btn-edit', function() {
            id = null;
            id = $(this).data('id');
            $('#labelModal').text('Sửa tác giả');
            $('.btn-submit-abc').text('Sửa');
            $('#name').val($(this).data('name'));
            $('#slug').val($(this).data('slug'));
            type = 'edit';
        });
        $('.btn-submit-abc').click(function(){
            let name = $('#name').val();
            let slug = $('#slug').val();
            if(!name || !slug){
                alert('Vui lòng nhập đầy đủ thông tin');
                return;
            }
            if(type == 'add'){
                $.ajax({
                    url: '{{route('admin.author.store')}}',
                    method: 'POST',
                    data: {
                        name: name,
                        slug: slug,
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
                    url: '{{route('admin.author.update', ["author" => 'id'])}}',
                    method: 'PUT',
                    data: {
                        name: name,
                        slug: slug,
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
