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
                            <small>Company Statutories</small>
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        

                        <!--Begin Page Content-->
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_0">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-gift"></i>Statutories </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse"> </a>
                                                <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                                <a href="javascript:;" class="reload"> </a>
                                                <a href="javascript:;" class="remove"> </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                                    <!-- BEGIN FORM-->
                                                    <form action="#" class="horizontal-form">
                                                        <div class="form-body">
                                                            <h3 class="form-section">Statutory Details</h3>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Company Name</label>
                                                                        <input type="text" id="firstName" class="form-control" placeholder="Chee Kin">
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>PIN Number</label>
                                                                        <input type="text" class="form-control" placeholder="KRA PIN Number"> </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>NSSF Number</label>
                                                                        <input type="text" class="form-control" placeholder="NSSF Number"> </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>NHIF Number</label>
                                                                        <input type="text" class="form-control" placeholder="NHIF Number"> </div>
                                                                </div>
                                                            </div>


                                                            <h3 class="form-section">Address</h3>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Street</label>
                                                                        <input type="text" class="form-control" placeholder="Street Name"> </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Building</label>
                                                                        <input type="text" class="form-control" placeholder="Physical Location"> </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>City / Town</label>
                                                                        <input type="text" class="form-control" placeholder="Town"> </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>County</label>
                                                                        <input type="text" class="form-control" placeholder="County"> </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row-->
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Post Code</label>
                                                                        <input type="text" class="form-control" placeholder="Post Code"> </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Country</label>
                                                                        <select class="form-control">  
                                                                            <option value="">Kenya</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                        </div>
                                                        <div class="form-actions right">
                                                            <button type="button" class="btn default">Cancel</button>
                                                            <button type="submit" class="btn red">
                                                                <i class="fa fa-check"></i> Save</button>
                                                        </div>
                                                    </form>
                                                    <!-- END FORM-->
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