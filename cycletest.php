<?php
    //include_once('config.php');
    //exit($base_url);
    include_once('assets/includes/header.php');
    include_once('assets/includes/menubar.php');
?>

                        


                        <?php

                            $query = $conn->prepare('SELECT empNumber FROM employees WHERE companyId = ? AND active =?');
                            $query->execute([$_SESSION['companyid'], '1']);
                            $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
                            $count = $query->rowCount();
                            print($count . "<br />");
                            print_r($ftres);
                            $counter = 0;
                        ?>                                

                        <?php
                            if ($_SESSION['emptrack'] == $count) {
                                $_SESSION['emptrack'] = 0;
                            }
                        ?>
                        <div class="row">
                            <a href="assets/classes/controller.php?act=getNextEmployee&track=<?php echo $_SESSION['emptrack'] + 1; ?>" class="btn red">Next Employee</a>
                        </div>

                        <div class="row">
                            <?php
                            print($_SESSION['emptrack']);
                            ?>
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