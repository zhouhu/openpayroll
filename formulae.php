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
                        <h1 class="page-title"> Earnings & Deductions Formulae
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
                                                        <a class="btn green" href=""> Create Earning <i class="fa fa-plus-square"></i></a>
                                                        <a class="btn red" href=""> Create Deduction <i class="fa fa-minus-square"></i></a>
                                                    </div>Create
                                                </div>
                                            </div>

                                        </div>

                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>  </th>
                                                    <th> Code </th>
                                                    <th> Description </th>
                                                    <th> Type </th>
                                                    <th> Formula </th>
                                                    <th> Actions </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <!--Begin Data Table-->
                                                <tr class="odd gradeX">
                                                    
                                                    <td> 1 </td>
                                                    <td> 301 </td>
                                                    <td> Overtime (1.5)  </td>
                                                    <td> <span class="label label-inverse label-sm label-success"> Earning </span> </td>
                                                    <td>A128355F</td>
                                                    <td><a href="" class="btn btn-xs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> </td>

                                                </tr>

                                                <tr class="odd gradeX">
                                                    
                                                    <td> 2 </td>
                                                    <td> 302 </td>
                                                    <td> NHIF Tier 1  </td>
                                                    <td> <span class="label label-inverse label-sm label-danger"> Deduction </span></td>
                                                    <td> B928305F </td>
                                                    <td><a href="" class="btn btn-xs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> 

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