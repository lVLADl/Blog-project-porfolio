@extends('frontend.layout.layout', ['hero_imgs' => []])
@section('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .hero-article {
            position: relative;
            height: 420px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
        }

        .hero-article::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* ===== ARTICLE BODY ===== */
        .article-content img {
            max-width: 100%;
            border-radius: 10px;
            margin: 20px 0;
        }

        .article-content h2 {
            margin-top: 30px;
            font-weight: 600;
        }

        .article-content p {
            line-height: 1.8;
            font-size: 1.05rem;
        }

        blockquote {
            border-left: 4px solid #0d6efd;
            padding-left: 15px;
            color: #555;
            font-style: italic;
            margin: 20px 0;
        }

        /* ===== COMMENTS ===== */
        #comments {
            margin-top: 60px;
        }

        .comment {
            background: #fff;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        /* ===== BUTTONS ===== */
        .article-nav a {
            text-decoration: none;
            font-weight: 500;
        }
    </style>
@endsection
@section('content')
    {{-- ================= ARTICLE CONTENT ================= --}}
    <main class="container my-5">
        <article class="article-content bg-white shadow-sm rounded-4 p-4">
            {!! $article->body !!}
        </article>

        {{-- ARTICLE NAVIGATION --}}
        <div class="article-nav d-flex justify-content-between align-items-center mt-5">
            <a href="#" class="btn btn-outline-secondary">&larr; Назад к статьям</a>
            <a href="#" class="btn btn-outline-primary">Следующая статья &rarr;</a>
        </div>

        @include('frontend.layout.__article_comments')
    </main>
@endsection
