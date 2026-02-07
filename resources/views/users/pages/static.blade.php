@extends('users.layout.main')

@section('styles')
    <style>
        .static-page-content { background: #fff; padding: 2rem; border-radius: 8px; }
        .static-page-content h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .static-page-content p { line-height: 1.7; margin-bottom: 1rem; color: #444; }
        .static-page-content ul { padding-left: 1.5rem; margin-bottom: 1rem; }
        .static-page-content ul li { margin-bottom: 0.5rem; line-height: 1.6; color: #444; }
        .static-page-content a { color: #2563eb; text-decoration: underline; }
    </style>
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background: transparent; padding: 0;">
                        <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </nav>

                <div class="static-page-content">
                    <h1 class="mb-4" style="font-size: 1.75rem; font-weight: 700;">{{ $title }}</h1>
                    @include($contentView)
                </div>
            </div>
        </div>
    </div>
@endsection
