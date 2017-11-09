<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect Marketplace</title>
    <!-- <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" href="../css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="../css/connectStyle.css"/>
</head>
<body>

    <div>
        <div id="projectMarketplace">
            <table class="table">
                <thead>
                <tr>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Budget</th>
                </tr>
                </thead>
                <tbody id="marketplaceTableBody"></tbody>
            </table>
        </div>
        <button onclick="loadMore()">
            LOAD
        </button>
    </div>
    

</body>
    <script src="../js/jQuery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/smoothScroll/smoothScroll.js"></script>
    <script src="../js/navBar.js"></script>
    <script src="../js/marketplace.js"></script>
</html>