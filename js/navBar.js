//When the register form is clicked, an ajax request is made to register the user
$('#registerDevForm').submit(function (e){
    e.preventDefault();
    //Pull the data from the form
    var data = {
        // 'firstName' : $('#registerForm input[name=firstName]').val(),
        // 'lastName'  : $('#registerForm input[name=lastName]').val(),
        // 'dob'       : $('#registerForm input[name=dob]').val(),
        // 'languages' : $('#registerForm input[name=languages]').val(),
        // 'email'     : $('#registerForm input[name=email]').val(),
        // 'password'  : $('#registerForm input[name=password]').val(),
        // 'devBio'    : $('#registerForm input[name=devBio]').val(),
        // 'phone'     : $('#registerForm input[name=phone]').val()
        'firstName' : 'adee',
        'lastName'  : 'king',
        'dob'       : '1994-06-25',
        'languages' : 'english',
        'email'     : 'test2@test.com',
        'password'  : 'pass',
        'devBio'    : 'bio',
        'phone'     : '1'
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

//When the login button is clicked, an ajax request is made to check if the details is correct
$('#loginDevForm').submit(function (e){
    e.preventDefault();
    var data = formData('loginDevForm','developers');
    $.ajax({
        url: "./api/endpoints/loginUser.php",
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

$('#loginBusForm').submit(function (e){
    e.preventDefault();
    var data = formData('loginBusForm','businesses');
    $.ajax({
        url: "./api/endpoints/loginUser.php",
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

function successProcedure(response){
    console.dir(response);
}

function errorProcedure(response){
    console.dir(response);
}