console.log(document.cookie['JWT']);
//When the register form is clicked, an ajax request is made to register the product
$('#registerProjectForm').submit(function (e){
    //console.log($document.cookie);
    e.preventDefault();
    //Pull the data from the form
    var data = {
        'projectCategory'   : $('#registerProjectForm select[name=projectCategory]').val(),
        'projectBio'        : $('#registerProjectForm textarea[name=projectBio]').val(),
        'projectBudget'     : $('#registerProjectForm select[name=projectBudget]').val(),
        'projectDeadline'   : $('#registerProjectForm input[name=projectDeadline]').val(),
        'projectCountry'    : $('#registerProjectForm select[name=projectCountry]').val(),
        'projectLanguage'   : $('#registerProjectForm select[name=projectLanguage]').val(),
        'projectCurrency'   : $('#registerProjectForm select[name=projectCurrency]').val(),
    };
    $.ajax({
        url: "../api/endpoints/registerProject.php",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ","+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            console.log(response);
        }
    });
});

