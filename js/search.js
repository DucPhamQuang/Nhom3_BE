document.getElementById('search-query').addEventListener('input', function () {
    const query = this.value.trim();
    if (query.length > 0) {
        fetch('search_ajax.php?query=' + encodeURIComponent(query))
            .then(response => response.text())
            .then(data => {
                document.getElementById('search-results').innerHTML = data;
            });
    } else {
        document.getElementById('search-results').innerHTML = '';
    }
});
