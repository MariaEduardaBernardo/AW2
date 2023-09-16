document.getElementById('filter-form').addEventListener('submit', function(event) {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'dados.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('resultados').innerHTML = xhr.responseText;
        }
    };

    xhr.send(new FormData(this));
});
