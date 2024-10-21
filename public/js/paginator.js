
const currentUrl = new URL(window.location.href);

let page = parseInt(currentUrl.searchParams.get('page')) || 1;
document.getElementById('loadMoreBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Предотвращаем переход по ссылке
    page += 1;
    currentUrl.searchParams.set('page', page);
    window.location.href = currentUrl.toString();
});
