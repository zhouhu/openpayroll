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
                            <small>Cost Centers</small>
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
                                                    <div class="btn-group">
                                                        <a class="btn red" data-toggle="modal" href="#responsive"> Add New <i class="fa fa-plus"></i></a>    
                                                    </div>
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


                                            <!-- Modal -->
                                            <div id="responsive" class="modal fade" tabindex="-1" data-width="560">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Add New Cost Centers</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=addcostcenter">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-body">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label">Cost Center</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" required class="form-control" name="cctrname" placeholder="Cost Center Name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                            <button type="submit" class="btn red">Create New</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>


                                        </div>
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                    
                                                    <th> Id </th>
                                                    <th> Cost Center </th>
                                                    <th> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <!--Begin Data Table-->
                                                <?php
                                                    try{
                                                        $query = $conn->prepare('SELECT * FROM company_costcenters WHERE companyId = ? AND active = ?');
                                                        $fin = $query->execute([$_SESSION['companyid'], '1']);
                                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                        //print_r($res);

                                                        foreach ($res as $row => $link) {
                                                            ?><tr class="odd gradeX"> <?php echo '<td>' . $link['costcenterId'] .  '</td><td>' . $link['costCenterName'] . '</td><td><a href="" class="btn btn-xs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td></tr>';
                                                        }

                                                    }
                                                    catch(PDOException $e){
                                                        echo $e->getMessage();
                                                    }
                                                ?>
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