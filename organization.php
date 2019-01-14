<?php
    //include_once('config.php');
    //exit($base_url);


    include_once('assets/includes/header.php');

    if($_SESSION['logged_in'] != '1'){
        session_start();
        header('Status: 401');
        header('Location: ' . urlencode('index.php'));
    }
    
    include_once('assets/includes/menubar.php');
?>


    
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Organization
                            <small>Company Settings</small>
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        

                        <!--Begin Page Content-->
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_0">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-gift"></i>Organization Details</div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse"> </a>
                                                <a href="javascript:;" class="reload"> </a>
                                                <!--<a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                                <a href="javascript:;" class="remove"> </a>-->
                                            </div>
                                        </div>
                                        <div class="portlet-body form">

                                            <?php
                                                $query = $conn->prepare('SELECT * FROM company WHERE id = ?');
                                                $result = $query->execute([$_SESSION['companyid']]);
                                                if ($row = $query->fetch()) {
                                                ?>


                                                    <!-- BEGIN FORM-->
                                                    <form action="assets/classes/controller.php?act=editorganization" method="post" class="horizontal-form">
                                                        <div class="form-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Company Name</label>
                                                                        <input type="text" value="<?php echo $row['companyName'];?>" name="compname" required class="form-control" placeholder="ABC Limited">
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>City / Town</label>
                                                                        <input type="text" value="<?php echo $row['city'];?>" name="city" required class="form-control" placeholder="Town"> </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>County</label>
                                                                        <select name="county" required class="form-control">
                                                                            <option value="">- - - Select County - - -</option>
                                                                            <option <?php if($row['county'] == 'Nairobi'){echo 'selected';} ?> value="Nairobi" value="Nairobi">Nairobi</option>
                                                                            <option <?php if($row['county'] == 'Mombasa'){echo 'selected';} ?> value="Mombasa">Mombasa</option>
                                                                            <option <?php if($row['county'] == 'Kisumu'){echo 'selected';} ?> value="Kisumu">Kisumu</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row-->
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Contact Email</label>
                                                                        <input type="email" value="<?php echo $row['companyEmail'];?>" name="compemail" class="form-control" placeholder="Contact Email"> </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Contact Phone</label>
                                                                        <input type="text" value="<?php echo $row['contactTelephone'];?>" name="compphone" class="form-control" placeholder="Contact Phone Number">
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Business Year Start</label>                                                                        
                                                                        <select name="startyear" required class="form-control"> 
                                                                            <option  value="">- - -Select Financial Year Start- - -</option>
                                                                            <option <?php if($row['yearStart'] == '1'){echo 'selected';} ?> value="1">January</option>
                                                                            <option <?php if($row['yearStart'] == '2'){echo 'selected';} ?> value="2">February</option>
                                                                            <option <?php if($row['yearStart'] == '3'){echo 'selected';} ?> value="3">March</option>
                                                                            <option <?php if($row['yearStart'] == '4'){echo 'selected';} ?> value="4">April</option>
                                                                            <option <?php if($row['yearStart'] == '5'){echo 'selected';} ?> value="5">May</option>
                                                                            <option <?php if($row['yearStart'] == '6'){echo 'selected';} ?> value="6">June</option>
                                                                            <option <?php if($row['yearStart'] == '7'){echo 'selected';} ?> value="7">July</option>
                                                                            <option <?php if($row['yearStart'] == '8'){echo 'selected';} ?> value="8">August</option>
                                                                            <option <?php if($row['yearStart'] == '9'){echo 'selected';} ?> value="9">September</option>
                                                                            <option <?php if($row['yearStart'] == '10'){echo 'selected';} ?> value="10">October</option>
                                                                            <option <?php if($row['yearStart'] == '11'){echo 'selected';} ?> value="11">November</option>
                                                                            <option <?php if($row['yearStart'] == '12'){echo 'selected';} ?> value="12">December</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Business Year End</label>
                                                                        <select name="endyear" required class="form-control"> 
                                                                            <option value="">- - -Select Financial Year End- - -</option>
                                                                            <option <?php if($row['yearEnd'] == '1'){echo 'selected';} ?> value="1">January</option>
                                                                            <option <?php if($row['yearEnd'] == '2'){echo 'selected';} ?> value="2">February</option>
                                                                            <option <?php if($row['yearEnd'] == '3'){echo 'selected';} ?> value="3">March</option>
                                                                            <option <?php if($row['yearEnd'] == '4'){echo 'selected';} ?> value="4">April</option>
                                                                            <option <?php if($row['yearEnd'] == '5'){echo 'selected';} ?> value="5">May</option>
                                                                            <option <?php if($row['yearEnd'] == '6'){echo 'selected';} ?> value="6">June</option>
                                                                            <option <?php if($row['yearEnd'] == '7'){echo 'selected';} ?> value="7">July</option>
                                                                            <option <?php if($row['yearEnd'] == '8'){echo 'selected';} ?> value="8">August</option>
                                                                            <option <?php if($row['yearEnd'] == '9'){echo 'selected';} ?> value="9">September</option>
                                                                            <option <?php if($row['yearEnd'] == '10'){echo 'selected';} ?> value="10">October</option>
                                                                            <option <?php if($row['yearEnd'] == '11'){echo 'selected';} ?> value="11">November</option>
                                                                            <option <?php if($row['yearEnd'] == '12'){echo 'selected';} ?> value="12">December</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>


                                                            


                                                            <h3 class="form-section">Statutory Details</h3>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>PIN Number</label>
                                                                        <input type="text" value="<?php echo $row['companyPin'];?>" name="companypin" class="form-control" placeholder="KRA PIN Number"> </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>NSSF Number</label>
                                                                        <input type="text" value="<?php echo $row['companyNssf'];?>" name="nssfnumber" class="form-control" placeholder="NSSF Number"> </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>NHIF Number</label>
                                                                        <input type="text" value="<?php echo $row['companyNhif'];?>" name="nhifnumber" class="form-control" placeholder="NHIF Number"> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-actions right">
                                                            <button type="button" class="btn default">Cancel</button>
                                                            <button type="submit" class="btn red">
                                                                <i class="fa fa-check"></i> Save</button>
                                                        </div>
                                                    </form>
                                                    <!-- END FORM-->



                                                <?php
                                                }
                                            ?>

                                            
                                        </div>

                                    </div>
                                </div>
                            </div>

                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                        
                        
                        
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
                
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> 2016 &copy; Redsphere Consulting v0.8
                    
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        
        

    <?php include_once('assets/includes/footer.php');?>