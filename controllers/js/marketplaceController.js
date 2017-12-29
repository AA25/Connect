// The amount of projects to retrieve from rest api
var returnFrom = 0;
var returnAmount = 8;

function retrieveProjects() {
    //Send a request to rest api endpoint to return projects between X and Y
    var url = '/api/projects/from/' + returnFrom + '/' + returnAmount;
    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        beforeSend: function(request) {
            //Attach token to the request for authentication
            request.setRequestHeader('Authorization', 'Bearer ' + getCookie('JWT').replace(" ", "+"));
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            //We want to set the returnFrom value to be the number of projects returned
            //So next time it can pick up from where it ended and return the next batch forward
            returnFrom = returnFrom + returnAmount;
            if (response['Error']) {
                //Display the error alert from the function inside the navbar controller
                errorDisplay(response['Error']);
            } else {
                //Add the projects to the table
                renderProjects(response['Success']['Projects']);
            }
        }
    });
};

//The load more buttom
function loadMore() {
    retrieveProjects();
}

function renderProjects(projectsArray) {
    projectsArray.forEach(function(element) {
        //Limit the project description
        element['projectBio'] = limitProjectDescription(element['projectBio']);

        //Create the table rows containing the data retrieved
        insertRow = '<tr onclick="window.location=\'/project/' + element['projectId'] + '\'">' +
            '<td><i class="fa fa-globe" aria-hidden="true"></i> ' + element['projectCountry'] + '</td>' +
            '<td>' + element['projectCategory'] + '</td>' +
            '<td>' + element['projectBio'] + '</td>' +
            '<td>' + element['projectCurrency'] + element['projectBudget'] + '</td>' +
            '</tr>';

        $('#marketplaceTableBody').append(insertRow);

    }, this);
    $('#marketplace').show();
}


//Function to limit the amount text that is shown from the project description
function limitProjectDescription(projectDesc) {
    if (projectDesc.length > 65) {
        projectDesc = projectDesc.slice(0, 65) + '...';
    }
    return projectDesc;
}

window.onload = retrieveProjects();