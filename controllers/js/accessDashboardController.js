var userType = '';

function renderSidebarOption(userType, file) {
    $(function() {
        //Load the correct dashboard sidebar depending on the user account type
        $("#renderOption").load("/views/dashboard/sidebarOptions/" + userType + "/" + file + ".html");

        //Depending on the option clickd on the sidebar the correct html will be displayed in the main view
        switch (file) {
            case "projectRequests":
                projectRequests();
                break;
            case "beginProjectJourney":
                retrieveDevPerProject(1);
                break;
            case "projectDevelopers":
                retrieveDevPerProject(null);
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
            } else if (typeof(response['Success']) === 'string') {
                console.log('No project requests');
            } else {
                console.log(response['Success']);
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
            '<td data-toggle="collapse" data-target="#requestDetail' + (i + 1) + '"><i class="fa fa-eye cl-blue-connect" aria-hidden="true"></i></td>' +
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
            '<h6>Their message contained the following...</h6>' +
            '<p class="padt-10">"' + pendingRequests[i]['devMsg'] + '"</p>' +
            '<a href="http://localhost:8081/developer/info/' + pendingRequests[i]['username'].replace(/\./g, "-") + '" class="cl-blue-connect">Click here to learn more about them by viewing their profile </a>' +
            '<br><br>' +
            //rework this and make it work with dev username instead of dev id
            '<div class="txt-ctr">' +
            '<button class="btn bg-cl-blue-connect cl-white mar-10" data-request-response="Accepted" data-dev="' + pendingRequests[i]['devId'] + '" data-project="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Accept</button>' +
            '<button class="btn navbar-bg cl-white mar-10" data-request-response="Rejected" data-dev="' + pendingRequests[i]['devId'] + '" data-project="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Reject</button>' +
            '</div>' +
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
            '<td data-toggle="collapse" data-target="#requestDetail' + (i + 1) + '"><i class="fa fa-eye" aria-hidden="true"></td>' +
            '<td><a href="http://localhost:8081/project/' + devProjectRequests[i]['projectId'] + '">' + devProjectRequests[i]['projectName'] + '</a></td>' +
            '<td>' + devProjectRequests[i]['status'] + '</td>' +
            '<td><button type="btn" class="btn cl-white bg-cl-blue-connect pad-0 h-30 w-60" data-project-request=' + devProjectRequests[i]['projectReqId'] + ' onclick="deleteProjectRequest(this)">Delete</button></td>' +
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
    $("#beginJourneyTableBody").empty();
    $("#projectDevelopersTableBody").empty();

    //A condition can be attached to the REST api endpoint to return developers per projects 
    //(Only projects in a particular status)
    (projectStatus == null) ? projectStatus = '': projectStatus = projectStatus;
    //Send an ajax request to the rest api endpoint for the developers on a project
    $.ajax({
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
                //For the begin your journey section we show the error div
                $("#noProject").show();
            } else {
                if (projectStatus == '') {
                    //Add the html view the developers per project
                    renderDevProjectHTML(response['Success']['ProjectDevelopers']);
                } else {
                    //Add the html view the developers per project and then an option to start the project
                    renderStartProjectHTML(response['Success']['ProjectDevelopers'], response['ProjectIds']);
                }
            }
        }
    })
}

function renderDevProjectHTML(projects) {
    $("#projectDevelopersTableBody").empty();

    //Traverse through each project
    for (var key in projects) {
        //Create table row for project and add it to the table
        keyId = key.replace(/ /g, "");
        var projectRow =
            '<tr class="row" data-toggle="collapse" data-target="#' + keyId + '">' +
            '<td class="col-xs-1"><i class="fa fa-eye cl-blue-connect padl-20" aria-hidden="true"></i></td>' +
            '<td class="col-xs-11">' + key + '</td>' +
            '</tr>';
        $("#projectDevelopersTableBody").append(projectRow);

        //Create table row where developers will be contained in
        var developerRow =
            '<tr id="' + keyId + '" class="collapse">' +
            '<td style="background-color:#d9edf7;">' +
            '<div id="' + keyId + 'Div" class="padl-10"></div>' +
            '</td></tr>';
        $("#projectDevelopersTableBody").append(developerRow);

        //If the project has no developers
        if (projects[key].length <= 0) {
            var developer =
                '<p> There no developers working on this project. Once you accept a developer request for this project they will be listed here</p>';
            $("#" + keyId + "Div").append(developer);
        } else {
            //Traverse through each developer and add them as a row
            for (var i = 0; i < projects[key].length; i++) {
                var developer =
                    '<p><i class="fa fa-user" aria-hidden="true"></i>' +
                    '<a href="http://localhost:8081/developer/info/' + projects[key][i]['username'].replace(/\./g, "-") + '" class="cl-black padl-20">' +
                    projects[key][i]['name'] +
                    '</a></p>';
                $("#" + keyId + "Div").append(developer);
                //console.log("#" + key + "Div");
            }
        }
    }

};

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
                retrieveDevPerProject(1);
            }
        }
    })
}

//Add html to create the table containing all projects ready to be started and their respective developers
function renderStartProjectHTML(projects, projectIds) {
    $("#beginJourneyTableBody").empty();
    var projectCounter = 0;
    //Traverse through each project
    for (var key in projects) {
        //Create table row for project and add it to the table
        keyId = key.replace(/ /g, "");
        var projectRow =
            '<tr>' +
            '<td class="" data-toggle="collapse" data-target="#' + keyId + 'Div"><i class="fa fa-eye cl-blue-connect padl-20" aria-hidden="true"></i></td>' +
            '<td class="">' + key + '</td>' +
            '<td class="">Pending Start</td>' +
            '<td class=""><button type="button" data-project=' + projectIds[projectCounter][key] + ' onclick="startTheProject(this)" class="btn btn-success cl-white pad-0 h-30 w-60">Start</button></td>'
        '</tr>';
        $("#beginJourneyTableBody").append(projectRow);

        //Create table row where developers will be contained in
        var developerRow =
            '<tr>' +
            '<td colspan="12" style="background-color:#d9edf7;">' +
            '<div id="' + keyId + 'Div" class="collapse padl-20"></div>' +
            '</td></tr>';
        $("#beginJourneyTableBody").append(developerRow);

        //If the project has no developers
        if (projects[key].length <= 0) {
            var developer =
                '<p> There no projects ready to be started</p>';
            $("#" + keyId + "Div").append(developer);
        } else {
            //Traverse through each developer and add them as a row
            for (var i = 0; i < projects[key].length; i++) {
                var developer =
                    '<p><i class="fa fa-user" aria-hidden="true"></i>' +
                    '<a href="http://localhost:8081/developer/info/' + projects[key][i]['username'].replace(/\./g, "-") + '" class="cl-black padl-20">' +
                    projects[key][i]['name'] +
                    '</a></p>';
                $("#" + keyId + "Div").append(developer);
                //console.log("#" + key + "Div");
            }
        }
        projectCounter++;
    }
}

function renderManageProjectsHTML(projects) {

    $("#manageProjectTableBody").empty();

    for (var i = 0; i < projects.length; i++) {
        var deleteProject = '<td class="txt-ctr"><i class="fa fa-ban" aria-hidden="true"></i></td>';
        var viewForum = '<td class="txt-ctr"><i class="fa fa-lock" aria-hidden="true"></i></td>';
        if (projects[i][8] >= 2) {
            // If this project is past the start project phase then a forum link is available
            //A check is also done on the server side before allowing user access to this page
            viewForum = '<td class="txt-ctr"><a href="http://localhost:8081/dashboard/forum/' + projects[i][0] + '" class="btn cl-white bg-cl-blue-connect pad-0 padl-5 padr-5">Forum</a></td>';
        }
        if (projects[i][8] < 2) {
            //If the project hasn't been started yet, then the delete button is available
            // A serverside check is done to make sure project hasn't been started
            deleteProject = '<td class="txt-ctr"><button class="btn btn-danger pad-0 padl-5 padr-5" data-project="' + projects[i][0] + '" onclick="deleteProject(this)">Delete</button></td>';
        }
        var manageProjectRow =
            '<tr>' +
            '<td>' + projects[i][1] + '</td>' +
            '<td>' + projects[i][7] + '</td>' +
            deleteProject +
            viewForum +
            '</tr>';

        $("#manageProjectTableBody").append(manageProjectRow);
    }
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
                console.log(response['Error']);
            } else {
                renderManageProjectsHTML(response['Success']);
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
                renderCurrentProjectHTML(response['Success']);
            }
        }
    })
}

function renderCurrentProjectHTML(currentProject) {
    console.log(currentProject);
    $("#noProject").hide();

    var currentProjectRow =
        '<tr>' +
        '<td><a href="http://localhost:8081/project/' + currentProject[0] + '" target="_blank">' + currentProject[1] + '</a></td>' +
        '<td>' + currentProject[7] + '</td>' +
        '<td class=""><a href="http://localhost:8081/dashboard/forum/' + currentProject[0] + '" class="btn cl-white bg-cl-blue-connect pad-0 padl-5 padr-5">Forum</a></td>' +
        '</tr>';

    $("table").show();
    $("#manageProjectTableBody").append(currentProjectRow);
}

function deleteProject(buttonClicked) {
    var projectId = buttonClicked.getAttribute("data-project");
    $.ajax({
        url: '../api/project/delete/' + projectId,
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
                retrieveBusinessesProjects();
            }
        }
    });
}