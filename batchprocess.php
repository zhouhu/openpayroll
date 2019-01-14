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
                        <h1 class="page-title"> Batch Process <small><b>Please note: A batch process will affect multiple employees in the organization.</b></small></h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        

                        <!--Begin Page Content-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet box green">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-cog"></i>Create Batch Process </div>

                                            <div class="tools">
                                                <!--<a href="javascript:;" class="reload"> </a>
                                                <a href="javascript:;" class="collapse"> </a>
                                                <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                                <a href="javascript:;" class="remove"> </a>-->
                                            </div>
                                        </div>
                                        <div class="portlet-body form">



                                            <!-- BEGIN FORM-->
                                            <form method="post" action="assets/classes/controller.php?act=#" class="form-horizontal">
                                                
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6"> 
                                                            <div class="form-group">
                                                                <label><b>Type of Batch Process</b></label>
                                                                <select  required class="form-control" name="batchtype">
                                                                    <option>- - Select Type of Batch Process - -</option>
                                                                    <option value="earning"> Earning </option>
                                                                    <option value="deduction"> Deduction </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6"> 
                                                                <label><b>Type of Operator</b></label>
                                                                <select  required class="form-control" name="batchtype">
                                                                    <option>- - Select Type of Batch Operator - -</option>
                                                                    <option value="earning"> Percentage - % </option>
                                                                    <option value="deduction"> Constant Value </option>
                                                                </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><b>Select Earning / Deduction Affected</b></label>
                                                                <select  required class="form-control" name="batchtype">
                                                                    <option>- - Select Earning / Deduction Affected - -</option>
                                                                    <?php
                                                                        $query = $conn->prepare('SELECT * FROM earnings_deductions WHERE edType != ? AND edType != ? AND companyId = ? AND active = ? AND globalComputed = ? ORDER BY edCode');
                                                                        $bat = $query->execute(['Calc', 'Notax', $_SESSION['companyid'], '1', '0']);
                                                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                        foreach ($res as $row => $link) {
                                                                            echo '<option value="">' . $link['edCode'] . " - " . $link['edDesc'] . '</option>';
                                                                        }
                                                                    ?>

                                                                </select>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label><b>Value / Amount</b> (% or value)</label>
                                                            <input type="text" name="fname" class="form-control" required placeholder="Value of Operator">
                                                        </div>
                                                    </div>

                                                    
                                                </div>

                                                <div class="form-actions">
                                                    <div class="row">
                                                        <div class="col-md-12 txt-ctr">
                                                            <button type="submit" class="btn btn-lg red">Create Batch Process <i class="fa fa-cog"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- END FORM-->


                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!--End Page Content-->

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