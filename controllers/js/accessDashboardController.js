var userType = '';

//Depending on the option clickd on the sidebar the correct html file and initial start function will be called
function renderSidebarOption(userType, file) {
    $(function() {
        $("#renderOption").load("/views/dashboard/sidebarOptions/" + userType + "/" + file + ".html");
        switch (file) {
            case "projectRequests":
                projectRequests();
                break;
            case "beginProjectJourney":
                retrieveDevPerProject(1);
                break;
            case "projectDevelopers":
                retrieveDevPerProject();
                break;
            case "manageProjects":
                retrieveBusinessesProjects();
                break;
            case "developerRequests":
                developerRequests();
                break;
            case "currentProject":
                retrieveCurrentProject();
                break;
            case "myAccount":
                //retrieveBusinessesProjects();
                break;
        }
    });
}

function respondToRequest(buttonClicked) {
    var data = {
        'busResponse': buttonClicked.getAttribute("data-request-response"),
        'devId': buttonClicked.getAttribute("data-dev"),
        'projectId': buttonClicked.getAttribute("data-project")
    };
    $.ajax({
        //url: '../api/endpoints/updateProjectRequest.php',
        url: '../api/project/requests/',
        data: JSON.stringify(data),
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
                console.log(response);
            } else {
                console.log(response);
                projectRequests();
            }
        }
    });
}

function projectRequests() {
    $.ajax({
        //url: '../api/endpoints/retrieveProjectRequests.php',
        url: '../api/project/requests/',
        //data: { 'userType': userType }, // review this line, is it needed?
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
                console.log(response);
                addProjectRequestsHTML(response['Success']);
            }
        }
    });
}

function addProjectRequestsHTML(pendingRequests) {
    $("#requestTableBody").empty();
    for (var i = 0; i < pendingRequests.length; i++) {
        var basicRowDetail =
            '<tr>' +
            '<td data-toggle="collapse" data-target="#requestDetail' + (i + 1) + '">click</td>' +
            '<td>' + pendingRequests[i]['projectName'] + '</td>' +
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
            '<a href="../views/developer.php?username=' + pendingRequests[i]['username'] + '">Click here to learn more about them by viewing their profile </a>' +
            '<br><br>' +
            //rework this and make it work with dev username instead of dev id
            '<button data-request-response="Accepted" data-dev="' + pendingRequests[i]['devId'] + '" data-project="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Accept</button>' +
            '<button data-request-response="Rejected" data-dev="' + pendingRequests[i]['devId'] + '" data-project="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Reject</button>' +
            '</div>' +
            '</td>' +
            '</tr>';

        $("#requestTableBody").append(basicRowDetail);
        $("#requestTableBody").append(indepthRowDetail);
    }
}

function addDevProjectRequestsHTML(devProjectRequests) {
    $("#devRequestTableBody").empty();
    for (var i = 0; i < devProjectRequests.length; i++) {
        var basicRowDetail =
            '<tr>' +
            '<td data-toggle="collapse" data-target="#requestDetail' + (i + 1) + '">click</td>' +
            '<td><a href="../views/projectDesc.php?projectId=' + devProjectRequests[i]['projectId'] + '">' + devProjectRequests[i]['projectName'] + '</a></td>' +
            '<td>' + devProjectRequests[i]['status'] + '</td>' +
            '<td><button data-project-request=' + devProjectRequests[i]['projectReqId'] + ' onclick="deleteProjectRequest(this)">X</button></td>' +
            '</tr>';

        var indepthRowDetail =
            '<tr>' +
            '<td colspan="12">' +
            '<div id="requestDetail' + (i + 1) + '" class="collapse">' +
            '<h5>The message you sent...</h5>' +
            '<p>"' + devProjectRequests[i]['devMsg'] + '"</p>' +
            '</div>' +
            '</td>' +
            '</tr>';

        $("#devRequestTableBody").append(basicRowDetail);
        $("#devRequestTableBody").append(indepthRowDetail);
    }
}

function developerRequests() {
    $.ajax({
        //url: '../api/endpoints/retrieveDeveloperRequests.php',
        url: '../api/developer/requests/',
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
                console.log(response);
            } else {
                console.log(response);
                addDevProjectRequestsHTML(response['Success']);
            }
        }
    });
}

function deleteProjectRequest(deleteButtonClicked) {
    var projectReqId = deleteButtonClicked.getAttribute("data-project-request");
    $.ajax({
        //url: '../api/endpoints/deleteProjectRequest.php?projectReqId=' + projectReqId,
        url: '../api/project/request/' + projectReqId,
        type: 'delete',
        method: 'DELETE',
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
                //Maybe consider a function that deletes the html instead of calling the ajax again
                developerRequests();
            }
        }
    });
}

function retrieveDevPerProject(projectStatus) {
    //(statusCondition) ? conditionParams = '?statusCondition=' + statusCondition + '&projectStatus=' + projectStatus: conditionParams = '';
    (projectStatus == null) ? projectStatus = '': projectStatus = projectStatus;
    $.ajax({
        //url: '../api/endpoints/retrieveDevelopersPerProject.php' + conditionParams,
        url: '../api/project/developers/' + projectStatus,
        data: {},
        type: 'get',
        method: 'get',
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
            }
        }
    })
}

function startTheProject(buttonClicked) {
    // var data = {
    //     projectId: buttonClicked.getAttribute("data-project")
    // };
    var projectId = buttonClicked.getAttribute("data-project");
    $.ajax({
        //url: '../api/endpoints/startProject.php',
        //data: JSON.stringify(data),
        url: '../api/project/start/' + projectId,
        type: 'PUT',
        method: 'PUT',
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
            }
        }
    })
}

function retrieveBusinessesProjects() {
    $.ajax({
        //url: '../api/endpoints/retrieveBusinessProjects.php',
        url: '/api/business/projects/',
        data: {},
        type: 'GET',
        method: 'get',
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
            }
        }
    })
}

function retrieveCurrentProject() {
    $.ajax({
        //url: '../api/endpoints/retrieveCurrentProject.php',
        url: '../api/developer/project/',
        data: {},
        type: 'GET',
        method: 'get',
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
            }
        }
    })
}