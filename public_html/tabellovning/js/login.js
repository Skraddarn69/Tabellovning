queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);

    let userType = urlParams.get('userType');
    document.getElementById("userType").value=userType;