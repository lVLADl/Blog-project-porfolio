{{-- ================= ARTICLE BODY ================= --}}



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

        .day-block {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .day-block img {
            border-radius: 10px;
            margin-top: 15px;
        }

        .map-frame {
            border-radius: 12px;
            overflow: hidden;
            margin: 25px 0;
        }

        .tip-box {
            background: #e7f1ff;
            border-left: 4px solid #0d6efd;
            border-radius: 6px;
            padding: 12px 16px;
            margin: 20px 0;
        }

        .article-nav a {
            text-decoration: none;
            font-weight: 500;
        }
    </style>
@endsection
@section('content')
    <main class="container my-5">
        <article class="article-content">

            <p class="lead">Париж — город, в который невозможно не влюбиться.
                Мы подготовили лёгкий, но насыщенный маршрут на 3 дня, чтобы вы увидели всё самое главное — и успели насладиться атмосферой.</p>

            <div class="map-frame">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d41993.42191727492!2d2.3123243380232185!3d48.85661401366082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e29604c9b03%3A0xa0b82c3688b7aa0!2zUGFyaXM!5e0!3m2!1sru!2sua!4v1730550000000"
                        width="100%" height="400" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>

            @foreach ($itinerary->itinerary_days['itinerary'] as $day)
                <div class="day-block">
                    <h2>День {{ $day['day'] }} — {{ $day['title'] }}</h2>
                    <ul>
                        @foreach ($day['activities'] as $activity)
                            <li>{!! $activity !!}</li>
                        @endforeach
                    </ul>
                    <img src="{{ $day['image']['src'] }}" alt="{{ $day['image']['alt'] }}" class="img-fluid">
                </div>
                @if(!empty($day['tip']))
                    <div class="tip-box">
                        {!! $day['tip'] !!}
                    </div>
                @endif
            @endforeach

            <h3>Бюджет поездки</h3>
            <table class="table table-bordered bg-white mt-3">
                <thead>
                    <tr class="table-light">
                        <th>Статья расходов</th>
                        <th>Средняя стоимость</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Проживание (3 ночи)</td><td>€180–250</td></tr>
                    <tr><td>Питание</td><td>€60–100</td></tr>
                    <tr><td>Транспорт и музеи</td><td>€40–70</td></tr>
                    <tr><td><strong>Итого</strong></td><td><strong>около €350–420</strong></td></tr>
                </tbody>
            </table>

            <div class="tip-box">
                🎫 <strong>Факт:</strong> Если планируете активно посещать музеи, купите <em>Paris Museum Pass</em> — это сэкономит деньги и время.
            </div>

            <h3>Итоги</h3>
            <p>За три дня вы увидите всё главное, почувствуете атмосферу и, возможно, захотите вернуться.
                Париж не отпускает — он просто ждёт вашего следующего визита.</p>

        </article>
        {!! '' // $article->body !!}

        {{-- ARTICLE NAVIGATION --}}
        <div class="article-nav d-flex justify-content-between align-items-center mt-5">
            <a href="#" class="btn btn-outline-secondary">&larr; Назад к статьям</a>
            <a href="#" class="btn btn-outline-primary">Следующая статья &rarr;</a>
        </div>

        @include('frontend.layout.__article_comments')

    </main>
@endsection
