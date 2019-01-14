<?php 
  include_once('assets/includes/header.php');
  include_once('assets/classes/class.db.php');

  $act = filter_var($_GET['act'], FILTER_SANITIZE_STRING);
  $source = $_SERVER['HTTP_REFERER'];

  global $conn;

?>

    <body class="auth">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="index.php">PAYROLL</a>
        </div>

        <div class="content">
            <br>

            <?php
              switch ($act) {
                case 'auth':
                  $authtok  = filter_var($_GET['token'], FILTER_SANITIZE_STRING);
                  $user_id = filter_var($_GET['jam'], FILTER_SANITIZE_STRING);
                  $compid = filter_var($_GET['queue'], FILTER_SANITIZE_STRING);
                  //check if auth token is valid, if so authenticate, activate account and redirect to login. If not, return error and redirect to login.
                  
                    try{
                      $query = $conn->prepare('SELECT emailAddress FROM users WHERE userId = ? AND active = ?');
                      $query->execute([$user_id, '0']);
                      $ftres = $query->fetch(PDO::FETCH_COLUMN);
                      //print_r($ftres);

                        $tok = $conn->prepare('SELECT * FROM reset_token WHERE userEmail = ? AND token = ? AND valid = ?');
                        $res = $tok->execute([$ftres, $authtok, '1']);

                        if ($row = $tok->fetch()) {
                          //valid token, invalidate token, activate user
                          $upquery = 'UPDATE reset_token SET valid = ? WHERE userEmail = ? AND token = ? AND valid = ?';
                          $conn->prepare($upquery)->execute(['0', $ftres, $authtok, '1']);

                          $upquery = 'UPDATE users SET active = ? WHERE emailAddress = ? AND  companyId = ?';
                          $conn->prepare($upquery)->execute(['1', $ftres, $compid]);

                          $_SESSION['msg'] = $msg = "Account activation successful. Please log into your account.";
                          $_SESSION['alertcolor'] = 'success';
                          header('Location: index.php');

                          //Send welcome email

                            $sendmessage = "Welcome to your payroll account. Your user name is: " . $useremail . "<br />";
                            //generate reset cdde and append to email submitted

                            require 'assets/classes/phpmailer/PHPMailerAutoload.php';

                            $mail = new PHPMailer;

                            $mail->SMTPDebug = 3;                               // Enable verbose debug output

                            $mail->isSMTP();                                      // Set mailer to use SMTP
                            $mail->Host = 'smtp.zoho.com';  // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true;                               // Enable SMTP authentication
                            $mail->Username = 'noreply@redsphere.co.ke';                 // SMTP username
                            $mail->Password = 'redsphere_2017***';                           // SMTP password
                            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = 587;                                    // TCP port to connect to

                            $mail->setFrom('noreply@redsphere.co.ke', 'Red Payroll');
                            $mail->addAddress($useremail, 'Redsphere Payroll');     // Add a recipient
                            //$mail->addAddress('ellen@example.com');               // Name is optional
                            $mail->addReplyTo('noreply@redsphere.co.ke', 'Red Payroll');
                            //$mail->addCC('fgesora@gmail.com');
                            //$mail->addBCC('fgesora@gmail.com');

                            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                            $mail->isHTML(true);                                  // Set email format to HTML

                            $mail->Subject = $title;
                            $mail->Body    = $sendmessage;
                            $mail->AltBody = $sendmessage;

                            if(!$mail->send()) {
                              //exit($mail->ErrorInfo);
                                echo 'Mailer Error: ' . $mail->ErrorInfo;
                                $_SESSION['msg'] = "Failed. Error sending email.";
                                $_SESSION['alertcolor'] = "danger";
                                header("Location: " . $source);
                            } else {
                                $status = "Success";
                                $_SESSION['msg'] = "An activation link has been sent to the provided email address. Please activate your account in order to log in.";
                                $_SESSION['alertcolor'] = "success";
                                header("Location: " . $source);
                            }

                          //echo '<br/>'.'valid' . '<br />';
                        } else {
                          //invalid token
                          //echo 'invalid token' . '<br />';
                          $_SESSION['msg'] = $msg = "Invalid account activation attempt. ";
                          $_SESSION['alertcolor'] = 'danger';
                          header('Location: index.php');
                        }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                  

                  //echo $authtok . '</br>';
                  //echo $user_id . '<br />';

                break;
                
                default:
                  echo 'error';
                break;
              }
            ?>

        </div>
        


        <div class="copyright"> &copy; 2017 Redsphere Consulting. </div>


<?php include_once('assets/includes/footer.php');?>