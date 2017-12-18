<div id="dashboardSidebar" class="col-sm-12 col-md-5 col-lg-3 padb-20 section-alt sidebar-border-connect">

    <div id="dashboardSidebarOptions" class="row padt-20 navbar-bg" style="border-bottom: 1px solid black;">
        <div class="col-sm-12 padl-30">
            <h4 class="cl-blue-connect">Dashboard Sidebar</h4>
        </div>
    </div>

    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-sm-12 padl-30">
            <p class="marb-15 mart-15 cl-black-connect" onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>', 'developerRequests')">
                Outgoing Requests
            </p>
        </div>
    </div>

    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-sm-12 padl-30">
            <p class="marb-15 mart-15 cl-black-connect" onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>', 'currentProject')">
                Current Project
            </p>
        </div>
    </div>

    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-sm-12 padl-30">
            <p class="marb-15 mart-15">
                <a class="cl-black-connect" href="http://localhost:8081/developer/info/<?php echo str_replace(".","-",$userVerifiedData['username']); ?>" target="_blank"> My Profile</a>
            </p>
        </div>
    </div>

    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-sm-12 padl-30">
            <p class="marb-15 mart-15 cl-black-connect" onclick="renderSidebarOption('<?php echo $userVerifiedData['type'] ?>', 'myAccount')">
                My Account
            </p>
        </div>
    </div>

</div>
