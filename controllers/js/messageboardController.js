//Updates the number of characters left on the project info textarea
var messageMaxText = 500;
$('#messageCount').html(messageMaxText + ' remaining');
$('#messageInputted').keyup(function() {
    var currentTextLen = $('#messageInputted').val().length;
    var remainingText = messageMaxText - currentTextLen;
    $('#messageCount').html(remainingText + ' remaining');
});

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
                renderMessagesHTML(response['Success']['Messages']);
            }
        }
    });
};

function renderMessagesHTML(retrieveMsgs) {
    console.log(retrieveMsgs);
}

window.onload = retrieveProjectMessages();