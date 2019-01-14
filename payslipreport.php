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
                        <h1 class="page-title"> Payslip Report
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        


                        <!--Begin Page Content-->



                                <?php
                                    $query = $conn->prepare('SELECT empNumber FROM employees WHERE companyId = ? AND active =? ORDER BY id ASC');
                                    $query->execute([$_SESSION['companyid'], '1']);
                                    $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
                                    $count = $query->rowCount();
                                    $counter = 1;
                                    //print($count . "<br />");
                                    //print_r($ftres);
                                    $counter = 0;
                                    if ($_SESSION['emptrack'] >= $count) {
                                        $_SESSION['emptrack'] = 0;
                                    }
                                    $currentemp = $ftres[''.$_SESSION['emptrack'].''];
                                ?>


                            
                            <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button class="btn btn-sm btn-primary" type="button">
                                                      Payroll Period <span class="badge"><?php print $_SESSION['activeperiodDescription']?></span>
                                                    </button>
                                                    <button class="btn btn-sm purple" type="button">
                                                      Number of Employees <span class="badge"><?php print $count?></span>
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                        <button class="btn btn-sm red" id="btnPrint">Print <i class="fa fa-print" aria-hidden="true"></i></button>
                                                        <!--<button class="btn blue  btn-outline dropdown-toggle" data-toggle="dropdown">Tools
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
                                                        </ul>-->
                                                    </div>
                                                </div>
                                            </div>
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

                                        <?php
                                            while ($counter < $count) {
                                                //Print employee payslips
                                                $thisemployee = $ftres[''.$counter.''];
                                                //print_r($thisemployee);
                                            ?>

                                                <!-- START ROLL-->
                                                <div class="row bottom-spacer-40">
                                                    <div class="col-md-3"></div>
                                                    
                                                    <div class="col-md-6">
                                                        
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
                                                                    <div class="col-md-6 col-xs-6 txt-right">Period: <?php print $_SESSION['activeperiodDescription']?></div>
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
                                                                    <div class="col-md-6 col-xs-6">Employment: 
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
                                                    
                                                    <div class="col-md-3"></div>
                                                </div>
                                                <!-- END ROLL-->

                                            <?php
                                                $counter++;
                                                //end employee payslips
                                            }
                                        ?>
                                        
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