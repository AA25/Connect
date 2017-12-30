function retrieveDeveloperDetails() {
    var url = window.location.pathname;
    //ajax request to the rest api endpoint to retrieve data on a developer
    $.ajax({
        url: '/api' + url,
        data: {},
        type: 'get',
        method: 'GET',
        //Attach cookie which contains user token for authentication
        beforeSend: function(request) {
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if (response['Error']) {
                // On error show no user message
                $("#noUser").show();
            } else {
                //On success show user found div and create html for user
                $("#developerProfileContent").show();
                renderContent(response['Success'][0]);
            }
        }
    });
};

function renderContent(profileData) {
    //Add the user data to the different id hooks on the page to create user profile
    profileData['dob'] = userAge(profileData['dob']);

    $("#name").text(profileData['firstName'] + ' ' + profileData['lastName']);
    $("#age").text(profileData['dob']);
    $("#languages").text(profileData['languages']);
    $("#phone").text(profileData['phone']);
    $("#email").text(profileData['email']);
    $("#profileDescription").text(profileData['devBio']);
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

window.onload = retrieveDeveloperDetails();