{{-- COMMENTS SECTION --}}
<section id="comments" class="mt-5">
    @php
        // $comments_visibility = ($article->comments->isEmpty()) ? 'none' : 'block';
    @endphp
    <h4 id="comment-list-header" class="mb-4" >Комментарии</h4>
    <div id="comment-list" >
        @foreach($article->comments as $comment)
            <div class="comment">
                <strong>{{ $comment->user_name }}</strong>
                <p>{{ $comment->comment }}</p>
            </div>
        @endforeach
    </div>

    {{-- COMMENT FORM --}}
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Оставить комментарий</h5>
            <form id="comment-form">
                <input type="hidden" class="form-control" id="article_id" value="{{ $article->id }}">
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
