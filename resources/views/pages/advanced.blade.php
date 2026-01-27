@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cài đặt nâng cao'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Thông báo</h6>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="form-group">
                            <input class="form-control" type="text" value="{{$notification->content ?? ''}}" id="notification">
                        </div>
                    </div>
                </div>
                <div class="form-group mt-10">
                    <button class="btn btn-primary" id="update-notification">Cập nhật</button>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#update-notification').click(function () {
                let notification = $('#notification').val();
                $.ajax({
                    url: '{{route('admin.updateAdvanced')}}',
                    method: 'POST',
                    data: {
                        _token: '{{csrf_token()}}',
                        notification: notification,
                        header: null
                    },
                    success: function (response) {
                        alert(response.message);
                    }, error: function () {
                        alert('Có lỗi xảy ra');
                    }
                });
            });
        });
    </script>
@endpush
