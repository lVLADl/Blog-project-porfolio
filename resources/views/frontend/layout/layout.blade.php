<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @yield('styles')
</head>
<body>

{{-- ================= HEADER ================= --}}
@include('frontend.layout.__header')

{{-- ================= HERO SECTION ================= --}}
@include('frontend.layout.__hero')

@yield('content')

{{-- ================= FOOTER ================= --}}
@include('frontend.layout.__footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if($page_type == 'article-default')
    @include('__article_comments')
@endif
@yield('script')

</body>
</html>
