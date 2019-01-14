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
                        <h1 class="page-title"> Employee Analysis
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        

                        <!--Begin Page Content-->
                            
                            <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                        <button class="btn blue  btn-outline dropdown-toggle" data-toggle="dropdown">Tools
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right">
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <i class="fa fa-print"></i> Print </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <i class="fa fa-file-pdf-o"></i> Save as PDF </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <i class="fa fa-file-excel-o"></i> Export to Excel </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- modal -->
                                            <div id="new-employee" class="modal fade" tabindex="-1" aria-hidden="true" data-width="700">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Create New Employee</h4>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                    <button type="button" class="btn red">Add Employee</button>
                                                </div>
                                            </div>


                                        </div>
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>  </th>
                                                    <th> Emp # </th>
                                                    <th> First Name </th>
                                                    <th> Last Name </th>
                                                    <th> Status </th>
                                                    <th> Gender </th>
                                                    <th> Department </th>
                                                    <th> Cost Center </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <!--Begin Data Table-->
                                                <tr class="odd gradeX">
                                                    
                                                    <td> 1 </td>
                                                    <td> A0013 </td>
                                                    <td> Andrew  </td>
                                                    <td> Moturi </td>
                                                    <td> Permanent </td>
                                                    <td> Male </td>
                                                    <td>A128355F</td>
                                                    <td>12672345</td>

                                                </tr>

                                                <tr class="odd gradeX">
                                                    
                                                    <td> 2 </td>
                                                    <td> A0018 </td>
                                                    <td> Halima  </td>
                                                    <td> Mwanza </td>
                                                    <td> Temporary </td>
                                                    <td> Female </td>
                                                    <td> B928305F </td>
                                                    <td> 12672345 </td>

                                                </tr>
                                                <!--End Data Table-->

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
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