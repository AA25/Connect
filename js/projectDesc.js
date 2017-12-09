var maxText = 500;
$('#msgCount').html(maxText + ' remaining');

$('#devMsg').keyup(function() {
    var currentTextLen = $('#devMsg').val().length;
    var remainingText = maxText - currentTextLen;
    $('#msgCount').html(remainingText + ' remaining');
});

function retrieveProjectDetails() {
    //var url = '../api/endpoints/retrieveProject.php' + location.search;
    var url = '../api' + window.location.pathname;
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
            } else {
                console.log(response);
                sideDisplay(response['Success']['userType']);
            }
        }
    });
};

function sideDisplay(userType) {
    if (userType === 'developer') {
        $('#projectReq').show();
    } else if (userType === 'business') {
        $('#projectReq').show();
    } else {
        //Show the guest view or business view
    }
}

//When the project request form is clicked, an ajax request is made to send a request towards the project
$('#projectRequestForm').submit(function(e) {
    e.preventDefault();
    //var urlParam = location.search;
    var urlParam = window.location.pathname;
    //Pull out the project id from the url parameter
    var projectId = parseInt(urlParam.slice(-1));
    //var projectId = parseInt(urlParam.slice((urlParam.indexOf('=')) + 1));
    //Pull the data from the form
    var data = {
        'projectId': projectId,
        'devMsg': $('#projectRequestForm textarea[name=devMsg]').val()
    };
    $.ajax({
        //url: "../api/endpoints/sendProjectReq.php",
        url: "../api/project/request/" + projectId,
        data: JSON.stringify(data),
        type: 'post',
        method: 'POST',
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(result) {
            console.log(result);
        }
    });
});

window.onload = retrieveProjectDetails();