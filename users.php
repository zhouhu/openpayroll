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
                            <small>Company Users Manager</small>
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
                                                        <a class="btn red" data-toggle="modal" href="#responsive"> Add User <i class="fa fa-plus"></i></a>    
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- new user -->
                                            
                                                <div id="responsive" class="modal fade" tabindex="-1" data-width="560">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Create New Company User</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=adduser">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">First Name</label>
                                                                            <div class="col-md-7">
                                                                                <input type="text" required class="form-control" name="ufname" placeholder="First Name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Last Name</label>
                                                                            <div class="col-md-7">
                                                                                <input type="text" required class="form-control" name="ulname" placeholder="Last Name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Email Address</label>
                                                                            <div class="col-md-7">
                                                                                <input type="email" required class="form-control" name="uemail" placeholder="Email Address">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Password</label>
                                                                            <div class="col-md-7">
                                                                                <input type="password" required class="form-control" name="upass" placeholder="Password">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Repeat Password</label>
                                                                            <div class="col-md-7">
                                                                                <input type="password" required class="form-control" name="upass1" placeholder="Repeat Password">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                            <button type="submit" class="btn red">Create Userd</button>
                                                        </div>
                                                    </form>
                                                </div>

                                            <!-- new user -->




                                        </div>

                                        <div>
                                            <?php
                                                if (isset($_SESSION['msg'])) {
                                                    echo '<div class="alert alert-' . $_SESSION['alertcolor'] . ' alert-dismissable role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $_SESSION['msg'] . '</div>';
                                                    unset($_SESSION['msg']);
                                                    unset($_SESSION['alertcolor']);
                                                }
                                            ?>
                                        </div>

                
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                    <th>  </th>
                                                    <th> User Name </th>
                                                    <th> First Name </th>
                                                    <th> Last Name </th>
                                                    <th> User Type </th>
                                                    <th> Actions </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <!--Begin Data Table-->
                                                <?php
                                                    try{
                                                        $query = $conn->prepare('SELECT * FROM users WHERE companyId = ? AND active = ?');
                                                        $fin = $query->execute([$_SESSION['companyid'], '1']);
                                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($res as $row => $link) {
                                                            $thisuser = $link['userId'];
                                                            $usermail = $link['emailAddress'];
                                                            ?><tr class="odd gradeX"><td><input type="checkbox"></td> <?php echo '<td>' . $link['emailAddress'] . '</td><td>' . $link['firstName'] . '</td><td>' . $link['lastName'] . '</td><td>'; 
                                                            	$typevar = $link['userTypeId'];
                                                            	retrieveDescSingleFilter ('usertypes', 'userTypeDesc', 'userTypeId', $typevar);
                                                            	echo '</td><td><a href="#edituser'.$thisuser .'" data-toggle="modal" class="btn btn-xs red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td></tr>';
                                                        ?>


                                                            <!-- Edit user -->                                            
                                                            <div id="edituser<?php echo $thisuser;?>" class="modal fade" tabindex="-1" data-width="560">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                    <h4 class="modal-title">Deactivate User</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="form-horizontal" method="post" action="assets/classes/controller.php?act=deactivateuser">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-body">
                                                                                    <input type="hidden" name ="thisuser" value="<?php echo $thisuser; ?>">
                                                                                    <div class="form-group">
                                                                                        <label class="col-md-12 control-label">Please confirm account deactivation for:</label>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                        <label class="col-md-4 control-label">User</label>
                                                                                        <div class="col-md-7">
                                                                                            <input type="text" class="form-control" value="<?php echo $link['firstName'] . ' ' . $link['lastName']?>" readonly placeholder="Name">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-md-4 control-label">Email Address</label>
                                                                                        <div class="col-md-7">
                                                                                            <input type="text" required readonly value="<?php echo $usermail;?>" class="form-control" name="useremail" placeholder="Email Address">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                                                                        <button type="submit" class="btn red">Deactivate User</button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                        <!-- Edit user -->


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