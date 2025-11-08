document.getElementById('comment-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const message = document.getElementById('message').value.trim();
    if (!name || !message) return;

    try {
        const res = await fetch('/api/article/comments', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                article_id: document.getElementById('article_id').value,
                user_name: name,
                comment: message
            })
        });

        if (!res.ok) {
            throw new Error(`Ошибка: ${res.status}`);
        }

        const data = await res.json();

        // Добавляем комментарий в DOM только после успешного ответа сервера
        const commentList = document.getElementById('comment-list');
        const newComment = document.createElement('div');
        newComment.classList.add('comment');
        newComment.innerHTML = `<strong>${data.user_name}</strong><p>${data.comment}</p>`;
        commentList.append(newComment);

        // Очистить форму
        this.reset();

    } catch (error) {
        console.error('Ошибка при отправке комментария:', error);
        alert('Не удалось отправить комментарий. Попробуйте позже.');
    }
});
