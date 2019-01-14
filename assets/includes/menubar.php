<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="dashboard.php">
                            <!--<img src="assets/layouts/layout/img/logo.png" alt="logo" class="logo-default" />--> </a>
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="bar-left-menu">
                        <ul class="nav navbar-nav">                            
                            <li class="dropdown dropdown-quick-sidebar-toggler">
                                <div class="bar-style">
                                    <?php
                                        retrieveDescSingleFilter ('company', 'companyName', 'id', $_SESSION['companyid']);

                                        if (isset($_SESSION['periodstatuschange']) && $_SESSION['periodstatuschange'] == '1') {
                                            echo '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; CURRENT PERIOD: ' . $_SESSION['activeperiodDescription'] . '&nbsp;<span class="label label-inverse label-sm label-danger"> VIEW MODE - CLOSED PERIOD </span>';
                                        } else {
                                            echo '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; CURRENT PERIOD: ' . $_SESSION['activeperiodDescription'];
                                        }
                                        
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </div>



                    <div class="top-menu">

                        <ul class="nav navbar-nav pull-right">
                            
                            <!-- END TODO DROPDOWN -->
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="icon-key"></i>
                                    <span class="username username-hide-on-mobile"> Reports </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="payslipreport.php">
                                            <i class="icon-key"></i> Payslip Report</a>
                                    </li>
                                    <li>
                                        <a href="reports.php">
                                            <i class="icon-key"></i> Payroll Report</a>
                                    </li>
                                    <li>
                                        <a href="payereport.php">
                                            <i class="icon-key"></i> PAYE Report</a>
                                    </li>
                                    <li>
                                        <a href="nhifreport.php">
                                            <i class="icon-key"></i> NHIF Report</a>
                                    </li>
                                    <li>
                                        <a href="nssfreport.php">
                                            <i class="icon-key"></i> NSSF Report</a>
                                    </li>
                                    <li>
                                        <a href="pnine.php">
                                            <i class="icon-key"></i> P9 Report</a>
                                    </li>
                                </ul>
                            </li>


                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="icon-key"></i>
                                    <span class="username username-hide-on-mobile"> Administration </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="organization.php">
                                            <i class="icon-key"></i> Global Settings </a>
                                    </li>
                                    <li>
                                        <a href="earningsdeductions.php">
                                            <i class="icon-key"></i> Earnings & Deductions </a>
                                    </li>
                                    <li>
                                        <a href="departments.php">
                                            <i class="icon-key"></i> Departments </a>
                                    </li>
                                    <li>
                                        <a href="branches.php">
                                            <i class="icon-key"></i> Branches </a>
                                    </li>
                                    <li>
                                        <a href="users.php">
                                            <i class="icon-key"></i> Users </a>
                                    </li>
                                </ul>
                            </li>


                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="fa fa-user"></i>
                                    <span class="username username-hide-on-mobile"> <?php print $_SESSION['first_name'] . " " . $_SESSION['last_name'];?> </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="#">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="#">
                                            <i class="icon-lock"></i> Lock Screen </a>
                                    </li>
                                    <li>
                                        <a href="assets/classes/controller.php?act=logout">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <!--<li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="assets/classes/controller.php?act=logout" class="dropdown-toggle">
                                    <i class="icon-logout"></i> Logout
                                </a>
                            </li>-->


                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                

            <?php include_once('assets/includes/sidebar.php');?>


                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN THEME PANEL -->
                        
                        <!-- END THEME PANEL -->
                        <!-- BEGIN PAGE BAR -->
                        <!--<div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="dashboard.php">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Dashboard</span>
                                </li>
                            </ul>
                            <div class="page-toolbar">
                                <div id="dashboard-report-range" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">sd
                                    <i class="icon-calendar"></i>&nbsp;
                                    <span class="thin uppercase hidden-xs"></span>&nbsp;
                                    <i class="fa fa-angle-down"></i>
                                </div>
                            </div>
                        </div>-->
                        <!-- END PAGE BAR -->
