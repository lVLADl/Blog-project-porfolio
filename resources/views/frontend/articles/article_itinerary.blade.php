<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мир Путешествий — 3 дня в Париже</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
</head>
<body>

{{-- ================= HEADER ================= --}}
<header class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Мир Путешествий</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navbarNav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="#" class="nav-link">Главная</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Маршруты</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Гиды</a></li>
                <li class="nav-item"><a href="#" class="nav-link active">Блог</a></li>
            </ul>
        </div>
    </div>
</header>

{{-- ================= HERO ================= --}}
<section class="hero-article" style="background-image: url('https://picsum.photos/1600/800?random=21');">
    <div class="hero-content container">
        <h1 class="fw-bold mb-3">3 дня в Париже: идеальный маршрут</h1>
        <p class="text-light mb-0">Категории: <span class="badge bg-primary">Гид</span> <span class="badge bg-success">Европа</span> <span class="badge bg-info">Маршруты</span></p>
    </div>
</section>

{{-- ================= ARTICLE BODY ================= --}}
<main class="container my-5">
    <article class="article-content">

        <p class="lead">Париж — город, в который невозможно не влюбиться.
            Мы подготовили лёгкий, но насыщенный маршрут на 3 дня, чтобы вы увидели всё самое главное — и успели насладиться атмосферой.</p>

        <div class="map-frame">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d41993.42191727492!2d2.3123243380232185!3d48.85661401366082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e29604c9b03%3A0xa0b82c3688b7aa0!2zUGFyaXM!5e0!3m2!1sru!2sua!4v1730550000000"
                    width="100%" height="400" style="border:0;" allowfullscreen loading="lazy"></iframe>
        </div>

        {{-- DAY 1 --}}
        <div class="day-block">
            <h2>День 1 — Классика Парижа</h2>
            <ul>
                <li><strong>Завтрак</strong> в кафе возле Сены — попробуйте круассаны и кофе с видом на Нотр-Дам.</li>
                <li><strong>Прогулка по Латинскому кварталу</strong> — узкие улочки, книжные лавки и атмосфера 19 века.</li>
                <li><strong>Лувр</strong> — выделите минимум 2 часа. Вход лучше бронировать заранее.</li>
                <li><strong>Закат у Эйфелевой башни</strong> — возьмите плед и вино в парке Марсово поле.</li>
            </ul>
            <img src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1000&q=80" alt="Эйфелева башня на закате" class="img-fluid">
        </div>

        {{-- DAY 2 --}}
        <div class="day-block">
            <h2>День 2 — Искусство и атмосфера</h2>
            <ul>
                <li><strong>Монмартр</strong> — утренняя прогулка по лестницам к Сакре-Кёр.</li>
                <li><strong>Музей Орсе</strong> — импрессионисты, залы в старом вокзале.</li>
                <li><strong>Обед</strong> в бистро «Le Consulat» — французский лук-суп и багет.</li>
                <li><strong>Прогулка по Сене</strong> на кораблике вечером — лучший способ увидеть огни Парижа.</li>
            </ul>
            <img src="https://picsum.photos/1600/800?random=21" alt="Монмартр утром" class="img-fluid">
        </div>

        {{-- DAY 3 --}}
        <div class="day-block">
            <h2>День 3 — Современный Париж</h2>
            <ul>
                <li><strong>Ла Дефанс</strong> — небоскрёбы, деловой район, панорама города.</li>
                <li><strong>Фонтан Сен-Мишель</strong> и прогулка вдоль Бульвара Сен-Жермен.</li>
                <li><strong>Заключительный ужин</strong> на террасе кафе с видом на Эйфелеву башню.</li>
            </ul>
            <img src="https://picsum.photos/1600/800?random=23" alt="Ла Дефанс" class="img-fluid">
        </div>

        <div class="tip-box">
            💡 <strong>Совет:</strong> Париж лучше всего исследовать пешком — многие достопримечательности расположены в пределах 10–15 минут ходьбы.
        </div>

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

    {{-- NAVIGATION --}}
    <div class="article-nav d-flex justify-content-between align-items-center mt-5">
        <a href="#" class="btn btn-outline-secondary">&larr; Назад к статьям</a>
        <a href="#" class="btn btn-outline-primary">Следующая статья &rarr;</a>
    </div>

</main>

{{-- ================= FOOTER ================= --}}
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1">&copy; {{ date('Y') }} Мир Путешествий. Все права защищены.</p>
        <p class="text-secondary small mb-0">Создано с 🗺️ вдохновением и Bootstrap</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
