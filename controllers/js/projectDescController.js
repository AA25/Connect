//Make a request to the RESTful api to retrieve details about the project
function retrieveProjectDetails() {
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
            } else if (response['Success']) {
                console.log(response);
                renderMainDisplay(response['Success'][0]);
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
            //Close the modal
            $("#pitchNowModal").modal("hide");
            console.log(result);
        }
    });
});

function renderMainDisplay(projectData) {
    $("#projectName").text(projectData['projectName']);
    $("#projectCategory").text(projectData['projectCategory']);
    $("#projectDate").text(projectData['dateEntered']);
    $("#projectBudget").text(projectData['projectCurrency'] + projectData['projectBudget']);
    $("#projectBio").text(projectData['projectBio']);
    $("#projectLocation").text(projectData['projectCountry']);
    $("#projectLanguage").text(projectData['projectLanguage']);
    if (projectData['projectStatusCode'] < 2) {
        $("#projectStatus").text('This project is currently recruiting developers');
    } else {
        $("#projectStatus").text('This project has already started and is not looking for developers');
    }
}

function renderSideDisplay(projectStatusCode, userType) {
    if (userType === 'developer' && (projectStatusCode < 2)) {
        //This will be the side view content if you are a developer
        $('#projectReq').show();
        $("#sideContentA p").text("Pitch now to send a request to join this project");
        $("#sideContentA").append('<button class="btn cl-white btn-success" data-toggle="modal" data-target="#pitchNowModal">Pitch Now</button>');
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