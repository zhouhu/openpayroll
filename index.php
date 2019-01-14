<?php include_once('assets/includes/header.php');?>

    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="index.php">PAYROLL</a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="assets/classes/controller.php?act=login" method="post">
                <h3 class="form-title font-green">Sign In</h3>
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
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <input class="form-control form-control-solid placeholder-no-fix" required type="text" autocomplete="off" placeholder="Username" name="uname" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <input class="form-control form-control-solid placeholder-no-fix" required type="password" autocomplete="off" placeholder="Password" name="upassword" /> </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-block red uppercase">Login</button>

                    
                </div>
                <!--<div class="forgotpass">
                    <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                </div>-->

               
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form class="forget-form" action="assets/classes/controller.php?act=resetpass" method="post">
                <h3 class="font-green">Forgot Password ?</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Please enter email address </span>
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

                <p> Enter your e-mail address below to reset your password. </p>
                <div class="form-group">
                    <input class="form-control placeholder-no-fix" required type="email" autocomplete="off" placeholder="Email Address" name="email" /> </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn green btn-outline">Back</button>
                    <button type="submit" class="btn btn-danger uppercase pull-right">Submit</button>
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->
            <!-- BEGIN REGISTRATION FORM -->


	            <form class="register-form" action="assets/classes/controller.php?act=createcompanyaccount" method="post">
	                <h3 class="font-green">Sign Up</h3>
	                <p > <b>Enter company details below: </b></p>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">Company Name</label>
	                    <input class="form-control placeholder-no-fix" type="text" placeholder="Company Name" name="fullname" /> </div>
	                <div class="form-group">
	                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
	                    <label class="control-label visible-ie8 visible-ie9">Contact Email</label>
	                    <input class="form-control placeholder-no-fix" type="text" placeholder="Company Contact Email" name="email" /> </div>
	                <div class="form-group">
	                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
	                    <label class="control-label visible-ie8 visible-ie9">Contact Phone Number</label>
	                    <input class="form-control placeholder-no-fix" type="text" placeholder="Company Contact Phone Number" name="phone" /> </div>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">Address</label>
	                    <input class="form-control placeholder-no-fix" type="text" placeholder="Address" name="address" /> </div>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">City/Town</label>
	                    <input class="form-control placeholder-no-fix" type="text" placeholder="City/Town" name="city" /> </div>
	                
	                <p> <b>Enter your user account details below: </b></p>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">Email Address</label>
	                    <input class="form-control placeholder-no-fix" type="email" autocomplete="off" placeholder="User Account Email Address" name="username" /> </div>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">First Name</label>
	                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" required placeholder="First Name" name="ufname" /> </div>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">Last Name</label>
	                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" required placeholder="Second Name" name="ulname" /> </div>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">Password</label>
	                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
	                <div class="form-group">
	                    <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
	                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>
	                <div class="form-group margin-top-20 margin-bottom-20">
	                    <label class="mt-checkbox mt-checkbox-outline">
	                        <input type="checkbox" name="tnc" /> I agree to the
	                        <a href="termsofservice.php" target="_blank">Terms of Service </a> 
	                        <span></span>
	                    </label>
	                    <div id="register_tnc_error"> </div>
	                </div>
	                <div class="form-actions">
	                    <button type="button" id="register-back-btn" class="btn green btn-outline">Cancel</button>
	                    <button type="submit" id="register-submit-btn" class="btn btn-danger uppercase pull-right">Create Account</button>
	                </div>
	            </form>


            <!-- END REGISTRATION FORM -->
        </div>
        <!--<div class="container top-spacer-60">
            <div class="col-md-12">
                <div>

                    <div class="item">
                      <div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
                        <div class="panel">
                          <div class="panel-heading">
                            <h4 class="text-center hprice"><b>Free</b></h4>
                          </div>
                          <div class="panel-body text-center">
                            <div class="hprice pricetag">
                                <span class="feature_desc">Full payroll features</span></div>
                          </div>
                          <div class="panel-body text-center">
                            <div class="hprice pricetag">
                                  <span class="glyphicon glyphicon-user"></span> <span class="feature_desc"><b>1 - 10 Employees</b></span></div>
                          </div>

                          <div class="panel-footer btn-hide"> <a class="btn btn-block btn-danger">KES 0.00</a> </div>
                        </div>
                      </div>
                    </div>

                    <div class="item active">
                      <div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
                        <div class="panel">
                          <div class="panel-heading">
                            <h4 class="text-center hprice"><b>Silver</b></h4>
                          </div>
                          <div class="panel-body text-center">
                            <div class="hprice pricetag">
                                <span class="feature_desc">Full payroll features</span></div>
                          </div>
                          <div class="panel-body text-center">
                            <div class="hprice pricetag">
                                <span class="glyphicon glyphicon-user"></span> <span class="feature_desc"><b>11 - 100 Employees</b></span></div>
                          </div>
                          
                          <div class="panel-footer btn-hide"> <a class="btn btn-block btn-danger">KES 1,000 / Month</a> </div>
                        </div>
                      </div>
                    </div>

                    

                    <div class="item">
                      <div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
                        <div class="panel">
                          <div class="panel-heading">
                            <h4 class="text-center hprice"><b>Gold</b></h4>
                          </div>
                          <div class="panel-body text-center">
                            <div class="hprice pricetag">
                                <span class="feature_desc">Full payroll features</span></div>
                          </div>
                          <div class="panel-body text-center">
                            <div class="hprice pricetag">
                                 <span class="glyphicon glyphicon-user"></span> <span class="feature_desc"><b> > 100 Employees</b></span>
                                 </div>
                          </div>

                          <div class="panel-footer btn-hide"> <a class="btn btn-block btn-danger btn-hide">KES 99 / Employee / Month</a> </div>
                        </div>
                      </div>
                    </div>


                </div>
            </div>
        </div>-->


        <div class="copyright"> &copy; 2017 Redsphere Consulting. </div>


<?php include_once('assets/includes/footer.php');?>