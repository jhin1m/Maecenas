@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'SEO'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#comic" role="tab" aria-controls="comic" aria-selected="false">
                                Truyện tranh
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#category" role="tab" aria-controls="category" aria-selected="false">
                                Thể loại
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#author" role="tab" aria-controls="author" aria-selected="false">
                                Tác giả
                                </a>
                            </li>
                         </ul>
                         <div class="tab-content mt-2">
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                @foreach ($seo as $item)
                                                @if ($item->group == "general")
                                                @if($item->key == 'head_meta')
                                                <div class="form-group">
                                                    <label for="{{$item->key}}">{{$item->name}}</label>
                                                    <textarea class="form-control" id="{{$item->key}}" rows="5">{{$item->value}}</textarea>
                                                </div>
                                                @elseif ($item->key == 'meta_image')
                                                <div class="form-group">
                                                    <label for="{{$item->key}}" class="form-control-label">{{$item->name}}</label>
                                                    <div class="input-group input-group-alternative mb-4">
                                                        <input class="form-control" value="{{$item->value}}" id="{{$item->key}}" type="text">
                                                        <a href="javascript:void(0)" onclick="openCKFinder2()" class="input-group-text"><i class="ni ni-zoom-split-in"></i></a>
                                                    </div>
                                                </div>
                                                @elseif ($item->key == 'shortcut')
                                                <div class="form-group">
                                                    <label for="{{$item->key}}" class="form-control-label">{{$item->name}}</label>
                                                    <div class="input-group input-group-alternative mb-4">
                                                        <input class="form-control" value="{{$item->value}}" id="{{$item->key}}" type="text">
                                                        <a href="javascript:void(0)" onclick="openCKFinder()" class="input-group-text"><i class="ni ni-zoom-split-in"></i></a>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="form-group">
                                                    <label for="{{$item->key}}" class="form-control-label">{{$item->name}}</label>
                                                    <input class="form-control" type="text" value="{{$item->value}}" id="{{$item->key}}">
                                                </div>
                                                @endif
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="comic" role="tabpanel" aria-labelledby="contact-tab" tabindex="-1">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                @foreach ($seo as $item)
                                                @if ($item->group == "comic")
                                                <div class="form-group">
                                                    <label for="{{$item->key}}" class="form-control-label">{{$item->name}}</label>
                                                    <input class="form-control" type="text" value="{{$item->value}}" id="{{$item->key}}">
                                                    <span class="text-xs">{{$item->description}}</span>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="category" role="tabpanel" aria-labelledby="contact-tab" tabindex="-1">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                @foreach ($seo as $item)
                                                @if ($item->group == "category")
                                                <div class="form-group">
                                                    <label for="{{$item->key}}" class="form-control-label">{{$item->name}}</label>
                                                    <input class="form-control" type="text" value="{{$item->value}}" id="{{$item->key}}">
                                                    <span class="text-xs">{{$item->description}}</span>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="author" role="tabpanel" aria-labelledby="contact-tab" tabindex="-1">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                @foreach ($seo as $item)
                                                @if ($item->group == "author")
                                                <div class="form-group">
                                                    <label for="{{$item->key}}" class="form-control-label">{{$item->name}}</label>
                                                    <input class="form-control" type="text" value="{{$item->value}}" id="{{$item->key}}">
                                                    <span class="text-xs">{{$item->description}}</span>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary btn-save">Lưu</button>
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
    <script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
    <script>
        CKFinder.config( { connectorPath: '/ckfinder/connector' } );
        function openCKFinder() {
            CKFinder.modal({
                chooseFiles: true,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        var file = evt.data.files.first();
                        document.getElementById('shortcut').value = file.getUrl();
                    });
                }
            });
        }
        function openCKFinder2() {
            CKFinder.modal({
                chooseFiles: true,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        var file = evt.data.files.first();
                        document.getElementById('meta_image').value = file.getUrl();
                    });
                }
            });
        }
        $('.btn-save').click(function(){
            var data = [];
            $('.col-12 input').each(function(){
                data.push({
                    key: $(this).attr('id'),
                    value: $(this).val()
                });
            });
            $('textarea').each(function(){
                data.push({
                    key: $(this).attr('id'),
                    value: $(this).val()
                });
            });
            $.ajax({
                url: '{{route('admin.updateSeo')}}',
                type: 'POST',
                data: {
                    data: data,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data){
                    if(data.status == "success"){
                        alert(data.message);
                        window.location.reload();
                    }else{
                        alert('Lưu thất bại');
                    }
                }, error: function(){
                   alert('Lưu thất bại');
                }
            });
        });
    </script>
@endpush
