<?php
	//session_start();
	/*if (!defined('DIRECTACC')) {
        header('Status: 200');
        header('Location: ../../index.php');
	}*/

	include_once('class.db.php');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	function retrieveDescSingleFilter($table, $basevar, $filter1, $val1){
		global $conn;
		
		try{
			$query = $conn->prepare('SELECT ' . $basevar .' FROM ' . $table . ' WHERE ' . $filter1 . ' = ?');
	        $res = $query->execute([$val1]);
	        if ($row = $query->fetch()) {
	            echo($row[''. $basevar .'']);
	        }
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function &returnDescSingleFilter($table, $basevar, $filter1, $val1){
		global $conn;
		
		try{
			$query = $conn->prepare('SELECT ' . $basevar .' FROM ' . $table . ' WHERE ' . $filter1 . ' = ?');
	        $res = $query->execute([$val1]);
	        if ($row = $query->fetch()) {
	            return $row[''. $basevar .''];
	        }
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function retrieveCompanyDepartment($table, $basevar, $val1, $filter1){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $basevar . ' FROM ' . $table .' WHERE ' . $filter1 .  ' = ?');
            $res = $query->execute([$val1]);
            if ($row = $query->fetch()) {
                echo($row[''.$basevar.'']);
            }
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function retrieveDescDualFilter($table, $basevar, $val1, $filter1, $filter2, $val2){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $basevar . ' FROM ' . $table .' WHERE ' . $filter1 .  ' = ? AND ' . $filter2 . ' = ?');
            $res = $query->execute([$val1, $val2]);
            if ($row = $query->fetch()) {
                echo($row[''.$basevar.'']);
            }
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	
	function retrieveDescQuadFilter($table, $basevar, $val1, $filter1, $filter2, $val2, $filter3, $val3, $filter4, $val4){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $basevar . ' FROM ' . $table .' WHERE ' . $filter1 .  ' = ? AND ' . $filter2 . ' = ? AND ' . $filter3 . ' = ? AND ' . $filter4 . ' = ?');
            $res = $query->execute([$val1, $val2, $val3, $val4]);
            if ($row = $query->fetch()) {
                echo(number_format($row[''.$basevar.'']));
            } else {
            	echo '0';
            }
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}


	function retrieveDescPentaFilter($table, $basevar, $val1, $filter1, $filter2, $val2, $filter3, $val3, $filter4, $val4, $filter5, $val5){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $basevar . ' FROM ' . $table .' WHERE ' . $filter1 .  ' = ? AND ' . $filter2 . ' = ? AND ' . $filter3 . ' = ? AND ' . $filter4 . ' = ? AND ' . $filter5 . ' = ?');
            $res = $query->execute([$val1, $val2, $val3, $val4, $val5]);
            if ($row = $query->fetch()) {
                echo(number_format($row[''.$basevar.'']));
            } else {
            	echo '0';
            }
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}


	function &returnDescPentaFilter($table, $basevar, $val1, $filter1, $filter2, $val2, $filter3, $val3, $filter4, $val4, $filter5, $val5){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $basevar . ' FROM ' . $table .' WHERE ' . $filter1 .  ' = ? AND ' . $filter2 . ' = ? AND ' . $filter3 . ' = ? AND ' . $filter4 . ' = ? AND ' . $filter5 . ' = ?');
            $res = $query->execute([$val1, $val2, $val3, $val4, $val5]);
            if ($row = $query->fetch()) {
                return $row[''.$basevar.''];
            } else {
            	echo '0';
            }
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	
	function styleLabelColor($labelType){
		global $conn;

		try{
			if ($labelType == 'Earning') {				
				return "success";
			} elseif ($labelType == 'Deduction') {
				return "danger";
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	
	function retrieveSelect($table, $filter1, $filter2, $basevar, $sortvar){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $filter1 . ' FROM ' . $table . ' WHERE ' . $filter2 . ' = ? AND companyId = ? AND active = ? AND globalComputed = ? ORDER BY ' . $sortvar .'');
			$res = $query->execute([$basevar, $_SESSION['companyid'], '1', '0']);
			$out = $query->fetchAll(PDO::FETCH_ASSOC);
			
			while ($row = array_shift($out)) {
				echo('<option value="' . $row['edCode'] .'">' . $row['edCode'] . ' - ' . $row['edDesc'] . '</option>');
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function retrievePayrollSubTotal($basevar, $table, $filter1, $filter2, $filter3, $filter4, $var1, $var2){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' .$basevar. ' FROM '.$table.' WHERE '.$filter1.' = ? AND '.$filter2.' = ? AND '.$filter3.' = ? AND '.$filter4.' = ?');
			$ans = $query->execute([$var1, $var2, $_SESSION['currentactiveperiod'], '1']);
			
	        if ($row = $query->fetch()) {
                echo number_format($row[''.$basevar.'']);
            }
		}
		catch(PDOException $e){
			echo $e-getMessage();
		}
	}

	function retrieveEmployees($table, $filter1, $filter2, $basevar, $sortvar){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $filter1 . ' FROM ' . $table . ' WHERE ' . $filter2 . ' = ? AND companyId = ?');
			$res = $query->execute([$basevar, $_SESSION['companyid']]);
			$out = $query->fetchAll(PDO::FETCH_ASSOC);
			
			while ($row = array_shift($out)) {
				echo('<option value="' . $row['empNumber'] .'">' . $row['empNumber'] . ' - ' . $row['fName'] . ' ' . $row['lName'] . '</option>');
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function retrieveLeaveStatus($table, $filter1, $filter2, $basevar){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $filter1 . ' FROM ' . $table . ' WHERE ' . $filter2 . ' = ?');
			$res = $query->execute([$basevar]);
			$out = $query->fetchAll(PDO::FETCH_ASSOC);
			
			while ($row = array_shift($out)) {
				echo('<option value="' . $row['id'] .'">' . $row['statusDescription'] . '</option>');
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function retrieveLeaveTypes($table, $filter1, $filter2, $basevar){
		global $conn;

		try{
			$query = $conn->prepare('SELECT ' . $filter1 . ' FROM ' . $table . ' WHERE ' . $filter2 . ' = ?');
			$res = $query->execute([$basevar]);
			$out = $query->fetchAll(PDO::FETCH_ASSOC);
			
			while ($row = array_shift($out)) {
				echo('<option value="' . $row['id'] .'">' . $row['Leave_type'] . ' Leave </option>');
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function returnNumberOfEmployees(){
        global $conn;

        try{
        	$query = $conn->prepare('SELECT empNumber FROM employees WHERE companyId = ? AND active =? ORDER BY id ASC');
	        $query->execute([$_SESSION['companyid'], '1']);
	        $ftres = $query->fetchAll(PDO::FETCH_COLUMN);
	        $count = $query->rowCount();
	        echo $count;
        }
        catch(PDOException $e){
        	echo $e->getMessage();
        }
	}


?>