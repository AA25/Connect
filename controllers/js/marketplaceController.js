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

function showProjectSuggestions(input) {
    //Function for live search in marketplace
    //https://www.w3schools.com/php/php_ajax_livesearch.asp

    //If the input key is less than 3 letters don't send ajax requests yet
    if (input.length < 2) {
        $("#livesearch").show();
        $("#livesearch").html('<span>More letters needed</span>');
    } else {
        $("#livesearch").empty();
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                $("#livesearch").show();
                $("#livesearch").html(this.responseText);
            }
        }
        xmlhttp.open("GET", "/controllers/php/liveSearchController.php?q=" + input, true);
        xmlhttp.send();
    }
}

window.onload = retrieveProjects();