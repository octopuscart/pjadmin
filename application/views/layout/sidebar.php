<?php
$userdata = $this->session->userdata('logged_in');
if ($userdata) {
    
} else {
    redirect("Authentication/index", "refresh");
}
$menu_control = array();

function getSubMenu($main_menu) {
    $dataManagementMenu = array();
    foreach (json_decode(MENULIST, true)[$main_menu] as $key => $value) {
        $api_menu_obj = json_decode(APISET, true)[$value];
        $dataManagementMenu[$api_menu_obj["title"]] = site_url("Services/tableReport/$value");
    }
    return $dataManagementMenu;
}

$report_menu = array(
    "title" => "Report Manegement",
    "icon" => "ion-cube",
    "active" => "",
    "sub_menu" => getSubMenu("Reports"),
);
array_push($menu_control, $report_menu);

$worship_songs_menu = array(
    "title" => "Worship Songs",
    "icon" => "ion-headphone",
    "active" => "",
    "sub_menu" => getSubMenu("WorshipSongs"),
);
array_push($menu_control, $worship_songs_menu);

$data_management_menu = array(
    "title" => "Data Management",
    "icon" => "ion-code-download",
    "active" => "",
    "sub_menu" => getSubMenu("DataManagement"),
);
array_push($menu_control, $data_management_menu);

$user_menu = array(
    "title" => "User Management",
    "icon" => "fa fa-user",
    "active" => "",
    "sub_menu" => array(
        "Add User" => site_url("#"),
        "Users Reports" => site_url("#"),
    ),
);

array_push($menu_control, $user_menu);

$setting_menu = array(
    "title" => "Settings",
    "icon" => "fa fa-cogs",
    "active" => "",
    "sub_menu" => array(
        "System Log" => site_url("Services/tableReport/system_log"),
    ),
);

array_push($menu_control, $setting_menu);

foreach ($menu_control as $key => $value) {
    $submenu = $value['sub_menu'];
    foreach ($submenu as $ukey => $uvalue) {
        if ($uvalue == current_url()) {
            $menu_control[$key]['active'] = 'active';
            break;
        }
    }
}
?>
<style>
    .sidebar .sub-menu>li.active>a {
        color: white;
        font-weight: bold;
    }
</style>

<!-- begin #sidebar -->
<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
            <li class="nav-profile">
                <div class="image">
                    <a href="javascript:;"><img src='<?php echo base_url(); ?>assets/profile_image/<?php echo $userdata['image'] ?>' alt="" class="media-object rounded-corner" style="    width: 35px;background: url(<?php echo base_url(); ?>assets/emoji/user.png);    height: 35px;background-size: cover;" /></a>
                </div>
                <div class="info textoverflow" >

<?php echo $userdata['first_name']; ?>
                    <small class="textoverflow" title="<?php echo $userdata['username']; ?>"><?php echo $userdata['username']; ?></small>
                </div>
            </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="nav-header">Navigation</li>
            <li class="has-sub ">
                <a href="javascript:;">
                    <b class="caret pull-right"></b>
                    <i class="fa fa-laptop"></i>
                    <span>Dashboard</span>
                </a>
                <ul class="sub-menu">

                    <li class="active"><a href="<?php echo site_url("Order/index"); ?>">Dashboard</a></li>

                </ul>
            </li>
<?php foreach ($menu_control as $mkey => $mvalue) { ?>

                <li class="has-sub <?php echo $mvalue['active']; ?>">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>  
                        <i class="<?php echo $mvalue['icon']; ?>"></i> 
                        <span><?php echo $mvalue['title']; ?></span>
                    </a>
                    <ul class="sub-menu">
    <?php
    $submenu = $mvalue['sub_menu'];
    foreach ($submenu as $key => $value) {
        ?>
                            <li><a href="<?php echo $value; ?>"><?php echo $key; ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                    <?php } ?>
            <li class="nav-header">Tailor Admin V <?php echo PANELVERSION; ?></li>
            <li class="nav-header">-</li>
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->
<script>
$(document).ready(function(){
    $("a[href='"+document.location.href+"']").parent().addClass("active");
})
</script> 