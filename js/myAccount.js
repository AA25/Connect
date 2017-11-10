function retrieveUserDetails(){
    var url = '../api/endpoints/retrieveUserDetails.php';
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
                console.log(response['Error']);
            }else{
                console.log(response);
            }
        }
    });
};