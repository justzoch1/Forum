$(document).ready(function() {
    let currentPage = 1;

    $('#loadMoreCommentsBtn').on('click', function(e) {
        e.preventDefault();
        currentPage++;

        const blogId = $(this).data('topic');

        $.ajax({
            url: `http://localhost:8876/api/${blogId}/more-comments?page=${currentPage}`,
            type: 'GET',
            success: function(data) {
                if (data.more_comments.data.length > 0) {
                    alert('It works')
                } else {
                    $('#loadMoreCommentsBtn').hide();
                }
            },
            error: function() {
                alert('Не удалось загрузить комментарии.');
            }
        });
    });
});
