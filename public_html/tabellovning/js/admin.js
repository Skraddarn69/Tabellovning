window.onload = function() {
    let ID;
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    if(!urlParams.get("ID")) {
        window.location.assign("login.html?userType=2");
    }
}

function getTeachers() {
    // Anropa API för att hämta lärare ur databas
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getTeachers.php')
    .then(function(response) {
        if(response.status == 200) {
            return response.json();
        }
    })
    .then(function(data) {
        if(data.noTeachers) {
            console.log("Inga lärare");
        } else {
            appendTeachers(data);
        }
    })
}

function appendTeachers(data) {

}