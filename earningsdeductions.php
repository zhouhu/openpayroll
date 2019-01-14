<?php
    //include_once('config.php');
    //exit($base_url);

    //include_once('assets/classes/functions.php');
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
                            <small>Manage Company's Earnings & Deductions</small>
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
                                                

                                                <div class="col-md-12">
                                                    <div class="btn-group pull-right">
                                                        
                                                        <a class="btn blue btn-sm" data-toggle="modal"  href="#newearning"> Create New Earning <i class="fa fa-plus-square"></i></a>
                                                        <a class="btn red btn-sm" data-toggle="modal"  href="#newdeduction"> Create New Deduction <i class="fa fa-minus-square"></i></a>  
                                                        <a class="btn purple btn-sm" data-toggle="modal"  href="#newloan"> Create New Loan <i class="fa fa-minus-square"></i></a>   
                                                    </div>
                                                </div>

                                            </div>


                                            <!-- Start Modal -->
                                            
                                                <div id="newearning" class="modal fade" tabindex="-1" data-width="560">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">New Earning</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=addearning">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Description</label>
                                                                            <div class="col-md-7">
                                                                                <input type="text" required class="form-control" name="eddescription" placeholder="Title of New Earning">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Recurrent?</label>
                                                                            <div class="col-md-7">
                                                                                <label class="radio-inline"><input type="radio" name="recurrent" checked value="0"> No</label>
                                                                                <label class="radio-inline"><input type="radio" name="recurrent" value="1"> Yes</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                            <button type="submit" class="btn red">Add Earning</button>
                                                        </div>
                                                    </form>
                                                </div>


                                                <div id="newdeduction" class="modal fade" tabindex="-1" data-width="560">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">New Deduction</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=adddeduction">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Description</label>
                                                                            <div class="col-md-7">
                                                                                <input type="text" required class="form-control" name="eddescription" placeholder="Title of New Deduction">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Recurrent?</label>
                                                                            <div class="col-md-7">
                                                                                <label class="radio-inline"><input type="radio" checked name="recurrent" value="0"> No</label>
                                                                                <label class="radio-inline"><input type="radio" name="recurrent" value="1"> Yes</label>
                                                                            </div>
                                                                        </div>
                                                                            
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                            <button type="submit" class="btn red">Add Deduction</button>
                                                        </div>
                                                    </form>
                                                </div>



                                                <div id="newloan" class="modal fade" tabindex="-1" data-width="560">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">New Loan Facility</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=addloan">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Description</label>
                                                                            <div class="col-md-7">
                                                                                <input type="text" required class="form-control" name="newloandesc" placeholder="e.g. Emergency Loan">
                                                                            </div>
                                                                        </div>                                                                            
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                            <button type="submit" class="btn red">Add Loan</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!--End Modal-->



                                        </div>
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                    <th> </th>
                                                    <th> E/D Code </th>
                                                    <th> Description </th>
                                                    <th> Type </th>
                                                    <th> Recurrrent? </th>
                                                    <th> Actions </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <!--Begin Data Table-->
                                                <?php
                                                    try{
                                                        $query = $conn->prepare('SELECT * FROM earnings_deductions WHERE companyId = ? AND active = ? AND globalComputed = ?');
                                                        $fin = $query->execute([$_SESSION['companyid'], '1', '0']);
                                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);


                                                        foreach ($res as $row => $link) {
                                                            ?><tr class="odd gradeX"><td></td><?php echo '<td>' . $link['edCode'] . '</td><td>' . $link['edDesc'] . '</td><td>';
                                                                $edtype = $link['edType']; 

                                                                
                                                                echo '<span class="label label-inverse label-sm label-' . styleLabelColor($edtype) . '">' . $link['edType'] . '</span></td>'; 

                                                                    if ($link['recurrentEd'] == 1) {
                                                                        echo '<td> Yes </td>';
                                                                    } else{
                                                                        echo '<td> No </td>';
                                                                    }
                                                                
                                                                echo '<td><a href="" class="btn btn-zs yellow"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
                                                                    <a href="" class="btn btn-zs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> </td></tr>';
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