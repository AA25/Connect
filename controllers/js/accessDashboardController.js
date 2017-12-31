var userType = '';

function renderSidebarOption(userType, file) {
    $(function() {
        //Load the correct dashboard sidebar depending on the user account type
        $("#renderOption").load("/views/dashboard/sidebarOptions/" + userType + "/" + file + ".html",
            function() {
                //When the sidebar html has loaded
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
    });
}

//Once a developer request has been accepted or rejected this function is called
function respondToRequest(buttonClicked) {
    //Pull the data from the button clicked (accepted or rejected) via the custom data tags
    var data = {
        'busResponse': buttonClicked.getAttribute("data-request-response"),
        'devId': buttonClicked.getAttribute("data-dev"),
        'projectId': buttonClicked.getAttribute("data-project")
    };

    //Send an ajax request to the rest endpoint api to updates the database accordingly 
    $.ajax({
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
                //If error then display error alert using function from navbar controller
                errorDisplay(response['Error']);
            } else {
                //If successful then display success alert and reload the table html
                successDisplay(response['Success']);
                projectRequests();
            }
        }
    });
}

function projectRequests() {
    $.ajax({
        //Sends an ajax request to retrieve the current project requests to any project the user owns
        url: '../api/project/requests/',
        type: 'get',
        method: 'GET',
        //Attach cookie token to request
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //On error we display error alert using function from navbar controller
                errorDisplay(response['Error']);
            } else if (typeof(response['Success']) === 'string') {
                //No project requests so we display the message to inform user of this
                $("#noRequests").show();
            } else {
                //If success then we add the project requests to the table to display
                addProjectRequestsHTML(response['Success']);
            }
        }
    });
}

function addProjectRequestsHTML(pendingRequests) {
    $("#requestTableBody").empty();

    //Add each project request to the table 
    for (var i = 0; i < pendingRequests.length; i++) {
        var basicRowDetail =
            '<tr>' +
            '<td data-toggle="collapse" data-target="#requestDetail' + (i + 1) + '"><i class="fa fa-eye cl-blue-connect pointer" aria-hidden="true"></i></td>' +
            '<td>' + pendingRequests[i]['projectName'] + '</td>' +
            '<td>' + pendingRequests[i]['projectCategory'] + '</td>' +
            '<td>' + pendingRequests[i]['devName'] + '</td>' +
            '<td>' + pendingRequests[i]['status'] + '</td>' +
            '</tr>';

        //Once a row is clicked another row containing more detail of the requests is collapsed
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
            '<button class="btn bg-cl-blue-connect cl-white mar-10 pointer" data-request-response="Accepted" data-dev="' + pendingRequests[i]['devId'] + '" data-project="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Accept</button>' +
            '<button class="btn navbar-bg cl-white mar-10 pointer" data-request-response="Rejected" data-dev="' + pendingRequests[i]['devId'] + '" data-project="' + pendingRequests[i]['projectId'] + '" onclick="respondToRequest(this)">Reject</button>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '</tr>';

        //Attach rows to the table
        $("#requestTableBody").append(basicRowDetail);
        $("#requestTableBody").append(indepthRowDetail);
    }
}

function addDevProjectRequestsHTML(devProjectRequests) {
    $("#devRequestTableBody").empty();
    //Take the project requests returned and create rows for each requests
    for (var i = 0; i < devProjectRequests.length; i++) {
        var basicRowDetail =
            '<tr>' +
            '<td class="pointer" data-toggle="collapse" data-target="#requestDetail' + (i + 1) + '"><i class="fa fa-eye" aria-hidden="true"></td>' +
            '<td><a href="http://localhost:8081/project/' + devProjectRequests[i]['projectId'] + '">' + devProjectRequests[i]['projectName'] + '</a></td>' +
            '<td>' + devProjectRequests[i]['status'] + '</td>' +
            '<td><button type="btn" class="btn cl-white bg-cl-blue-connect pad-0 h-30 w-60 pointer" data-project-request=' + devProjectRequests[i]['projectReqId'] + ' onclick="deleteProjectRequest(this)">Delete</button></td>' +
            '</tr>';

        //A more detailed row of the requests is provided when basic row is clicked
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
    //Send an ajax request to the rest api endpoint to retrieve a developers requests
    $.ajax({
        url: '../api/developer/requests/',
        data: {},
        type: 'get',
        method: 'GET',
        //Attach cookie with request that contains user token for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //if error then show message
                $("#noDevRequests").show();
            } else {
                //If success add the project requests to the table html
                addDevProjectRequestsHTML(response['Success']);
            }
        }
    });
}

function deleteProjectRequest(deleteButtonClicked) {
    //Pull out the developer request to be deleted from the delete button
    var projectReqId = deleteButtonClicked.getAttribute("data-project-request");

    //Ajax request to the rest api endpoint to delete the project request
    $.ajax({
        url: '../api/project/request/' + projectReqId,
        type: 'delete',
        method: 'DELETE',
        //Attach cookie which contains user token used for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //If error then display error alert
                errorDisplay(response['Error']);
            } else {
                //On success display success alert and reload the project requests table
                successDisplay(response['Success']);
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
                //For the begin your journey section we show the error message div
                $("#noProject").show();
                //For the project developers section we show the error message div
                $("#noDevelopers").show();
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
            '<td class="col-xs-1"><i class="fa fa-eye pointer cl-blue-connect padl-20" aria-hidden="true"></i></td>' +
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

//Function to start a project once it has developers assigned to it
function startTheProject(buttonClicked) {
    var projectId = buttonClicked.getAttribute("data-project");

    //Send an ajax request to the rest api endpoint for starting a project
    $.ajax({
        url: '../api/project/start/' + projectId,
        type: 'PUT',
        method: 'PUT',
        //Attach a cookie which contains user token for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //if error display error alert using the function from the navbarcontroller 
                errorDisplay(response['Error']);
            } else {
                //If success then display success alert and reload the begin your journey html
                successDisplay(response['Success']);
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
            '<td class=""><button type="button" data-project=' + projectIds[projectCounter][key] + ' onclick="startTheProject(this)" class="btn btn-success cl-white pad-0 h-30 w-60 pointer">Start</button></td>'
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
    //Create a row for each project
    $("#manageProjectTableBody").empty();
    console.log(projects);
    for (var i = 0; i < projects.length; i++) {
        var deleteProject = '<td class="txt-ctr"><i class="fa fa-ban" aria-hidden="true"></i></td>';
        var viewForum = '<td class="txt-ctr"><i class="fa fa-lock" aria-hidden="true"></i></td>';
        if (projects[i][8] >= 2 && projects[i][8] < 5) {
            // If this project is past the start project phase and not finished then a forum link is available
            //A check is also done on the server side before allowing user access to this page
            viewForum = '<td class="txt-ctr"><a href="http://localhost:8081/dashboard/forum/' + projects[i][0] + '" class="btn cl-white bg-cl-blue-connect pad-0 padl-5 padr-5">Forum</a></td>';
        }
        if (projects[i][8] < 2) {
            //If the project hasn't been started yet, then the delete button is available
            // A serverside check is done to make sure project hasn't been started
            deleteProject = '<td class="txt-ctr"><button class="btn btn-danger pad-0 padl-5 padr-5 pointer" data-project="' + projects[i][0] + '" onclick="deleteProject(this)">Delete</button></td>';
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
    //Send an ajax request to the rest api endpoint to return all projects owned by the busienss
    $.ajax({
        url: '/api/business/projects/',
        data: {},
        type: 'GET',
        method: 'get',
        //Attach cookie which contains user user token for authentication
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
            } else {
                //If projects are returned then add them to the html table
                renderManageProjectsHTML(response['Success']);
            }
        }
    })
}

function retrieveCurrentProject() {
    //ajax request to the rest api endpoint to return the project the developer is currently assigned
    //to if any
    $.ajax({
        url: '../api/developer/project/',
        data: {},
        type: 'GET',
        method: 'get',
        //
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //If error then show error div
                $("#noCurrentProject").show();
            } else {
                //On success then add the current project to the table html
                renderCurrentProjectHTML(response['Success']);
            }
        }
    })
}

function renderCurrentProjectHTML(currentProject) {
    //Add the current project as a row to the table
    var currentProjectRow =
        '<tr>' +
        '<td><a href="http://localhost:8081/project/' + currentProject[0] + '">' + currentProject[1] + '</a></td>' +
        '<td>' + currentProject[7] + '</td>' +
        '<td class=""><a href="http://localhost:8081/dashboard/forum/' + currentProject[0] + '" class="btn cl-white bg-cl-blue-connect pad-0 padl-5 padr-5">Forum</a></td>' +
        '</tr>';

    $("table").show();
    $("#manageProjectTableBody").append(currentProjectRow);
}

function deleteProject(buttonClicked) {
    //Pull the project id from the button clicked to delete that specific project
    var projectId = buttonClicked.getAttribute("data-project");
    //Send an ajax request to the rest api endpoint to delete the project
    $.ajax({
        url: '../api/project/delete/' + projectId,
        type: 'delete',
        method: 'DELETE',
        //Attach cookie with contains user token used for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //If error then display error alert using function from the navbar controller
                errorDisplay(response['Error']);
            } else {
                //On success we display the success alert and reload that table html
                successDisplay(response['Success'])
                retrieveBusinessesProjects();
            }
        }
    });
}