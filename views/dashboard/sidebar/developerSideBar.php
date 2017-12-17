<div id="dashboardSidebarOptions" class="row" style="background-color:none">
    <h3>Developer View SideBar</h3>
    <ul>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>', 'developerRequests')">
            Outgoing Requests
        </li>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>', 'currentProject')">
            Current Project
        </li>
        <li>
            <a href="http://localhost:8081/developer/info/<?php echo str_replace(".","-",$userVerifiedData['username']); ?>" target="_blank">Your Profile</a>
        </li>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>', 'myAccount')">
            My Account
        </li>
    </ul>
</div>