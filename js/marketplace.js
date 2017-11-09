var returnFrom = 0;
var returnAmount = 2;

function retrieveProjects(){
    var url = '../api/endpoints/retrieveProjects.php?returnAmount='+returnAmount+'&returnFrom='+returnFrom;
    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            returnFrom = returnFrom + returnAmount;
            if(response['Error']){
                //No more to load
                console.log(response['Error']);
            }else{
                //console.log(response['Projects']);
                renderProjects(response['Projects']);
            }
        }
    });
};

function loadMore(){
    retrieveProjects();    
}

function renderProjects(projectsArray){
    projectsArray.forEach(function(element) {
        insertRow = '<tr>'+
        '<td>'+element['projectCountry']+'</td>' +
        '<td>'+element['projectCategory']+'</td>' +
        '<td>'+element['projectBio']+'</td>' +
        '<td>'+element['projectCurrency']+element['projectBudget']+'</td>' +
        '</tr>';
        $('#marketplaceTableBody').append(insertRow);
    }, this);    
}

window.onload = retrieveProjects();
