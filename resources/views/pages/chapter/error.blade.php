@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.snow.css" rel="stylesheet">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Chương lỗi'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>Chương lỗi</h3>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-2">
                                <table id="myTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Truyện</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Chap</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Server</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Slug</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Lượt báo cáo</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($chapters as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <span style="color: #7c69ef;">{{$item->comic->name}}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">{{$item->name}}</span>
                                            </td>
                                            <td>
                                                <span class="text-primary">{{$item->server}}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <span style="color: #7c69ef;">{{$item->slug}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <span style="color: #7c69ef;">{{$item->has_report}}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <button data-id="{{$item->id}}" style="background: #7c69ef;border: none" class="text-white btn-edit px-2 py-1 rounded font-weight-bold" type="button">
                                                        Reset
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '{{route('admin.chapter.updateError')}}',
                method: 'POST',
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
        });
    </script>
@endpush
