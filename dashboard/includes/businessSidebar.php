<div id="dashboardSidebarOptions" class="" style="background-color:none">
    <h3>Business View SideBar</h3>
    <ul>
        <li>
            <a href="./dashboard/registerProject.php" target="_blank"> Register A New Project</a>
        </li>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>','beginProjectJourney')">Begin Project Journey</li>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>','projectRequests')">Current Project Requests</li>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>','projectDevelopers')">Your Project Developers</li>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>','manageProjects')">Manage Your Projects</li>
    </ul>
</div>