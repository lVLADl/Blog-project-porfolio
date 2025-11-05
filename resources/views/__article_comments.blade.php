<script>
    // ==== AJAX COMMENT TEMPLATE ====
    document.getElementById('comment-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('name').value.trim();
        const message = document.getElementById('message').value.trim();
        if (!name || !message) return;

        // Имитация AJAX-запроса
        const commentList = document.getElementById('comment-list');
        const newComment = document.createElement('div');
        newComment.classList.add('comment');
        newComment.innerHTML = `<strong>${name}</strong><p>${message}</p>`;
        commentList.prepend(newComment);

        this.reset();
    });
</script>
