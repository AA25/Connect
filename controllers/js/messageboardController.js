//Updates the number of characters left on the project info textarea
var messageMaxText = 500;
$('#messageCount').html(messageMaxText + ' remaining');
$('#messageInputted').keyup(function() {
    var currentTextLen = $('#messageInputted').val().length;
    var remainingText = messageMaxText - currentTextLen;
    $('#messageCount').html(remainingText + ' remaining');
});


function initialise() {
    //Retrieve the developers for the side bar
    retrieveProjectDevelopers();
    //Retrieve project messages for the main display
    retrieveProjectMessages();
}

window.onload = initialise();

//Make a request to the RESTful api to retrieve developers working on the current project
function retrieveProjectDevelopers() {
    //Creating the rest api endpoint which will be where the ajax request will be made 
    //to retrieve the developers on the current project
    var endpoint = (window.location.pathname).replace('/dashboard/forum/', '/forum/developers/');
    var url = '/api' + endpoint;

    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        //Attach cookie which contains the user token used for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //On error display error alert
                errorDisplay(response['Error']);
            } else if (response['Success']['Developers'].length > 0) {
                //If response is not empty then add the developers to the sidebar html
                renderDeveloperListHTML(response['Success']['Developers']);
            }
        }
    });
}

//Make a request to the rest api endpoint to retrieve projectMessages
function retrieveProjectMessages() {
    //Creating the rest api endpoint which will be where the ajax request will be made 
    //to retrieve the messages for this project so far
    var endpoint = (window.location.pathname).replace('/dashboard', '');
    var url = '/api' + endpoint;

    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        //Attach cookie which contains user token used for authenication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //If error display errr alert
                errorDisplay(response['Error']);
            } else if (response['Success']) {
                //Add the project name and project status to the page
                $('#projectName').text(response['Success']['projectName']);
                $('#projectStatus').text(response['Success']['projectStatus']);
                //create the html that displays each message to the main display
                renderMessagesHTML(response['Success']['Messages']);
            }
        }
    });
};

//When the message form button is clicked, an ajax request is made to send the message
$('#messagePost').submit(function(e) {
    //console.log($document.cookie);
    e.preventDefault();
    //Pull the data from the form
    var data = {
        'sentMessage': $('#messagePost textarea[name=messageInputted]').val(),
    };

    //Creating the rest api endpoint which will be where the ajax request will be made 
    //to post a message to the project
    var endpoint = (window.location.pathname).replace('/dashboard', '');
    var url = '/api' + endpoint;

    $.ajax({
        url: url,
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        //Attach cookie which contains user token used for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //If error display error alert
                errorDisplay(response['Error'])
            } else if (response['Success']) {
                //If successful then wipe the input field and reload the messages html
                $("#messagePost").trigger("reset");
                retrieveProjectMessages();
            }
        }
    });
});

function toggleReadyStatus() {
    //Creating the rest api endpoint which will be where the ajax request will be made 
    //to say if a developer is ready or not to proceed stages
    $.ajax({
        url: '/api/developer/toggleProceedStatus',
        data: {},
        type: 'put',
        method: 'PUT',
        //Attach cookie which contains user token used for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //if error then display error display
                errorDisplay(response['Error']);
            } else if (response['Success']) {
                //If successful then reload sidebar
                retrieveProjectDevelopers();
            }
        }
    });
}

function proceedProject() {
    //Creating the rest api endpoint which will be where the ajax request will be made 
    //to proceed the project to the next stage
    var projectId = (window.location.pathname).replace('/dashboard/forum/', '');
    var url = '/api/project/proceedStage/' + projectId;

    $.ajax({
        url: url,
        data: {},
        type: 'put',
        method: 'PUT',
        //Attach cookie which contains user token used for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //If error display error alert
                errorDisplay(response['Error']);
            } else if (response['Success']) {
                //If successful then we want to reload the sidebar and main display
                initialise();
            }
        }
    });
}

function renderMessagesHTML(retrieveMsgs) {
    $("#messages").empty();

    //Create a p that represents each message
    for (var i = 0; i < retrieveMsgs.length; i++) {

        var messageHTML =
            '<p class="speech-bubble padl-20 padr-20 padt-10 padb-10">' +
            retrieveMsgs[i]['sentMessage'] + '<br>' +
            '<span class="txt-right cl-white fs-11">' +
            retrieveMsgs[i]['fromWho'] + ' ' + retrieveMsgs[i]['messageTime'] +
            '</span></p>';

        $("#messages").append(messageHTML);
    }
}

function renderDeveloperListHTML(developers) {
    $("#developerList").empty();
    //Create a li for each developer and attach to the ul in sidebar
    for (var i = 0; i < developers.length; i++) {
        if (developers[i]['proceedStatus'] === 1) {
            var proceedStatus = '<i class="fa fa-check cl-success pull-right" aria-hidden="true"></i>';
        } else {
            var proceedStatus = '<i class="fa fa-check pull-right" aria-hidden="true"></i>';
        }
        var developer =
            '<li class="padl-20 padr-10 padt-10 padb-10 cl-black-connect" style="border-bottom: 1px solid black; list-style-type: none;">' +
            '<a href="http://localhost:8081/developer/info/' + developers[i]['username'] + '" target="_blank" class="cl-black">' + developers[i]['name'] + '</a>' + proceedStatus +
            '</li>';

        $("#developerList").append(developer);
    }
}