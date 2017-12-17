function retrieveBusinessDetails() {
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
                renderContent(response['Success'][0]);
            }
        }
    });
};

function renderContent(profileData) {

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