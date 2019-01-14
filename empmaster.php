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


    
                        <!-- BEGIN PAGE TITLE-->
                        <div class="row">
                        	<div class="col-md-3">
		                        <h1 class="page-title"> Employee Master
		                        </h1>
		                    </div>
		                    <div class="col-md-9">
		                    	<div class="col-md-12">

                                    <div class="btn-group pull-right top-spacer-20">
                                        <a class="btn red btn-sm" data-toggle="modal" href="#new-employee"><i class="fa fa-user-plus"></i> New Employee </a> 
                                        <a class="btn blue btn-sm" href="leave.php"><i class="fa fa-cogs"></i> Leave Management </a>    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        

                        <!--Begin Page Content-->
                            
                            <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <!--<div class="row">
                                                <div class="col-md-12">
                                                    <div class="btn-group pull-right">
                                                        <a class="btn red" data-toggle="modal" href="#new-employee"> Add New Employee <i class="fa fa-user-plus"></i></a>    
                                                    </div>
                                                </div>
                                            </div>-->


                                            <!-- modal -->
                                            <div id="new-employee" class="modal fade" tabindex="-1" aria-hidden="true" data-width="800">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Create New Employee</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action="assets/classes/controller.php?act=addNewEmp" class="horizontal-form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                                            
                                                            <!-- BEGIN New Employee Popup-->
                                                            
                                                                <div class="form-body">
                                                                    
                                                                    <h4 class="form-section"><b>Personal Details</b></h4>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>First Name</label>
                                                                                <input type="text" name="fname" class="form-control" required placeholder="First Name"> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Middle Name</label>
                                                                                <input type="text" name="lname" class="form-control" required placeholder="Middle Name"> </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Gender</label>
                                                                                <select required class="form-control" name="gender">  
                                                                                    <option value="1">Male</option>
                                                                                    <option value="2">Female</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!--/row-->
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Date of Birth</label> 
                                                                                <div class="input-group date" data-provide="datepicker">
                                                                                    <input type="text" required name="dob" class="form-control">
                                                                                    <div class="input-group-addon">
                                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Citizenship</label>
                                                                                <select name="citizenship" required class="form-control">  
                                                                                    <option value="1">Kenya</option>
                                                                                    <option value="2">Uganda</option>
                                                                                    <option value="3">Tanzania</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>ID / Passport Number</label>
                                                                                <input type="text" required name="idnumber" class="form-control" placeholder="ID / Passport Number"> </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                    
                                                                    
                                                                    <div class="row">
                                                                        
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>PIN Number</label>
                                                                                <input name="emppin" required type="text" class="form-control" placeholder="KRA PIN Number"> </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>NSSF Number</label>
                                                                                <input name="empnssf" type="text" class="form-control" placeholder="NSSF Number"> </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>NHIF Number</label>
                                                                                <input name="empnhif" type="text" class="form-control" placeholder="NHIF Number"> </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Bank</label>
                                                                                <select name="empbank" class="form-control">  
                                                                                    <option value="1">Barclays</option>
                                                                                    <option value="2">KCB</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Bank Branch</label>
                                                                                <select name="empbankbranch" class="form-control">  
                                                                                    <option value="1">Westlands</option>
                                                                                    <option value="2">Kangemi</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Account Number</label>
                                                                                <input name="empacctnum" type="text" class="form-control" placeholder="Bank Account Number"> </div>
                                                                        </div>
                                                                    </div>



                                                                    <h4 class="form-section"><b>Employment Details</b></h4>

                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Employee Number</label>
                                                                                <input type="text" name="empnumber" required class="form-control" placeholder="Employee Number"> </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Date of Employment</label>
                                                                                <div class="input-group date" data-provide="datepicker">
                                                                                    <input type="text" required name="employdate" class="form-control">
                                                                                    <div class="input-group-addon">
                                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Employment Type</label>
                                                                                <select required name="emptype" class="form-control">  
                                                                                    <option value="1">Permanent</option>
                                                                                    <option value="2">Temporary</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Department</label>
                                                                                <select name="empdept" class="form-control">  
                                                                                    <option value="1">- - - Select Department - - -</option>
                                                                                    <?php

                                                                                        $query = $conn->prepare('SELECT * FROM company_departments WHERE companyId = ? AND active = ?');
                                                                                        $res = $query->execute([$_SESSION['companyid'], '1']);
                                                                                        $out = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                                        
                                                                                        while ($row = array_shift($out)) {
                                                                                            echo('<option value="' . $row['departmentId'] .'">' .  $row['companyDescription'] . '</option>');
                                                                                        }

                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Branch</label>
                                                                                <select name="empcompbranch" class="form-control">
                                                                                    <option value="1">- - - Select Branch - - -</option>

                                                                                    <?php

                                                                                        $query = $conn->prepare('SELECT * FROM company_branches WHERE companyId = ? AND active = ?');
                                                                                        $res = $query->execute([$_SESSION['companyid'], '1']);
                                                                                        $out = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                                        
                                                                                        while ($row = array_shift($out)) {
                                                                                            echo('<option value="' . $row['branchId'] .'">' .  $row['branchName'] . '</option>');
                                                                                        }

                                                                                    ?>

                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Position</label>
                                                                                <select name="empposition" class="form-control">
                                                                                    <option value="1">- - - Select Position - - -</option>

                                                                                    <?php

                                                                                        $query = $conn->prepare('SELECT * FROM company_positions WHERE companyId = ? AND active = ?');
                                                                                        $res = $query->execute([$_SESSION['companyid'], '1']);
                                                                                        $out = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                                        
                                                                                        while ($row = array_shift($out)) {
                                                                                            echo('<option value="' . $row['id'] .'">' .  $row['positionDescription'] . '</option>');
                                                                                        }

                                                                                    ?>

                                                                                </select>
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
                                                    <button type="submit" name="addemp" class="btn red">Add Employee</button>
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
                                                            <th width="10"> </th>
                                                            <th> Emp# </th>
                                                            <th> Names </th>
                                                            <th> Type </th>
                                                            <th> Department </th>
                                                            <th> Branch </th>
                                                            <th> Actions </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <!--Begin Data Table-->
                                                        <?php
                                                            //retrieveData('employment_types', 'id', '2', '1');

                                                            try{
                                                                $query = $conn->prepare('SELECT * FROM employees WHERE companyId = ? AND active = ?');
                                                                $fin = $query->execute([$_SESSION['companyid'], '1']);
                                                                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                //sdsd
                                                                
                                                                foreach ($res as $row => $link) {
                                                                    ?><tr class="odd gradeX"> 
                                                                        <?php
                                                                            $thisemployeealterid = $link['id'];
                                                                            $thisemployeeNum = $link['empNumber'];
                                                                            echo '<td><input type="checkbox"></td><td>' . $link['empNumber'] .  '</td><td class="stylecaps">' . $link['fName'] . " " . $link['lName'] . '</td><td>';
                                                                                $typevar = $link['empType'];
                                                                                retrieveDescDualFilter('employment_types', 'description', $typevar, 'id', 'companyId', $_SESSION['companyid']);

                                                                            echo '</td><td>';
                                                                                $typevar = $link['companyDept'];
                                                                                retrieveDescDualFilter('company_departments', 'companyDescription', $typevar, 'departmentId', 'companyId', $_SESSION['companyid']);
                                                                                
                                                                            echo '</td><td>'; 
                                                                                $typevar = $link['companyBranch'];
                                                                                retrieveDescDualFilter('company_branches', 'branchName', $typevar, 'branchId', 'companyId', $_SESSION['companyid']);
                                                                                
                                                                            echo '</td><td> 
                                                                                <a href="#viewemp'.$thisemployeealterid.'" class="btn btn-xs blue" data-toggle="modal" data-placement="top" title="View employee details"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></a> 
                                                                                <!--<a href="" class="btn btn-xs green" data-toggle="tooltip" data-placement="top" title="Edit employee details"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>--> 
                                                                                <a data-toggle="modal" href="#suspend'.$thisemployeealterid.'" class="btn btn-xs yellow" data-placement="top" title="Suspend Employee"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></a>
                                                                                <a data-toggle="modal" href="#deactivate'.$thisemployeealterid.'" class="btn btn-xs red" data-toggle="modal" data-placement="top" title="Terminate employee"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                                                <a href="assets/classes/controller.php?act=vtrans&td='.$link['empNumber'].'" class="btn btn-xs purple" data-placement="top" title="View Transactions"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                                                                                <!--<a href="" class="btn btn-xs yellow" data-toggle="modal" data-placement="left" title="Go to employee Earnings / Deductions"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></a></td></tr>-->';
                                                                                ?>

                                                                                    <!-- Deactiv Modal -->
                                                                                    <div id="deactivate<?php echo $thisemployeealterid;?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                            <h4 class="modal-title">Deactivate Employee <?php echo $thisemployeeNum; ?> </h4>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <form class="horizontal-form" method="post" action="assets/classes/controller.php?act=deactivateEmployee">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="form-body">

                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    <input type="hidden" value="<?php echo $thisemployeealterid;?>" name="empalterid">
                                                                                                                    <input type="hidden" value="<?php echo $thisemployeeNum;?>" name="empalternumber">
                                                                                                                    
                                                                                                                    <label>Please confirm you would like to deactivate this employee?  
                                                                                                                        <b><?php 
                                                                                                                            //echo $thisemployeealterid;
                                                                                                                            retrieveDescSingleFilter('employees','fName','id', $thisemployeealterid);
                                                                                                                            echo " ";
                                                                                                                            retrieveDescSingleFilter('employees','lName','id', $thisemployeealterid);
                                                                                                                            echo " - " . $thisemployeeNum;
                                                                                                                        ?></b>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <p></p>


                                                                                                            <div class="row">
                                                                                                                <div class="col-md-6">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>Date of Exit</label>

                                                                                                                        <div class="input-group date" data-provide="datepicker">
                                                                                                                            <input type="text" required name="exitdate" class="form-control">
                                                                                                                            <div class="input-group-addon">
                                                                                                                                <span class="glyphicon glyphicon-th"></span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>Reason for Exit</label>
                                                                                                                        <textarea class="form-control" rows="3" name="exitreason" placeholder="Enter reason for exit"></textarea>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>


                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                    <button type="submit" class="btn red">Deactivate Employee</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- Deactiv Modal -->


                                                                                    <!-- Deactiv Modal -->
                                                                                    <div id="suspend<?php echo $thisemployeealterid;?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                            <h4 class="modal-title">Suspend Employee <?php echo $thisemployeeNum; ?> </h4>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <form class="horizontal-form" method="post" action="assets/classes/controller.php?act=suspendEmployee">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="form-body">

                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    <input type="hidden" value="<?php echo $thisemployeealterid;?>" name="empalterid">
                                                                                                                    <input type="hidden" value="<?php echo $thisemployeeNum;?>" name="empalternumber">
                                                                                                                    
                                                                                                                    <label>Please confirm you would like to suspend this employee?  
                                                                                                                        <b><?php 
                                                                                                                            //echo $thisemployeealterid;
                                                                                                                            retrieveDescSingleFilter('employees','fName','id', $thisemployeealterid);
                                                                                                                            echo " ";
                                                                                                                            retrieveDescSingleFilter('employees','lName','id', $thisemployeealterid);
                                                                                                                            echo " - " . $thisemployeeNum;
                                                                                                                        ?></b>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <p></p>


                                                                                                            <div class="row">
                                                                                                                <div class="col-md-6">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>Start Date</label>

                                                                                                                        <div class="input-group date" data-provide="datepicker">
                                                                                                                            <input type="text" required name="startsuspension" class="form-control">
                                                                                                                            <div class="input-group-addon">
                                                                                                                                <span class="glyphicon glyphicon-th"></span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-6">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>End Date</label>

                                                                                                                        <div class="input-group date" data-provide="datepicker">
                                                                                                                            <input type="text" required name="endsuspension" class="form-control">
                                                                                                                            <div class="input-group-addon">
                                                                                                                                <span class="glyphicon glyphicon-th"></span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>Suspension Details</label>
                                                                                                                        <textarea class="form-control" rows="3" required name="suspendreason" placeholder="Enter reason for exit"></textarea>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>


                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                    <button type="submit" class="btn red">Deactivate Employee</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- Deactiv Modal -->


                                                                                    <!-- View Emp Modal -->
                                                                                    <div id="viewemp<?php echo $thisemployeealterid;?>" class="modal fade" tabindex="-1" data-width="650">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                            <h4 class="modal-title">Employee Details </h4>
                                                                                        </div>

                                                                                    <?php
                                                                                        $query = $conn->prepare('SELECT * FROM employees WHERE id = ? AND active = ?');
                                                                                        $fin = $query->execute([$thisemployeealterid, '1']);
                                                                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                                        //sdsd
                                                                                        
                                                                                        foreach ($res as $row => $empd) {
                                                                                            ?>
                                                                                                <div class="modal-body">
                                                                                                    <form class="horizontal-form">
                                                                                                        <div class="row">
                                                                                                            <div class="col-md-12">
                                                                                                                <div class="form-body">

                                                                                                                    
                                                                                                                    <h4 class="form-section"><b>Personal Details</b></h4>
                                                                                                                    <div class="row">
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>Employee Number</label>
                                                                                                                                <input type="text" disabled name="empnumber" class="form-control" value="<?php echo $empd['empNumber'];?>"> </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>Employment Type</label>
                                                                                                                                <input name="emptype" disabled type="text" class="form-control" value="<?php echo $empd['empType'];?>">
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                    <div class="row">
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>First Name</label>
                                                                                                                                <input type="text" disabled name="fname" class="form-control"  value="<?php echo $empd['fName'];?>"> </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>Last Name</label>
                                                                                                                                <input type="text" disabled name="lname" class="form-control"  value="<?php echo $empd['lName'];?>"> </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="row">
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>Gender</label>
                                                                                                                                <input type="text" disabled name="gender" class="form-control"  value="<?php
                                                                                                                                    if ($empd['gender'] == 1) { echo "Male";} else echo "Female"; ?>"> </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>ID / Passport Number</label>
                                                                                                                                <input type="text" disabled name="idnumber" class="form-control"  value="<?php echo $empd['idNumber'];?>"> </div>
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                    <div class="row">
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>Department</label>
                                                                                                                                <input name="empacctnum" disabled type="text" class="form-control" value="<?php echo $empd['empBankBranch'];?>">
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>Branch</label>
                                                                                                                                <input name="empacctnum" disabled type="text" class="form-control" value="<?php echo $empd['empBankBranch'];?>">
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                    <h4 class="form-section"><b>Other Details</b></h4>
                                                                                                                    
                                                                                                                    <div class="row">
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>PIN Number</label>
                                                                                                                                <input name="emppin" disabled type="text" class="form-control" value="<?php echo $empd['empTaxPin'];?>"> </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>NSSF Number</label>
                                                                                                                                <input name="empnssf" disabled type="text" class="form-control"  value="<?php echo $empd['empNssf'];?>"> </div>
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                    <div class="row">
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>NHIF Number</label>
                                                                                                                                <input name="empnhif" disabled type="text" class="form-control" value="<?php echo $empd['empNhif'];?>"> </div>
                                                                                                                        </div>
                                                                                                                    </div>


                                                                                                                    


                                                                                                                        
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" data-dismiss="modal" class="btn btn-primary dark">Close</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            <?php
                                                                                        }
                                                                                    ?>


                                                                                        
                                                                                    </div>
                                                                                    <!-- View Emp Modal -->

                                                                                <?php
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