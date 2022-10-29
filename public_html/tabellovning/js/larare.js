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
            fyllMenyLarare(data, page);
        })
}

function fyllMenyLarare(data, page) {
    // Ange href för tillbaka-knappen
    document.getElementById("tillbaka").href = "index.html";

    let meny = document.getElementById("meny-tabell").getElementsByTagName("td");
    let cell;
    for(let i = 0; i < 9; i++) {
        cell = meny.item(i);
    }
}
