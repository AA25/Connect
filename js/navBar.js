//When the register form is clicked, an ajax request is made to register the user
// $('#registerForm').submit(function (e){
//     e.preventDefault();
//     //Pull the data from the form
//     var formData = {
//         'firstName' : $('#registerForm input[name=firstName]').val(),
//         'lastName'  : $('#registerForm input[name=lastName]').val(),
//         'dob'       : $('#registerForm input[name=dob]').val(),
//         'userName'  : $('#registerForm input[name=userName]').val(),
//         'password'  : $('#registerForm input[name=password]').val(),
//         'email'     : $('#registerForm input[name=email]').val(),
//         'phone'     : $('#registerForm input[name=phone]').val()
//     };

//     $.ajax({
//         url: "./logic/checklogin.php",
//         data: formData,
//         type: 'post',
//         method: 'POST',
//         error: function(XMLHttpRequest, textStatus, errorThrown) {
//             //Error in setting status
//         },
//         success: function(result) {
//             console.log(result);
//             if(result == 'registered'){
//                 location.reload();
//             }
//         }
//     });
// });

//When the login button is clicked, an ajax request is made to check if the details is correct
$('#loginDevForm').submit(function (e){
    e.preventDefault();
    var data = formData();
    $.ajax({
        url: "./logic/developerLogin.php",
        data: data,
        type: 'post',
        method: 'POST',
        error: function formError(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
            console.log("error ajax post to checklogin");
            console.log(textStatus);
        },
        success: function formSuccess(result) {
            result = JSON.parse(result)
            console.dir(result);
        }
    });
});

function formData(){
    var formData = {
        'loginLocation' : 'developers',
        'email'  : $('#loginDevForm input[name=email]').val(),
        'password'  : $('#loginDevForm input[name=password]').val()
    };
    return formData;
}

