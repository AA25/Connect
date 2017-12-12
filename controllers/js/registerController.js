$('#registerBusForm').submit(function(e) {
    e.preventDefault();
    //Pull the data from the form
    var data = {
        'busName': $('#registerBusForm input[name=busName]').val(),
        'busIndustry': $('#registerBusForm input[name=busIndustry]').val(),
        'busBio': $('#registerBusForm textarea[name=busBio]').val(),
        'username': $('#registerBusForm input[name=username]').val(),
        'firstName': $('#registerBusForm input[name=firstName]').val(),
        'lastName': $('#registerBusForm input[name=lastName]').val(),
        'password': $('#registerBusForm input[name=password]').val(),
        'email': $('#registerBusForm input[name=email]').val(),
        'phone': $('#registerBusForm input[name=phone]').val()
    };
    $.ajax({
        url: "/api/business/register/",
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

//When the register form is clicked, an ajax request is made to register the user
$('#registerDevForm').submit(function(e) {
    e.preventDefault();
    //Pull the data from the form
    var data = {
        'username': $('#registerDevForm input[name=username]').val(),
        'firstName': $('#registerDevForm input[name=firstName]').val(),
        'lastName': $('#registerDevForm input[name=lastName]').val(),
        'dob': $('#registerDevForm input[name=dob]').val(),
        'languages': $('#registerDevForm input[name=languages]').val(),
        'email': $('#registerDevForm input[name=email]').val(),
        'password': $('#registerDevForm input[name=password]').val(),
        'devBio': $('#registerDevForm textarea[name=devBio]').val(),
        'phone': $('#registerDevForm input[name=phone]').val()
    };
    $.ajax({
        //url: "./api/endpoints/registerDeveloper.php",
        url: "/api/developer/register/",
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