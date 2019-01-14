<?php
    //include_once('config.php');
    //exit($base_url);
    //check for valid page access
    

    include_once('assets/includes/header.php');

    if($_SESSION['logged_in'] != '1'){
        session_start();
        header('Status: 401');
        header('Location: ' . urlencode('index.php'));
    }

    include_once('assets/includes/menubar.php');
    include_once('assets/classes/model.php');

    
?>
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="form-group pull-right top-spacer-20">
                                        <a class="btn blue btn-sm" data-toggle="modal" href="#assignleave"><i class="fa fa-user-plus"></i> Assign Leave </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE BAR -->




                        <div class="row">
                        	<div class="col-md-4">
		                        <h1 class="page-title"> Leave Summary
                                    <?php
                                        if (isset($_SESSION['leavestate'])) {
                                            if ($_SESSION['leavestate'] == '1') {
                                                $labelcode = 'success';
                                                $labelstat = 'Approved';
                                            } elseif ($_SESSION['leavestate'] == '2') {
                                                    $labelcode = 'primary';
                                                    $labelstat = 'Pending';
                                                }  elseif ($_SESSION['leavestate'] == '3') {
                                                    $labelcode = 'warning';
                                                    $labelstat = 'Cancelled';
                                                    } elseif ($_SESSION['leavestate'] == '4') {
                                                        $labelcode = 'danger';
                                                        $labelstat = 'Declined';
                                                        }
                                        }
                                        else {
                                            $labelcode = 'success';
                                            $labelstat = 'Approved';
                                        }
                                    ?>
                                    <span class="label label-inverse label-lg label-<?php echo($labelcode)?>"> <?php echo($labelstat);?> Leaves </span>
		                        </h1>
		                    </div>


		                    <div class="col-md-8"> 
		                    	<div class="col-md-12">

                                    <div class="btn-group pull-right top-spacer-20">
                                        <a class="btn green btn-sm" href="assets/classes/controller.php?act=retrieveLeaveData&state=1"> Approved <i class="fa fa-check-square"></i></a>
                                        <a class="btn purple btn-sm" href="assets/classes/controller.php?act=retrieveLeaveData&state=2"> Pending Approval <i class="fa fa-refresh"></i></a>
                                        <a class="btn black btn-sm" href="assets/classes/controller.php?act=retrieveLeaveData&state=3"> Cancelled <i class="fa fa-times"></i></a>
                                        <a class="btn red btn-sm" href="assets/classes/controller.php?act=retrieveLeaveData&state=4"> Declined <i class="fa fa-exclamation-triangle"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE HEADER-->
                        

                        <!--Begin Page Content-->
                            
                            <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    
                                    <div class="portlet-body">
                                        <div class="table-toolbar">



                                            <!-- modal -->
                                            <div id="assignleave" class="modal fade" tabindex="-1" aria-hidden="true" data-width="600">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Assign Employee Leave</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action="assets/classes/controller.php?act=addNewLeave" class="horizontal-form">
                                                    <div class="row">
                                                        <div class="col-md-12">


                                                                            
                                                            <!-- BEGIN New Employee Popup-->
                                                            
                                                                <div class="form-body">
                                                                    
                                                                    <h4 class="form-section"><b>Assign New Leave</b></h4>

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Employee</label>
                                                                                <select required class="form-control" name="empnumber"> 
                                                                                    <option value="">- - Pick Employee - -</option>
                                                                                    <?php retrieveEmployees('employees', '*', 'active', '1', $_SESSION['companyid']); ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Type of Leave</label>
                                                                                <select required class="form-control" name="leavetype"> 
                                                                                    <option value="">- - Leave Type - -</option>
                                                                                    <?php retrieveLeaveTypes('hr_leave_types', '*', 'active', '1'); ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!--/row-->
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>From</label>
                                                                                <div class="input-group date" data-provide="datepicker">
                                                                                    <input type="text" required name="startleave" class="form-control">
                                                                                    <div class="input-group-addon">
                                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>To</label>
                                                                                <div class="input-group date" data-provide="datepicker">
                                                                                    <input type="text" required name="endleave" class="form-control">
                                                                                    <div class="input-group-addon">
                                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>

                                                                </div>
                                                            <!-- END New Employee Popup-->



                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                    <button type="submit" name="addemp" class="btn red">Add Leave</button>
                                                </div>

                                                </form>
                                            </div><!--End Modal-->



                                        </div> <!--End Action Bar-->

                                            <?php
                                                if (isset($_SESSION['msg'])) {
                                                    echo '<div class="alert alert-' . $_SESSION['alertcolor'] . ' alert-dismissable role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $_SESSION['msg'] . '</div>';
                                                    unset($_SESSION['msg']);
                                                    unset($_SESSION['alertcolor']);
                                                }
                                            ?>


                                        <div class="row top-spacer-20">     

                                            <div class="col-md-12">
                                                
                                                

                                                <table class="table table-striped table-bordered table-hover table-checkable order-column tblbtn" id="sample_1">
                                                    <thead>
                                                        <tr>
                                                            <th> Emp# </th>
                                                            <th> Employee </th>
                                                            <th> Leave Type </th>
                                                            <th> From </th>
                                                            <th> To </th>
                                                            <th> # Days</th>
                                                            <th> Days Left </th>
                                                            <?php
                                                                if (isset($_SESSION['leavestate']) && $_SESSION['leavestate'] == '2') {
                                                                    echo '<th> Actions </th>';
                                                                }
                                                            ?>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <!--Begin Data Table-->
                                                        <?php
                                                            //retrieveData('employment_types', 'id', '2', '1');


                                                            if (isset($_SESSION['leavestate'])) {
                                                                
                                                                try{
                                                                    $query = $conn->prepare('SELECT * FROM hr_leave_requests WHERE status = ?');
                                                                    $fin = $query->execute([$_SESSION['leavestate']]);
                                                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                    
                                                                    foreach ($res as $row => $link) {
                                                                        ?><tr class="odd gradeX"> 
                                                                            <?php
                                                                                $thisleavealterid = $link['id'];
                                                                                $thisemployeeNum = $link['employeeNumber'];

                                                                                echo '<td>' . $link['employeeNumber'] .  '</td>';

                                                                                echo '<td>'; 
                                                                                    retrieveDescSingleFilter('employees','fName','empNumber', $thisemployeeNum);
                                                                                    echo " ";
                                                                                    retrieveDescSingleFilter('employees','lName','empNumber', $thisemployeeNum);
                                                                                echo '</td>';


                                                                                echo '<td>'; 
                                                                                    retrieveDescSingleFilter('hr_leave_types','Leave_type','id', $link['leaveType']);
                                                                                echo '</td>';

                                                                                echo '<td>' . $link['fromDate'] . '</td>';

                                                                                echo '<td>' . $link['toDate'] . '</td>';

                                                                                echo '<td>' . $link['numberOfDays'] . '</td>';

                                                                                echo '<td>' . $link['numberOfDays'] . '</td>';

                                                                                    
                                                                                /**echo '<td> 
                                                                                    <a href="#viewleavedetails'.$thisleavealterid.'" class="btn btn-xs blue" data-toggle="modal" data-placement="top" title="View Employee Leave Details"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></a>&nbsp;';**/
                                                                                if ($_SESSION['leavestate'] == '2') {
                                                                                    echo '<td><a data-toggle="modal"  href="#manageleave'.$thisleavealterid.'" class="btn btn-xs purple" data-toggle="modal" data-placement="top" title="Manage Leave Request"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>';
                                                                                }
                                                                                echo '</td>';
                                                                            ?>

                                                                                <!-- Leave Modal -->
                                                                                    <div id="manageleave<?php echo $thisleavealterid;?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                            <h4 class="modal-title">Manage Leave</h4>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <form class="horizontal-form" method="post" action="assets/classes/controller.php?act=manageLeave">
                                                                                                <input type="hidden" value="<?php echo $thisemployeeNum;?>" name="empalternumber">
                                                                                                <input type="hidden" value="<?php echo $thisleavealterid;?>" name="empalterid">

                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="form-body">

                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    
                                                                                                                    <label>Employee Number: </label>
                                                                                                                    <label class="txt-ctr"> <b> <?php echo $thisemployeeNum; ?>  </b> </label>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    
                                                                                                                    <label>Employee Name: </label>
                                                                                                                    <label class="txt-ctr"> <b>
                                                                                                                        <?php 
                                                                                                                                retrieveDescSingleFilter('employees','fName','empNumber', $thisemployeeNum);
                                                                                                                                echo " ";
                                                                                                                                retrieveDescSingleFilter('employees','lName','empNumber', $thisemployeeNum);
                                                                                                                            ?>
                                                                                                                      </b> </label>
                                                                                                                </div>
                                                                                                            </div> 


                                                                                                            <div class="row top-spacer-20">
                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>Action</label>
                                                                                                                        <select required class="form-control" name="leaveaction">  
                                                                                                                            <option value="">- - - Select action to apply - - -</option>
                                                                                                                            <option value="1">Approve</option>
                                                                                                                            <option value="4">Reject</option>
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div> 


                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                    <button type="submit" class="btn red">Save</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                <!-- Leave Modal -->


                                                                            <?php
                                                                    }

                                                                }
                                                                catch(PDOException $e){
                                                                    echo $e->getMessage();
                                                                }

                                                                unset($_SESSION['leavestate']);

                                                            } else {
                                                                
                                                                
                                                                try{
                                                                    $_SESSION['leavestate'] = '1';

                                                                    $query = $conn->prepare('SELECT * FROM hr_leave_requests WHERE status = ?');
                                                                    $fin = $query->execute([$_SESSION['leavestate']]);
                                                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                    
                                                                    foreach ($res as $row => $link) {
                                                                        ?><tr class="odd gradeX"> 
                                                                            <?php
                                                                                $thisleavealterid = $link['id'];
                                                                                $thisemployeeNum = $link['employeeNumber'];
                                                                                
                                                                                echo '<td>' . $link['employeeNumber'] .  '</td>';

                                                                                echo '<td>'; 
                                                                                    retrieveDescSingleFilter('employees','fName','empNumber', $thisemployeeNum);
                                                                                    echo " ";
                                                                                    retrieveDescSingleFilter('employees','lName','empNumber', $thisemployeeNum);
                                                                                echo '</td>';


                                                                                echo '<td>'; 
                                                                                    retrieveDescSingleFilter('hr_leave_types','Leave_type','id', $link['leaveType']);
                                                                                echo '</td>';

                                                                                echo '<td>' . $link['fromDate'] . '</td>';

                                                                                echo '<td>' . $link['toDate'] . '</td>';

                                                                                echo '<td>' . $link['numberOfDays'] . '</td>';

                                                                                echo '<td>' . $link['numberOfDays'] . '</td>';
                                                                                    
                                                                                
                                                                            ?>


                                                                            <!-- Leave Modal -->
                                                                                <div id="cancelleave<?php echo $thisleavealterid;?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                        <h4 class="modal-title">Cancel Approved Leave</h4>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <form class="horizontal-form" method="post" action="assets/classes/controller.php?act=manageLeave">
                                                                                            <input type="hidden" value="<?php echo $thisemployeeNum;?>" name="empalternumber">
                                                                                            <input type="hidden" value="<?php echo $thisleavealterid;?>" name="empalterid">
                                                                                            
                                                                                            <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                    <div class="form-body">

                                                                                                        <div class="row">
                                                                                                            <div class="col-md-12">
                                                                                                                
                                                                                                                <label>Employee Number: </label>
                                                                                                                <label class="txt-ctr"> <b> <?php echo $thisemployeeNum; ?>  </b> </label>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="row">
                                                                                                            <div class="col-md-12">
                                                                                                                <label>Employee Name: </label>
                                                                                                                <label class="txt-ctr"> <b>
                                                                                                                    <?php 
                                                                                                                        retrieveDescSingleFilter('employees','fName','empNumber', $thisemployeeNum);
                                                                                                                        echo " ";
                                                                                                                        retrieveDescSingleFilter('employees','lName','empNumber', $thisemployeeNum);
                                                                                                                        ?>
                                                                                                                  </b> </label>
                                                                                                            </div>
                                                                                                        </div> 


                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                <button type="submit" class="btn red">Save</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            <!-- Leave Modal -->



                                                                            <?php
                                                                    }

                                                                }
                                                                catch(PDOException $e){
                                                                    echo $e->getMessage();
                                                                }


                                                            }

                                                            
                                                        ?>
                                                        <!--End Data Table-->


                                                    </tbody>
                                                </table>


                                            </div>

                                        </div>
                                        <!--end primary data row-->



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