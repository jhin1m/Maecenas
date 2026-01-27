@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.1/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    #drop-area, #drop-area-for-chap {
        border: 2px dashed #007bff;
        padding: 50px;
        text-align: center;
        cursor: pointer;
        border-radius: 10px;
        width: 80%;
        margin: 0 auto;
        background-color: #f8f9fa;
    }

    #drop-area.hover, #drop-area-for-chap.hover {
        background-color: #e9ecef;
    }

    .folder-list {
        margin-top: 20px;
        list-style-type: none;
        padding: 0;
    }

    .folder-item {
        padding: 5px;
        background-color: #e9ecef;
        margin-bottom: 5px;
        border-radius: 5px;
    }
</style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Chương'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>Chương truyện {{$comic->name}}</h3>
                            <button type="button" data-type="add" class="text-white btn btn-add rounded font-weight-bold btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                Thêm mới
                            </button>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-many-chapters">Thêm Nhiều</button>

                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-2">
                                <table id="myTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
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
                                                Tiêu đề</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($comic->chapters as $item)
                                        <tr>
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
                                                    <span style="color: #7c69ef;">{{$item->title}}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <button data-name="{{$item->name}}" data-title="{{$item->title}}" data-server="{{$item->server}}" data-images="{{$item->images}}" data-type="edit" data-id="{{$item->id}}" data-slug="{{$item->slug}}" style="background: #7c69ef;border: none" class="text-white btn-edit px-2 py-1 rounded font-weight-bold" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                                        Sửa
                                                    </button>
                                                    <button data-name="{{$item->chapter_number}}" data-id="{{$item->id}}" type="button" style="border: none" class="text-white px-2 btn-delete py-1 rounded font-weight-bold bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-notification">
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
                                              <label for="name" class="col-form-label">Chap:</label>
                                              <input type="text" class="form-control" value="" id="name">
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="col-form-label">Tiêu đề:</label>
                                                <input type="text" class="form-control" value="" id="title">
                                              </div>
                                            <div class="form-group">
                                              <label for="server" class="col-form-label">Server:</label>
                                              <input type="text" class="form-control" value="" id="server">
                                            </div>
                                            <div>
                                                <div id="drop-area-for-chap">
                                                    <p>Kéo và thả một thư mục vào đây.</p>
                                                </div>
                                                <ul id="folder-list-for-chap" class="folder-list"></ul>
                                                <button id="upload-button-for-chap" class="btn btn-primary" onclick="uploadFile()">Tải lên</button>
                                            </div>
                                            <div class="form-group">
                                                <label for="images">PAGE|LINK</label>
                                                <textarea class="form-control" id="images" rows="10"></textarea>
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

                        <div class="modal fade" id="add-many-chapters" tabindex="-1" aria-labelledby="add-many-chaptersLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="add-many-chaptersLabel">Thêm nhiều chap</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4">
                                        <div>
                                            <h4>Tiến trình tải lên</h4>
                                            <div class="progress">
                                                <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p id="upload-status">Đang tải lên: 0/0 thư mục</p>
                                        </div>

                                        <div id="drop-area">
                                            <p>Kéo và thả một hoặc nhiều thư mục vào đây.</p>
                                        </div>

                                        <ul id="folder-list" class="folder-list"></ul>
                                        <button id="upload-button" class="btn btn-primary" onclick="uploadFiles()">Tải lên</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let table = new DataTable('#myTable');
        let id = null;
        let type = null;
        $(document).on('click', '.btn-delete', function() {
            id = null;
            $('.container-text').text('');
            id = $(this).data('id');
            $('.container-text').text('Bạn có chắc chắn muốn xóa chương ' + $(this).data('name') + ' không?' );
        });
        $('.btn-submit').click(function(){
            $.ajax({
                url: '{{route('admin.chapterComic.destroy', ["chapterComic" => 'id'])}}',
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
            $('#labelModal').text('Thêm mới chương');
            $('.btn-submit-abc').text('Thêm mới');
            $('#name').val('');
            $('#title').val('');
            $('#server').val('');
            $('#images').empty();
            type = 'add';
        })
        $(document).on('click', '.btn-edit', function() {
            id = null;
            id = $(this).data('id');
            $('#labelModal').text('Sửa chương');
            $('.btn-submit-abc').text('Sửa');
            $('#name').val($(this).data('name'));
            $('#title').val($(this).data('title'));
            $('#server').val($(this).data('server'));
            $('#images').empty();
            let images = $(this).data('images');
            $('#images').empty();
            if(images){
                images.forEach(image => {
                    $('#images').append(image.page + '|' + image.link + '\n');
                });
            }
            type = 'edit';
        });
        $('.btn-submit-abc').click(function(){
            let name = $('#name').val();
            let serverName = $('#server').val();
            let images = $('#images').val();
            let title = $('#title').val();
            images = images.split('\n');
            if(!name || !serverName || !images){
                alert('Vui lòng nhập đầy đủ thông tin');
                return;
            }
            if(type == 'add'){
                $.ajax({
                    url: '{{route('admin.chapterComic.store')}}',
                    method: 'POST',
                    data: {
                        name: name,
                        title: title,
                        serverName: serverName,
                        images: images,
                        comic_id: '{{$comic->id}}',
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
                    url: '{{route('admin.chapterComic.update', ['chapterComic' => 'id'])}}',
                    method: 'PUT',
                    data: {
                        name: name,
                        title: title,
                        serverName: serverName,
                        images: images,
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
    <script>
        const dropArea = document.getElementById('drop-area');
        const folderList = document.getElementById('folder-list');
        const uploadButton = document.getElementById('upload-button');
        let allFolders = [];

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('hover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('hover');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('hover');
            const items = e.dataTransfer.items;

            for (let i = 0; i < items.length; i++) {
                const item = items[i];

                if (item.kind === 'file' && item.webkitGetAsEntry) {
                    const entry = item.webkitGetAsEntry();
                    if (entry.isDirectory) {
                        readDirectory(entry);
                    }
                }
            }
        });

        function readDirectory(directoryEntry) {
            const reader = directoryEntry.createReader();
            const allEntries = [];
            let imageCount = 0;

            function readEntries() {
                reader.readEntries((entries) => {
                    if (entries.length > 0) {
                        allEntries.push(...entries);
                        readEntries();
                    } else {
                        const imagesInFolder = [];
                        allEntries.forEach((entry) => {
                            if (entry.isFile && entry.name.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
                                imageCount++;
                                imagesInFolder.push(entry);
                            }
                        });
                        allFolders.push({ folderName: directoryEntry.name, images: imagesInFolder });
                        displayFolder(directoryEntry.name, imageCount);
                    }
                });
            }

            readEntries();
        }

        function displayFolder(folderName, imageCount) {
            const li = document.createElement('li');
            li.classList.add('folder-item');
            li.textContent = `Chapter ${folderName} - Có ${imageCount} ảnh`;
            folderList.appendChild(li);
        }

        async function uploadFiles() {
            const totalFolders = allFolders.length;
            let uploadedFolders = 0;

            $('#upload-button').prop('disabled', true);
            $('#upload-button').html('<span class="spinner-border me-1" role="status" aria-hidden="true" style="width:1em; height: 1em;"></span>Đang tải...');
            $('#upload-button').attr('style','color:white');

            for(const folder of allFolders) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('slug', '{{$comic->slug}}');
                formData.append('folders[]', folder.folderName);
                const filePromises = [];

                folder.images.forEach(imageEntry => {
                    const promise = new Promise((resolve, reject) => {
                        imageEntry.file((file) => {
                            formData.append('files[]', file);
                            resolve();
                        });
                    });
                    filePromises.push(promise);
                });

                await Promise.all(filePromises);

                await $.ajax({
                    url: '/admin/upload-many-images',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr) {
                        var response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            title: 'Thất bại!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });

                uploadedFolders++;
                const progressPercentage = (uploadedFolders / totalFolders) * 100;

                $('#progress-bar').css('width', progressPercentage + '%');
                $('#progress-bar').attr('aria-valuenow', progressPercentage);

                $('#upload-status').text(`Đang tải lên: ${uploadedFolders}/${totalFolders} thư mục`);
            };

            allFolders = [];
            folderList.innerHTML = '';
            $('#upload-button').prop('disabled', false);
            $('#upload-button').html('Tải lên');
            $('#upload-button').attr('style','');

            Swal.fire({
                title: 'Thành công!',
                text: 'Tải lên thành công.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    </script>

    <script>
            const dropAreaChap = document.getElementById('drop-area-for-chap');
            const uploadButtonChap = document.getElementById('upload-button-for-chap');
            let allFoldersChap = [];
            dropAreaChap.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropAreaChap.classList.add('hover');
            });

            dropAreaChap.addEventListener('dragleave', () => {
                dropAreaChap.classList.remove('hover');
            });

            dropAreaChap.addEventListener('drop', (e) => {
                e.preventDefault();
                dropAreaChap.classList.remove('hover');
                const items = e.dataTransfer.items;
                for (let i = 0; i < items.length; i++) {
                    const item = items[i];
                    if (item.kind === 'file' && item.webkitGetAsEntry) {
                        const entry = item.webkitGetAsEntry();
                        if (entry.isDirectory) {
                            readDirectoryChap(entry);
                        }
                    }
                }
            });

            function readDirectoryChap(directoryEntry) {
                const reader = directoryEntry.createReader();
                const allEntries = [];
                let imageCount = 0;
                function readEntries() {
                    reader.readEntries((entries) => {
                        if (entries.length > 0) {
                            allEntries.push(...entries);
                            readEntries();
                        } else {
                            const imagesInFolder = [];
                            allEntries.forEach((entry) => {
                                if (entry.isFile && entry.name.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
                                    imageCount++;
                                    imagesInFolder.push(entry);
                                }
                            });
                            allFoldersChap.push({ folderName: directoryEntry.name, images: imagesInFolder });
                            displayFolder(directoryEntry.name, imageCount);
                        }
                    });
                }
                readEntries();
            }

            function displayFolder(folderName, imageCount) {
                const li = document.createElement('li');
                const folderListChap = document.getElementById('folder-list-for-chap');
                li.classList.add('folder-item');
                li.textContent = `Folder ${folderName} - Có ${imageCount} ảnh`;
                folderListChap.appendChild(li);
            }
            async function uploadFile(){
                const totalFolders = allFoldersChap.length;
                let uploadedFolders = 0;

                if(!$('#name').val()){
                    alert('Vui lòng nhập tên chương');
                    return;
                }

                $('#upload-button-for-chap').prop('disabled', true);
                $('#upload-button-for-chap').html('<span class="spinner-border me-1" role="status" aria-hidden="true" style="width:1em; height: 1em;"></span>Đang tải...');
                $('#upload-button-for-chap').attr('style','color:white');

                for(const folder of allFoldersChap) {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('slug', '{{$comic->slug}}');
                    formData.append('name', $('#name').val());
                    formData.append('folders[]', folder.folderName);
                    const filePromises = [];

                    folder.images.forEach(imageEntryChap => {
                        const promise = new Promise((resolve, reject) => {
                            imageEntryChap.file((file) => {
                                formData.append('files[]', file);
                                resolve();
                            });
                        });
                        filePromises.push(promise);
                    });

                    await Promise.all(filePromises);

                    await $.ajax({
                        url: '/admin/upload-images',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            const imagesResponse = response.images;
                            $('#images').empty();
                            imagesResponse.forEach((image, index) => {
                                $('#images').append(index + '|' + image + '\n');
                            });
                        },
                        error: function(xhr) {
                            var response = JSON.parse(xhr.responseText);
                            Swal.fire({
                                title: 'Thất bại!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });

                    uploadedFolders++;
                };

                allFoldersChap = [];
                $('#upload-button-for-chap').prop('disabled', false);
                $('#upload-button-for-chap').html('Tải lên');
                $('#upload-button-for-chap').attr('style','');
            }
    </script>
@endpush
