//When the register form is clicked, an ajax request is made to register the user
$('#registerDevForm').submit(function (e){
    e.preventDefault();
    //Pull the data from the form
    var data = {
        'firstName' : $('#registerDevForm input[name=firstName]').val(),
        'lastName'  : $('#registerDevForm input[name=lastName]').val(),
        'dob'       : $('#registerDevForm input[name=dob]').val(),
        'languages' : $('#registerDevForm input[name=languages]').val(),
        'email'     : $('#registerDevForm input[name=email]').val(),
        'password'  : $('#registerDevForm input[name=password]').val(),
        'devBio'    : $('#registerDevForm textarea[name=devBio]').val(),
        'phone'     : $('#registerDevForm input[name=phone]').val()
    };
    $.ajax({
        url: "./api/endpoints/registerDeveloper.php",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(result) {
            console.log(result);
            // if(result == 'registered'){
            //     location.reload();
            // }
        }
    });
});

$('#registerBusForm').submit(function (e){
    e.preventDefault();
    //Pull the data from the form
    var data = {
        'busName' : $('#registerBusForm input[name=busName]').val(),
        'busIndustry'  : $('#registerBusForm input[name=busIndustry]').val(),
        'busBio'       : $('#registerBusForm textarea[name=busBio]').val(),
        'firstName' : $('#registerBusForm input[name=firstName]').val(),
        'lastName'     : $('#registerBusForm input[name=lastName]').val(),
        'password'  : $('#registerBusForm input[name=password]').val(),
        'email'    : $('#registerBusForm input[name=email]').val(),
        'phone'     : $('#registerBusForm input[name=phone]').val()
        // 'busName'       : 'busName',
        // 'busIndustry'   : 'busIndustry',
        // 'busBio'        : 'busBio',
        // 'firstName'     : 'firstName',
        // 'lastName'      : 'lastName',
        // 'password'      : 'p',
        // 'email'         : 'email@email.com',
        // 'phone'         : '1'
    };
    $.ajax({
        url: "./api/endpoints/registerBusiness.php",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(result) {
            console.log(result);
            // if(result == 'registered'){
            //     location.reload();
            // }
        }
    });
});

//When the login button is clicked, an ajax request is made to check if the details is correct
$('#loginDevForm').submit(function (e){
    e.preventDefault();
    var data = formData('loginDevForm','developers');
    $.ajax({
        url: "./logic/loginUser.php",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        error: function formError(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            console.log("error ajax post to checklogin");
            //console.log(textStatus);
        },
        success: function formSuccess(response) {
            //response = JSON.parse(response);
            console.log(response);
            //response['Success'] ? decodeJWT(response['Success']) : console.log(response);

            //response = JSON.parse(atob(response));
            //response['Success'] ? successProcedure(response) : errorProcedure(response);
        }
    });
});

$('#loginBusForm').submit(function (e){
    e.preventDefault();
    var data = formData('loginBusForm','businesses');
    $.ajax({
        url: "./logic/loginUser.php",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        error: function formError(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            console.log("error ajax post to checklogin");
            console.log(textStatus);
        },
        success: function formSuccess(response) {
            console.log(response);
            //response = JSON.parse(atob(response));
            //response['Success'] ? successProcedure(response) : errorProcedure(response);
        }
    });
});

function formData(formLocation,searchLocation){
    var formData = {
        'location' : searchLocation,
        'email'  : $('#'+formLocation+' input[name=email]').val(),
        'password'  : $('#'+formLocation+' input[name=password]').val()
    };
    return formData;
}

function successDisplay(response){
    console.dir(response);
}

function errorDisplay(response){
    console.dir(response);
}

function logOut(){
    $.ajax({
        url: "./logic/logoutUser.php",
        data: {},
        type: 'get',
        method: 'GET',
        error: function formError(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            console.log("error ajax post to checklogin");
            console.log(textStatus);
        },
        success: function formSuccess(response) {
            console.log(response);
        }
    });
}

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


