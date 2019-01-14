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

                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <div class="row payperiod">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b>Current Payroll Period: </b></label>
                                        <div class="col-md-8">
                                            <?php echo $_SESSION['activeperiodDescription'];?> &nbsp; <span class="label label-inverse label-sm label-success"> Open </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE BAR -->


  
                        <!-- BEGIN PAGE TITLE-->
                        <div class="row bottom-spacer-20">
                                                     
                                <?php
                                    if ($_SESSION['empDataTrack'] == 'next') {
                                        $query = $conn->prepare('SELECT empNumber FROM employees WHERE companyId = ? AND active =? ORDER BY id ASC');
                                        $query->execute([$_SESSION['companyid'], '1']);
                                        $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
                                        $count = $query->rowCount();
                                        //print($count . "<br />");
                                        //print_r($ftres);
                                        $counter = 0;
                                        if ($_SESSION['emptrack'] >= $count) {
                                            $_SESSION['emptrack'] = 0;
                                        }
                                        $currentemp = $ftres[''.$_SESSION['emptrack'].''];  
                                    } elseif ($_SESSION['empDataTrack'] == 'option') {
                                        $currentemp = $_SESSION['emptNumTack'];
                                    }
                                                                      
                                ?>


                            <div class="col-md-6 top-spacer-20">
                                <?php
                                    $query = $conn->prepare('SELECT fName, lName FROM employees WHERE empNumber = ?');
                                    $query->execute([$currentemp]);
                                    if ($row = $query->fetch()) {
                                        $empfname = $row['fName'];
                                        $emplname = $row['lName'];
                                    }
                                ?>
                                <div class="empname"> <span class="empnumbersize"> Emp # <?php echo $currentemp . ' - '; ?></span> <span class="empnamesize"> <?php echo $empfname . " " . $emplname;?> </span></div>
                            </div>


		                    <div class="col-md-3 top-spacer-20">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="assets/classes/controller.php?act=retrieveSingleEmployeeData">
                                            <div class="input-group">
                                                <div class="input-icon">
                                                    <i class="fa fa-user fa-fw"></i>
                                                    <select required class="form-control" name="empearnings">
                                                        <option value="">- - Pick Employee - -</option>
                                                        <?php retrieveEmployees('employees', '*', 'active', '1', $_SESSION['companyid']); ?>
                                                    </select></div>
                                                <span class="input-group-btn">
                                                    <button type="submit" id="retrieveemp" class="btn btn-success" >
                                                         Go</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 top-spacer-20">
                                <div class="pull-right">
                                    <!--<a href="# ?>" class="btn red"><i class="fa fa-angle-double-left fa-lg" aria-hidden="true"></i> Previous Employee  </a>-->
                                    <a href="assets/classes/controller.php?act=getNextEmployee&track=<?php echo $_SESSION['emptrack'] + 1; ?>" class="btn blue">Next Employee <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i> </a>
                                    <?php
                                        //print($_SESSION['emptrack']);
                                    ?>
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


                                        <!-- Start Modal -->
                                            
                                            <div id="newemployeeearning" class="modal fade" tabindex="-1" data-width="560">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Add New Earning for this Employee</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=addemployeeearning">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-body">
                                                                    <input type="hidden" name="curremployee" value="<?php echo $currentemp;?>">
                                                                    <input type="hidden" name="edtype" value="Earning">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label">Description</label>
                                                                        <div class="col-md-7">
                                                                            <select required class="form-control" name="newearningcode">
                                                                                <option>- - Select Earning - -</option>
                                                                                <?php retrieveSelect('earnings_deductions', '*', 'edType', 'Earning', 'edCode'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label">Amount</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" required class="form-control" name="earningamount" placeholder="Amount">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                        <button type="submit" class="btn red">Add</button>
                                                    </div>
                                                </form>
                                            </div>


                                            <div id="newemployeededuction" class="modal fade" tabindex="-1" data-width="560">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title danger">Add New Deduction for this Employee</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=addemployeededuction">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-body">
                                                                    <input type="hidden" name="curremployee" value="<?php echo $currentemp;?>">
                                                                    <input type="hidden" name="edtype" value="Deduction">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label">Description</label>
                                                                        <div class="col-md-7">
                                                                            <select required class="form-control" name="newdeductioncode">
                                                                                <option value="">- - Select Deduction - -</option>
                                                                                <?php retrieveSelect('earnings_deductions', '*', 'edType', 'Deduction', 'edCode'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label">Amount</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" required class="form-control" name="deductionamount" placeholder="Amount">
                                                                        </div>
                                                                    </div>
                                                                        
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                        <button type="submit" class="btn red">Add</button>
                                                    </div>
                                                </form>
                                            </div>


                                        
                                            <!--Printer-->
                                                <script type='text/javascript'>//<![CDATA[
                                                window.onload=function(){
                                                jQuery.fn.extend({
                                                    printElem: function() {
                                                        var cloned = this.clone();
                                                    var printSection = $('#printSection');
                                                    if (printSection.length == 0) {
                                                        printSection = $('<div id="printSection"></div>')
                                                        $('body').append(printSection);
                                                    }
                                                    printSection.append(cloned);
                                                    var toggleBody = $('body *:visible');
                                                    toggleBody.hide();
                                                    $('#printSection, #printSection *').show();
                                                    window.print();
                                                    printSection.remove();
                                                    toggleBody.show();
                                                    }
                                                });

                                                $(document).ready(function(){
                                                    $(document).on('click', '#btnPrint', function(){
                                                    $('.printMe').printElem();
                                                  });
                                                });
                                                }//]]> 

                                                </script>
                                            <!--Printer-->


                                            <div id="viewemployeepayslip" class="modal fade" tabindex="-1" data-width="660">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Employee Payslip</h4>
                                                </div>
                                                <div class="modal-body"><?php $thisemployee = $currentemp;?>


                                                	<!-- START ROLL-->
	                                                <div class="row bottom-spacer-40">
	                                                    <div class="col-md-1"></div>
	                                                    
	                                                    <div class="col-md-10">
	                                                        
	                                                        <div id="printThis" class="printMe payslip-wrapper">
	                                                            <div class="payslip-header">
	                                                                <div class="row header-label">
	                                                                    <div class="col-md-12 txt-ctr text-uppercase"><b>
	                                                                        <?php
	                                                                            retrieveDescSingleFilter ('company', 'companyName', 'id', $_SESSION['companyid']);
	                                                                        ?>
	                                                                    </b></div>
	                                                                    <div class="col-md-12 txt-ctr text-uppercase">
	                                                                        <b> Employee PAYSLIP  </b></div>
	                                                                </div>
	                                                                <div class="row header-label">
	                                                                    <div class="col-md-6 col-xs-6">
	                                                                        <span class="pay-header-item">Name: 
	                                                                            <?php 
	                                                                                retrieveDescSingleFilter('employees', 'fName', 'empNumber', $thisemployee);
	                                                                                echo " ";
	                                                                                retrieveDescSingleFilter('employees', 'lName', 'empNumber', $thisemployee);
	                                                                            ?>
	                                                                            
	                                                                        </span> 
	                                                                    </div>
	                                                                    <div class="col-md-6 col-xs-6 txt-right">Pay Period: <?php print $_SESSION['activeperiodDescription']?></div>
	                                                                </div>
	                                                                <div class="row header-label">
	                                                                    <div class="col-md-6 col-xs-6">Emp #: <?php print_r($thisemployee);?> </div>
	                                                                    <div class="col-md-6 col-xs-6 txt-right">
	                                                                        Title: 
	                                                                        <?php
	                                                                            $mainFilter = &returnDescSingleFilter('employees', 'companyDept', 'empNumber', $thisemployee);
	                                                                            retrieveDescDualFilter('company_departments', 'companyDescription', $mainFilter, 'departmentId', 'companyId', $_SESSION['companyid']);
	                                                                        ?>
	                                                                    </div>
	                                                                </div>
	                                                                <div class="row header-label">
	                                                                    <div class="col-md-6 col-xs-6">Type: 
	                                                                        <?php
	                                                                            $mainFilter = &returnDescSingleFilter('employees', 'empType', 'empNumber', $thisemployee);
	                                                                            retrieveDescDualFilter('employment_types', 'description', $mainFilter, 'id', 'companyId', $_SESSION['companyid']);
	                                                                        ?>
	                                                                    </div>
	                                                                    <div class="col-md-6 col-xs-6 txt-right">Location:
	                                                                        <?php
	                                                                            $mainFilter = &returnDescSingleFilter('employees', 'companyBranch', 'empNumber', $thisemployee);
	                                                                            retrieveDescDualFilter('company_branches', 'branchName', $mainFilter, 'branchId', 'companyId', $_SESSION['companyid']);
	                                                                        ?>
	                                                                    </div>
	                                                                </div>
	                                                            </div>

	                                                            <div class="payslip-body">
	                                                                <div class="row header-label">
	                                                                    <div class="col-md-12 col-xs-12"><b>Earnings</b></div>
	                                                                </div>
	                                                                

	                                                                <div class="row payslip-data">
	                                                                    <?php
	                                                                        try{
	                                                                            $query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ORDER BY earningDeductionCode');
	                                                                            $fin = $query->execute([$thisemployee, $_SESSION['companyid'], 'Earning', $_SESSION['currentactiveperiod'], '1']);
	                                                                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
	                                                                            //print_r($res);

	                                                                            foreach ($res as $row => $link) {

	                                                                                //Get ED description
	                                                                                $payp = $conn->prepare('SELECT edDesc FROM earnings_deductions WHERE companyId = ? AND edCode = ?');
	                                                                                $myperiod = $payp->execute([$_SESSION['companyid'], $link['earningDeductionCode']]);
	                                                                                $final = $payp->fetch();
	                                                                                //End ED Fetch
	                                                                                    
	                                                                                echo '<div class="col-md-8 col-xs-8">' . $final['edDesc'];
	                                                                                    
	                                                                                echo '</div><div class="col-md-4 col-xs-4 payslip-amount">' . number_format($link['amount']) . '</div>';
	                                                                            }

	                                                                        }
	                                                                        catch(PDOException $e){
	                                                                            echo $e->getMessage();
	                                                                        }
	                                                                    ?>
	                                                                </div>

	                                                                <div class="row payslip-total">
	                                                                    <div class="col-md-8 col-xs-8"><b>Gross Salary</b></div>
	                                                                    <div class="col-md-4 col-xs-4 payslip-amount"><b>
	                                                                        <?php
	                                                                            retrievePayrollSubTotal('amount', 'employee_earnings_deductions', 'employeeId', 'earningDeductionCode', 'payperiod', 'active', $thisemployee, '601');
	                                                                        ?>
	                                                                    </b></div>
	                                                                </div>
	                                                            </div>



	                                                            <div class="payslip-body">
	                                                                <div class="row header-label">
	                                                                    <div class="col-md-12 col-xs-12"><b>Deductions</b></div>
	                                                                </div>
	                                                                <div class="row payslip-data">
	                                                                    <?php
	                                                                    try{
	                                                                        $query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND earningDeductionCode != ? AND payPeriod = ? AND active = ? ORDER BY earningDeductionCode');
	                                                                        $fin = $query->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '483', $_SESSION['currentactiveperiod'], '1']);
	                                                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
	                                                                        
	                                                                        
	                                                                        foreach ($res as $row => $link) {
	                                                                                        
	                                                                            //Get ED description
	                                                                            $payp = $conn->prepare('SELECT edDesc FROM earnings_deductions WHERE companyId = ? AND edCode = ?');
	                                                                            $myperiod = $payp->execute([$_SESSION['companyid'], $link['earningDeductionCode']]);
	                                                                            $final = $payp->fetch();
	                                                                            //End ED Fetch

	                                                                            echo '<div class="col-md-8 col-xs-8">' . $final['edDesc'];
	                                                                                
	                                                                            echo '</div><div class="col-md-4 col-xs-4 payslip-amount">' . number_format($link['amount']) . '</div>';
	                                                                        }

	                                                                    }
	                                                                    catch(PDOException $e){
	                                                                        echo $e->getMessage();
	                                                                    }
	                                                                ?>
	                                                                    

	                                                                </div>



	                                                                <div class="row payslip-total">
	                                                                    <div class="col-md-8 col-xs-8"><b>Total Deductions</b></div>
	                                                                    <div class="col-md-4 col-xs-4 payslip-amount"><b>
	                                                                        <?php
	                                                                            retrievePayrollSubTotal('amount', 'employee_earnings_deductions', 'employeeId', 'earningDeductionCode', 'payperiod', 'active', $thisemployee, '603');
	                                                                        ?>
	                                                                    </b></div>
	                                                                </div>
	                                                            </div>


	                                                            <div class="payslip-body">
	                                                                
	                                                                <div class="row payslip-data">
	                                                                    <div class="col-md-8 col-xs-8">Tax Relief</div>
	                                                                    <div class="col-md-4 col-xs-4 payslip-amount">1,280</div>
	                                                                </div>
	                                                                <div class="row payslip-total">
	                                                                    <div class="col-md-8 col-xs-8"><b>Net Pay</b></div>
	                                                                    <div class="col-md-4 col-xs-4 payslip-amount"><b>
	                                                                        <?php
	                                                                            retrievePayrollSubTotal('amount', 'employee_earnings_deductions', 'employeeId', 'earningDeductionCode', 'payperiod', 'active', $thisemployee, '600');
	                                                                        ?>
	                                                                    </b></div>
	                                                                </div>
	                                                            </div>

	                                                        </div>

	                                                    </div>
	                                                    
	                                                    <div class="col-md-1"></div>
	                                                </div>
	                                                <!-- END ROLL-->

                                                    <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                        <button class="btn red" id="btnPrint">Print <i class="fa fa-print" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                              // tell the embed parent frame the height of the content
                                              if (window.parent && window.parent.parent){
                                                window.parent.parent.postMessage(["resultsFrame", {
                                                  height: document.body.getBoundingClientRect().height,
                                                  slug: "95ezN"
                                                }], "*")
                                              }
                                            </script>
                                        <!--End Modal-->



                                        <div class="row">

                                            <div class="col-md-9">

                                                <?php
                                                    if (isset($_SESSION['msg'])) {
                                                        echo '<div class="alert alert-' . $_SESSION['alertcolor'] . ' alert-dismissable role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $_SESSION['msg'] . '</div>';
                                                        unset($_SESSION['msg']);
                                                        unset($_SESSION['alertcolor']);
                                                    }
                                                ?>
                                                
                                                <!--Begin Earnings/Ded table-->
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr class="earnings-ded-header">
                                                                <th> Code </th>
                                                                <th> Description </th>
                                                                <th> Amount </th>
                                                                <th width="110"> </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="earnings-row">
                                                                <td> <strong>Earnings</strong></td>
                                                                <td> </td>
                                                                <td>   </td>
                                                                <td>   </td>
                                                            </tr>

                                                            <!--New Earning-->
                                                            <?php
                                                                try{
                                                                    $query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ORDER BY earningDeductionCode');
                                                                    $fin = $query->execute([$currentemp, $_SESSION['companyid'], 'Earning', $_SESSION['currentactiveperiod'], '1']);
                                                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                    //print_r($res);

                                                                    foreach ($res as $row => $link) {
                                                                        ?><tr class="odd gradeX"> 
                                                                            <?php 

                                                                                echo '<td>' . $link['earningDeductionCode'];

                                                                                    //Get ED description
                                                                                    $payp = $conn->prepare('SELECT edDesc FROM earnings_deductions WHERE companyId = ? AND edCode = ?');
                                                                                    $myperiod = $payp->execute([$_SESSION['companyid'], $link['earningDeductionCode']]);
                                                                                    $final = $payp->fetch();
                                                                                    //End ED Fetch
                                                                                    
                                                                                echo '</td><td>' . $final['edDesc'];
                                                                                    
                                                                                echo '</td><td class="align-right">' . number_format($link['amount']) . '</td>';

                                                                                    if (isset($_SESSION['periodstatuschange']) && $_SESSION['periodstatuschange'] == '1') {
                                                                                        echo '<td></td>';
                                                                                    } else {
                                                                                        echo '<td><a href="#editearning'. $link['earningDeductionCode'].'" data-toggle="modal" class="btn btn-zs yellow"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a href="#deleteed'. $link['earningDeductionCode'].'" data-toggle="modal" class="btn btn-zs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';
                                                                                    }
                                                                                ;
                                                                                ?>


                                                                                    <!--Employee Earning Edits-->
                                                                                        <div id="editearning<?php echo $link['earningDeductionCode'];?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                            <div class="modal-header">
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                <h4 class="modal-title">Edit Employee Earning</h4>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=editemployeeearning">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-body">
                                                                                                                <input type="hidden" value="<?php echo $currentemp;?>" name="empeditnum">
                                                                                                                <input type="hidden" value="<?php echo $link['earningDeductionCode'];?>" name="edited">
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Description</label>
                                                                                                                    <div class="col-md-7">                                                                                                                        
                                                                                                                        <input type="text" required class="form-control" name="editname" disabled="" value="<?php retrieveDescSingleFilter('earnings_deductions','edDesc','edCode', $link['earningDeductionCode']); ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Amount </label>
                                                                                                                    <div class="col-md-7">
                                                                                                                        <input type="text" required class="form-control" name="editvalue" value="<?php echo $link['amount']; ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                    <button type="submit" class="btn red">Edit Earning</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>

                                                                                    <!--Delete ED-->
                                                                                        <div id="deleteed<?php echo $link['earningDeductionCode'];?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                            <div class="modal-header">
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                <h4 class="modal-title">Delete Employee Earning / Deduction</h4>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=deactivateEd">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-body">
                                                                                                                <input type="hidden" value="<?php echo $currentemp;?>" name="empeditnum">
                                                                                                                <input type="hidden" value="<?php echo $link['earningDeductionCode'];?>" name="edited">
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Description</label>
                                                                                                                    <div class="col-md-7">                                                                                                                        
                                                                                                                        <input type="text" required class="form-control" name="editname" disabled="" value="<?php retrieveDescSingleFilter('earnings_deductions','edDesc','edCode', $link['earningDeductionCode']); ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Amount </label>
                                                                                                                    <div class="col-md-7">
                                                                                                                        <input type="text" required class="form-control" name="editvalue" disabled value="<?php echo $link['amount']; ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                    <button type="submit" class="btn red">Delete E/D</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>



                                                                                <?php
                                                                    }

                                                                }
                                                                catch(PDOException $e){
                                                                    echo $e->getMessage();
                                                                }
                                                            ?>




                                                            <tr class="lighter-row">
                                                                <th> 601 </th>
                                                                <th> Gross Salary </th>
                                                                <th class="align-right">
                                                                    <?php
                                                                        retrievePayrollSubTotal('amount', 'employee_earnings_deductions', 'employeeId', 'earningDeductionCode', 'payperiod', 'active', $currentemp, '601');
                                                                    ?>
                                                                </th>
                                                                <th></th>
                                                                <!--Write Function to run query and output based on one record-->
                                                            </tr>


                                                            <!--Computing Taxable income-->
                                                            <?php
                                                                try{
                                                                    $query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ? ORDER BY earningDeductionCode');
                                                                    $fin = $query->execute([$currentemp, $_SESSION['companyid'], '482', $_SESSION['currentactiveperiod'], '1']);
                                                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                    //print_r($res);

                                                                    foreach ($res as $row => $link) {
                                                                        ?><tr class="odd gradeX"> 
                                                                            <?php 

                                                                                echo '<td>' . $link['earningDeductionCode'];

                                                                                    //Get ED description
                                                                                    $payp = $conn->prepare('SELECT edDesc FROM earnings_deductions WHERE companyId = ? AND edCode = ?');
                                                                                    $myperiod = $payp->execute([$_SESSION['companyid'], $link['earningDeductionCode']]);
                                                                                    $final = $payp->fetch();
                                                                                    //End ED Fetch
                                                                                    
                                                                                echo '</td><td>' . $final['edDesc'];
                                                                                    
                                                                                echo '</td><td class="align-right">' . number_format($link['amount']) . '</td>';
                                                                                echo '<td></td>';
                                                                    }

                                                                }
                                                                catch(PDOException $e){
                                                                    echo $e->getMessage();
                                                                }
                                                            ?>


                                                            <tr class="lighter-row">
                                                                <td> <strong>400</strong></td>
                                                                <th> Taxable Pay</th>
                                                                <th class="align-right">
                                                                    <?php
                                                                        retrievePayrollSubTotal('amount', 'employee_earnings_deductions', 'employeeId', 'earningDeductionCode', 'payperiod', 'active', $currentemp, '400');
                                                                    ?>
                                                                </th>
                                                                <th></th>
                                                            </tr>



                                                            <tr class="earnings-row">
                                                                <td> <strong>Deductions</strong></td>
                                                                <td> </td>
                                                                <td class="align-right">   </td>
                                                                <td></td>
                                                            </tr>
                                                            <?php
                                                                try{
                                                                    $query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND earningDeductionCode != ? AND earningDeductionCode != ? AND payPeriod = ? AND active = ? ORDER BY earningDeductionCode');
                                                                    $fin = $query->execute([$currentemp, $_SESSION['companyid'], 'Deduction', '482', '483', $_SESSION['currentactiveperiod'], '1']);
                                                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                                                    
                                                                    
                                                                    foreach ($res as $row => $link) {
                                                                        ?><tr class="odd gradeX"> 
                                                                            <?php 

                                                                                echo '<td>' . $link['earningDeductionCode'];
                                                                                    
                                                                                    //Get ED description
                                                                                    $payp = $conn->prepare('SELECT edDesc FROM earnings_deductions WHERE companyId = ? AND edCode = ?');
                                                                                    $myperiod = $payp->execute([$_SESSION['companyid'], $link['earningDeductionCode']]);
                                                                                    $final = $payp->fetch();
                                                                                    //End ED Fetch

                                                                                echo '</td><td>' . $final['edDesc'];
                                                                                    
                                                                                echo '</td><td class="align-right">' . number_format($link['amount']) . '</td>';
                                                                                    $edquery = $conn->prepare('SELECT * FROM earnings_deductions WHERE edCode = ? AND companyId = ? AND globalComputed = ?');
                                                                                    $result = $edquery->execute([$link['earningDeductionCode'], $_SESSION['companyid'], '0']);
                                                                                    if ($row = $edquery->fetch()) {

                                                                                        if (isset($_SESSION['periodstatuschange']) && $_SESSION['periodstatuschange'] == '1') {
                                                                                            echo '<td></td>';
                                                                                        } else {
                                                                                            echo '<td><a href="#editearning'. $link['earningDeductionCode'].'" data-toggle="modal" class="btn btn-zs yellow"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a href="#deleteed'. $link['earningDeductionCode'].'" data-toggle="modal" class="btn btn-zs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';
                                                                                        }
                                                                                        
                                                                                    } else echo '<td></td>';

                                                                                ?>


                                                                                <!--Employee Earning Edits-->
                                                                                        <div id="editearning<?php echo $link['earningDeductionCode'];?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                            <div class="modal-header">
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                <h4 class="modal-title">Edit Employee Deduction</h4>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=editemployeeearning">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-body">
                                                                                                                <input type="hidden" value="<?php echo $currentemp;?>" name="empeditnum">
                                                                                                                <input type="hidden" value="<?php echo $link['earningDeductionCode'];?>" name="edited">
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Description</label>
                                                                                                                    <div class="col-md-7">                                                                                                                        
                                                                                                                        <input type="text" required class="form-control" name="editname" disabled="" value="<?php retrieveDescSingleFilter('earnings_deductions','edDesc','edCode', $link['earningDeductionCode']); ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Amount </label>
                                                                                                                    <div class="col-md-7">
                                                                                                                        <input type="text" required class="form-control" name="editvalue" value="<?php echo $link['amount']; ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                    <button type="submit" class="btn red">Edit Earning</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>

                                                                                        <!--Delete ED-->
                                                                                        <div id="deleteed<?php echo $link['earningDeductionCode'];?>" class="modal fade" tabindex="-1" data-width="560">
                                                                                            <div class="modal-header">
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                <h4 class="modal-title">Delete Employee Earning / Deduction</h4>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=deactivateEd">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-body">
                                                                                                                <input type="hidden" value="<?php echo $currentemp;?>" name="empeditnum">
                                                                                                                <input type="hidden" value="<?php echo $link['earningDeductionCode'];?>" name="edited">
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Description</label>
                                                                                                                    <div class="col-md-7">                                                                                                                        
                                                                                                                        <input type="text" required class="form-control" name="editname" disabled="" value="<?php retrieveDescSingleFilter('earnings_deductions','edDesc','edCode', $link['earningDeductionCode']); ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Amount </label>
                                                                                                                    <div class="col-md-7">
                                                                                                                        <input type="text" required class="form-control" name="editvalue" disabled value="<?php echo $link['amount']; ?>">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                                    <button type="submit" class="btn red">Delete E/D</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>


                                                                                <?php
                                                                                
                                                                    }

                                                                }
                                                                catch(PDOException $e){
                                                                    echo $e->getMessage();
                                                                }
                                                            ?>

                                                            <tr>
                                                                <td> 401 </td>
                                                                <td> ( Tax Relief ) </td>
                                                                <td class="align-right"> (1,280) </td>
                                                                <td></td>
                                                            </tr>


                                                            <tr class="lighter-row">
                                                                <th> 603 </th>
                                                                <th> Total Deductions  </th>
                                                                <th class="align-right"> 
                                                                    <?php
                                                                        retrievePayrollSubTotal('amount', 'employee_earnings_deductions', 'employeeId', 'earningDeductionCode', 'payperiod', 'active', $currentemp, '603');
                                                                    ?>
                                                                </th>
                                                                <th></th>
                                                            </tr>


                                                            <tr class="earnings-row">
                                                                <th> Net Pay </th>
                                                                <th>  </th>
                                                                <th class="align-right"> 
                                                                    <?php
                                                                        retrievePayrollSubTotal('amount', 'employee_earnings_deductions', 'employeeId', 'earningDeductionCode', 'payperiod', 'active', $currentemp, '600');
                                                                    ?>
                                                                </th>
                                                                <th></th>
                                                            </tr>


                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--End Earnings/Ded table-->


                                            </div>

                                            <div class="col-md-3">

                                                    <?php
                                                        if (isset($_SESSION['periodstatuschange']) && $_SESSION['periodstatuschange'] == '1') {
                                                         ?>
                                                            <a class="btn blue btn-block top-spacer-5" data-toggle="modal"  href="#viewemployeepayslip"> View Employee Payslip <i class="fa fa-file-text" aria-hidden="true"></i></a>
                                                         <?php
                                                        } else {
                                                        ?>
                                                            <a class="btn green btn-block" data-toggle="modal"  href="#newemployeeearning"> Add Earning <i class="fa fa-plus-square"></i></a>
                                                            <a class="btn red btn-block" data-toggle="modal"  href="#newemployeededuction"> Add Deduction <i class="fa fa-minus-square"></i></a>

                                                            <form method="post" action="assets/classes/controller.php?act=runCurrentEmployeePayroll">
                                                                <input type="hidden" name="thisemployee" value="<?php echo $currentemp; ?>">
                                                                <button class="btn btn-block black top-spacer-5">Run this Employee's Payroll <i class="fa fa-refresh" aria-hidden="true"></i></button>
                                                            </form>

                                                            <a class="btn blue btn-block top-spacer-5" data-toggle="modal"  href="#viewemployeepayslip"> View Employee Payslip <i class="fa fa-file-text" aria-hidden="true"></i></a>
                                                        <?php
                                                        }
                                                    ?>
                                                
                                                    
                                                
                                            </div>

                                        </div>
                                        

                                        <!--<div class="row top-spacer-40">
                                            <div class="col-md-12 profile-info">
                                                <div class="col-md-12 top-spacer-20">
                                                    <form method="post" action="assets/classes/controller.php?act=runCurrentEmployeePayroll">
                                                        <div class="btn-group pull-right">
                                                            <input type="hidden" name="thisemployee" value="<?php //echo $currentemp; ?>">
                                                            <button type="submit" class="btn black" > Run Current Employee's Payroll <i class="fa fa-refresh" aria-hidden="true"></i></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>-->
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
                <div class="page-footer-inner"> 2016 &copy; Redsphere Consulting v0.8 v0.8
                    
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        
        

    <?php include_once('assets/includes/footer.php');?>