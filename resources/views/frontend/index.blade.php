@extends('frontend.layout.layout', [])
@section('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            position: relative;
            height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 2s ease-in-out;
        }

        .hero-bg.active {
            opacity: 1;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 700px;
        }

        .hero h1 {
            font-weight: 700;
        }

        .hero p {
            font-size: 1.25rem;
            color: #e2e6ea;
        }

        /* ===== CARDS ===== */
        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .card-img-top {
            height: 220px;
            object-fit: cover;
        }

        .badge {
            font-size: 0.75rem;
        }

        /* ===== PAGINATION ===== */
        .loader {
            display: none;
            text-align: center;
            margin: 30px 0;
        }

        .fade-transition {
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .fade-transition.show {
            opacity: 1;
        }
    </style>
@endsection
@section('content')
    {{-- ================= MAIN CONTENT ================= --}}
    <main class="container my-5" id="blog-container">
        <div class="row g-4 fade-transition show" id="post-list">
            @foreach ($articles as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ $post->hero_image }}" class="card-img-top" alt="Изображение статьи">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <div class="mb-2">

                                @isset($post->categories)
                                    @php
                                        $category_append = '';
                                        foreach($post->categories as $counter => $article_category) {
                                            $category_append .= "<span class=\"badge bg-primary-subtle text-primary border border-primary me-1\">$article_category->title</span>";
                                        }
                                    @endphp
                                    {!! $category_append !!}
                                @endisset
                            </div>
                            <p class="card-text text-muted mb-4">{{ $post->description }}</p>
                            <a href="{{ route('articles.show', ['slug' => $post->slug]) }}" class="mt-auto btn btn-outline-primary btn-sm w-100">Читать далее</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- LOADER --}}
        <div class="loader" id="loader">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="text-muted mt-2">Загрузка...</p>
        </div>

        {{-- ================= PAGINATION ================= --}}
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination">
                    @php
                        $is_prev = $pagination_page > 1;
                        $prev = ($pagination_page - 1);

                        $is_next = $pagination_page < $pagination_page_count;
                        $next = $pagination_page + 1;
                    @endphp
                    <li class="page-item{{ (!$is_prev) ? ' disabled' : '' }}"><a class="page-link" href="{{ ($is_prev) ? (route('index', ['page' => $prev])) : '#' }}" {{ ($is_prev) ? "data-page=\"$prev\"" : '' }}>Предыдущая</a></li>
                    @for ($i = 1; $i <= $pagination_page_count; $i++)
                        @php
                            $pagination_page_is_active = ($pagination_page == $i);
                        @endphp
                        <li class="page-item{{ $pagination_page_is_active ? ' active' : '' }}"><a class="page-link" href="{{ route('index', ['page' => $i]) }}" data-page="{{ $i }}">{{ $i }}</a></li>
                    @endfor()
                    <li class="page-item{{ (!$is_next) ? ' disabled' : '' }}"><a class="page-link" href="{{ ($is_next) ? (route('index', ['page' => $next])) : '#' }}" {{ ($is_next) ? "data-page=\"$next\"" : '' }}>Следующая</a></li>
                </ul>
            </nav>
        </div>
    </main>
@endsection
@section('script')
    <script>
        // ==== HERO BACKGROUND SLIDESHOW ====
        const slides = document.querySelectorAll('.hero-bg');
        let current = 0;
        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 7000);
    </script>
@endsection
