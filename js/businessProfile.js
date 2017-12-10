function retrieveBusinessDetails() {
    //var url = '../api/endpoints/retrieveBusiness.php' + location.search;
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
                console.log(response);
                //sideDisplay(response['userType']);
            }
        }
    });
};

window.onload = retrieveBusinessDetails();