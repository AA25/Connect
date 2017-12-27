//Updates the number of characters left on the project info textarea
var messageMaxText = 500;
$('#messageCount').html(messageMaxText + ' remaining');
$('#messageInputted').keyup(function() {
    var currentTextLen = $('#messageInputted').val().length;
    var remainingText = messageMaxText - currentTextLen;
    $('#messageCount').html(remainingText + ' remaining');
});

function initialise() {
    retrieveProjectDevelopers();
    retrieveProjectMessages();
}

window.onload = initialise();

//Make a request to the RESTful api to retrieve developers working on the current project
function retrieveProjectDevelopers() {
    var endpoint = (window.location.pathname).replace('/dashboard/forum/', '/forum/developers/');
    var url = '/api' + endpoint;

    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                console.log(response['Error']);
            } else if (response['Success']) {
                renderDeveloperListHTML(response['Success']['Developers']);
            }
        }
    });
}

//Make a request to the RESTful api to retrieve projectMessages
function retrieveProjectMessages() {
    var endpoint = (window.location.pathname).replace('/dashboard', '');
    var url = '/api' + endpoint;

    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                console.log(response['Error']);
            } else if (response['Success']) {
                $('#projectName').text(response['Success']['projectName']);
                $('#projectStatus').text(response['Success']['projectStatus']);
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

    var endpoint = (window.location.pathname).replace('/dashboard', '');
    var url = '/api' + endpoint;

    $.ajax({
        url: url,
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
            if (response['Error']) {
                console.log(response['Error']);
            } else if (response['Success']) {
                console.log(response['Success']);
                $("#messagePost").trigger("reset");
                retrieveProjectMessages();
            }
        }
    });
});

function toggleReadyStatus() {
    $.ajax({
        url: '/api/developer/toggleProceedStatus',
        data: {},
        type: 'put',
        method: 'PUT',
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                console.log(response['Error']);
            } else if (response['Success']) {
                retrieveProjectDevelopers();
            }
        }
    });
}

function renderMessagesHTML(retrieveMsgs) {
    $("#messages").empty();

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

function proceedProject() {
    var projectId = (window.location.pathname).replace('/dashboard/forum/', '');
    var url = '/api/project/proceedStage/' + projectId;

    $.ajax({
        url: url,
        data: {},
        type: 'put',
        method: 'PUT',
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                console.log(response['Error']);
            } else if (response['Success']) {
                initialise();
            }
        }
    });
}