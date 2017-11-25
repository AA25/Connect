<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connects Project Requests</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="../../css/connectStyle.css"/>
</head>
        <!--This page should allow users to businesses/developers to view their dashboard and the different options available-->
<body>

    <div id="pseudoContainer" class="row">
        <div id="dashboardSidebar" class="col-md-3">
            <div id="dashboardSidebarOptions" class="row" style="background-color:none">
                <ul>
                    <li onclick="projectRequests()">Project Requests</li>
                </ul>
            </div>
        </div>
        <div id="renderOption" class="col-md-8" style="background-color:none">
            <div>
                <h2>Incoming Project Requests</h2>
                <p>Below is a list of requests sent by developers to one or more of your projects</p>
                <p>Click a row to view the request in more detail</p>
            </div>   
            <table class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Project ID</th>
                        <th>Project category</th>
                        <th>Request sent by</th>
                        <th>Request status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-toggle="collapse" data-target="#requestIndepth1">
                        <td>click</td>
                        <td>1</td>
                        <td>Website Creation</td>
                        <td>Sam Smith</td>
                        <td>Pending...</td>
                    </tr>
                    <tr>
                        <td colspan="12">
                            <div id="requestIndepth1" class="collapse">
                                <h3>Sam Smith sent a request to join this project</h3>

                                <h5>Their message...</h5>

                                <p>
                                    "Hi, Could I join your project?"
                                </p>

                                <a href="">Click here to view their profile to find out more about them</a>
                                <br><br>
                                <button data-request-response="Accepted" data-dev=1 onclick="respondToRequest(this)">Accept</button>
                                <button data-request-response="Rejected" data-dev=1 onlick="respondToRequest(this)">Reject</button>
                            </div>
                        </td> 
                    </tr>
                    <tr>
                        <td>click</td>
                        <td>1</td>
                        <td>Website Creation</td>
                        <td>James Smith</td>
                        <td>Pending...</td>
                    </tr>
                    <tr>
                        <td>click</td>
                        <td>1</td>
                        <td>Website Creation</td>
                        <td>Ade Akingbade</td>
                        <td>Pending...</td>
                    </tr>
                </tbody>
            </table> 
        </div>
        
    </div>
    

</body>
    <script src="../js/jQuery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/smoothScroll/smoothScroll.js"></script>
    <script src="../js/navBar.js"></script>
    <script src="../js/accessDashboard.js"></script>
</html>