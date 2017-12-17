//When the login button is clicked, an ajax request is made to check if the details is correct
$('#loginForm').submit(function(e) {
    e.preventDefault();
    var data = {
        'email': $('#loginForm input[name=email]').val(),
        'password': $('#loginForm input[name=password]').val(),
        'location': $('#loginForm select[name=location] option:selected').val()
    }
    $.ajax({
        url: "/controllers/php/loginController.php",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        error: function formError(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            console.log("error ajax post to checklogin");
        },
        success: function formSuccess(response) {
            location.reload();
        }
    });
});

function successDisplay(response) {
    console.dir(response);
}

function errorDisplay(response) {
    console.dir(response);
}

function logOut() {
    location.reload();
    $.ajax({
        url: "/controllers/php/logoutController.php",
        data: {},
        type: 'get',
        method: 'GET',
        error: function formError(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            console.log("error ajax post to checklogin");
        },
        success: function formSuccess(response) {
            //Cookie containing token has been deleted, now refresh page
            location.reload();
        }
    });
}

//Function to get a specific cookie 
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


// var getUrl = window.location;
// var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
// console.log(baseUrl);

// function formData(formLocation, searchLocation) {
//     var formData = {
//         'location': searchLocation,
//         'email': $('#' + formLocation + ' input[name=email]').val(),
//         'password': $('#' + formLocation + ' input[name=password]').val()
//     };
//     return formData;
// }

//When the login button is clicked, an ajax request is made to check if the details is correct
// $('#loginDevForm').submit(function(e) {
//     e.preventDefault();
//     var data = formData('loginDevForm', 'developers');
//     $.ajax({
//         url: "./logic/loginUser.php",
//         data: JSON.stringify(data),
//         type: 'post',
//         method: 'POST',
//         error: function formError(XMLHttpRequest, textStatus, errorThrown) {
//             //Error in setting status
//             console.log("error ajax post to checklogin");
//             //console.log(textStatus);
//         },
//         success: function formSuccess(response) {
//             //response = JSON.parse(response);
//             console.log(response);
//             //response['Success'] ? decodeJWT(response['Success']) : console.log(response);

//             //response = JSON.parse(atob(response));
//             //response['Success'] ? successProcedure(response) : errorProcedure(response);
//         }
//     });
// });

// $('#loginBusForm').submit(function(e) {
//     e.preventDefault();
//     var data = formData('loginBusForm', 'businesses');
//     $.ajax({
//         url: "./logic/loginUser.php",
//         data: JSON.stringify(data),
//         type: 'post',
//         method: 'POST',
//         error: function formError(XMLHttpRequest, textStatus, errorThrown) {
//             //Error in setting status
//             console.log("error ajax post to checklogin");
//             console.log(textStatus);
//         },
//         success: function formSuccess(response) {
//             console.log(response);
//             //response = JSON.parse(atob(response));
//             //response['Success'] ? successProcedure(response) : errorProcedure(response);
//         }
//     });
// });

//This isnt needed anymore?
// function testRequest(){
//     $.ajax({
//         url: "./views/testView.php",
//         beforeSend: function(request){
//             request.setRequestHeader('Authorization', 'Bearer ' + localStorage.JWT);
//         },
//         type: 'GET',
//         success: function(data) {
//             // Decode and show the returned data nicely.
//         },
//         error: function() {
//             alert('error');
//         }
//     });
// }

//This isnt needed anymore?
// function decodeJWT(jwtToken){
//     //jwtToken = "eyJhbGciOiAiSFMyNTYiLCJ0eXAiOiAiSldUIn0=.eyJTdWNjZXNzIjoiU3VjY2Vzc2Z1bCBsb2dpbiIsImZpcnN0TmFtZSI6ImFkZWUiLCJsYXN0TmFtZSI6ImtpbmciLCJkb2IiOiIxOTk0LTA2LTI1IiwibGFuZ3VhZ2VzIjoiZW5nbGlzaCIsImVtYWlsIjoidGVzdEB0ZXN0LmNvbSIsImRldkJpbyI6ImJpbyIsInBob25lIjoiMSJ9.ELfHZXsMOcoH4XJzaF658t8KIlINqb1mCsIA0zvTVK8=";
//     //Break the JWT into its three parts, header, payload and signature
//     tokenParts = jwtToken.split(".");
//     jwtHeaderEnc = tokenParts[0];
//     jwtPayloadEnc = tokenParts[1];
//     jwtSigEnc = tokenParts[2];

//     jwtPayloadDec = JSON.parse(atob(jwtPayloadEnc));
//     //console.log(jwtPayloadDec);
//     setCookieUser(jwtToken,jwtPayloadDec);
// }
//decodeJWT("eyJhbGciOiAiSFMyNTYiLCJ0eXAiOiAiSldUIn0=.eyJTdWNjZXNzIjoiU3VjY2Vzc2Z1bCBsb2dpbiIsImZpcnN0TmFtZSI6ImFkZWUiLCJsYXN0TmFtZSI6ImtpbmciLCJkb2IiOiIxOTk0LTA2LTI1IiwibGFuZ3VhZ2VzIjoiZW5nbGlzaCIsImVtYWlsIjoidGVzdEB0ZXN0LmNvbSIsImRldkJpbyI6ImJpbyIsInBob25lIjoiMSJ9.ELfHZXsMOcoH4XJzaF658t8KIlINqb1mCsIA0zvTVK8=");

//This isnt needed anymore?
// function setCookieUser(itemA, itemB){
//     // if (typeof(Storage) !== "undefined") {
//     //     // Code for localStorage/sessionStorage.
//     //     localStorage.setItem("JWT", itemA);
//     //     for(var info in itemB){
//     //         //console.log(itemB[info]);
//     //         localStorage.setItem(info,itemB[info]);
//     //     } 
//     // } else {
//     //     // Sorry! No Web Storage support..
//     // }
//     document.cookie = "JWT="+itemA+";";
//     for(var info in itemB){
//         document.cookie = info + "=" + itemB[info] + ";";
//     } 
// }