@if($page_type === 'index')
    <section class="hero">
        @php
            $first_active_is_set = false;
        @endphp
        @foreach($slider ?? [] as $slider_img)
            <div class="hero-bg{{ !$first_active_is_set ? " active" : "" }}" style="background-image: url('{{ Storage::url($slider_img['hero_image']) }}')"></div>
        @endforeach
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <h1 class="fw-bold mb-3">Открой для себя мир</h1>
            <p>Путешествуй, вдохновляйся и находи новые горизонты вместе с нашим блогом.</p>
        </div>
    </section>
@elseif($page_type === 'article' && isset($article->categories))
    @php
        /* TODO: заменить на проверку соответствия url- "этого" сайта; 'если hero_img - не с этого сайта: не используй Storage::url(...)'*/
        /* if($hero_src=$article->hero_image) {
            $hero_img =
                !\App\Helpers\StrHelper::glob_match('*://picsum.photos/*', $hero_src) ?
                    Storage::url($hero_src) : $hero_src;
        }
        else {
            // $hero_img = "https://picsum.photos/1600/800?random=21";
            $hero_img = \App\Helpers\PageHelper::$image_placeholder;
        } */
            $hero_background_style = "background-image: url('$article->hero_image_url')";
    @endphp
        <section class="hero-article" style="{{ $hero_background_style }}">
            <div class="hero-content container">
                <h1 class="fw-bold mb-3">{{ $article->hero_title }}</h1>

                @if(count($article->categories))
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
                @endif

            </div>
        </section>
@endif
