window.onload = function() {
    // Hämtar användartyp ur URL-parametrar
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    let userType = urlParams.get('userType');

    // Lägger till användartyp i html-formulär
    document.getElementById("userType").value = userType;

    // Hämta formulärsdata
    let username = document.getElementsByName("username")[0].value;
    let password = document.getElementsByName("password")[0].value;

    // Sätter onclick-attribut på login-knapp
    document.getElementById("login").onsubmit = function() {
        const options = {
            method: "POST"
        };
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/verifyLogin.php?username=' + username + "&password=" + password + "&userType=" + userType, options)
            .then(function(response) {
                if(response.status == 200) {
                    return response.json();
                }
            })
            .then(function(data) {
                console.log(data);
                if(data.loginStatus) {
                    console.log("Hej");
                } else {
                    alert("Felaktigt användarnamn eller lösenord.");
                }
            })
    }
}