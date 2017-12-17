function retrieveProjectDetails() {
    var url = window.location.pathname;
    $.ajax({
        url: '/api' + url,
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
                console.log(response['Success'][0]);
                renderContent(response['Success'][0]);
            }
        }
    });
};

function renderContent(profileData) {
    profileData['dob'] = userAge(profileData['dob']);

    $("#name").text(profileData['firstName'] + ' ' + profileData['lastName']);
    $("#age").text(profileData['dob']);
    $("#languages").text(profileData['languages']);
    $("#phone").text(profileData['phone']);
    $("#email").text(profileData['email']);
    $("#profileDescription").text(profileData['devBio']);
    console.log(profileData['currentProject']);
    if (profileData['currentProject'] !== null) {
        $("#projectStatus").text(profileData['firstName'] + ' is currently part of a project and unavailable');
        $("#projectStatus").addClass("cl-danger");
    } else if (profileData['currentProject'] === null) {
        $("#projectStatus").text(profileData['firstName'] + ' is currently available to join a project');
        $("#projectStatus").addClass("cl-success");
    }
};

//Function to calculate the age of the user
function userAge(dob) {
    dob = dob.split("-");
    var today = new Date();
    var date = new Date(dob[0], dob[1], dob[2], 0, 0, 0);
    var userAge = (today - date) / (1000 * 60 * 60 * 24 * 365);
    userAge = Math.floor(userAge);

    return userAge;
}

window.onload = retrieveProjectDetails();