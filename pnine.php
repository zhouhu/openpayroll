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
                        <h1 class="page-title"> P9 Report
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
                                                    <button class="btn btn-primary btn-sm" type="button">
                                                      Year <span class="badge"><?php print substr($_SESSION['activeperiodDescription'], -4); ?></span>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" type="button">
                                                      Number of Employees <span class="badge"><?php print $count;?></span>
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                        <button class="btn btn-sm purple" id="btnPrint">Print <i class="fa fa-print" aria-hidden="true"></i></button>
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


                                                //Initialise all individual's columnised totals
                                                $totalp9basic = 0;
                                                $totalp9gross = 0;
                                                $totalvalquarters = 0;
                                                $totalp9e1 = 0;
                                                $totalp9e2 = 0;
                                                $totalp9e3 = 0;
                                                $totalp9ownerocc = 0;
                                                $totalp9contribown = 0;
                                                $totalp9chargeable = 0;
                                                $totalp9taxcharged = 0;
                                                $totalp9mnthrelief = 0;
                                                $totalp9insurrelief = 0;
                                                $totalp9paye = 0;
                                            ?>


                                                <!--Begin P9-->
                                                <div class="p9">
                                                    <div class="row p9headers">
                                                        <div class="col-md-6"><b>Employer's Name</b> : &nbsp;<?php retrieveDescSingleFilter ('company', 'companyName', 'id', $_SESSION['companyid']); ?></div>
                                                        <div class="col-md-6"><b>Employer's PIN</b> : &nbsp;<?php retrieveDescSingleFilter ('company', 'companyPin', 'id', $_SESSION['companyid']); ?></div>
                                                    </div>

                                                    <div class="row p9headers">
                                                        <div class="col-md-6"><b>Employee's Main Name</b> : &nbsp;<?php retrieveDescSingleFilter('employees', 'lName', 'empNumber', $thisemployee);?></div>
                                                        <div class="col-md-6"><b>Employee's Other Names</b> : &nbsp;<?php retrieveDescSingleFilter('employees', 'fName', 'empNumber', $thisemployee);?></div>
                                                    </div>

                                                    <div class="row p9headertop">
                                                        <div class="col-md-6"><b>Employee's PIN</b> : &nbsp;<?php retrieveDescSingleFilter('employees', 'empTaxPin', 'empNumber', $thisemployee);?></div>
                                                        <div class="col-md-6"> <b>YEAR</b> : &nbsp; <?php print substr($_SESSION['activeperiodDescription'], -4); ?></div>
                                                    </div>

                                                    <div class="row">
                                                        <br />
                                                    </div>

                                                    <table class="table table-striped table-bordered table-hover table-checkable order-column small-txt">
                                                        <thead>
                                                            <tr class="txt-ctr">                                                            
                                                                <th class="txt-ctr"> Month </th>
                                                                <th class="txt-ctr"> Basic <br />Salay</th>
                                                                <th class="txt-ctr"> Benefits<br />Non-Cash </th>
                                                                <th class="txt-ctr"> Value of <br />Quarters </th>
                                                                <th class="txt-ctr"> Total <br />Gross Pay</th>

                                                                <th class="txt-ctr"> E1 <br />30%</th>
                                                                <th class="txt-ctr"> E2 <br />Actual</th>
                                                                <th class="txt-ctr"> E3 <br />Fixed</th>


                                                                <th class="txt-ctr"> Owner<br />Occupied </th>
                                                                <th class="txt-ctr"> Contribution <br />Benefit & <br />Owner Occupied </th>
                                                                <th class="txt-ctr"> Chargeable<br />Pay </th>
                                                                <th class="txt-ctr"> Tax <br />Charged </th>

                                                                <th class="txt-ctr"> Monthly <br />Relief</th>
                                                                <th class="txt-ctr"> Insurance <br />Relief</th>
                                                                <th class="txt-ctr"> PAYE <br />K-L</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>

                                                            <tr class="txt-ctr">                                                            
                                                                <td>  </td>
                                                                <td> A </td>
                                                                <td> B </td>
                                                                <td> C </td>
                                                                <td> D </td>

                                                                <td> E </td>
                                                                <td> F </td>
                                                                <td> G </td>


                                                                <td> H </td>
                                                                <td> I </td>
                                                                <td> J </td>
                                                                <td> K </td>

                                                                <td> L </td>
                                                                <td> M </td>
                                                                <td> N </td>
                                                            </tr>


                            <?php
                                $thiscurrentyear = substr($_SESSION['activeperiodDescription'], -4);
                                $pquery = $conn->prepare('SELECT periodId, description FROM payperiods WHERE periodYear = ? AND companyId = ? AND active = ? OR active = ? ORDER BY periodId ASC');
                                $pres = $pquery->execute([$thiscurrentyear, $_SESSION['companyid'], '1', '2']);
                                    $pcount = $pquery->rowCount();
                                    while ( $pcount > 0) {
                                        
                                        if ($prow = $pquery->fetch()) {
                                        //print $prow['description'];
                                        ?>
                                            <!--Using respective period id's pull individual month data-->
                                            <tr class="txt-ctr">
                                                <td> <?php print substr($prow['description'], 0, 3);?> </td>
                                                <td> <?php $returnbasic = &returnDescPentaFilter('employee_earnings_deductions', 'amount', $thisemployee, 'employeeId', 'companyId', $_SESSION['companyid'], 'earningDeductionCode', '200', 'payPeriod', $prow['periodId'], 'active', '1'); 
                                                    echo number_format($returnbasic = intval($returnbasic));
                                                    $totalp9basic = $totalp9basic + $returnbasic;
                                                    ?>
                                                </td>
                                                <td>  </td>
                                                <td>  </td>
                                                <td> <?php $returngross = &returnDescPentaFilter('employee_earnings_deductions', 'amount', $thisemployee, 'employeeId', 'companyId', $_SESSION['companyid'], 'earningDeductionCode', '601', 'payPeriod', $prow['periodId'], 'active', '1'); 
                                                    echo number_format($returngross = intval($returngross));
                                                    $totalp9gross = $totalp9gross + $returngross;
                                                    ?> 
                                                </td>

                                                <td>  
                                                    <?php 
                                                        echo number_format($e1val = 0.3*$returnbasic);
                                                        $totalp9e1 = $totalp9e1 + $e1val;
                                                    ?> 
                                                </td>
                                                <td>  </td>
                                                <td> 
                                                    <?php
                                                        print number_format($e3val = intval(20000));
                                                        $totalp9e3 = $totalp9e3 + $e3val;
                                                    ?>
                                                </td>


                                                <td>  </td>
                                                <td>  </td>
                                                <td>  <?php $returnhargeable = &returnDescPentaFilter('employee_earnings_deductions', 'amount', $thisemployee, 'employeeId', 'companyId', $_SESSION['companyid'], 'earningDeductionCode', '400', 'payPeriod', $prow['periodId'], 'active', '1'); 
                                                    echo number_format($returnhargeable = intval($returnhargeable));
                                                    $totalp9chargeable = $totalp9chargeable + $returnhargeable;
                                                    ?> 
                                                </td>

                                                <td>  <?php $returntaxcharged = &returnDescPentaFilter('employee_earnings_deductions', 'amount', $thisemployee, 'employeeId', 'companyId', $_SESSION['companyid'], 'earningDeductionCode', '399', 'payPeriod', $prow['periodId'], 'active', '1'); 
                                                    echo number_format($returntaxcharged = intval($returntaxcharged));
                                                    $totalp9taxcharged = $totalp9taxcharged + $returntaxcharged; ?> </td>

                                                <td> <?php print number_format(intval(1162)); ?> </td>
                                                <td>  </td>
                                                <td> <?php $returnpaye = &returnDescPentaFilter('employee_earnings_deductions', 'amount', $thisemployee, 'employeeId', 'companyId', $_SESSION['companyid'], 'earningDeductionCode', '550', 'payPeriod', $prow['periodId'], 'active', '1'); 
                                                    echo number_format($returnpaye = intval($returnpaye));
                                                    $totalp9paye = $totalp9paye + $returnpaye; ?>
                                                </td>
                                            </tr>

                                        <?php
                                        }

                                        $pcount--;
                                    }
                            ?>


                                                            <tr class="txt-ctr txt-bold">
                                                                <td> Total </td>
                                                                <td> <?php echo number_format($totalp9basic); ?> </td>
                                                                <td>  </td>
                                                                <td> <?php echo number_format($totalvalquarters);?> </td>
                                                                <td> <?php echo number_format($totalp9gross); ?> </td>

                                                                <td> <?php echo number_format($totalp9e1);?> </td>
                                                                <td> <?php echo number_format($totalp9e2);?>  </td>
                                                                <td> <?php echo number_format($totalp9e3);?>  </td>


                                                                <td> <?php echo number_format($totalp9ownerocc); ?> </td>
                                                                <td> <?php echo number_format($totalp9contribown); ?> </td>
                                                                <td> <?php echo number_format($totalp9chargeable); ?> </td>
                                                                <td> <?php echo number_format($totalp9taxcharged); ?> </td>

                                                                <td> <?php echo number_format(intval(1162));?> </td>
                                                                <td> <?php echo number_format($totalp9insurrelief); ?> </td>
                                                                <td>  <?php echo number_format($totalp9paye); ?> </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="top-spacer-60"></div>
                                                <!--End P9-->


                                                <div class="txt-ctr bottom-spacer-40">* * * * * * * * * * * * * * *</div>


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