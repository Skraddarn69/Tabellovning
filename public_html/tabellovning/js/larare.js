window.onload = function() {
    hamtaLarare(1);
}

function hamtaLarare(page) {
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getTeachers.php?page=' + page)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendTeachers(data, page);
        })
}
