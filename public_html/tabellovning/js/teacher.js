window.onload = function() {
    // Hämtar användar-ID ur URL-parametrar
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    let ID = urlParams.get('ID');

    // Anropa API för att hämta klasser ur databas
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getStudents.php?teacherID=' + ID)
    .then(function(response) {
        if(response.status == 200) {
            return response.json();
        }
    })
    .then(function(data) {
        appendStudents(data);
    })

    // Fyll dropdown med klasser
}

// Fyll tabell med elever
function appendStudents(data) {
    let table = document.getElementById("studentList");
}