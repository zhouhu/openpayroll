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
                        <h1 class="page-title"> NHIF Report
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
                                                        <button class="btn btn-sm purple" id="btnPrint">Print <i class="fa fa-print" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                    
                                                    <th> # </th>
                                                    <th> Emp # </th>
                                                    <th> Names </th>
                                                    <th> NHIF # </th>
                                                    <th> Contribution </th>
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
                                                        $numberofstaff = count($res);
                                                        $counter = 1;
                                                        //sdsd
                                                        
                                                        foreach ($res as $row => $link) {
                                                            ?><tr class="odd gradeX"> 
                                                                <?php 
                                                                    echo '<td><input type="checkbox"></td><td>' . $link['empNumber'] .  '</td><td class="stylecaps">' . $link['fName'] . " " . $link['lName'] . '';
                                                                        
                                                                        /*$typevar = $link['companyDept'];
                                                                        retrieveDescDualFilter('company_departments', 'companyDescription', $typevar, 'departmentId', 'companyId', $_SESSION['companyid']);*/

                                                                    echo '</td> <td>' . $link['empNhif'];
                                                                        
                                                                    echo '</td><td>'; 
                                                                         $typevar = $link['empNumber'];
                                                                        retrieveDescQuadFilter('employee_earnings_deductions', 'amount', $typevar, 'employeeId', 'earningDeductionCode', '481', 'companyId', $_SESSION['companyid'], 'payPeriod', $_SESSION['currentactiveperiod']);
                                                                        
                                                                    echo '</td></tr>';
                                                                    $counter ++;
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
        
        

    <?php include_once('assets/includes/footer.php');?>.