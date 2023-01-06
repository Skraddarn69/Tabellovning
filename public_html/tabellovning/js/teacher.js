window.onload = function() {
    // Hämtar användar-ID ur URL-parametrar
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    let ID = urlParams.get('ID');
    getClasses(ID);

function getClasses(ID) {
        // Anropa API för att hämta klasser ur databas
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getClasses.php?teacherID=' + ID)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            // Fyll dropdown med klasser
            appendClasses(data);
        })
    }
}

function appendClasses(data) {
    let thisClass;
    let dropdown = document.getElementById("classSelect");
    let option;

    for(let i = 0; i < data.classes.length; i++) {
        thisClass = data.classes[i];
        option = document.createElement("option");
        option.innerHTML = thisClass.klass;
        option.value = thisClass.ID;
        option.onchange = getStudents(thisClass.ID)
        dropdown.appendChild(option);
    }
}

function getStudents(ID) {
    // Anropa API för att hämta elever ur databas
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getStudents.php?classID=' + ID)
    .then(function(response) {
        if(response.status == 200) {
            return response.json();
        }
    })
    .then(function(data) {
        // Fyll tabell med elever
        appendStudents(data);
    })
}

function appendStudents(data) {
    let table = document.getElementById("studentList");

    
}