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
            if(data.noClasses) {
                console.log("Inga klasser")
            } else {
                appendClasses(data);
            }
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
        dropdown.appendChild(option);
        dropdown.onchange = function() {
            getStudents(dropdown.value);
        }
    }
    getStudents(data.classes[0].ID);
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
        // Rensa tabell
        let table = document.getElementById("studentList");
        while(table.childNodes[1]) {
            table.removeChild(table.childNodes[1]);
        }
        
        if(data.noStudents) {
            console.log("Inga elever")
        } else {
            appendStudents(data);
        }
    })
}

function appendStudents(data) {
    let student;
    let row;
    let ID;
    let firstname;
    let lastname;
    let username;
    let table = document.getElementById("studentList");

    // Lägg till tabellrader för alla elever
    for(let i = 0; i < data.students.length; i++) {
        student = data.students[i];
        row = document.createElement("tr");
        row.onclick = function() {
            row.style.background_color = "blue";
        }

        ID = document.createElement("td");
        ID.innerHTML = student.ID;
        ID.style.display = "none";

        firstname = document.createElement("td");
        firstname.innerHTML = student.fornamn;

        lastname = document.createElement("td");
        lastname.innerHTML = student.efternamn;

        username = document.createElement("td");
        username.innerHTML = student.anvandarnamn;

        row.appendChild(ID);
        row.appendChild(firstname);
        row.appendChild(lastname);
        row.appendChild(username);
        table.appendChild(row);
    }
}