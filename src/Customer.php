<?php

namespace CT275\Project;

class Customer
{
	private $db;

	private $id = -1;

	public $name;
	public $address;
	public $phone;
	public $email;
	public $password;

	private $errors = [];

	public function getId() {
		return $this->id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function fill(array $data) {
		if (isset($data['name'])) {
			$this->name = trim($data['name']);
		}
		if (isset($data['address'])) {
			$this->address = trim($data['address']);
		}
		if (isset($data['phone'])) {
			$this->phone = $data['phone'];
		}
		if (isset($data['email'])) {
			$this->email = trim($data['email']);
		}
		if (isset($data['password'])) {
			$this->password = trim($data['password']);
		}
		return $this;
	}

	public function fillFromDB(array $row) {
		[
			'id' => $this->id,
			'name' => $this->name,
            'address' => $this->address,
			'phone' => $this->phone,
			'email' => $this->email,
			'password' => $this->password
		] = $row;
		return $this;
	}


	public function validate() {

		if (!$this->name) {
			$this->errors['name'] = 'Chưa nhập họ và tên!';
		} else if ($this->name < 6){
			$this->errors['name'] = 'Họ tên phải lớn hơn 6 ký tự';
		}

		if(!$this->address){
			$this->errors["address"] = "Bạn chưa nhập địa chỉ!";
		}

		if (strlen($this->phone) < 10 || strlen($this->phone) > 11) {
			$this->errors['phone'] = 'Số điện thoại không hợp lệ!';
		} else if(!preg_match('/^[0-9]{10}+$/', $this->phone)){
			$this->errors['phone'] = 'Số điện thoại phải bao gồm 10 chữ số!';
		}
		else if ($this->db->query("select * from tbl_customer where phone = $this->phone")->rowCount() > 0){
			$this->errors['phone'] = 'Số điện thoại đã được sử dụng!';
		}

		if (!$this->email) {
			$this->errors['email'] = 'Chưa nhập email!';
		} else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
			$this->errors['email'] = 'Email không hợp lệ!';
		}
		else if ($this->db->query("select * from tbl_customer where email like '$this->email'")->rowCount() > 0){
			$this->errors['email'] = 'Email đã được sử dụng!';
		}

		if(!$this->password){
			$this->errors["password"] = "Bạn chưa nhập mật khẩu";
		} else if(strlen($this->password) < 8){
			$this->errors["password"] = "Mật khẩu phải lớn hơn hoặc bằng 8 ký tự";
		}
		return empty($this->errors);
	}
	
	public function getValidationErrors() {
		return $this->errors;
	}

	public function validateLogin(){
		if(!$this->email){
			$this->errors["email"] = "Bạn chưa nhập email!";
		} elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
			$this->errors["email"] = "Email không hợp lệ!";
		} 
		if (!$this->password) {
			$this->errors["password"] = "Bạn chưa nhập mật khẩu!";
		} 
		return empty($this->errors);
	}

	public function getValidationLoginErrors() {
		return $this->errors;
	}
	
	public function insert_customers(){

		$stmt = $this->db->prepare("INSERT INTO tbl_customer(name,address,phone,email,password) 
									VALUES(:name,:address,:phone,:email,:password)");
		$stmt->execute(['name' => $this->name, 'address' => $this->address,'phone' => $this->phone,'email' => $this->email,'password' => md5($this->password)]);

		if($stmt -> rowCount() > 0) {
			$alert = "<span class='success text-success'>Đăng ký khách hàng thành công</span>";
			return $alert;
		}
		else{
			$alert = "<span class='error'>Đăng ký khách hàng thất bại </span>";
			return $alert;
		}
	}
	
	public function login_customers() {

		$stmt = $this->db->prepare("SELECT * FROM tbl_customer WHERE email = :email and password = :password");
		$stmt->execute(['email' => $this->email, 'password' => md5($this->password) ]);

		if($row = $stmt -> fetch()) {
			$_SESSION["customer_login"] = true;
			$_SESSION["customer_id"] = $row["id"];
			$_SESSION["customer_name"] = $row["name"];
		}	
	}

	public function find($id) {
		$stmt = $this->db->prepare('select * from tbl_customer where id = :id');
		$stmt->execute(['id' => $id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}
	
	public function all(){
	$customers = [];
	$stmt = $this->db->prepare("SELECT * FROM tbl_customer");
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		$customer = new Customer($this->db);
		$customer->fillFromDB($row);
		$customers[] = $customer;
	}
	return $customers;
	}
	
}
