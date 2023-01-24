window.onload = function() {
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    if(!urlParams.get("ID")) {
        window.location.assign("login.html?userType=2");
    } else {
        getTeachers();
        // Skapa funktionalitet för "ny lärare"-knapp
        document.getElementById("saveTeach").onclick = function() {
            appendSaveForm();
        }
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
        let table = document.getElementById("teachList");
        let message = document.getElementById("teachMsg");

        // Rensa tabell
        while(table.childNodes[2]) {
            table.removeChild(table.childNodes[2]);
        }

        // Visa elevtabell
        document.getElementById("teachMenu").style.display = "unset";

        // Dölj redigeringsformulär
        document.getElementById("editMenu").style.display = "none";
        
        // Lägg till lärare i tabell om elever finns annars visa meddelande
        if(data.noTeachers) {
            message.style.display = "unset";
        } else {
            message.style.display = "none";
            appendTeachers(data);
        }
    })
}

function appendTeachers(data) {
    let teacher;
    let row;
    let rows = [];
    let defColor;
    let ID;
    let firstname;
    let lastname;
    let username;
    let edit;
    let erase;
    let table = document.getElementById("teachList");
    let back = document.getElementById("back");

    // Skapa funktionalitet för tillbakaknapp
    back.style.display = "unset";
    back.onclick = function() {
        window.location.assign("admin.html");
    }

    // Lägg till tabellrader för alla elever
    for(let i = 0; i < data.teachers.length; i++) {
        teacher = data.teachers[i];
        row = document.createElement("tr");

        // Skapa celler
        ID = document.createElement("td");
        ID.innerHTML = teacher.ID;
        ID.style.display = "none";

        firstname = document.createElement("td");
        firstname.innerHTML = teacher.fornamn;
        firstname.onclick = function() {
            selectRow(rows, defColor, i);
        }

        lastname = document.createElement("td");
        lastname.innerHTML = teacher.efternamn;
        lastname.onclick = function() {
            selectRow(rows, defColor, i);
        }

        username = document.createElement("td");
        username.innerHTML = teacher.anvandarnamn;
        username.onclick = function() {
            selectRow(rows, defColor, i);
        }

        edit = document.createElement("td");
        edit.class = "editCell";
        edit.innerHTML = "Redigera";
        edit.onclick = function() {
            appendEditForm(data.teachers[i].ID, data.teachers[i].fornamn, data.teachers[i].efternamn, data.teachers[i].anvandarnamn);
        }

        erase = document.createElement("td");
        erase.class = "eraseCell";
        erase.innerHTML = "X";
        erase.onclick = function() {
            if(confirm("Är du säker på att du vill radera läraren: " + firstname.innerHTML + " " + lastname.innerHTML)) {
                deleteTeacher(data.teachers[i].ID);
            }
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

function selectRow(rows, defColor, i) {
    if(rows[i].style.backgroundColor == defColor) {
        for(let o = 0; o < rows.length; o++) {
            rows[o].style.backgroundColor = defColor;
        }
        rows[i].style.backgroundColor = "cyan";
    }
}

function appendSaveForm() {
    // Dölj lärarfält och visa redigeringsmeny
    document.getElementById("teachMenu").style.display = "none";
    document.getElementById("editMenu").style.display = "unset";
    document.getElementById("back").style.display = "none";

    // Hämta formulärsfält
    let idField = document.getElementById("id");
    let firstnameField = document.getElementById("firstname");
    let lastnameField = document.getElementById("lastname");
    let usernameField = document.getElementById("username");
    let passwordField = document.getElementById("password");

    // Töm formulärsfält
    idField.value = "";
    firstnameField.value = "";
    lastnameField.value = "";
    usernameField.value = "";
    passwordField.value = "";

    // Generera användarnamn utifrån förnamn och efternamn
    firstnameField.onchange = function() {
        usernameField.value = firstnameField.value + "." + lastnameField.value;
    }
    lastnameField.onchange = function() {
        usernameField.value = firstnameField.value + "." + lastnameField.value;
    }

    // Spara ändringar eller ge felmeddelande när användare klickar på spara
    document.getElementById("editButton").onclick = function() {
        if(firstnameField.value.trim() === "" || lastnameField.value.trim() === "" || usernameField.value.trim() === "" || passwordField.value.trim() === "") {
            alert("Alla fält måste vara ifyllda.");
        } else {
            saveTeacher(firstnameField.value, lastnameField.value, usernameField.value, passwordField.value);
        }
    }

    // Gå tillbaka till lärarmeny när användare klickar på avbryt
    document.getElementById("abortButton").onclick = function() {
        getTeachers();
    }
}

function saveTeacher(firstname, lastname, username, password) {
    // Anropa API för att spara lärare i databasen
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/saveTeacher.php?firstname=' + firstname + '&lastname=' + lastname + '&username=' + username + '&password=' + password)
    .then(function(response) {
        if(response.status == 200) {
            return response.json();
        }
    })
    .then(function() {
        getTeachers();
    })
}

function appendEditForm(id, firstname, lastname, username) {
    // Dölj lärarfält och tillbakaknapp, visa redigeringsmeny
    document.getElementById("teachMenu").style.display = "none";
    document.getElementById("editMenu").style.display = "unset";
    document.getElementById("back").style.display = "none";

    // Hämta formulärsfält
    let idField = document.getElementById("id");
    let firstnameField = document.getElementById("firstname");
    let lastnameField = document.getElementById("lastname");
    let usernameField = document.getElementById("username");
    let passwordField = document.getElementById("password");

    // Töm formulärsfält
    idField.value = "";
    firstnameField.value = "";
    lastnameField.value = "";
    usernameField.value = "";
    passwordField.value = "";

    // Fyll formulär
    idField.value = id;
    firstnameField.value = firstname;
    lastnameField.value = lastname;
    usernameField.value = username;

    // Generera användarnamn utifrån förnamn och efternamn
    firstnameField.onchange = function() {
        usernameField.value = firstnameField.value + "." + lastnameField.value;
    }
    lastnameField.onchange = function() {
        usernameField.value = firstnameField.value + "." + lastnameField.value;
    }

    // Spara ändringar eller ge felmeddelande när användare klickar på spara
    document.getElementById("editButton").onclick = function() {
        if(firstnameField.value.trim() === "" || lastnameField.value.trim() === "" || usernameField.value.trim() === "" || passwordField.value.trim() === "") {
            alert("Alla fält måste vara ifyllda.");
        } else {
            editTeacher(idField.value, firstnameField.value, lastnameField.value, usernameField.value, passwordField.value);
        }
    }

    // Gå tillbaka till elevmeny när användare klickar på avbryt
    document.getElementById("abortButton").onclick = function() {
        getTeachers();
    }
}

function editTeacher(id, firstname, lastname, username, password) {
    // Anropa API för att uppdatera lärare i databasen
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/editTeacher.php?ID=' + id + '&firstname=' + firstname + '&lastname=' + lastname + '&username=' + username + '&password=' + password)
    .then(function(response) {
        if(response.status == 200) {
            return response.json();
        }
    })
    .then(function() {
        getTeachers();
    })
}

function deleteTeacher(id) {
    // Anropa API för att radera lärare ur databasen
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/deleteTeacher.php?id=' + id)
    .then(function(response) {
        if(response.status == 200) {
            return response.json();
        }
    })
    .then(function(data) {
        if(data.result) {
            getTeachers();
        } else {
            alert("Kan inte radera. Läraren har klasser registrerade.");
        }
    })
}