<?php
	session_start();
	include_once('functions.php');
	include_once('cinfig.php');
	//$act = strip_tags(addslashes($_GET['act']));
	$act = filter_var($_GET['act'], FILTER_SANITIZE_STRING);
	$source = $_SERVER['HTTP_REFERER'];
	//$hostname = gethostbyname($_SERVER['REMOTE_ADDR']);
	//session variables
	$comp = '1';



	switch ($act) {
		case 'login':
			$uname = filter_var((filter_var($_POST['uname'], FILTER_SANITIZE_EMAIL)), FILTER_VALIDATE_EMAIL);
			$pass = filter_var($_POST['upassword'], FILTER_SANITIZE_STRING);
			//$upass = password_hash(filter_var($_POST['upassword'], FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);

			try{
				$query = $conn->prepare('SELECT * FROM users WHERE emailAddress = ? AND active = ?');
				$fin = $query->execute([$uname, '1']);

				unset($_SESSION['email']);
        		unset($_SESSION['first_name']);
        		unset($_SESSION['last_name']);

        		if (isset($_SESSION['periodstatuschange'])) {
	    			unset($_SESSION['periodstatuschange']);
	    		}

				if (($row = $query->fetch()) && (password_verify($pass, $row['password']))) {
					$_SESSION['logged_in'] = '1';
					$_SESSION['user'] = $row['userId'];
					$_SESSION['email'] = $row['emailAddress'];
            		$_SESSION['first_name'] = $row['firstName'];
            		$_SESSION['last_name'] = $row['lastName'];
            		$_SESSION['companyid'] = $row['companyId'];
            		$_SESSION['emptrack'] = 0;            		
					$_SESSION['empDataTrack'] = 'next';

            			//Get current active period for the organization
	            		$payp = $conn->prepare('SELECT periodId, description, periodYear FROM payperiods WHERE companyId = ? AND active = ? ORDER BY periodId ASC LIMIT 1');
	            		$myperiod = $payp->execute([$_SESSION['companyid'], 1]);
	            		$final = $payp->fetch();
	            		$_SESSION['currentactiveperiod'] = $final['periodId'];
	            		$_SESSION['activeperiodDescription'] = $final['description'] . " " . $final['periodYear'];
	            		//exit($_SESSION['currentactiveperiod']);

	            		//If temp period change, reset session
            			if (isset($_SESSION['periodstatuschange'])) {
			    			unset($_SESSION['periodstatuschange']);
			    		}

					$page = "../../dashboard.php";
					$_SESSION['msg'] = $msg = "Welcome " . $_SESSION['first_name'] . " " . $_SESSION['last_name'];
					$_SESSION['alertcolor'] = $type = "success";
					header('Location: ../../dashboard.php');
					//redirect($msg, $type, $page);
				}
				else {
					$_SESSION['msg'] = $msg = "Invalid login. Please try again.";
					$_SESSION['alertcolor'] = 'danger';
					header('Location: ' . $source);
				}
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
		break;

		
		case 'adduser':
			//
			$ufname = filter_var($_POST['ufname'], FILTER_SANITIZE_STRING);
			$ulname = filter_var($_POST['ulname'], FILTER_SANITIZE_STRING);
			$uemail = filter_var((filter_var($_POST['uemail'], FILTER_SANITIZE_EMAIL)),FILTER_VALIDATE_EMAIL);
			$upass1 = filter_var($_POST['upass'], FILTER_SANITIZE_STRING);
			$upass2 = filter_var($_POST['upass1'], FILTER_SANITIZE_STRING);

			if ($upass1 == $upass2) {
				try{

					$query = $conn->prepare('SELECT * FROM users WHERE emailAddress = ? AND companyId = ? AND active = ? ');
					$res = $query->execute([$uemail, $_SESSION['companyid'], '1']);
					$existtrans = $query->fetch();

					if ($existtrans) {
						//user exists
						$_SESSION['msg'] = "A user account associated with the supplied email exists.";
						$_SESSION['alertcolor'] = "danger";
						$source = $_SERVER['HTTP_REFERER'];
						header('Location: ' . $source);
					}
					else {
						$upass = password_hash($upass1, PASSWORD_DEFAULT);

						$query = 'INSERT INTO users (emailAddress, password, userTypeId, firstName, lastName, companyId, active) VALUES (?,?,?,?,?,?,?)';
						$conn->prepare($query)->execute([$uemail, $upass, '1', $ufname, $ulname, $_SESSION['companyid'], '1']);

						$_SESSION['msg'] = $msg = 'User Successfully Created';
						$_SESSION['alertcolor'] = $type = 'success';
						$source = $_SERVER['HTTP_REFERER'];
						header('Location: ' . $source);
					}
				}
				catch(PDOException $e){
					echo $e->getMessage();
				}
			}
			else {

				$_SESSION['msg'] = $msg = 'Entered passwords are not matching.';
				$_SESSION['alertcolor'] = $type = 'danger';
				header('Location: ' . $source);
			}
			
		break;

		case 'createcompanyaccount':
			//create new company
			$title = "New Payroll Account";
			$companyname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
			$contactemail = filter_var((filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)), FILTER_VALIDATE_EMAIL);
			$contactphone = filter_var($_POST['phone'], FILTER_VALIDATE_INT);
			$companyaddress = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
			$compcity = filter_var($_POST['city'], FILTER_SANITIZE_STRING);

			$useremail = filter_var((filter_var($_POST['username'], FILTER_SANITIZE_EMAIL)), FILTER_VALIDATE_EMAIL);
			$userfname = filter_var($_POST['ufname'], FILTER_SANITIZE_STRING);
			$userlname = filter_var($_POST['ulname'], FILTER_SANITIZE_STRING);
			$userpass1 = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
			$userpass = password_hash($userpass1, PASSWORD_DEFAULT);

			try{
				$query = $conn->prepare('SELECT * FROM users WHERE emailAddress = ? AND active = ? ');
				$res = $query->execute([$useremail, '1']);
				$existtrans = $query->fetch();

				if ($existtrans) {
					//same transaction for current employee, current period posted
					$_SESSION['msg'] = "A user account associated with the supplied email exists.";
					$_SESSION['alertcolor'] = "danger";
					$source = $_SERVER['HTTP_REFERER'];
					header('Location: ' . $source);
				}
				else {
					
					$query = 'INSERT INTO company (companyName, city, companyAddress, companyEmail, contactTelephone) VALUES (?,?,?,?,?)';
					$conn->prepare($query)->execute([$companyname, $compcity, $companyaddress, $contactemail, $contactphone]);
					$last_id = $conn->lastInsertId();
					
					$query = 'INSERT INTO users (emailAddress, password, userTypeId, firstName, lastName, companyId, active) VALUES (?,?,?,?,?,?,?)';
					$conn->prepare($query)->execute([$useremail, $userpass, '1', $userfname, $userlname, $last_id, '0']);
					$latestuserinsert = $conn->lastInsertId();
				
					//user account becomes active after validating emailed link
					//Send email validation
					//Generate update token
					$reset_token = bin2hex(openssl_random_pseudo_bytes(32));
					
					//write token to token table and assign validity state, creation timestamp
					$tokenrecordtime = date('Y-m-d H:i:s');


					//check for any previous tokens and invalidate
						$tokquery = $conn->prepare('SELECT * FROM reset_token WHERE userEmail = ? AND valid = ? AND type = ?');
						$fin = $tokquery->execute([$useremail, '1', '2']);
						
						if($row = $tokquery->fetch()){
							$upquery = 'UPDATE reset_token SET valid = ? WHERE userEmail = ? AND valid = ?';
							$conn->prepare($upquery)->execute(['0', $useremail, '1']);
						}

					$tokenquery = 'INSERT INTO reset_token (userEmail, token, creationTime, valid, type) VALUES (?,?,?,?,?)';
					$conn->prepare($tokenquery)->execute([$useremail, $reset_token, $tokenrecordtime, '1', '2']);
						
					//exit($resetemail . " " . $reset_token);
					
					$sendmessage = "You've recently created a new Red Payroll account linked to the email address: " . $useremail . "<br /><br />To activate your account, click the link below:<br /><br /> " . $sysurl . 'validate.php?act=auth&jam=' . $latestuserinsert .'&queue=' . $last_id . '&token=' . $reset_token;
					//generate reset cdde and append to email submitted

					require 'phpmailer/PHPMailerAutoload.php';

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
					$mail->addBCC('fgesora@gmail.com');

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
				}

					/*
					********
					********
					Check if user account exists
					********
					********
					*/

				


			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

			//exit($companyname . ', ' . $contactemail . ', ' . $last_id);
		break;


		case 'addcostcenter':
			$ccname = filter_var($_POST['cctrname'], FILTER_SANITIZE_STRING);

			try{
				$query = 'INSERT INTO company_costcenters (companyId, costCenterName, active) VALUES (?,?,?)';
				$conn->prepare($query)->execute([$_SESSION['companyid'], $ccname, '1']);
				$_SESSION['msg'] = $msg = 'Cost Center successfully Created';
				$_SESSION['alertcolor'] = $type = 'success';
				$source = $_SERVER['HTTP_REFERER'];
				header('Location: ' . $source);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'adddepartment':
			$dept = filter_var($_POST['deptname'], FILTER_SANITIZE_STRING);
			
			try{
				$query = 'INSERT INTO company_departments (companyId, companyDescription, active) VALUES (?,?,?)';
				$conn->prepare($query)->execute([$_SESSION['companyid'], $dept, '1']);
				$_SESSION['msg'] = $msg = 'Department successfully Created';
				$_SESSION['alertcolor'] = $type = 'success';
				$source = $_SERVER['HTTP_REFERER'];
				header('Location: ' . $source);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'addearning':
			$newearning = filter_var($_POST['eddescription'], FILTER_SANITIZE_STRING);
			$recurrent = filter_var($_POST['recurrent'], FILTER_VALIDATE_INT);

			try{
				$getlast = $conn->prepare('SELECT edCode FROM earnings_deductions WHERE edType = ? AND companyId = ? AND active = ? ORDER BY id DESC');
				$res = $getlast->execute(['Earning', $_SESSION['companyid'], '1']);

				if ($row = $getlast->fetch()) {
			            $latestcode = intval($row['edCode']);
						$insertcode = $latestcode + 1;
			        }

			
				$query = 'INSERT INTO earnings_deductions (edCode, edDesc, edType, companyId, active, recurrentEd) VALUES (?,?,?,?,?,?)';
				$conn->prepare($query)->execute([$insertcode, $newearning, 'Earning', $_SESSION['companyid'], '1', $recurrent]);

				$_SESSION['msg'] = $msg = 'New earning Created';
				$_SESSION['alertcolor'] = $type = 'success';
				$source = $_SERVER['HTTP_REFERER'];
				header('Location: ' . $source);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'adddeduction':
			$newearning = filter_var($_POST['eddescription'], FILTER_SANITIZE_STRING);
			$recurrent = filter_var($_POST['recurrent'], FILTER_VALIDATE_INT);

			try{
				$getlast = $conn->prepare('SELECT edCode FROM earnings_deductions WHERE edType = ? AND companyId = ? AND active = ? ORDER BY id DESC');
				$res = $getlast->execute(['Deduction', $_SESSION['companyid'], '1']);

				if ($row = $getlast->fetch()) {

			            $latestcode = intval($row['edCode']);
						$insertcode = $latestcode + 1;
			        }
			
				$query = 'INSERT INTO earnings_deductions (edCode, edDesc, edType, companyId, active, recurrentEd) VALUES (?,?,?,?,?,?)';
				$conn->prepare($query)->execute([$insertcode, $newearning, 'Deduction', $_SESSION['companyid'], '1', $recurrent]);

				$_SESSION['msg'] = $msg = 'New Deduction Created';
				$_SESSION['alertcolor'] = $type = 'success';
				$source = $_SERVER['HTTP_REFERER'];
				header('Location: ' . $source);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'addloan':
			$newloandesc = filter_var($_POST['newloandesc'], FILTER_SANITIZE_STRING);
			//define 900** as the loan ED Code
			try{
				$getlast = $conn->prepare('SELECT edCode FROM earnings_deductions WHERE edType = ? AND companyId = ? AND active = ? ORDER BY id DESC');
				$res = $getlast->execute(['Loan', $_SESSION['companyid'], '1']);

				if ($row = $getlast->fetch()) {
					$latestcode = intval($row['edCode']);
					$principleinsertcode = $latestcode + 1;
					$repaymentinsertcode = $latestcode + 2;
				}
				$principleinsertdesc = $newloandesc . 'Loan Principle';
				$repaymentinsertdesc = $newloandesc . 'Loan Repayment';
				exit($principleinsertcode . ',' . $repaymentinsertcode);

				$query = 'INSERT INTO earnings_deductions (edCode, edDesc, edType, companyId, active, recurrentEd) VALUES (?,?,?,?,?,?)';
				$conn->prepare($query)->execute([$principleinsertcode, $principleinsertdesc, 'Loan', $_SESSION['companyid'], '1', '0']);

				$query = 'INSERT INTO earnings_deductions (edCode, edDesc, edType, companyId, active, recurrentEd) VALUES (?,?,?,?,?,?)';
				$conn->prepare($query)->execute([$repaymentinsertcode, $repaymentinsertdesc, 'Deduction', $_SESSION['companyid'], '1', '1']);



			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

			exit($newloandesc);
		break;


		case 'addemployeeearning':
			$currentempl = $_POST['curremployee'];
			$edtype = $_POST['edtype'];
			$edcode = $_POST['newearningcode'];
			$earningamount = $_POST['earningamount'];
			$recordtime = date('Y-m-d H:i:s');

			try{
				$query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND payperiod = ? AND earningDeductionCode = ? AND active = ?');
				$res = $query->execute([$currentempl, $_SESSION['currentactiveperiod'], $edcode, '1']);
				$existtrans = $query->fetch();

				if ($existtrans) {
					//same transaction for current employee, current period posted
					$_SESSION['alertcolor'] = $type = "danger";
					$msg = "Duplicate Earning not allowed";
					$source = $_SERVER['HTTP_REFERER'];
					redirect($msg, $type, $source);
				} else {
					$query = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
					$conn->prepare($query)->execute([$currentempl, $_SESSION['companyid'], $edtype, $edcode, $earningamount, $_SESSION['currentactiveperiod'], '1', '1', $recordtime, $_SESSION['user']]);
					$_SESSION['msg'] = $msg = "Earning successfully saved";
					$_SESSION['alertcolor'] = $type = "success";
					$source = $_SERVER['HTTP_REFERER'];
					//redirect($msg, $type, $source);					
					header('Location: ' . $source);
				}
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
		break;


		case 'addemployeededuction':
			$currentempl = $_POST['curremployee'];
			$edtype = $_POST['edtype'];
			$edcode = $_POST['newdeductioncode'];
			$deductionamount = $_POST['deductionamount'];
			$recordtime = date('Y-m-d H:i:s');
			//exit($currentempl . " " . $edtype . " " .$edcode . " " . $deductionamount);
			try{
				$query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND payperiod = ? AND earningDeductionCode = ? and active = ?');
				$res = $query->execute([$currentempl, $_SESSION['currentactiveperiod'], $edcode, '1']);
				$existtrans = $query->fetch();

				if ($existtrans) {
					//same transaction for current employee, current period posted
					$_SESSION['alertcolor'] = $type = "danger";
					$_SESSION['msg'] = $msg = "Duplicate Deduction not allowed";
					$source = $_SERVER['HTTP_REFERER'];
					//redirect($msg, $type, $source);					
					header('Location: ' . $source);
				} else {
					$query = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
					$conn->prepare($query)->execute([$currentempl, $_SESSION['companyid'], $edtype, $edcode, $deductionamount, $_SESSION['currentactiveperiod'], '1', '1', $recordtime, $_SESSION['user']]);
					$_SESSION['msg'] = $msg = "Deduction successfully saved";
					$_SESSION['alertcolor'] = $type = "success";
					$source = $_SERVER['HTTP_REFERER'];
					//redirect($msg, $type, $source);					
					header('Location: ' . $source);
				}
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
		break;


		case 'editorganization':
			$compname = filter_var($_POST['compname'], FILTER_SANITIZE_STRING);
			$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
			$county = filter_var($_POST['county'], FILTER_SANITIZE_STRING);
			$compemail = filter_var((filter_var($_POST['compemail'], FILTER_SANITIZE_STRING)), FILTER_VALIDATE_EMAIL);
			$compphone = filter_var($_POST['compphone'], FILTER_SANITIZE_STRING);
			$companypin = filter_var($_POST['companypin'], FILTER_SANITIZE_STRING);
			$nssfnumber = filter_var($_POST['nssfnumber'], FILTER_SANITIZE_STRING);
			$nhifnumber = filter_var($_POST['nhifnumber'], FILTER_SANITIZE_STRING);

			$startyear = date('Y-m-d', strtotime(filter_var($_POST['startyear'], FILTER_SANITIZE_STRING)));
			$endyear = date('Y-m-d', strtotime(filter_var($_POST['endyear'], FILTER_SANITIZE_STRING)));

			try{
				$query = 'INSERT INTO company (companyName, city, county, companyEmail, contactTelephone, companyPin, companyNssf, companyNhif, companyId, yearStart, yearEnd) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
				$conn->prepare($query)->execute([$compname, $city, $county, $compemail, $compphone, $companypin, $nssfnumber, $nhifnumber, $_SESSION['companyid'], $startyear, $endyear]);
				$msg = "Company Details successfully saved";
				$type = "success";
				$source = $_SERVER['HTTP_REFERER'];
				redirect($msg, $type, $source);
			}
			catch(PDOExcepton $e){
				echo $e->getMessage();
			}

		break;


		case 'addperiod':
			$periodname = filter_var($_POST['perioddesc'], FILTER_SANITIZE_STRING);
			$periodyear = filter_var($_POST['periodyear'], FILTER_SANITIZE_STRING);
			$periodDescription = $periodname . " " . $periodyear;
			//exit(var_dump(is_int($_SESSION['currentactiveperiod'])));
			try{
				//check for replication and create period
				$query = $conn->prepare('SELECT * FROM payperiods WHERE description = ? AND periodYear = ? AND companyId = ?');
				$fin = $query->execute([$periodname, $periodyear, $_SESSION['companyid']]);

				if ($row = $query->fetch()){
					$_SESSION['msg'] = "Selected period values already exist.";
					$_SESSION['alertcolor'] = "danger";
					header('Location: ' . $source);
				} else {
					//Get last id in table
					$payp = $conn->prepare('SELECT periodId, description FROM payperiods WHERE companyId = ? ORDER BY id DESC LIMIT 1');
            		$myperiod = $payp->execute([$_SESSION['companyid']]);
            		$final = $payp->fetch();

					$workperiod = intval($final['periodId']);
					$insertPayId = $workperiod + 1;

					$query = 'INSERT INTO payperiods (periodId, description, periodYear, companyId, active, payrollRun) VALUES (?,?,?,?,?,?)';
					$conn->prepare($query)->execute([$insertPayId, $periodname, $periodyear ,$_SESSION['companyid'], '0', '0']);
					$_SESSION['msg'] = "New Period Succesfully Created";
					$_SESSION['alertcolor'] = "success";
					header('Location: ' . $source);
				}

			}
			catch(PDOExcepton $e){
				echo $e->getMessage();
			}
		break;


		case 'closeActivePeriod':
			try{

				//reset period id
				//reset assigned active period id

				exit('closeActivePeriod');
			}
			catch(PDOEXception $e){
				echo $e->getMessage();
			}
		break;


		case 'activateclosedperiod':
			try{
				$reactivateperiodid = filter_var($_POST['reactivateperiodid'], FILTER_SANITIZE_STRING);
				//exit('activateclosedperiod ' . $reactivateperiodid);

				//Change period session variables
        		$_SESSION['currentactiveperiod'] = $reactivateperiodid;

        			$periodquery = $conn->prepare('SELECT description, periodYear FROM payperiods WHERE periodId = ?');
        			$perres = $periodquery->execute([$_SESSION['currentactiveperiod']]);
        			if ($rowp = $periodquery->fetch()) {
        				$reactivatedperioddesc = $rowp['description'];
        				$reactivatedperiodyear = $rowp['periodYear'];
        			}

        		$_SESSION['activeperiodDescription'] = $reactivatedperioddesc . ' ' . $reactivatedperiodyear;

        			//Ensure all openview status are reset before activating particular one
					$statuschange = $conn->prepare('UPDATE payperiods SET openview = ? ');
					$perres = $statuschange->execute(['0']);

        			//set openview status
        			$statuschange = $conn->prepare('UPDATE payperiods SET openview = ? WHERE periodId = ?');
        			$perres = $statuschange->execute(['1', $_SESSION['currentactiveperiod']]);
        			$_SESSION['periodstatuschange'] = '1';

        		$_SESSION['msg'] = "You are now viewing data from a closed period. Transactions are not allowed.";
				$_SESSION['alertcolor'] = "success";
				header('Location: ' . $source);

			}
			catch(PDOExcepton $e){
				echo $e->getMessage();
			}
		break;

		
		case 'addNewEmp':
			//check for existing same employee number


			$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
			$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
			$gender = ucwords(strtolower(strip_tags(addslashes($_POST['gender']))));
			$idnumber = ucwords(strtolower(strip_tags(addslashes($_POST['idnumber']))));
			$dob = date('Y-m-d', strtotime(filter_var($_POST['dob'], FILTER_SANITIZE_STRING)));
			$citizenship = filter_var($_POST['citizenship'], FILTER_SANITIZE_STRING);	

			$emppin = filter_var($_POST['emppin'], FILTER_SANITIZE_STRING);
			$empnssf = filter_var($_POST['empnssf'], FILTER_SANITIZE_STRING);
			$empnhif = filter_var($_POST['empnhif'], FILTER_SANITIZE_STRING);
			$empbank = ucwords(strtolower(strip_tags(addslashes($_POST['empbank']))));
			$empbankbranch = ucwords(strtolower(strip_tags(addslashes($_POST['empbankbranch']))));

			$empacctnum = ucwords(strtolower(strip_tags(addslashes($_POST['empacctnum']))));
			$empdept = ucwords(strtolower(strip_tags(addslashes($_POST['empdept']))));
			$empcompbranch = ucwords(strtolower(strip_tags(addslashes($_POST['empcompbranch']))));
			$emptype = ucwords(strtolower(strip_tags(addslashes($_POST['emptype']))));
			$empnumber = filter_var($_POST['empnumber'], FILTER_SANITIZE_STRING);
			$employdate = date('Y-m-d', strtotime(filter_var($_POST['employdate'], FILTER_SANITIZE_STRING)));
			$empposition = ucwords(strtolower(strip_tags(addslashes($_POST['empposition']))));

			//validate for empty mandatory fields

			try{
				//check for replication and create period
				$query = $conn->prepare('SELECT * FROM employees WHERE empNumber = ? AND  companyId = ?');
				$fin = $query->execute([$empnumber, $_SESSION['companyid']]);

				if ($row = $query->fetch()){
					$_SESSION['msg'] = "Employee with same Employee Number exists.";
					$_SESSION['alertcolor'] = "danger";
					header('Location: ' . $source);
				} else {

					$query = 'INSERT INTO employees (empNumber, fName, lName, gender, idNumber, companyId, companyDept, companyBranch, empType, dob, citizenship, empTaxPin, empNssf, empNhif, empEmplDate, empPosition, active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
				
					$conn->prepare($query)->execute([$empnumber, $fname, $lname, $gender, $idnumber, $_SESSION['companyid'], $empdept, $empcompbranch, $emptype, $dob, $citizenship, $emppin, $empnssf, $empnhif, $employdate, $empposition, '1']);
					

					$_SESSION['msg'] = $msg = "Employee Successfully added.";
					$_SESSION['alertcolor'] = 'success';
					header('Location: ' . $source);
					//redirect($msg,$type,$source);

				}
			
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'getPreviousEmployee':
			
			$_SESSION['emptrack'] =$_SESSION['emptrack'] - 1;
			header('Location: ' . $source);
		break;


		case 'getNextEmployee':
			
			$_SESSION['emptrack'] =$_SESSION['emptrack'] + 1;
			$_SESSION['empDataTrack'] = 'next';
			header('Location: ' . $source);

		break;


		case 'retrieveLeaveData':

			$leavestate = filter_var($_GET['state'], FILTER_SANITIZE_STRING);
			$_SESSION['leavestate'] = $leavestate;

			header('Location: ' . $source);


		break;

		case 'retrieveSingleEmployeeData':

                $empnumber = filter_var($_POST['empearnings'], FILTER_SANITIZE_STRING);
                $_SESSION['empDataTrack'] = 'option';
                $_SESSION['emptNumTack'] = $empnumber;

                header('Location: ' . $source);

        break;



		case 'vtrans':
			$empRecordId = filter_var($_GET['td'], FILTER_SANITIZE_STRING);
			//exit($empRecordId);
			$_SESSION['empDataTrack'] = 'option';
			$_SESSION['emptNumTack'] = $empRecordId;

			header('Location: ../../empearnings.php');
		break;


		case 'runCurrentEmployeePayroll':
			
			define('TAX_RELIEF', '1280' );

			$thisemployee = filter_var($_POST['thisemployee'], FILTER_SANITIZE_STRING);

			//check if employee has basic salary, if not return error & exit
			$query = $conn->prepare('SELECT earningDeductionCode FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ? ');
			$rerun = $query->execute([$thisemployee, $_SESSION['companyid'], '200', $_SESSION['currentactiveperiod'], '1']);

			if (!$row = $query->fetch()) {
				$_SESSION['msg'] = $msg = "This employee has no basic salary. Please assign basic salary in order to process employee's earnings.";
				$_SESSION['alertcolor'] = 'danger';
				header('Location: ' . $source);
			} else {

				//check if employee rerun
				try{
					$query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ? ');
					$rerun = $query->execute([$thisemployee, $_SESSION['companyid'], '601', $_SESSION['currentactiveperiod'], '1']);

					if ($row = $query->fetch()) {

						$query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
	                    $fin = $query->execute([$thisemployee, $_SESSION['companyid'], 'Earning', $_SESSION['currentactiveperiod'], '1']);
	                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
	                    $thisemployeeearnings = 0;

	                    foreach ($res as $row => $link) {
	                    	$thisemployeeearnings = $thisemployeeearnings + $link['amount'];
	                    }

	                    $recordtime = date('Y-m-d H:i:s');
	                    	//Run with an update query
	                    $grossquery = 'UPDATE employee_earnings_deductions SET amount = ?, editTime = ?, userId = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
						$conn->prepare($grossquery)->execute([$thisemployeeearnings, $recordtime, $_SESSION['user'], $thisemployee, $_SESSION['companyid'], '601',  $_SESSION['currentactiveperiod'], '1']);

						//NHIF Bands
								if ($thisemployeeearnings > 0 && $thisemployeeearnings < 5999) {
									$thisEmpNhif = 150;
								} elseif ($thisemployeeearnings > 5999 && $thisemployeeearnings <= 7999) {
									$thisEmpNhif = 300;
								} elseif ($thisemployeeearnings > 7999 && $thisemployeeearnings <= 11999) {
									$thisEmpNhif = 400;
								} elseif ($thisemployeeearnings > 11999 && $thisemployeeearnings <= 14999) {
									$thisEmpNhif = 500;
								} elseif ($thisemployeeearnings > 14999 && $thisemployeeearnings <= 19999) {
									$thisEmpNhif = 600;
								} elseif ($thisemployeeearnings > 19999 && $thisemployeeearnings <= 24999) {
									$thisEmpNhif = 750;
								} elseif ($thisemployeeearnings > 24999 && $thisemployeeearnings <= 29999) {
									$thisEmpNhif = 850;
								} elseif ($thisemployeeearnings > 29999 && $thisemployeeearnings <= 34999) {
									$thisEmpNhif = 900;
								} elseif ($thisemployeeearnings > 34999 && $thisemployeeearnings <= 39999) {
									$thisEmpNhif = 950;
								} elseif ($thisemployeeearnings > 39999 && $thisemployeeearnings <= 44999) {
									$thisEmpNhif = 1000;
								} elseif ($thisemployeeearnings > 44999 && $thisemployeeearnings <= 49999) {
									$thisEmpNhif = 1100;
								} elseif ($thisemployeeearnings > 49999 && $thisemployeeearnings <= 59999) {
									$thisEmpNhif = 1200;
								} elseif ($thisemployeeearnings > 59999 && $thisemployeeearnings <= 69999) {
									$thisEmpNhif = 1300;
								} elseif ($thisemployeeearnings > 69999 && $thisemployeeearnings <= 79999) {
									$thisEmpNhif = 1400;
								} elseif ($thisemployeeearnings > 79999 && $thisemployeeearnings <= 89999) {
									$thisEmpNhif = 1500;
								} elseif ($thisemployeeearnings > 89999 && $thisemployeeearnings <= 99999) {
									$thisEmpNhif = 1600;
								} elseif ($thisemployeeearnings > 99999) {
									$thisEmpNhif = 1700;
								}

							$nhifquery = 'UPDATE employee_earnings_deductions SET amount = ?, editTime = ?, userId = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
							$conn->prepare($nhifquery)->execute([$thisEmpNhif, $recordtime, $_SESSION['user'], $thisemployee, $_SESSION['companyid'], '481',  $_SESSION['currentactiveperiod'], '1']);

						//NSSF is standard. No recalculation
							$thisemployeeNssfBand1 = 200;
						//Compute Taxable Income
							$thisEmpTaxablePay = $thisemployeeearnings - $thisemployeeNssfBand1;
							$taxpayquery = 'UPDATE employee_earnings_deductions SET amount = ?, editTime = ?, userId = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
							$conn->prepare($taxpayquery)->execute([$thisEmpTaxablePay, $recordtime, $_SESSION['user'], $thisemployee, $_SESSION['companyid'], '400',  $_SESSION['currentactiveperiod'], '1']);

						//Compute PAYE
							$employeepayee = 0;
							$taxpay = $thisEmpTaxablePay;
							if ($taxpay > 0 && $taxpay <= 11180) {
								$employeepayee = $taxpay * 0.1;
							} elseif ($taxpay > 11180 && $taxpay <= 21714) {
								$employeepayee = (11180 * 0.1) + (($taxpay - 11180)*0.15);
							} elseif ($taxpay > 21714 && $taxpay <= 32248) {
								$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (($taxpay - 11181 - 10533)*0.2);
							} elseif ($taxpay > 32248 && $taxpay <= 42782) {
								$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (10534 * 0.2) + (($taxpay - 11181 - 10533 - 10534)*0.25);
							} elseif ($taxpay > 42782) {
								$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (10534 * 0.2) + (10534 * 0.25) + (($taxpay - 11181 - 10533 - 10534 - 10534)*0.3);
							}

							$taxcharged = $employeepayee;
							$taxchargequery = 'UPDATE employee_earnings_deductions SET amount = ?, editTime = ?, userId = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
							$conn->prepare($taxchargequery)->execute([$taxcharged, $recordtime, $_SESSION['user'], $thisemployee, $_SESSION['companyid'], '399',  $_SESSION['currentactiveperiod'], '1']);


							$finalEmployeePayee = $employeepayee - TAX_RELIEF;

							if ($finalEmployeePayee  <= 0) {
								$finalEmployeePayee = 0;
							}

							$taxpayequery = 'UPDATE employee_earnings_deductions SET amount = ?, editTime = ?, userId = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
							$conn->prepare($taxpayequery)->execute([$finalEmployeePayee, $recordtime, $_SESSION['user'], $thisemployee, $_SESSION['companyid'], '550',  $_SESSION['currentactiveperiod'], '1']);


						//Fetch and populate all deductions and write total
							$query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
		                    $fin = $query->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', $_SESSION['currentactiveperiod'], '1']);
		                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
		                    $thisemployeeearnings = 0;

		                    foreach ($res as $row => $link) {
		                    	$thisemployeedeductions = $thisemployeedeductions + $link['amount'];
		                    }

		                    $recordtime = date('Y-m-d H:i:s');
		                    $deductionsquery = 'UPDATE employee_earnings_deductions SET amount = ?, editTime = ?, userId = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
							$conn->prepare($deductionsquery)->execute([$thisemployeedeductions, $recordtime, $_SESSION['user'], $thisemployee, $_SESSION['companyid'], '603',  $_SESSION['currentactiveperiod'], '1']);

						//Calculate Net Salary
							$thisemployeeNet = $thisEmpTaxablePay - $thisemployeedeductions;

							$netquery = 'UPDATE employee_earnings_deductions SET amount = ?, editTime = ?, userId = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
							$conn->prepare($netquery)->execute([$thisemployeeNet, $recordtime, $_SESSION['user'], $thisemployee, $_SESSION['companyid'], '600',  $_SESSION['currentactiveperiod'], '1']);


						$_SESSION['msg'] = 'Employee payroll re-run successful';
						$_SESSION['alertcolor'] = 'success';
						//echo $thisemployeeearnings;
						//exit("Re run");
						header('Location: ' . $source);
					}
					else {
						//new; insert records
						//Fetch and populate all taxable earnings and write total
							$query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
		                    $fin = $query->execute([$thisemployee, $_SESSION['companyid'], 'Earning', $_SESSION['currentactiveperiod'], '1']);
		                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
		                    $thisemployeeearnings = 0;

		                    foreach ($res as $row => $link) {
		                    	$thisemployeeearnings = $thisemployeeearnings + $link['amount'];
		                    }

		                    $recordtime = date('Y-m-d H:i:s');
		                    $grossquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
							$conn->prepare($grossquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '601', $thisemployeeearnings, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

						//Get initial statutories - NHIF, NSSF, Tax relief
							//NHIF Bands
								if ($thisemployeeearnings > 0 && $thisemployeeearnings < 5999) {
									$thisEmpNhif = 150;
								} elseif ($thisemployeeearnings > 5999 && $thisemployeeearnings <= 7999) {
									$thisEmpNhif = 300;
								} elseif ($thisemployeeearnings > 7999 && $thisemployeeearnings <= 11999) {
									$thisEmpNhif = 400;
								} elseif ($thisemployeeearnings > 11999 && $thisemployeeearnings <= 14999) {
									$thisEmpNhif = 500;
								} elseif ($thisemployeeearnings > 14999 && $thisemployeeearnings <= 19999) {
									$thisEmpNhif = 600;
								} elseif ($thisemployeeearnings > 19999 && $thisemployeeearnings <= 24999) {
									$thisEmpNhif = 750;
								} elseif ($thisemployeeearnings > 24999 && $thisemployeeearnings <= 29999) {
									$thisEmpNhif = 850;
								} elseif ($thisemployeeearnings > 29999 && $thisemployeeearnings <= 34999) {
									$thisEmpNhif = 900;
								} elseif ($thisemployeeearnings > 34999 && $thisemployeeearnings <= 39999) {
									$thisEmpNhif = 950;
								} elseif ($thisemployeeearnings > 39999 && $thisemployeeearnings <= 44999) {
									$thisEmpNhif = 1000;
								} elseif ($thisemployeeearnings > 44999 && $thisemployeeearnings <= 49999) {
									$thisEmpNhif = 1100;
								} elseif ($thisemployeeearnings > 49999 && $thisemployeeearnings <= 59999) {
									$thisEmpNhif = 1200;
								} elseif ($thisemployeeearnings > 59999 && $thisemployeeearnings <= 69999) {
									$thisEmpNhif = 1300;
								} elseif ($thisemployeeearnings > 69999 && $thisemployeeearnings <= 79999) {
									$thisEmpNhif = 1400;
								} elseif ($thisemployeeearnings > 79999 && $thisemployeeearnings <= 89999) {
									$thisEmpNhif = 1500;
								} elseif ($thisemployeeearnings > 89999 && $thisemployeeearnings <= 99999) {
									$thisEmpNhif = 1600;
								} elseif ($thisemployeeearnings > 99999) {
									$thisEmpNhif = 1700;
								}

			                    $nhifquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
								$conn->prepare($nhifquery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '481', $thisEmpNhif, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

						//NSSF Band Calculation
							$thisemployeeNssfBand1 = 200;

							/*$thisemployeeNssfBand1 = $thisemployeeearnings * 0.06;
							if ($thisemployeeNssfBand1 > 360) {
								$thisemployeeNssfBand1 = 360;
							}*/
							$nssfquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
							$conn->prepare($nssfquery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '482', $thisemployeeNssfBand1, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

						//Compute Taxable Income
							$thisEmpTaxablePay = $thisemployeeearnings - $thisemployeeNssfBand1;
							$taxpayquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
							$conn->prepare($taxpayquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '400', $thisEmpTaxablePay, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

						
						//Compute PAYE
							$employeepayee = 0;
							$taxpay = $thisEmpTaxablePay;
							if ($taxpay > 0 && $taxpay <= 11180) {
								$employeepayee = $taxpay * 0.1;
							} elseif ($taxpay > 11180 && $taxpay <= 21714) {
								$employeepayee = (11180 * 0.1) + (($taxpay - 11180)*0.15);
							} elseif ($taxpay > 21714 && $taxpay <= 32248) {
								$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (($taxpay - 11181 - 10533)*0.2);
							} elseif ($taxpay > 32248 && $taxpay <= 42782) {
								$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (10534 * 0.2) + (($taxpay - 11181 - 10533 - 10534)*0.25);
							} elseif ($taxpay > 42782) {
								$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (10534 * 0.2) + (10534 * 0.25) + (($taxpay - 11181 - 10533 - 10534 - 10534)*0.3);
							}

							$taxcharged = $employeepayee;
							$taxchargequery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
							$conn->prepare($taxchargequery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '399', $taxcharged, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

							$finalEmployeePayee = $employeepayee - TAX_RELIEF;

							if ($finalEmployeePayee  <= 0) {
								$finalEmployeePayee = 0;
							}

							$taxpayequery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
							$conn->prepare($taxpayequery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '550', $finalEmployeePayee, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);


						//Fetch and populate all deductions and write total
							$query = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
		                    $fin = $query->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', $_SESSION['currentactiveperiod'], '1']);
		                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
		                    $thisemployeedeductions = 0;

		                    foreach ($res as $row => $link) {
		                    	$thisemployeedeductions = $thisemployeedeductions + $link['amount'];
		                    }

		                    $recordtime = date('Y-m-d H:i:s');
		                    $deductionsquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
							$conn->prepare($deductionsquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '603', $thisemployeedeductions, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

						//Calculate Net Salary
							$thisemployeeNet = $thisEmpTaxablePay - $thisemployeedeductions;

							$netquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
							$conn->prepare($netquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '600', $thisemployeeNet, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

						$_SESSION['msg'] = 'Employee payroll run successful';
						$_SESSION['alertcolor'] = 'success';
						header('Location: ' . $source);

					}	
				}

				catch(PDOException $e){
					echo $e->getMessage();
				}
			}
			
			

		break;


		case 'runGlobalPayroll':
			//Check all employees on missing basic salaries. If return error. --- Check done on submitting page

			//Check if first period run, or its a rerun
				//exit($_SESSION['companyid']);
				$query = $conn->prepare('SELECT payrollRun FROM payperiods WHERE periodId = ? AND companyId = ? AND active = ? AND payrollRun = ? ');
				$rerun = $query->execute([$_SESSION['currentactiveperiod'], $_SESSION['companyid'], '1', '0']);

				if ($row = $query->fetch()) {
					//New run
					//Delete all computed figures for this period and copany
						$globalsql = $conn->prepare('DELETE FROM employee_earnings_deductions WHERE companyId = ? AND transactionType = ? OR transactionType = ? OR earningDeductionCode = ? OR earningDeductionCode = ? OR earningDeductionCode = ? AND payPeriod = ?');
						$globalsql->execute([$_SESSION['companyid'], 'Calc', 'Notax', '481', '482', '550', $_SESSION['currentactiveperiod']]);
					
					//Cycle through employees & execute payroll

							$query = $conn->prepare('SELECT empNumber FROM employees WHERE companyId = ? AND active =? ORDER BY id ASC');
		                    $query->execute([$_SESSION['companyid'], '1']);
		                    $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
		                    $employeecount = $query->rowCount();
		                    //print($employeecount . "<br />");
		                    //print_r($ftres);
		                    //exit();

		                    $counter = 0;
		                    //$missingbasic = 0;
		                    //$setbasic = 0;

		                    while ($counter < $employeecount) {
		                        //echo $ftres[$counter] . "<br /> ";
		                        $thisemployee = $ftres[$counter];


		                        	//Fetch and populate all taxable earnings and write total
									$equery = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
				                    $fin = $equery->execute([$thisemployee, $_SESSION['companyid'], 'Earning', $_SESSION['currentactiveperiod'], '1']);
				                    $res = $equery->fetchAll(PDO::FETCH_ASSOC);
				                    $thisemployeeearnings = 0;

				                    foreach ($res as $row => $link) {
				                    	$thisemployeeearnings = $thisemployeeearnings + $link['amount'];
				                    }

				                    $recordtime = date('Y-m-d H:i:s');
				                    $grossquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($grossquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '601', $thisemployeeearnings, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								//Get initial statutories - NHIF, NSSF, Tax relief
									//NHIF Bands
										if ($thisemployeeearnings > 0 && $thisemployeeearnings < 5999) {
											$thisEmpNhif = 150;
										} elseif ($thisemployeeearnings > 5999 && $thisemployeeearnings <= 7999) {
											$thisEmpNhif = 300;
										} elseif ($thisemployeeearnings > 7999 && $thisemployeeearnings <= 11999) {
											$thisEmpNhif = 400;
										} elseif ($thisemployeeearnings > 11999 && $thisemployeeearnings <= 14999) {
											$thisEmpNhif = 500;
										} elseif ($thisemployeeearnings > 14999 && $thisemployeeearnings <= 19999) {
											$thisEmpNhif = 600;
										} elseif ($thisemployeeearnings > 19999 && $thisemployeeearnings <= 24999) {
											$thisEmpNhif = 750;
										} elseif ($thisemployeeearnings > 24999 && $thisemployeeearnings <= 29999) {
											$thisEmpNhif = 850;
										} elseif ($thisemployeeearnings > 29999 && $thisemployeeearnings <= 34999) {
											$thisEmpNhif = 900;
										} elseif ($thisemployeeearnings > 34999 && $thisemployeeearnings <= 39999) {
											$thisEmpNhif = 950;
										} elseif ($thisemployeeearnings > 39999 && $thisemployeeearnings <= 44999) {
											$thisEmpNhif = 1000;
										} elseif ($thisemployeeearnings > 44999 && $thisemployeeearnings <= 49999) {
											$thisEmpNhif = 1100;
										} elseif ($thisemployeeearnings > 49999 && $thisemployeeearnings <= 59999) {
											$thisEmpNhif = 1200;
										} elseif ($thisemployeeearnings > 59999 && $thisemployeeearnings <= 69999) {
											$thisEmpNhif = 1300;
										} elseif ($thisemployeeearnings > 69999 && $thisemployeeearnings <= 79999) {
											$thisEmpNhif = 1400;
										} elseif ($thisemployeeearnings > 79999 && $thisemployeeearnings <= 89999) {
											$thisEmpNhif = 1500;
										} elseif ($thisemployeeearnings > 89999 && $thisemployeeearnings <= 99999) {
											$thisEmpNhif = 1600;
										} elseif ($thisemployeeearnings > 99999) {
											$thisEmpNhif = 1700;
										}

					                    $nhifquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
										$conn->prepare($nhifquery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '481', $thisEmpNhif, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								//NSSF Band Calculation
									$thisemployeeNssfBand1 = 200;

									/*$thisemployeeNssfBand1 = $thisemployeeearnings * 0.06;
									if ($thisemployeeNssfBand1 > 360) {
										$thisemployeeNssfBand1 = 360;
									}*/
									$nssfquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($nssfquery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '482', $thisemployeeNssfBand1, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								//Compute Taxable Income
									$thisEmpTaxablePay = $thisemployeeearnings - $thisemployeeNssfBand1;
									$taxpayquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($taxpayquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '400', $thisEmpTaxablePay, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								
								//Compute PAYE
									$employeepayee = 0;
									$taxpay = $thisEmpTaxablePay;
									if ($taxpay > 0 && $taxpay <= 11180) {
										$employeepayee = $taxpay * 0.1;
									} elseif ($taxpay > 11180 && $taxpay <= 21714) {
										$employeepayee = (11180 * 0.1) + (($taxpay - 11180)*0.15);
									} elseif ($taxpay > 21714 && $taxpay <= 32248) {
										$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (($taxpay - 11181 - 10533)*0.2);
									} elseif ($taxpay > 32248 && $taxpay <= 42782) {
										$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (10534 * 0.2) + (($taxpay - 11181 - 10533 - 10534)*0.25);
									} elseif ($taxpay > 42782) {
										$employeepayee = (11180 * 0.1) + (10534 * 0.15) + (10534 * 0.2) + (10534 * 0.25) + (($taxpay - 11181 - 10533 - 10534 - 10534)*0.3);
									}

									$taxcharged = $employeepayee;
									$taxchargequery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($taxchargequery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '399', $taxcharged, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);


									$finalEmployeePayee = $employeepayee - TAX_RELIEF;

									if ($finalEmployeePayee  <= 0) {
										$finalEmployeePayee = 0;
									}

									$taxpayequery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($taxpayequery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '550', $finalEmployeePayee, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);


								//Fetch and populate all deductions and write total
									$dedquery = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
				                    $fin = $dedquery->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', $_SESSION['currentactiveperiod'], '1']);
				                    $res = $dedquery->fetchAll(PDO::FETCH_ASSOC);
				                    $thisemployeedeductions = 0;

				                    foreach ($res as $row => $link) {
				                    	$thisemployeedeductions = $thisemployeedeductions + $link['amount'];
				                    }

				                    $recordtime = date('Y-m-d H:i:s');
				                    $deductionsquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($deductionsquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '603', $thisemployeedeductions, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);
									//exit($thisemployee . "," . $thisemployeedeductions);

								//Calculate Net Salary
									$thisemployeeNet = $thisEmpTaxablePay - $thisemployeedeductions;									
									$netquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($netquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '600', $thisemployeeNet, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								$counter++;
		                    }
		            //end employee cycle

					//change payroll run flag::::::::::::::::::::::::::::::::::::::::::::::::
		                $periodsql = $conn->prepare('UPDATE payperiods SET payrollRun = ? WHERE periodId = ? AND companyId = ? AND active = ? AND payrollRun = ?');
						$periodsql->execute(['1', $_SESSION['currentactiveperiod'], $_SESSION['companyid'], '1', '0']);


		                $_SESSION['msg'] = 'Employee payroll run successful';
						$_SESSION['alertcolor'] = 'success';
						header('Location: ' . $source);

					//exit('New run');

				} 
				else {
					//Rerun
						//Delete all computed figures for this period and copany
						$globalsql = $conn->prepare('DELETE FROM employee_earnings_deductions WHERE companyId = ? AND transactionType = ? OR transactionType = ? OR earningDeductionCode = ? OR earningDeductionCode = ? OR earningDeductionCode = ? AND payPeriod = ?');
						$globalsql->execute([$_SESSION['companyid'], 'Calc', 'Notax', '481', '482', '550', $_SESSION['currentactiveperiod']]);

						//Emplyee cycle
						$query = $conn->prepare('SELECT empNumber FROM employees WHERE companyId = ? AND active =? ORDER BY id ASC');
	                    $query->execute([$_SESSION['companyid'], '1']);
	                    $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
	                    $employeecount = $query->rowCount();
	                    //print($employeecount . "<br />");
	                    //print_r($ftres);

	                    $counter = 0;
	                    //$missingbasic = 0;
	                    //$setbasic = 0;

	                    while ($counter < $employeecount) {
	                        //echo $ftres[$counter] . "<br /> ";
	                    		$thisemployee = $ftres[$counter];


		                        	//Fetch and populate all taxable earnings and write total
									$equery = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
				                    $fin = $equery->execute([$thisemployee, $_SESSION['companyid'], 'Earning', $_SESSION['currentactiveperiod'], '1']);
				                    $res = $equery->fetchAll(PDO::FETCH_ASSOC);
				                    $thisemployeeearnings = 0;

				                    foreach ($res as $row => $link) {
				                    	$thisemployeeearnings = $thisemployeeearnings + $link['amount'];
				                    }

				                    $recordtime = date('Y-m-d H:i:s');
				                    $grossquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($grossquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '601', $thisemployeeearnings, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								//Get initial statutories - NHIF, NSSF, Tax relief
									//NHIF Bands
										if ($thisemployeeearnings > 0 && $thisemployeeearnings < 5999) {
											$thisEmpNhif = 150;
										} elseif ($thisemployeeearnings > 5999 && $thisemployeeearnings <= 7999) {
											$thisEmpNhif = 300;
										} elseif ($thisemployeeearnings > 7999 && $thisemployeeearnings <= 11999) {
											$thisEmpNhif = 400;
										} elseif ($thisemployeeearnings > 11999 && $thisemployeeearnings <= 14999) {
											$thisEmpNhif = 500;
										} elseif ($thisemployeeearnings > 14999 && $thisemployeeearnings <= 19999) {
											$thisEmpNhif = 600;
										} elseif ($thisemployeeearnings > 19999 && $thisemployeeearnings <= 24999) {
											$thisEmpNhif = 750;
										} elseif ($thisemployeeearnings > 24999 && $thisemployeeearnings <= 29999) {
											$thisEmpNhif = 850;
										} elseif ($thisemployeeearnings > 29999 && $thisemployeeearnings <= 34999) {
											$thisEmpNhif = 900;
										} elseif ($thisemployeeearnings > 34999 && $thisemployeeearnings <= 39999) {
											$thisEmpNhif = 950;
										} elseif ($thisemployeeearnings > 39999 && $thisemployeeearnings <= 44999) {
											$thisEmpNhif = 1000;
										} elseif ($thisemployeeearnings > 44999 && $thisemployeeearnings <= 49999) {
											$thisEmpNhif = 1100;
										} elseif ($thisemployeeearnings > 49999 && $thisemployeeearnings <= 59999) {
											$thisEmpNhif = 1200;
										} elseif ($thisemployeeearnings > 59999 && $thisemployeeearnings <= 69999) {
											$thisEmpNhif = 1300;
										} elseif ($thisemployeeearnings > 69999 && $thisemployeeearnings <= 79999) {
											$thisEmpNhif = 1400;
										} elseif ($thisemployeeearnings > 79999 && $thisemployeeearnings <= 89999) {
											$thisEmpNhif = 1500;
										} elseif ($thisemployeeearnings > 89999 && $thisemployeeearnings <= 99999) {
											$thisEmpNhif = 1600;
										} elseif ($thisemployeeearnings > 99999) {
											$thisEmpNhif = 1700;
										}

					                    $nhifquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
										$conn->prepare($nhifquery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '481', $thisEmpNhif, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								//NSSF Band Calculation
									$thisemployeeNssfBand1 = 200;

									/*$thisemployeeNssfBand1 = $thisemployeeearnings * 0.06;
									if ($thisemployeeNssfBand1 > 360) {
										$thisemployeeNssfBand1 = 360;
									}*/
									$nssfquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($nssfquery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '482', $thisemployeeNssfBand1, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								//Compute Taxable Income
									$thisEmpTaxablePay = $thisemployeeearnings - $thisemployeeNssfBand1;
									$taxpayquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($taxpayquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '400', $thisEmpTaxablePay, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								
								//Compute PAYE
									$employeepayee = 0;
									$taxpay = $thisEmpTaxablePay;
									if ($taxpay < 11181) {
										$employeepayee = $taxpay * 0.1;
									} elseif ($taxpay > 11181 && $taxpay < 21714) {
										$employeepayee = (11181 * 0.1) + (($taxpay - 11181)*0.15);
									} elseif ($taxpay > 21714 && $taxpay < 32248) {
										$employeepayee = (11181 * 0.1) + (10533 * 0.15) + (($taxpay - 11181 - 10533)*0.2);
									} elseif ($taxpay > 32248 && $taxpay < 42782) {
										$employeepayee = (11181 * 0.1) + (10533 * 0.15) + (10534 * 0.2) + (($taxpay - 11181 - 10533 - 10534)*0.25);
									} elseif ($taxpay > 42782) {
										$employeepayee = $taxpay * 0.3;
									}

									$taxcharged = $employeepayee;
									$taxchargequery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($taxchargequery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '399', $taxcharged, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);


									$finalEmployeePayee = $employeepayee - 1162;

									if ($finalEmployeePayee  <= 0) {
										$finalEmployeePayee = 0;
									}

									$taxpayequery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($taxpayequery)->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', '550', $finalEmployeePayee, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);


								//Fetch and populate all deductions and write total
									$dedquery = $conn->prepare('SELECT * FROM employee_earnings_deductions WHERE employeeId = ? AND companyId = ? AND transactionType = ? AND payPeriod = ? AND active = ? ');
				                    $fin = $dedquery->execute([$thisemployee, $_SESSION['companyid'], 'Deduction', $_SESSION['currentactiveperiod'], '1']);
				                    $res = $dedquery->fetchAll(PDO::FETCH_ASSOC);
				                    $thisemployeedeductions = 0;

				                    foreach ($res as $row => $link) {
				                    	$thisemployeedeductions = $thisemployeedeductions + $link['amount'];
				                    }

				                    $recordtime = date('Y-m-d H:i:s');
				                    $deductionsquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($deductionsquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '603', $thisemployeedeductions, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);
									//exit($thisemployee . "," . $thisemployeedeductions);

								//Calculate Net Salary
									$thisemployeeNet = $thisEmpTaxablePay - $thisemployeedeductions;									
									$netquery = 'INSERT INTO employee_earnings_deductions (employeeId, companyId, transactionType, earningDeductionCode, amount, payPeriod, standardRecurrent, active, editTime, userId) VALUES (?,?,?,?,?,?,?,?,?,?)';
									$conn->prepare($netquery)->execute([$thisemployee, $_SESSION['companyid'], 'Calc', '600', $thisemployeeNet, $_SESSION['currentactiveperiod'], '0', '1', $recordtime, $_SESSION['user']]);

								$counter++;
	                    }

						$_SESSION['msg'] = 'Employee payroll run successful';
						$_SESSION['alertcolor'] = 'success';
						header('Location: ' . $source);
				}

			exit($_SESSION['companyid'] . 'Entire Employee Run');
		break;


		case 'addNewLeave':
			//check for existing same employee number

			$empnumber = filter_var($_POST['empnumber'], FILTER_SANITIZE_STRING);
			$leavetype = filter_var($_POST['leavetype'], FILTER_SANITIZE_STRING);
			$startleave = date('Y-m-d', strtotime(filter_var($_POST['startleave'], FILTER_SANITIZE_STRING)));
				$day1 = strtotime(filter_var($_POST['startleave'], FILTER_SANITIZE_STRING));
			$endleave = date('Y-m-d', strtotime(filter_var($_POST['endleave'], FILTER_SANITIZE_STRING)));
				$day2 = strtotime(filter_var($_POST['endleave'], FILTER_SANITIZE_STRING));

			$days_diff = $day2 - $day1;
    		$numofdays = date('d',$days_diff);

			$currdate = date('Y-m-d');
			//validate for empty mandatory fields

			try{
				//check for same leave request for same staffer
				$leavequery = $conn->prepare('SELECT * FROM hr_leave_requests WHERE employeeNumber = ? AND leaveType = ? AND status = ? OR status = ? AND active = ?');
				$res = $leavequery->execute([$empnumber, $leavetype, '1', '2', '1']);

				if ($row = $leavequery->fetch()) {
					$_SESSION['msg'] = $msg = "Active / Pending similar leave type for this employee. Please review all approved or pending leave requests.";
					$_SESSION['alertcolor'] = 'danger';
					header('Location: ' . $source);
				} else {
					$query = 'INSERT INTO hr_leave_requests (employeeNumber, leaveType, fromDate, toDate, applicationDate, numberOfDays, status) VALUES (?,?,?,?,?,?,?)';

					$conn->prepare($query)->execute([$empnumber, $leavetype, $startleave, $endleave, $currdate, $numofdays, '2']);
					
					$_SESSION['msg'] = $msg = "New Leave Successfully added.";
					$_SESSION['alertcolor'] = 'success';
					header('Location: ' . $source);
				}

			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'manageLeave':

			$empalternumber = filter_var($_POST['empalternumber'], FILTER_SANITIZE_STRING);
			$empalterid = filter_var($_POST['empalterid'], FILTER_SANITIZE_STRING);
			$leaveaction = filter_var($_POST['leaveaction'], FILTER_SANITIZE_STRING);
				//exit($empalternumber . ",". $empalterid. "," .$leaveaction);

			try{

				$query = ('UPDATE hr_leave_requests SET status = ? WHERE id = ? AND employeeNumber = ?');
				$conn->prepare($query)->execute([$leaveaction, $empalterid, $empalternumber]);

				$_SESSION['msg'] = $msg = "Leave status successfully amended";
				$_SESSION['alertcolor'] = 'success';
				header('Location: ' . $source);

			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'deactivateEmployee':
			$empalterid = filter_var($_POST['empalterid'], FILTER_VALIDATE_INT);
			$empalternumber = filter_var($_POST['empalternumber'], FILTER_SANITIZE_STRING);
			$exitdate = date('Y-m-d', strtotime(filter_var($_POST['exitdate'], FILTER_SANITIZE_STRING)));
			$exitreason = filter_var($_POST['exitreason'], FILTER_SANITIZE_STRING);
			$editDate = date('Y-m-d H:i:s');

			//exit($empalternumber . ", " . $empalterid . ", " . $exitdate . ", " . $exitreason);
			$query = 'UPDATE employees SET active = ? WHERE id = ? AND companyId = ? AND active = ?';
			$conn->prepare($query)->execute(['0', $empalterid, $_SESSION['companyid'], '1']);

				$deactivatequery = 'INSERT INTO hr_exited_employees (employeeId, exitDate, exitReason, editTime, userEditorId) VALUES (?,?,?,?,?)';
				$conn->prepare($deactivatequery)->execute([$empalternumber, $exitdate, $exitreason, $editDate, $_SESSION['user']]);
			
			$_SESSION['msg'] = $msg = "Employee successfully deactivated.";
			$_SESSION['alertcolor'] = 'success';
			header('Location: ' . $source);
		break;


		case 'suspendEmployee':
			$empalterid = filter_var($_POST['empalterid'], FILTER_VALIDATE_INT);
			$empalternumber = filter_var($_POST['empalternumber'], FILTER_SANITIZE_STRING);
			$startsuspension = date('Y-m-d', strtotime(filter_var($_POST['startsuspension'], FILTER_SANITIZE_STRING)));
			$endsuspension = date('Y-m-d', strtotime(filter_var($_POST['endsuspension'], FILTER_SANITIZE_STRING)));
			$suspendreason = filter_var($_POST['suspendreason'], FILTER_SANITIZE_STRING);
			$editDate = date('Y-m-d H:i:s');

			try{
				
				$susquery = $conn->prepare('SELECT * FROM employees WHERE empNumber = ? AND companyId = ? AND active = ? AND suspended = ?');
				$fin = $susquery->execute([$empalternumber, $_SESSION['companyid'], '1', '1']);

				if ($row = $susquery->fetch()){
					$_SESSION['msg'] = "Selected employee currently on suspension.";
					$_SESSION['alertcolor'] = "danger";
					header('Location: ' . $source);
				} else {
					//exit($empalternumber . ", " . $empalterid . ", " . $exitdate . ", " . $exitreason);
					$query = 'UPDATE employees SET suspended = ? WHERE empNumber = ? AND companyId = ? AND active = ? AND suspended = ?';
					$conn->prepare($query)->execute(['1', $empalternumber, $_SESSION['companyid'], '1', '0']);

						$deactivatequery = 'INSERT INTO employee_suspensions (employeeId, suspendStartDate, suspendEndDate, suspenReason, editTime, userEditorId) VALUES (?,?,?,?,?,?)';
						$conn->prepare($deactivatequery)->execute([$empalternumber, $startsuspension, $endsuspension, $suspendreason, $editDate, $_SESSION['user']]);
					
					$_SESSION['msg'] = $msg = "Employee successfully suspended.";
					$_SESSION['alertcolor'] = 'success';
					header('Location: ' . $source);
				}

			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'editemployeeearning':
			//exit('Edit Employee Earning');
			$empedit = filter_var($_POST['empeditnum'], FILTER_SANITIZE_STRING);
			$edited = filter_var($_POST['edited'], FILTER_VALIDATE_INT);
			$editname = filter_var($_POST['editname'], FILTER_VALIDATE_INT);
			$editvalue = filter_var($_POST['editvalue'], FILTER_VALIDATE_INT);
			$grossquery = 'UPDATE employee_earnings_deductions SET amount = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
			$conn->prepare($grossquery)->execute([$editvalue, $empedit, $_SESSION['companyid'], $edited,  $_SESSION['currentactiveperiod'], '1']);

			$_SESSION['msg'] = 'Successfully Edited Earning / Deduction';
			$_SESSION['alertcolor'] = 'success';
			header('Location: ' . $source);
		break;


		case 'deactivateEd':
			$empeditnum = filter_var($_POST['empeditnum'], FILTER_SANITIZE_STRING);
			$edited = filter_var($_POST['edited'], FILTER_VALIDATE_INT);
			//exit($empeditnum . " " . $edited . " " . $_SESSION['currentactiveperiod']);
			try{
				$query = 'UPDATE employee_earnings_deductions SET active = ? WHERE employeeId = ? AND companyId = ? AND earningDeductionCode = ? AND payPeriod = ? AND active = ?';
				$conn->prepare($query)->execute(['0', $empeditnum, $_SESSION['companyid'], $edited, $_SESSION['currentactiveperiod'], '1']);

				$_SESSION['msg'] = $msg = "E/D successfully deactivated.";
				$_SESSION['alertcolor'] = 'success';
				header('Location: ' . $source);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;


		case 'batchprocess':
			exit('Batch Process');
		break;


		case 'resetpass':
			//exit('reset');

			$title = "Password Reset";
			$resetemail = filter_var((filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)), FILTER_VALIDATE_EMAIL);

			//check if account exists with emailaddress
			$query = $conn->prepare('SELECT emailAddress FROM users WHERE emailAddress = ? AND active = ?');
			$fin = $query->execute([$resetemail, '1']);

			if ($row = $query->fetch()) {

				//Generate update token
				$reset_token = bin2hex(openssl_random_pseudo_bytes(32));
				
				//write token to token table and assign validity state, creation timestamp
				$tokenrecordtime = date('Y-m-d H:i:s');

				//check for any previous tokens and invalidate
					$tokquery = $conn->prepare('SELECT * FROM reset_token WHERE userEmail = ? AND valid = ? AND type = ?');
					$fin = $tokquery->execute([$resetemail, '1', '1']);
					
					if($row = $tokquery->fetch()){
						$upquery = 'UPDATE reset_token SET valid = ? WHERE userEmail = ? AND valid = ?';
						$conn->prepare($upquery)->execute(['0', $resetemail, '1']);
					}

				$tokenquery = 'INSERT INTO reset_token (userEmail, token, creationTime, valid, type) VALUES (?,?,?,?,?)';
				$conn->prepare($tokenquery)->execute([$resetemail, $reset_token, $tokenrecordtime, '1', '1']);
					
				//exit($resetemail . " " . $reset_token);
				
				$sendmessage = "You've recently asked to reset the password for this Redsphere Payroll account: " . $resetemail . "<br /><br />To update your password, click the link below:<br /><br /> " . $sysurl . 'password_reset.php?token=' . $reset_token;
				//generate reset cdde and append to email submitted

				require 'phpmailer/PHPMailerAutoload.php';

				$mail = new PHPMailer;

				$mail->SMTPDebug = 3;                               // Enable verbose debug output

				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.zoho.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'noreply@redsphere.co.ke';                 // SMTP username
				$mail->Password = 'redsphere_2017***';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				$mail->setFrom('noreply@redsphere.co.ke', 'Redsphere Payroll');
				$mail->addAddress($resetemail, 'Redsphere Payroll');     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				$mail->addReplyTo('info@example.com', 'Information');
				$mail->addCC('fgesora@gmail.com');
				//$mail->addBCC('bcc@example.com');

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
				    $_SESSION['msg'] = "If there is an account associated with this email address, an email has been sent to reset your password.";
				    $_SESSION['alertcolor'] = "success";
				    header("Location: " . $source);
				}

			} else {

				$_SESSION['msg'] = "If there is an account associated with this email address, an email has been sent to reset your password.";
			    $_SESSION['alertcolor'] = "success";
			    header("Location: " . $source);
			}
			
		break;



		case 'deactivateuser':
			$thisuser = filter_var($_POST['thisuser'], FILTER_SANITIZE_STRING);
			$useremail = filter_var($_POST['useremail'], FILTER_SANITIZE_STRING);

			try{
				$query = 'UPDATE users SET active = ? WHERE userId = ? AND emailAddress = ? AND companyId = ? AND active = ?';
				$conn->prepare($query)->execute(['0', $thisuser, $useremail, $_SESSION['companyid'], '1']);

				$_SESSION['msg'] = $msg = "User successfully deactivated.";
				$_SESSION['alertcolor'] = 'success';
				header('Location: ' . $source);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

		break;



		case 'logout':
			$_SESSION['logged_in'] = '0';
			unset($_SESSION['user']);
			unset($_SESSION['email']);
    		unset($_SESSION['first_name']);
    		unset($_SESSION['last_name']);
    		unset($_SESSION['companyid']);
    		unset($_SESSION['emptrack']);
    		unset($_SESSION['currentactiveperiod']);
    		unset($_SESSION['activeperiodDescription']);
    		unset($_SESSION['msg']);
    		unset($_SESSION['alertcolor']);
    		unset($_SESSION['empDataTrack']);
    		unset($_SESSION['emptNumTack']);
    		
    		if (isset($_SESSION['leavestate'])) {
    			unset($_SESSION['leavestate']);
    		}

    		if (isset($_SESSION['periodstatuschange'])) {
    			unset($_SESSION['periodstatuschange']);
    		}

    		//reset global openview status
			$statuschange = $conn->prepare('UPDATE payperiods SET openview = ? ');
			$perres = $statuschange->execute(['0']);

    		$_SESSION['msg'] = $msg = "Successfully logged out";
    		$_SESSION['alertcolor'] = $type = "success";
    		$page = "../../index.php";
			header('Location: ' . $page);
		break;

		
		default:
			exit('Unexpected route. Please contact administrator.');
		break;
	}