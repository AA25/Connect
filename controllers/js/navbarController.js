//When the login button is clicked, an ajax request is made to check if the details is correct
$('#loginForm').submit(function(e) {
    e.preventDefault();
    //Get the inputted values from the login form
    var data = {
            'email': $('#loginForm input[name=email]').val(),
            'password': $('#loginForm input[name=password]').val(),
            'location': $('#loginForm select[name=location] option:selected').val()
        }
        //Make an ajax request to the login controller php file
    $.ajax({
        url: "/controllers/php/loginController.php",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            //console.log("error ajax post to checklogin");
        },
        success: function(response) {
            if (response['Error']) {
                //On fail error show error alert
                errorDisplay(response['Error']);
            } else if (response['Success']) {
                //On success reload the page
                location.reload();
            }
        }
    });
});

function errorDisplay(response) {
    //Show the container containing alerts
    $("#alertContainer").show();
    //Attach the response to the alert
    $(".alertError p").text(response);
    //Display the specific alert
    $(".alertError").show();
    //Remove alert after 2 seconds
    setTimeout(function() { $(".alertError").alert('close') }, 2000);
}

function logOut() {
    //Make an ajax request to the logout controller php file
    $.ajax({
        url: "/controllers/php/logoutController.php",
        data: {},
        type: 'get',
        method: 'GET',
        error: function formError(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            //console.log("error ajax post to checklogin");
        },
        success: function formSuccess(response) {
            //Cookie containing token has been deleted, now refresh page
            location.reload();
        }
    });
}

//Function to get a specific cookie
//For certain requests the login token must be attached with it so we get the the cookie that contains the token
//and attach it to the ajax requests
//https://www.w3schools.com/js/js_cookies.asp
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});