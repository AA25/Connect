var returnFrom = 0;
var returnAmount = 2;

function retrieveProjects() {
    var url = '/api/endpoints/retrieveProjects.php?returnAmount=' + returnAmount + '&returnFrom=' + returnFrom;
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
            returnFrom = returnFrom + returnAmount;
            if (response['Error']) {
                //No more to load
                console.log(response['Error']);
            } else {
                console.log(response);
                renderProjects(response['Projects']);
            }
        }
    });
};

function loadMore() {
    retrieveProjects();
}

function renderProjects(projectsArray) {
    projectsArray.forEach(function(element) {
        insertRow = '<tr onclick="window.location=\'../views/projectDesc.php?projectId=' + element['projectId'] + '\'">' +
            '<td>' + element['projectCountry'] + '</td>' +
            '<td>' + element['projectName'] + '</td>' +
            '<td>' + element['projectCategory'] + '</td>' +
            '<td>' + element['projectBio'] + '</td>' +
            '<td>' + element['projectCurrency'] + element['projectBudget'] + '</td>' +
            '</tr>';
        $('#marketplaceTableBody').append(insertRow);
    }, this);
    $('#marketplace').show();
}


window.onload = retrieveProjects();