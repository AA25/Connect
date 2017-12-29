//Make a request to the RESTful api to retrieve details about the project
function retrieveProjectDetails() {
    var url = '../api' + window.location.pathname;
    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        //Attach a cookie that contains the user token for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                //On error the error div will show
                $('#notFound').show();
            } else if (response['Success']) {
                //Show the page
                $('#projectDescription').show();
                //Add the project details to the page
                renderMainDisplay(response['Success'][0]);
                //Add options to the side bar
                renderSideDisplay(response['Success'][0]['projectStatusCode'], response['Success']['userType']);
            }
        }
    });
};

//When the project request form in the modal is clicked, an ajax request is made to send a request towards the project
$('#projectRequestForm').submit(function(e) {
    e.preventDefault();

    var urlParam = window.location.pathname;

    //Pull out the project id from the url parameter
    var projectId = parseInt(urlParam.replace("/project/", ""));

    //Pull the data from the form
    var data = {
        'projectId': projectId,
        'devMsg': $('#projectRequestForm textarea[name=devMsg]').val()
    };

    $.ajax({
        //Make ajax request to send the project request to the rest api endpoint
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
        success: function(response) {
            //Close the modal
            $("#pitchNowModal").modal("hide");
            if (response['Success']) {
                //Display success alert
                successDisplay(response['Success']);
            } else {
                //Display error alert
                errorDisplay(response['Error']);
            }
        }
    });
});

function renderMainDisplay(projectData) {
    //We take the project data from the ajax response and add it to the respective parts
    dateEntered = projectData['dateEntered'].split(" ");

    $("#projectName").text(projectData['projectName']);
    $("#projectCategory").text(projectData['projectCategory']);
    $("#projectDate").text(dateEntered[0]);
    $("#projectBudget").text(projectData['projectCurrency'] + projectData['projectBudget']);
    $("#projectBio").text(projectData['projectBio']);
    $("#projectLocation").text(projectData['projectCountry']);
    $("#projectLanguage").text(projectData['projectLanguage']);
    if (projectData['projectStatusCode'] < 2) {
        $("#projectStatus").text('This project is currently recruiting developers');
        $("#projectStatus").addClass("cl-success");
    } else {
        $("#projectStatus").text('This project has already started and is not looking for developers');
        $("#projectStatus").addClass("cl-danger");
    }
}

function renderSideDisplay(projectStatusCode, userType) {
    //Depending on the user viewing the page we show different options
    if (userType === 'developer' && (projectStatusCode < 2)) {
        //This will be the side view content if you are a developer
        $('#projectReq').show();
        $("#sideContentA p").text("Pitch now to send a request to join this project");
        $("#sideContentA").append('<button class="btn cl-white btn-success pointer" data-toggle="modal" data-target="#pitchNowModal">Pitch Now</button>');
        $("#sideContentB p").text("Want to register your project? Sign Up as a business to do just that");
        $("#sideContentB").append('<a href="http://localhost:8081/register/business" class="btn cl-white btn-success">Sign Up</a>');

    } else if (projectStatusCode < 2) {
        //This will be the side view for businesses and guests when the project is recruiting devleopers
        $('#projectReq').show();
        $("#sideContentA p").text("To send a request to this project sign up now as a developer");
        $("#sideContentA").append('<a href="http://localhost:8081/register/developer" class="btn cl-white btn-success">Sign Up</a>');
        $("#sideContentB p").text("Want to register your own project? Sign Up as a business to do just that");
        $("#sideContentB").append('<a href="http://localhost:8081/register/business" class="btn cl-white bg-cl-blue-connect">Sign Up</a>');
    }
}

//Updates the number of characters left on the business bio textarea
var maxText = 500;
$('#msgCount').html(maxText + ' remaining');
$('#devMsg').keyup(function() {
    var currentTextLen = $('#devMsg').val().length;
    var remainingText = maxText - currentTextLen;
    $('#msgCount').html(remainingText + ' remaining');
});

window.onload = retrieveProjectDetails();