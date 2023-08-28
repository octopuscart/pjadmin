<?php
$userdata = $this->session->userdata('logged_in');
if ($userdata) {
    
} else {
    redirect("Authentication/index", "refresh");
}
$menu_control = array();

$dataManagementMenu = array();
foreach (APISET as $key => $value) {
    $dataManagementMenu[$value["title"]] = site_url("Services/tableReport/$key");
}

$product_menu = array(
    "title" => "Data Manegement",
    "icon" => "ion-cube",
    "active" => "",
    "sub_menu" => $dataManagementMenu,
);



array_push($menu_control, $product_menu);

$order_menu = array(
    "title" => "Order Manegement",
    "icon" => "fa fa-list",
    "active" => "",
    "sub_menu" => array(
        "Orders Reports" => site_url("Order/orderslist"),
        "Order Analytics" => site_url("Order/index"),
    ),
);
array_push($menu_control, $order_menu);

$booking_menu = array(
    "title" => "Appointment Mgmt.",
    "icon" => "fa fa-calendar",
    "active" => "",
    "sub_menu" => array(
        "Set Schedule" => site_url("Services/appointment"),
        "Schedule List" => site_url("Services/appointmentList_report"),
        "Schedule Booking Reports" => site_url("Services/appointment_report"),
    ),
);
array_push($menu_control, $booking_menu);

$coupon_menu = array(
    "title" => "Coupon Management",
    "icon" => "fa fa-gift",
    "active" => "",
    "sub_menu" => array(
        "Coupon Setting" => site_url("CouponManager/index"),
        "Coupon Report" => site_url("CouponManager/couponReport"),
    ),
);
array_push($menu_control, $coupon_menu);

$client_menu = array(
    "title" => "Client Manegement",
    "icon" => "fa fa-users",
    "active" => "",
    "sub_menu" => array(
        "Clients Reports" => site_url("UserManager/usersReport"),
    ),
);
array_push($menu_control, $client_menu);

$cms_menu = array(
    "title" => "Content Management",
    "icon" => "fa fa-file-text",
    "active" => "",
    "sub_menu" => array(
        "Add New Look Book" => site_url("CMS/newLookbook"),
        "Look Book" => site_url("CMS/lookbook"),
    ),
);
array_push($menu_control, $cms_menu);

$website_content = array(
    "title" => "Website Management",
    "icon" => "fa fa-globe",
    "active" => "",
    "sub_menu" => array(
        "FAQ's Management" => site_url("CMS/faqSetting"),
        "Testimonial Management" => site_url("CMS/testimonialSetting"),
        "Website Pages" => site_url("CMS/pageList"),
    ),
);
array_push($menu_control, $website_content);

$msg_menu = array(
    "title" => "Newsletter Management",
    "icon" => "fa fa-envelope",
    "active" => "",
    "sub_menu" => array(
        "Create Template" => site_url("CMS/createNewsletter"),
        "Template List" => site_url("CMS/newsLetterTempalteList"),
    ),
);

array_push($menu_control, $msg_menu);

$user_menu = array(
    "title" => "User Management",
    "icon" => "fa fa-user",
    "active" => "",
    "sub_menu" => array(
        "Add User" => site_url("#"),
        "Users Reports" => site_url("#"),
    ),
);

//array_push($menu_control, $user_menu);

$webqnruiry_content = array(
    "title" => "Contact From Report",
    "icon" => "fa fa-phone",
    "active" => "",
    "sub_menu" => array(
        "Report" => site_url("Services/contectFormReport"),
    ),
);
array_push($menu_control, $webqnruiry_content);

$setting_menu = array(
    "title" => "Settings",
    "icon" => "fa fa-cogs",
    "active" => "",
    "sub_menu" => array(
        "System Log" => site_url("Services/systemLogReport"),
        "Report Configuration" => site_url("Configuration/reportConfiguration"),
        "Payment & Shipping" => site_url("Configuration/checkokutConfiguration")
    ),
);

array_push($menu_control, $setting_menu);

$social_menu = array(
    "title" => "Social Management",
    "icon" => "fa fa-facebook",
    "active" => "",
    "sub_menu" => array(
        "Social Link" => site_url("CMS/socialLink"),
    ),
);
array_push($menu_control, $social_menu);

$seo_menu = array(
    "title" => "SEO",
    "icon" => "fa fa-area-chart",
    "active" => "",
    "sub_menu" => array(
        "General" => site_url("CMS/siteSEOConfigUpdate"),
        "Page Wise Setting" => site_url("CMS/seoPageSetting"),
    ),
);
array_push($menu_control, $seo_menu);

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