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
            <a href="../views/developer.php?<?php echo 'username='.$userVerifiedData['username']?>" target="_blank">Your Profile</a>
        </li>
        <li onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>', 'myAccount')">
            My Account
        </li>
    </ul>
</div>