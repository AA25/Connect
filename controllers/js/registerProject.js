//When the register form is clicked, an ajax request is made to register the product
$('#registerProjectForm').submit(function(e) {
    //console.log($document.cookie);
    e.preventDefault();
    //Pull the data from the form
    var data = {
        'projectName': $('#registerProjectForm input[name=projectName]').val(),
        'projectCategory': $('#registerProjectForm select[name=projectCategory]').val(),
        'projectBio': $('#registerProjectForm textarea[name=projectBio]').val(),
        'projectBudget': $('#registerProjectForm select[name=projectBudget]').val(),
        'projectDeadline': '',
        'projectCountry': $('#registerProjectForm select[name=projectCountry]').val(),
        'projectLanguage': $('#registerProjectForm select[name=projectLanguage]').val(),
        'projectCurrency': $('#registerProjectForm select[name=projectCurrency]').val(),
    };

    //Send an ajax request to the rest api endpoint to request a project
    $.ajax({
        url: "/api/project/",
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Success']) {
                //On success, hide form and display success section
                $('#registerProject').hide();
                $('#successfulProject').show();
            } else {
                //On error, display success alert
                errorDisplay(response['Error']);
            }
        }
    });
});


//Updates the number of characters left on the project info textarea
var projectMaxText = 500;
$('#projectBioCount').html(projectMaxText + ' remaining');
$('#projectBio').keyup(function() {
    var currentTextLen = $('#projectBio').val().length;
    var remainingText = projectMaxText - currentTextLen;
    $('#projectBioCount').html(remainingText + ' remaining');
});