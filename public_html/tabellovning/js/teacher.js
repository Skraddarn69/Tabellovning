window.onload = function() {
    // Hämta användar-ID ur URL-parametrar
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    let ID = urlParams.get('ID');
    getClasses(ID);
}

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
            console.log("Inga klasser");
        } else {
            appendClasses(data);
        }
    })
}

function appendClasses(data) {
    let thisClass;
    let dropdown = document.getElementById("classSelect");
    let option;

    // Fyll dropdown med klasser
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
        let table1 = document.getElementById("studentList");
        let table2 = document.getElementById("results");
        let message = document.getElementById("studMsg");

        // Rensa tabeller
        while(table1.childNodes[2]) {
            table1.removeChild(table1.childNodes[2]);
        }

        while(table2.childNodes[2]) {
            table2.removeChild(table2.childNodes[2]);
        }

        // Visa elevtabell
        document.getElementById("studMenu").style.display = "unset";

        // Dölj inga resultat meddelande
        document.getElementById("resMsg").style.display = "none";

        // Dölj redigeringsformulär
        document.getElementById("editMenu").style.display = "none";
        
        // Lägg till elever i tabell om elever finns annars visa meddelande
        if(data.noStudents) {
            message.style.display = "unset";
        } else {
            message.style.display = "none";
            appendStudents(data);
        }
    })
}

function appendStudents(data) {
    let student;
    let row;
    let rows = [];
    let defColor;
    let ID;
    let firstname;
    let lastname;
    let username;
    let edit;
    let erase;
    let table = document.getElementById("studentList");

    // Dölj inga resultat meddelande
    document.getElementById("resMsg").style.display = "none";

    // Lägg till tabellrader för alla elever
    for(let i = 0; i < data.students.length; i++) {
        student = data.students[i];
        row = document.createElement("tr");
        
        // Markera tabellrad och visa elevens resultat när man klickar på raden
        row.onclick = function() {
            if(rows[i].style.backgroundColor == defColor) {
                for(let o = 0; o < rows.length; o++) {
                    rows[o].style.backgroundColor = defColor;
                }
                rows[i].style.backgroundColor = "cyan";
            }
            getResults(data.students[i].ID);
        }

        // Skapa celler
        ID = document.createElement("td");
        ID.innerHTML = student.ID;
        ID.style.display = "none";

        firstname = document.createElement("td");
        firstname.innerHTML = student.fornamn;

        lastname = document.createElement("td");
        lastname.innerHTML = student.efternamn;

        username = document.createElement("td");
        username.innerHTML = student.anvandarnamn;

        edit = document.createElement("td");
        edit.innerHTML = "Redigera";
        edit.onclick = function() {
            appendEditForm();
        }

        erase = document.createElement("td");
        erase.innerHTML = "X";
        erase.onclick = function() {
            console.log(confirm("Är du säker på att du vill radera eleven: " + firstname.innerHTML + " " + lastname.innerHTML))
        }

        // Lätt till celler till raden och lägg till raden i tabellen
        row.appendChild(ID);
        row.appendChild(firstname);
        row.appendChild(lastname);
        row.appendChild(username);
        row.appendChild(edit);
        row.appendChild(erase);
        table.appendChild(row);

        rows.push(row);
        defColor = rows[i].style.backgroundColor;
    }
}

function getResults(ID) {
    // Anropa API för att hämta resultat ur databas
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getResults.php?ID=' + ID)
    .then(function(response) {
        if(response.status == 200) {
            return response.json();
        }
    })
    .then(function(data) {
        let table = document.getElementById("results");
        let message = document.getElementById("resMsg");

        // Rensa tabell
        while(table.childNodes[2]) {
            table.removeChild(table.childNodes[2]);
        }

        // Lägg till resultat i tabell om eleven har resultat annars visa meddelande
        if(data.noResults) {
            message.style.display = "unset";
        } else {
            message.style.display = "none";
            appendResults(data);
        }
    })
}

function appendResults(data) {
    let table = document.getElementById("results");
    let row;
    let multTable;
    let points;
    let date;
    let result;

    // Visa resultat
    document.getElementById("resMenu").style.display = "unset";

    // Fyll tabell med resultat
    for(let i = 0; i < data.results.length; i++) {
        result = data.results[i];

        multTable = document.createElement("td");
        multTable.innerHTML = result.tabell;

        points = document.createElement("td");
        points.innerHTML = result.poang + "/10";

        date = document.createElement("td");
        date.innerHTML = result.datum;

        row = document.createElement("tr");
        row.appendChild(multTable);
        row.appendChild(points);
        row.appendChild(date);
        table.appendChild(row);
    }
}

function appendEditForm() {
    // Dölj och visa 
    document.getElementById("resMenu").style.display = "none";
    document.getElementById("studMeny").style.display = "none";
    document.getElementById("editMenu").style.display = "unset";
}