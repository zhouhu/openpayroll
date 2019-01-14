<?php include_once('assets/includes/header.php');?>


    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            PAYROLL
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="assets/classes/controller.php?act=login" method="post">
                <h3 class="form-title font-green">New Password</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Please enter login details </span>
                </div>

                <div>
                	<?php
                        if (isset($_SESSION['msg'])) {
                            echo '<div class="alert alert-' . $_SESSION['alertcolor'] . ' alert-dismissable role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $_SESSION['msg'] . '</div>';
                            unset($_SESSION['msg']);
                            unset($_SESSION['alertcolor']);
                        }
                    ?>
                </div>

                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">New Password</label>
                    <input class="form-control form-control-solid placeholder-no-fix" required type="password" autocomplete="off" placeholder="New Password" name="newpass1" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Repeat Password</label>
                    <input class="form-control form-control-solid placeholder-no-fix" required type="password" autocomplete="off" placeholder="Password" name="newpass2" /> </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-block red uppercase">Reset Password</button>

                    
                </div>

                
            </form>
            <!-- END LOGIN FORM -->

        </div>
        <div class="copyright"> 2016 Redsphere Consulting. </div>


<?php include_once('assets/includes/footer.php');?>