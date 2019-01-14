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
                        <h1 class="page-title"> Payroll Processing </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        

                        <!--Begin Page Content-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-gift"></i>Run Final Payroll Processing Sequence </div>

                                            <div class="tools">
                                                <!--<a href="javascript:;" class="reload"> </a>
                                                <a href="javascript:;" class="collapse"> </a>
                                                <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                                <a href="javascript:;" class="remove"> </a>-->
                                            </div>
                                        </div>
                                        <div class="portlet-body form">

                                            <div>
                                                <div class="portlet light bordered">
                                                    <!--<div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-social-dribbble font-purple"></i>
                                                            <span class="caption-subject font-purple bold uppercase">Please Note</span>
                                                        </div>
                                                        
                                                    </div>-->
                                                    <div class="portlet-body">
                                                        <div class="well">
                                                            <b>Before running the final payroll sequence, please ensure all pre requisites regarding employee earnings and deductions have been fulfilled.</b> </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- BEGIN FORM-->
                                            <form method="post" action="assets/classes/controller.php?act=runGlobalPayroll" class="form-horizontal">
                                                
                                                <div class="form-body">
                                                    <div class="row">


                                                        <div class="col-md-12">
                                                            <?php
                                                                $query = $conn->prepare('SELECT empNumber FROM employees WHERE companyId = ? AND active =? ORDER BY id ASC');
                                                                $query->execute([$_SESSION['companyid'], '1']);
                                                                $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
                                                                $employeecount = $query->rowCount();
                                                                //print($employeecount . "<br />");
                                                                //print_r($ftres);

                                                                $counter = 0;
                                                                $missingbasic = 0;
                                                                $setbasic = 0;

                                                                while ($counter < $employeecount) {
                                                                    //echo $ftres[$counter] . ", ";
                                                                    $payrollquery = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?');
                                                                    $payrollquery->execute([$ftres[$counter], $_SESSION['companyid'], '200', $_SESSION['currentactiveperiod'], '1']);
                                                                    
                                                                    if ($payrollquery->fetch()) {
                                                                        $setbasic = $setbasic +1;
                                                                    } else {
                                                                        $missingbasic = $missingbasic +1;
                                                                    }

                                                                    $counter++;
                                                                }

                                                                    //print("<br />Set basic: " . $setbasic . "<br />" . "Missing Basic: " . $missingbasic);

                                                                if ($missingbasic > 0) {
                                                                    $_SESSION['msg'] = $missingbasic . ' employees are missing basic salaries. Please correct this to be able to run payroll.';
                                                                    $_SESSION['alertcolor'] = 'danger';
                                                                    $processingerrors = true;
                                                                } else {
                                                                    $processingerrors = false;
                                                                }

                                                                if (isset($_SESSION['msg'])) {
                                                                    echo '<div class="alert alert-' . $_SESSION['alertcolor'] . ' alert-dismissable role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $_SESSION['msg'] . '</div>';
                                                                    unset($_SESSION['msg']);
                                                                    unset($_SESSION['alertcolor']);
                                                                }
                                                            ?>
                                                        </div>



                                                        <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-md-4 control-label"><b>Current Active Payroll Period</b></label>
                                                                    <div class="col-md-4">

                                                                        

                                                                        <?php
                                                                            /*$query = $conn->prepare('SELECT description FROM payperiods WHERE companyId = ? AND active =?');
                                                                            $query->execute([$_SESSION['companyid'], '1']);
                                                                            $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
                                                                            //print_r($ftres);
                                                                            $closingperiodname = $ftres[0];*/
                                                                        ?>
                                                                        
                                                                        <input type="text" required class="form-control" name="activeperiod" value="<?php echo $_SESSION['activeperiodDescription']; ?>" disabled>

                                                                    </div>
                                                                </div>




                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-actions">
                                                    <div class="row">
                                                        <div class="col-md-12 txt-ctr">
                                                            <?php
                                                                if (isset($_SESSION['periodstatuschange']) && $_SESSION['periodstatuschange'] == '1') {
                                                                    ?><button disabled class="btn btn-lg yellow" data-toggle="modal" data-placement="top" title="You are in a closed period. Unable to process data.">Viewing Closed Period <i class="fa fa-cog"></i></button><?php
                                                                }
                                                                else {
                                                                    if ($processingerrors) {
                                                                       ?><button disabled class="btn btn-lg yellow" data-toggle="modal" data-placement="top" title="Processing disabled. Fix errors to be able to run full payroll.">Process Payroll <i class="fa fa-cog"></i></button><?php
                                                                    } else {
                                                                        ?><button type="submit" class="btn btn-lg red">Process Payroll <i class="fa fa-cog"></i></button><?php
                                                                    }
                                                                }
                                                                
                                                            ?>
                                                            
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