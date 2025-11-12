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

            <p class="lead">{!! $itinerary->intro ?? '' !!}</p>

            @if(!empty($itinerary->map_url))
            <div class="map-frame">
                <iframe src="{{ $itinerary->map_url }}"
                        width="100%" height="400" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>
            @endif

            @foreach ($itinerary->itinerary_days['itinerary'] as $day)
                <div class="day-block">
                    <h2>День {{ $day['day'] }} — {{ $day['title'] }}</h2>
                    <ul>
                        @foreach ($day['activities'] as $activity)
                            <li>{!! $activity !!}</li>
                        @endforeach
                    </ul>
                    @if(isset($day['image']['src']))
                        @php
                            $itinerary_day_img_src = Storage::url($day['image']['src']);
                            $itinerary_day_img_alt = $day['image']['alt'] ?? '';
                        @endphp
                        <img src="{{ $itinerary_day_img_src }}" alt="{{ $itinerary_day_img_alt }}" class="img-fluid">
                    @endif
                    @if(!empty($day['tip']))
                        <div class="tip-box">
                            {!! $day['tip'] !!}
                        </div>
                    @endif
                </div>
            @endforeach

            @if($itinerary->trip_budget)
                <h3>Бюджет поездки</h3>
                <table class="table table-bordered bg-white mt-3">
                    <thead>
                    <tr class="table-light">
                        <th>Статья расходов</th>
                        <th>Средняя стоимость</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($itinerary->trip_budget['table']['rows'] as $budget)
                            <tr><td>{!! $budget['Статья расходов'] ?? '' !!}</td><td>{!! $budget['Средняя стоимость'] ?? '' !!}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                @if(!empty($itinerary->trip_budget_advice))
                    <div class="tip-box">
                        {!! $itinerary->trip_budget_advice !!}
                    </div>
                @endif
            @endif

            <h3>{{ $itinerary->results_title }}</h3>
            <p>{{ $itinerary->results_description }}</p>
        </article>

        {{-- ARTICLE NAVIGATION --}}
        <div class="article-nav d-flex justify-content-between align-items-center mt-5">
            <a href="#" class="btn btn-outline-secondary">&larr; Назад к статьям</a>
            <a href="#" class="btn btn-outline-primary">Следующая статья &rarr;</a>
        </div>

        @include('frontend.layout.__article_comments')

    </main>
@endsection
