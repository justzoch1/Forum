$(document).ready(function() {
    let currentPage = 1;
    const blogId = (this).data('topic');

    $('#loadMoreCommentsBtn').on('click', function(e) {
        e.preventDefault();
        currentPage++;

        $.ajax({
            url: `http://localhost:8876/blog/${blogId}?page=${currentPage}`,
            type: 'GET',
            success: function(data) {
                alert(`it's works`);
            },
            error: function() {
                alert('');
            }
        });
    });
});
