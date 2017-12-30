function retrieveBusinessDetails() {
    var url = window.location.pathname;
    //ajax request to the rest api endpoint to retrieve data on a business
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
                $("#businessProfileContent").show();
                renderContent(response['Success'][0]);
            }
        }
    });
};

function renderContent(profileData) {
    //Add the user data to the different id hooks on the page to create user profile

    $("#name").text(profileData['firstName'] + ' ' + profileData['lastName']);
    $("#busName").text(profileData['busName']);
    $("#busIndustry").text(profileData['busIndustry']);
    $("#phone").text(profileData['phone']);
    $("#email").text(profileData['email']);
    $("#businessDescription").text(profileData['busBio']);

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

window.onload = retrieveBusinessDetails();