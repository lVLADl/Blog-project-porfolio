<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @yield('styles')
    <style>
        /* базовое sticky позиционирование */
        header.navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: transform 0.35s ease;
        }

        /* скрытый navbar */
        .navbar-hidden {
            transform: translateY(-100%);
        }
    </style>
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

@if($page_type === 'article')
    <script src="{{ asset('/js/article_comments.js') }}"></script>
@endif
@yield('script')
<script>
    let lastScrollPosition = 0;
    const navbar = document.querySelector('header.navbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll > lastScrollPosition && currentScroll > 80) {
            // Скроллим вниз → прячем
            navbar.classList.add('navbar-hidden');
        } else {
            // Скроллим вверх → показываем
            navbar.classList.remove('navbar-hidden');
        }

        lastScrollPosition = currentScroll;
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.querySelector('.search-header-form input[name="title"]');
        const dropdown = document.getElementById('search-suggestions');

        let lastValue = '';

        input.addEventListener('input', async () => {
            const q = input.value.trim();

            if (q.length < 2) {
                dropdown.style.display = 'none';
                return;
            }

            // Запоминаем значение
            lastValue = q;

            const res = await fetch(`{{ route('search.ajax') }}?q=${encodeURIComponent(q)}`);
            const items = await res.json();

            if (input.value.trim() !== lastValue) return; // защита от race-condition

            if (!items.length) {
                dropdown.style.display = 'none';
                return;
            }

            // Генерация разметки
            dropdown.innerHTML = items.map(item => `
            <a href="/articles/${item.id}-${item.slug}" class="dropdown-item">
                ${item.title}
            </a>
        `).join('');

            dropdown.style.display = 'block';
        });

        // Закрытие списка при клике вне
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
