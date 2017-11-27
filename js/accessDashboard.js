var userType = '';

function accessDashboardPermission() {
    $.ajax({
        url: '../../logic/accessDashboard.php',
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
                window.location.href = "/";
            } else {
                console.log(response);
                userType = response['Success'];
                //render sidebar content depending on userType
            }
        }
    });
};

//window.onload = accessDashboardPermission();

function respondToRequest(buttonClicked) {
    var data = {
        'busResponse': buttonClicked.getAttribute("data-request-response"),
        'devId': buttonClicked.getAttribute("data-dev"),
        'projectId': buttonClicked.getAttribute("data-project")
    };
    $.ajax({
        url: '../api/endpoints/updateProjectRequest.php',
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
                console.log(response);
            } else {
                console.log(response);
                //then remove it from the table html
            }
        }
    });
}

function renderProjectRequests(type) {
    $(function() {
        $("#renderOption").load("../dashboard/options/" + type + "/projectRequests.html");
        projectRequests();
    });
}

function projectRequests() {
    $.ajax({
        url: '../api/endpoints/retrieveProjectRequests.php',
        data: { 'userType': userType },
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
                console.log(response);
            } else {
                console.log(response['Success']);
                addProjectRequestsHTML(response['Success']);
            }
        }
    });
}

function addProjectRequestsHTML(pendingRequests) {
    //Remove whats currently there and create 
    $("#requestTableBody").empty();
    for (var i = 0; i < pendingRequests.length; i++) {
        var basicRowDetail =
            '<tr data-toggle="collapse" data-target="#requestDetail' + (i + 1) + '">' +
            '<td>click</td>' +
            '<td>' + pendingRequests[i]['projectId'] + '</td>' +
            '<td>' + pendingRequests[i]['projectCategory'] + '</td>' +
            '<td>' + pendingRequests[i]['devName'] + '</td>' +
            '<td>' + pendingRequests[i]['status'] + '</td>' +
            '</tr>';

        var indepthRowDetail =
            '<tr>' +
            '<td colspan="12">' +
            '<div id="requestDetail' + (i + 1) + '" class="collapse">' +
            '<h3>' + pendingRequests[i]['devName'] + ' sent a request to join this project</h3>' +
            '<h5>Their message contained the following...</h5>' +
            '<p>"' + pendingRequests[i]['devMsg'] + '"</p>' +
            '<a href="">Click here to learn more about them by viewing their profile </a>' +
            '<br><br>' +
            '<button data-request-response="Accepted" data-dev="' + pendingRequests[i]['devId'] + '" data-dev="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Accept</button>' +
            '<button data-request-response="Rejected" data-dev="' + pendingRequests[i]['devId'] + '" data-dev="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Reject</button>' +
            '</div>' +
            '</td>' +
            '</tr>';

        $("#requestTableBody").append(basicRowDetail);
        $("#requestTableBody").append(indepthRowDetail);
    }
}