@if($page_type === 'index')
    <section class="hero">
        <div class="hero-bg active" style="background-image: url('https://picsum.photos/1600/800?random=11');"></div>
        <div class="hero-bg" style="background-image: url('https://picsum.photos/1600/800?random=12');"></div>
        <div class="hero-bg" style="background-image: url('https://picsum.photos/1600/800?random=13');"></div>

        <div class="hero-overlay"></div>

        <div class="hero-content">
            <h1 class="fw-bold mb-3">Открой для себя мир / {{ $page_type }}</h1>
            <p>Путешествуй, вдохновляйся и находи новые горизонты вместе с нашим блогом.</p>
        </div>
    </section>
@elseif($page_type === 'article')
    <section class="hero-article" style="background-image: url('https://picsum.photos/1600/800?random=21');">
        <div class="hero-content container">
            <h1 class="fw-bold mb-3">{{ $article->hero_title }}</h1>
            @isset($article->categories)
                @php
                    $category_append = 'Категории:';
                    $prev_badge_color_key = '';
                    foreach($article->categories as $counter => $article_category) {
                        $color = \App\Helpers\UiHelper::randomBadgeColor([$prev_badge_color_key]);
                        $prev_badge_color_key = \App\Helpers\UiHelper::findBadgeColorKey($color);
                        $category_append .= " <span class=\"badge $color\">$article_category->title</span>";
                    }
                @endphp
                <p class="text-light mb-0">{!! $category_append !!}</p>
            @endisset
        </div>
    </section>
@endif
