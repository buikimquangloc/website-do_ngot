<?php

namespace CT275\Project; 


// tạo lớp đăng nhập admin
class Adminlogin
{	
	// khai báo các biến cần thiết so với csdl admin 
	private $db;
	private $adminId = -1;
	public $adminName;
	public $adminEmail;
	public $adminUser;
	public $adminPass;

	private $errors = []; // khai báo mảng lỗi = rổng

	// hàm lấy id 
	public function getId()
	{
		return $this->id;
	}

	// hàm khỏi tạo đối tượng
	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// hàm kiểm tra xem các phần tử có tồn tại trong mảng data hay không. nếu tồn tại lưu lại
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

	// hàm kiểm tra xác thực tào khoản
	public function validate() {

		// nếu chưa nhập user name của admin 
		if (!$this->adminUser) {
			// lưu vào mảng lỗi
			$this->errors['adminUser'] = 'Chưa nhập tài khoản!';
		} 
		
		// nếu chưa nhập password
		if (!$this->adminPass) {
			// lưu vào mảng lỗi
			$this->errors['adminPass'] = 'Chưa nhập mật khẩu!';
		} 
		// ngược lại select đến bảng admin nếu password > 1 hàng 
		else if ($this->db->query("select * from tbl_admin where adminPass = $this->adminPass")->rowCount() > 0){
			// lưu lỗi
			$this->errors['adminPass'] = 'Mật khẩu không khớp!';
		}
		// ngược lại không có lỗi
		return empty($this->errors);
	}

	/// hàm lấy mã lỗi
	public function getValidationErrors() {
		return $this->errors;
	}

	// hàm đăng nhập
	public function login_admin()
	{	
		// khởi tạo đối tượng db kết nối đến csdl admin qua prepare() điều kiện trùng pass và user
		$stmt = $this->db->prepare('SELECT * FROM tbl_admin WHERE adminUser = :adminUser AND adminPass = :adminPass');
		// truyền tham số vào cho đối tượng
		$stmt->execute(['adminUser' => $this->adminUser,'adminPass' => md5($this->adminPass)]);
		// md5 mã hóa mật khẩu

		$row = $stmt->fetch(); // truy vấn đến csdl và lưu vào biến row
		if ($stmt -> rowCount() > 0) { // nếu lớn hơn 0 bảng ghi 
			session_start(); // bắt dầu phiên giao dịch
			$_SESSION["adminlogin"] = true; // đăng nhập = true
			$_SESSION["adminId"] = $row["adminId"]; // luu id của phiên giao dịch
			$_SESSION["adminUser"] = $row["adminUser"]; // ....
			$_SESSION["adminName"] = $row["adminName"]; // ...
			header('Location:index.php'); // chuyển hướng đến trang index.php của admin
		} 
		else { // ngược lại nếu bảng ghi = 0
			// hiển thị mật khẩu ko khớp
			$alert = "Tài khoản và mật khẩu không khớp"; 
			return $alert;
		}  
	}


}
