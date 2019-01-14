<?php
include_once('class.db.php');

class UserServices{
	private $db;

	function __construct($conn){
		$this->db = $conn;
	}

	public function login($umail, $pass ){
		try{
			$stmt = $conn->prepare('SELECT * FROM users WHERE emailAddress = ? AND password = ?');
			$stmt->execute([$uname, $upass]);
			$res = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmt->rowCount() > 0) {
				exit('success');
			} else {exit('fail');}

		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
}