window.onload = function() {
    // Hämtar användartyp ur URL-parametrar
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    let userType = urlParams.get('userType');

    // Sätter onclick-attribut på login-knapp
    document.getElementById("loginButton").onclick = function() {
        // Hämta formulärsdata
        let username = document.getElementById("username").value;
        let password = document.getElementById("password").value;

        // Kolla så att alla formulärsfält är ifyllda
        if(username === "" || password === "") {
            alert("Användarnamn eller lösenord saknas");
            return;
        }

        // Anropa API för att verifiera lösenord
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/verifyLogin.php?username=' + username + '&password=' + password + '&userType=' + userType)
            .then(function(response) {
                if(response.status == 200) {
                    return response.json();
                }
            })
            .then(function(data) {
                if(data.loginStatus) {
                    if(userType === 0) {
                        window.location = "../exercise.html";
                    }
                } else {
                    alert("Felaktigt användarnamn eller lösenord.");
                }
            })
    }
}