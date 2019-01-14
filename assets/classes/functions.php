<?php
	include_once('class.db.php');

	function redirect($msg,$type,$page)
	{
		$_SESSION['msg'] = $msg;
		$_SESSION['type'] = $type;
		header("Location: $page");
	}

	function retrieveUsers(){
		try{
            $query = $conn->prepare('SELECT * FROM users WHERE companyId = ? AND active = ?');
            $fin = $query->execute([$_SESSION['companyid'], '1']);
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            //print_r($res);

            foreach ($res as $row => $link) {
                ?><tr class="odd gradeX"> <?php echo '<td>' . $link['emailAddress'] .  '</td><td>' . $link['firstName'] . '</td><td>' . $link['lastName'] . '</td><td>' . $link['userTypeId'] . '</td></tr>';
            }

        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
	}