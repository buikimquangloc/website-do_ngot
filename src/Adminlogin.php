<?php

namespace CT275\Project;

class Adminlogin
{
	private $db;
	private $adminId = -1;
	public $adminName;
	public $adminEmail;
	public $adminUser;
	public $adminPass;

	private $errors = [];

	public function getId()
	{
		return $this->id;
	}
	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function fill(array $data) {
		if (isset($data['adminName'])) {
			$this->adminName = trim($data['adminName']);
		}
		if (isset($data['adminEmail'])) {
			$this->adminEmail = trim($data['adminEmail']);
		}
		if (isset($data['adminUser'])) {
			$this->adminUser = $data['adminUser'];
		}
		if (isset($data['adminPass'])) {
			$this->adminPass = trim($data['adminPass']);
		}
		return $this;
	}

	public function validate() {
		if (!$this->adminUser) {
			$this->errors['adminUser'] = 'Chưa nhập tài khoản!';
		} 
		if (!$this->adminPass) {
			$this->errors['adminPass'] = 'Chưa nhập mật khẩu!';
		} 
		else if ($this->db->query("select * from tbl_admin where adminPass = $this->adminPass")->rowCount() > 0){
			$this->errors['adminPass'] = 'Mật khẩu không khớp!';
		}
		return empty($this->errors);
	}

	public function getValidationErrors() {
		return $this->errors;
	}

	public function login_admin()
	{
		$stmt = $this->db->prepare('SELECT * FROM tbl_admin WHERE adminUser = :adminUser AND adminPass = :adminPass');
		$stmt->execute(['adminUser' => $this->adminUser,'adminPass' => md5($this->adminPass)]);

		$row = $stmt->fetch();
		if ($stmt -> rowCount() > 0) {
			session_start();
			$_SESSION["adminlogin"] = true;
			$_SESSION["adminId"] = $row["adminId"];
			$_SESSION["adminUser"] = $row["adminUser"];
			$_SESSION["adminName"] = $row["adminName"];
			header('Location:index.php');
		} 
		else {
			$alert = "Tài khoản và mật khẩu không khớp";
			return $alert;
		}  
	}


}
