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

//When the register form is clicked, an ajax request is made to register the product
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
            console.log(response);
        }
    });
});

function renderMessagesHTML(retrieveMsgs) {
    console.log('in');
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

window.onload = retrieveProjectMessages();