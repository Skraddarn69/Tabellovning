window.onload = function() {
    // Hämta användartyp ur URL-parametrar
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    let userType = urlParams.get('userType');

    // Lägg till användartyp i html-formulär
    document.getElementById("userType").value=userType;
}