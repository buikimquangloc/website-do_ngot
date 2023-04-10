<?php

namespace CT275\Project;


/// tạo một lớp cart
class Cart
{
	// khai báo các biến cần thiết
	private $db;
	private $cartId = -1;
    public $customer_id;
    private $errors = []; // khai báo mảng lỗi 
    

	// hàm lấy id 
    public function getId()
	{
		return $this->cartId;
	}

	// hàm khỏi tao đối tượng db
	public function __construct($pdo)
	{
		$this->db = $pdo;
	}


	// kiểm tra phần tử có tồn tại trong csdl không, nếu có lưu lại 
    public function fill(array $data) {
		if (isset($data['customer_id'])) {
			$this->customer_id = trim($data['customer_id']);
		}
		return $this;
	}

	// lấy các thuộc tính của đối tượng
	protected function fillFromDB(array $row)
	{
		[
		'cartId' => $this->cartId,
		'customer_id' => $this->customer_id
		] = $row;
		return $this;
	}
    
	// hàm tìm sản phẩm
    public function find($cartId) {
		// tạo đối tượng db đọc select từ bảng cart với cartID
		$stmt = $this->db->prepare('select * from tbl_cart where cartId = :cartId');
		//lưu lại 
		$stmt->execute(['cartId' => $cartId]);
		// if có bảng ghi > 0 lưu vào row
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row); // lấy các thuộc tính và lưu vào đối tượng
			return $this;
		}
		// ngược lại không có kết quả
		return null;
	}

	// tìm theo id nười dùng
    public function find3($customer_id) {
		$stmt = $this->db->prepare('select * from tbl_cart where customer_id = :customer_id');
		$stmt->execute(['customer_id' => $customer_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	// hàm save
	public function save() {
		// khai báo kết quả chưa lưu
		$result = false;
		// if mà đã có cartid
		if ($this->cartId >= 0) {
			// update len bảng cart với cartid
			$stmt = $this->db->prepare('UPDATE tbl_cart SET status = 1 WHERE cartId = :cartId');
			// lưu lại 
			$result = $stmt->execute(['cartId' => $this->cartId]);
		} else { // chưa có 
			// insert sản phẩm 
			$stmt = $this->db->prepare('INSERT INTO tbl_cart(customer_id) 
							VALUES(:customer_id)');
			$result = $stmt->execute(['customer_id' => $this->customer_id]);

			if ($result) {
				$this->cartId = $this->db->lastInsertId();
			}
		}
		return $result;
	}
	
	// hàm xóa khỏi giỏ hàng
    public function del_product_cart() {
		// xóa hàng có id
        $stmt = $this->db->prepare(" DELETE FROM tbl_cart where cartId = :cartId");
		// lưu lại
        $stmt->execute(['cartId' => $this->cartId]);
    }

	// tất cả ản phẩm 
    public function all() {
        $carts = []; // giỏ hàng  = rổng
		// lấy tất cả phần tử trong giỏ hàng
        $stmt = $this->db->prepare("SELECT * FROM tbl_cart where status = 0");
		// lưu 
        $stmt->execute();
        while ($row = $stmt->fetch()) { // nếu có dl
            $cart = new Cart($this->db); // khỏi tạo đối tượng cart 
            $cart->fillFromDB($row); // điền vào đối tượng
            $carts[] = $cart; // lưu lại đối tương
        }
        return $carts;
    }  

	// hàm kiểm tra giỏ hàng
    public function check_cart() {
        $customer_id = $_SESSION['customer_id']; // lấy id của phiên giao dịch
		//lấy tất cả trong bảng giỏ hàng với id người dùng
        $stmt = $this->db->prepare("SELECT * FROM tbl_cart WHERE customer_id = :customer_id AND status = 0");4
		// thực thi
        $stmt->execute(['customer_id' => $customer_id]);
        return $stmt -> rowCount();
    }
          
}
