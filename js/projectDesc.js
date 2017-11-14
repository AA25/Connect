function retrieveProjectDetails(){
    var url = '../api/endpoints/retrieveProject.php'+location.search;
    $.ajax({
        url: url,
        data: {},
        type: 'get',
        method: 'GET',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //Error in setting status
        },
        success: function(response) {
            if(response['Error']){
                console.log(response['Error']);
            }else{
                console.log(response);
            }
        }
    });
};

window.onload=retrieveProjectDetails();