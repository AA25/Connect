// This file contains javascript for the homepage

//We are sending a request for the 8 newest projects to the RESTFul API
(function retrieveProjects() {
    var url = '/api/projects/from/0/6';
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
            console.log("Issue with AJAX Request");
        },
        success: function(response) {
            if (response['Error']) {
                //Error from REST API
                console.log(response['Error']);
            } else {
                //Result from REST API
                renderProjects(response['Success']['Projects']);
            }
        }
    });
}());

//Function that will create an HTML table containing the projects for the AJAX request
function renderProjects(projectsArray) {
    projectsArray.forEach(function(element) {
        element['projectBio'] = limitProjectDescription(element['projectBio']);

        //Create the table rows containing the data retrieved
        insertRow = '<tr onclick="window.location=\'/project/' + element['projectId'] + '\'">' +
            '<td><i class="fa fa-globe" aria-hidden="true"></i> ' + element['projectCountry'] + '</td>' +
            '<td>' + element['projectCategory'] + '</td>' +
            '<td>' + element['projectBio'] + '</td>' +
            '<td>' + element['projectCurrency'] + element['projectBudget'] + '</td>' +
            '</tr>';
        $('#slimMarketplaceTableBody').append(insertRow);
    }, this);
    $('#slimMarketplace').show();
}

//Function to limit the amount text that is shown from the project description
function limitProjectDescription(projectDesc) {
    if (projectDesc.length > 61) {
        projectDesc = projectDesc.slice(0, 61) + '...';
    }
    return projectDesc;
}