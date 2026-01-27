@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Quản Lý Truyện Tranh'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Truyện tranh</h6>
                            <a href="{{route('admin.comic.create')}}" class="btn btn-primary">Thêm Mới</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <livewire:comics-table-view />
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
    @laravelViewsScripts
    <script>
        let id =null;
        $(document).on('click', '.btn-delete', function() {
            id = null;
            $('.container-text').text('');
            id = $(this).data('id');
            $('.container-text').text('Bạn có chắc chắn muốn xóa truyện ' + $(this).data('name') + ' không?' );
        });
        $('.btn-submit').click(function(){
            $.ajax({
                url: '{{route('admin.comic.destroy', ["comic" => 'id'])}}',
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
    </script>
@endpush
