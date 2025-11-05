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

        {{-- COMMENTS SECTION --}}
        <section id="comments" class="mt-5">
            <h4 class="mb-4">Комментарии</h4>

            <div id="comment-list">
                <div class="comment">
                    <strong>Алексей</strong>
                    <p>Отличная статья! Бывал в Карпатах — согласен, место волшебное.</p>
                </div>
                <div class="comment">
                    <strong>Марина</strong>
                    <p>Спасибо за советы! В этом году как раз планирую поездку 😊</p>
                </div>
            </div>

            {{-- COMMENT FORM --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Оставить комментарий</h5>
                    <form id="comment-form">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="name" placeholder="Ваше имя" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="message" rows="3" placeholder="Ваш комментарий..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
