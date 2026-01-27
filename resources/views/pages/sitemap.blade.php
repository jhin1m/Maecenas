@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Sitemap'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>Sitemap</h3>
                        </div>
                        <div class="card-body align-items-center pb-2">
                            Sitemap sẽ được lưu tại đường dẫn: <a target="_blank" href="{{ url('/') }}/sitemap.xml">{{ url('/') }}/sitemap.xml</a>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-icon btn-generate btn-3 btn-primary" type="button">
                                <span class="btn-inner--icon"><i class="ni ni-scissors"></i></span>
                              <span class="btn-inner--text">Tạo sitemap</span>
                            </button>
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
        $('.btn-generate').click(function(){
            $.ajax({
                url: '{{ route('admin.generateSitemap') }}',
                type: 'POST',
                data:{
                    _token: '{{ csrf_token() }}'
                },
                success: function(response){
                    if(response.status == 'success'){
                        alert('Tạo sitemap thành công');
                    }else{
                        alert('Tạo sitemap thất bại');
                    }
                }, error: function(){
                    alert('Tạo sitemap thất bại');
                }
            });
        });
    </script>
@endpush
