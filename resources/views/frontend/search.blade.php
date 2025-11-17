@extends('frontend.layout.layout')
@section('styles')
    <style>
        /* --- Базовое изображение --- */
        .card-img-top {
            height: 220px;
            object-fit: cover;
            object-position: center;
            width: 100%;
            display: block;

            transition: transform 0.35s ease, filter 0.35s ease;
            border-radius: inherit;
        }

        /* --- Контейнер карточки --- */
        .card {
            overflow: hidden;
            position: relative;
            border-radius: 0.75rem;
            /* внешняя мягкая тень */
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 28px rgba(0,0,0,0.16);
            transform: translateY(-3px);
        }

        /* --- ВНУТРЕННЯЯ тень по краям изображения --- */
        .card-img-top {
            /* внутренняя тень */
            box-shadow: inset 0 0 40px rgba(0, 0, 0, 0.25);
        }

        /* --- Градиентное затемнение снизу (под заголовок) --- */
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 220px; /* совпадает с высотой изображения */
            border-radius: inherit;

            background: linear-gradient(
                to bottom,
                rgba(0,0,0,0) 20%,
                rgba(0,0,0,0.25) 70%,
                rgba(0,0,0,0.45) 100%
            );
            pointer-events: none; /* не мешает кликам */
            opacity: 0.0;         /* включаем только при hover */
            transition: opacity 0.35s ease;
        }

        .card:hover::before {
            opacity: 1;
        }

        /* --- Zoom эффекты --- */
        .card:hover .card-img-top {
            transform: scale(1.08);
            filter: brightness(0.95);
        }
    </style>
@endsection
@section('content')

    <div class="container my-5">

        <h1 class="mb-4">Поиск статей</h1>

        {{-- ===== SEARCH FORM ===== --}}
        <form method="GET" action="{{ route('search.index') }}" class="mb-5 p-4 border rounded bg-light">

            <div class="row">
                {{-- TITLE SEARCH --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Название статьи</label>
                    <input type="text"
                           name="title"
                           value="{{ request('title') }}"
                           class="form-control"
                           placeholder="Введите часть названия">
                </div>


                {{-- CATEGORY MULTISELECT --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Теги (категории)</label>
                    <select name="categories[]" class="form-select" multiple size="5">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(in_array($cat->id, request()->input('categories', [])))>
                                {{ $cat->title }}
                            </option>
                        @endforeach
                    </select>

                    <small class="text-muted">Можно выбрать несколько (Ctrl/Cmd + click)</small>
                </div>
            </div>

            <div class="d-flex gap-3 mt-3">
                <button class="btn btn-primary">Поиск</button>

                <a href="{{ route('search.index') }}" class="btn btn-outline-secondary">
                    Очистить
                </a>
            </div>
        </form>


        {{-- ===== RESULTS ===== --}}
        @if($articles->count())
{{--            <h2 class="mb-4">Результаты ({{ $articles->total() }})</h2>--}}

            <div class="row">
                @foreach($articles as $article)
                    <div class="col-md-4 mb-4">
                        <div class="card article-card h-100 shadow-sm border-0">
                            <img src="{{ $article->hero_image_url }}"
                                 class="card-img-top"
                                 alt="{{ $article->title }}">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $article->title }}</h5>

                                {{-- Categories --}}
                                <div class="mb-2">
                                    @isset($article->categories)
                                        @php
                                            $category_append = '';
                                            foreach($article->categories as $counter => $article_category) {
                                                $category_append .= "<span class=\"badge bg-primary-subtle text-primary border border-primary me-1\">$article_category->title</span>";
                                            }
                                        @endphp
                                        {!! $category_append !!}
                                    @endisset
                                </div>

                                <p class="card-text text-muted mb-4 flex-grow-1">{{ Str::limit(strip_tags($article->description), 100) }}</p>
                                <a href="{{ route('articles.show', [$article->id, $article->slug]) }}"
                                   class="btn btn-outline-primary btn-sm mt-auto">
                                    Читать →
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
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

                            $query_l = request()->all();
                        @endphp
                        <li class="page-item{{ (!$is_prev) ? ' disabled' : '' }}"><a class="page-link" href="{{ ($is_prev) ? (route('search.index', array_merge($query_l, ['page' => $prev, ]))) : '#' }}" {{ ($is_prev) ? "data-page=\"$prev\"" : '' }}>Предыдущая</a></li>
                        @for ($i = 1; $i <= $pagination_page_count; $i++)
                            @php
                                $pagination_page_is_active = ($pagination_page == $i);
                            @endphp
                            <li class="page-item{{ $pagination_page_is_active ? ' active' : '' }}"><a class="page-link" href="{{ route('search.index', array_merge($query_l, ['page' => $i, ])) }}" data-page="{{ $i }}">{{ $i }}</a></li>
                        @endfor()
                        <li class="page-item{{ (!$is_next) ? ' disabled' : '' }}"><a class="page-link" href="{{ ($is_next) ? (route('search.index', array_merge($query_l, ['page' => $next, ]))) : '#' }}" {{ ($is_next) ? "data-page=\"$next\"" : '' }}>Следующая</a></li>
                    </ul>
                </nav>
            </div>
            {{--<div class="mt-4">
                {{ $articles->links() }}
            </div>--}}

        @else
            <h4 class="text-muted">Ничего не найдено… Попробуйте изменить параметры поиска.</h4>
        @endif

    </div>

@endsection
