<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect Register Project</title>
    <!-- <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" href="../css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="../css/connectStyle.css"/>
</head>
<body>

    <form method="post" action="" id="registerProjectForm">
        <div class="">
            <label for="projectName">Project Name:</label>
            <input type="text" class="form-control" placeholder="Enter a project name" name="projectName">
        </div>
        <div class="">
            <label for="projectCategory">Project Category:</label>
            <select name="projectCategory">
                <option disabled selected value> Select the category that best fits your project</option>
                <option value="Website Creation">Website Creation</option>
                <option value="Web Application">Web Application</option>
                <option value="Backend Development">Backend Development</option>
                <option value="FrontEnd Development">FrontEnd Development</option>
                <option value="UX/UI Development">UX/UI Development</option>
                <option value="Data Analysing">Data Analysing</option>
                <option value="Machine Learning">Machine Learning</option>
            </select>  
        </div>
        <div class="">
            <label for="projectBio">Project Bio:</label></br>
            <textarea rows="4" cols="50" placeholder="Enter a description of what this project involves" name="projectBio"></textarea>
        </div>
        <div class="">
            <label for="projectCountry">Project Country:</label>
            <select name="projectCountry">
                <option disabled selected value> Select the country your project will take place</option>
                <option value="Ireland">Ireland</option>
                <option value="United Kingdom">United</option>
            </select>        
        </div>
        <div class="">
            <label for="projectCurrency">Project Currency:</label>
            <select name="projectCurrency">
                <option disabled selected value> Select the appropriate currency for this project</option>
                <option value="€">Euro</option>
                <option value="£">Pound</option>
                <option value="$">Dollar</option>
            </select>        
        </div>
        <div class="">
            <label for="projectBudget">Project Budget:</label>
            <select name="projectBudget">
                <option disabled selected value> Select your budget range </option>
                <option value="100 - 1,000">100 - 1,000</option>
                <option value="1,000 - 2,500">1,000 - 2,500</option>
                <option value="2,500 - 5,000">2,500 - 5,000</option>
                <option value="5,000 - 10,000">5,000 - 10,000</option>
                <option value="10,000+">10,000+</option>
            </select>        
        </div>
        <div class="">
            <label for="projectLanguage">Enter a language required for this project</label>
            <select name="projectLanguage">
                <option disabled selected value> Select the main language required </option>
                <option value="English">English</option>
                <option value="Irish">Irish</option>
            </select>  
        </div>
        
        <div class="">
            <label for="projectDeadline">Project Deadline:</label>
            <input type="text" class="form-control" placeholder="Enter a deadline date for the project" name="projectDeadline">
        </div>

        <button type="submit" class="" style="">Register Project</button>
    </form>

</body>
    <script src="../js/jQuery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/smoothScroll/smoothScroll.js"></script>
    <script src="../js/navBar.js"></script>
    <script src="../js/registerProject.js"></script>
</html>