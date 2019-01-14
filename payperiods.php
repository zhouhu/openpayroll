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
                        
                        <?php
                            if (isset($_SESSION['msg'])) {
                                echo '<div class="alert alert-' . $_SESSION['alertcolor'] . ' alert-dismissable role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $_SESSION['msg'] . '</div>';
                                unset($_SESSION['msg']);
                                unset($_SESSION['alertcolor']);
                            }
                        ?>

    
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Organization - 
                            <small>Create & Manage organization's payroll periods ( Close current period before moving to next period )</small>
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
                                                
                                                <div class="col-md-6"></div>

                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                        
                                                        <a class="btn green" data-toggle="modal" href="#newperiod"> Add New Period <i class="fa fa-plus-square"></i></a>
                                                    </div>
                                                </div>

                                            </div>


                                            <!-- Start Modal -->
                                            
                                                <div id="newperiod" class="modal fade" tabindex="-1" data-width="560">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Add New Payment Period</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=addperiod">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Description</label>
                                                                            <div class="col-md-7">
                                                                                <select class="form-control" name="perioddesc" >
                                                                                    <option value="January">January</option>
                                                                                    <option value="February">February</option>
                                                                                    <option value="March">March</option>
                                                                                    <option value="April">April</option>
                                                                                    <option value="May">May</option>
                                                                                    <option value="June">June</option>
                                                                                    <option value="July">July</option>
                                                                                    <option value="August">August</option>
                                                                                    <option value="September">September</option>
                                                                                    <option value="October">October</option>
                                                                                    <option value="November">November</option>
                                                                                    <option value="December">December</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Year</label>
                                                                            <div class="col-md-7">
                                                                                <select class="form-control" name="periodyear" >
                                                                                    <option value="2017">2017</option>
                                                                                    <option value="2018">2018</option>
                                                                                    <option value="2019">2019</option>
                                                                                    <option value="2020">2020</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                            <button type="submit" class="btn red">Create Period</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!--End Modal-->



                                        </div>
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                    <th> </th>
                                                    <th> Payment Period </th>
                                                    <th> Status </th>
                                                    <th> Actions </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <!--Begin Data Table-->
                                                <?php
                                                    try{
                                                        $query = $conn->prepare('SELECT * FROM payperiods WHERE companyId = ? ORDER BY periodId DESC');
                                                        $fin = $query->execute([$_SESSION['companyid']]);
                                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($res as $row => $link) {
                                                            $thisperiod = $link['periodId'];
                                                            ?><tr class="odd gradeX"><td></td><?php echo '<td>' . $link['description'] . " " . $link['periodYear'] . '</td>';

                                                                    if ($link['active'] == 0) {
                                                                        echo '<td> <span class="label label-inverse label-sm label-warning">Open </span> </td>';
                                                                    } elseif ($link['active'] == 1) {
                                                                        echo '<td> <span class="label label-inverse label-sm label-primary"> Current Active </span> </td>';
                                                                    } elseif ($link['active'] == 2) {
                                                                        echo '<td> <span class="label label-inverse label-sm label-danger"> Closed </span> </td>';
                                                                    }
                                                                
                                                                echo '<td>'; 
                                                                    if ($link['active'] == 1) {
                                                                        echo '<!--<a href="" class="btn btn-xs yellow"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a href="" class="btn btn-xs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--> 
                                                                            <a data-toggle="modal" href="#closeperiod" class="btn btn-xs red"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Close Active Period </a>';
                                                                    } else {
                                                                        if ($link['active'] == 2) {
                                                                            echo '<a data-toggle="modal" href="#viewperiod'.$thisperiod .'" class="btn btn-xs yellow"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Closed Period</a> ';
                                                                        } else {
                                                                            echo '<button class="btn btn-zs yellow"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button> <!--<button disabled class="btn btn-xs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>-->';
                                                                        }
                                                                        
                                                                    }
                                                                echo '</td></tr>';
                                                                ?>

                                                                    <!--View Closed Period-->
                                                                    <div id="viewperiod<?php echo $thisperiod;?>" class="modal fade" tabindex="-1" data-width="560">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                            <h4 class="modal-title"><b>Re-activate Period To View Data</b></h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=activateclosedperiod">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-body">
                                                                                            <div class="form-group">
                                                                                                <label class="col-md-12 txt-ctr">Please confirm you would like to reactivate this <b>CLOSED</b> period to <b>VIEW</b> data. <b>Please note you cannot transact in this period.</b><p></p></label>
                                                                                            </div>
                                                                                            <input type="hidden" value="<?php echo $thisperiod; ?>" name="reactivateperiodid">
                                                                                            <div class="form-group">
                                                                                                <label class="col-md-4 control-label txt-right"><b>Period</b></label>
                                                                                                <div class="col-md-7">
                                                                                                    <input type="text" disabled class="form-control" value="<?php
                                                                                                            retrieveDescSingleFilter('payperiods','description','periodId', $thisperiod);
                                                                                                            echo " ";
                                                                                                            retrieveDescSingleFilter('payperiods','periodYear','periodId', $thisperiod); 
                                                                                                        ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                <button type="submit" class="btn red">Reactivate Period</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!--View Closed Period-->



                                                                    <!--Close Period-->
                                                                    <div id="closeperiod" class="modal fade" tabindex="-1" data-width="560">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                            <h4 class="modal-title">Close Current Period</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=closeActivePeriod">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-body">
                                                                                            <div class="form-group">
                                                                                                <label class="col-md-12 txt-ctr">Please confirm you would like to close the period below. Ensure you have completed all transactional changes and processing for the current month. <b>This process is irreversible.</b><p></p></label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-md-4 control-label txt-right"><b>Period</b></label>
                                                                                                <div class="col-md-7">
                                                                                                    <input type="text" disabled class="form-control" value="<?php
                                                                                                            retrieveDescSingleFilter('payperiods','description','periodId', $_SESSION['currentactiveperiod']);
                                                                                                            echo " ";
                                                                                                            retrieveDescSingleFilter('payperiods','periodYear','periodId', $_SESSION['currentactiveperiod']); 
                                                                                                        ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                                <button type="submit" class="btn red">Close Period</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!--Close Period-->

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