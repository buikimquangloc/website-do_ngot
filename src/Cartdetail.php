<?php

namespace CT275\Project;

// tạo lớp đơn hàng
class Cartdetail
{
	// khai báo các biến cần thiết
	private $db;
	private $cartId = -1;
    public $productId,$quantity;
    private $errors = []; // khai báo mảng lỗi
    
	// hàm lấy id
    public function getId()
	{
		return $this->cartId;
	}

	// khởi tạo lớp đối tượng
	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// kiểm tra các phần tử có tồn tại ko ->  lưu
    public function fill(array $data) {
        if (isset($data['cartId'])) {
			$this->cartId = trim($data['cartId']);
		}
		if (isset($data['productId'])) {
			$this->productId = trim($data['productId']);
		}
		if (isset($data['quantity'])) {
			$this->quantity = trim($data['quantity']);
		}
		return $this;
	}

	// gán gái trị vào đối tượng
	protected function fillFromDB(array $row)
	{
		[
		'cartId' => $this->cartId,
		'productId' => $this->productId,
		'quantity' => $this->quantity,
		] = $row;
		return $this;
	}

	// kiểm tra số lượng
    public function validate() {
		if (!$this->quantity) { // nếu không có số lương
			// lưu vào mảng lỗi
			$this->errors['quantity'] = 'Chưa chọn số lượng!';
		} 
		// không có lỗi
		return empty($this->errors);
	}

	// hàm lấy lỗi
    public function getValidationErrors() {
		return $this->errors;
	}

	// hàm thêm vào bảng 
    public function insert() {
		$result = false; // ban đầu kết quả  = false

		// chuẩn bị đối tượng để chèn vào
		$stmt = $this->db->prepare('INSERT INTO tbl_cartdetail(cartId,productId,quantity) 
						VALUES(:cartId,:productId,:quantity)');
		
		// thực thi câu lệnh trên
		$result = $stmt->execute(['cartId' => $this->cartId,
								'productId' => $this->productId,
								'quantity' => $this->quantity]);
		if ($result) {
			$this->cartId = $this->db->lastInsertId();
		}
		return $result;
	}

	// cạp nhật lại số lượng của một sản phẩm trong đơn hàng
	public function update_quantity_cart()
	{
		$result = false; // ban đầu = false
		// chuẩn bị đối tượng để update
		$stmt = $this->db->prepare('UPDATE tbl_cartdetail SET quantity = :quantity WHERE cartId = :cartId AND productId = :productId');
		// thực thi update đối tượng
		$result = $stmt->execute(['quantity' => $this->quantity, 'cartId' => $this->cartId, 'productId' => $this->productId]);
		return $result;
	}
    
	/// lấy đơn hàng
    public function all_customer_cart($cartId) {
		$cartdetails = []; // đơn hàng rổng
		// chuẩn bị lấy phần tử có cartid
        $stmt = $this->db->prepare("SELECT * FROM tbl_cartdetail WHERE cartId = ?");
        // thực thi 
		$stmt->execute([$cartId]);
        while ($row = $stmt->fetch()) { // nếu có chạy từng hàng
            $cartdetail = new Cartdetail($this->db); // tạo một đối tượng trên hàng
            $cartdetail->fillFromDB($row); // chèn dữ liệu vào
            $cartdetails[] = $cartdetail; // thêm vào đơn hàng
        }
        return $cartdetails; // trả về đơn hàng
	}

	// hàm tìm kiếm id
	public function find1($cartId) {
		// chuẩn bi đối tượng tìm kiếm với cartid
		$stmt = $this->db->prepare('SELECT * FROM tbl_cartdetail WHERE cartId = :cartId');
		// thuc thi câu lệnh
		$stmt->execute(['cartId' => $cartId]);
		if ($row = $stmt->fetch()) { // nếu có hàng
			$this->fillFromDB($row); ///  điền dữ liệu
			return $this; // trả về  đối tượng
		}
		return null;
	}

	// tìm kiếm theo tên
    public function find2($productId) {
		// chuẩn bị đối tượng để tìm kiếm theo tên
		$stmt = $this->db->prepare('select * from tbl_cartdetail where productId = :productId');
		$stmt->execute(['productId' => $productId]); // thực thi câu lệnh
		if ($row = $stmt->fetch()) { // kiểm tra nếu có
			$this->fillFromDB($row); // chèn dl
			return $this; //trả về đối tượng
		}
		return null;
	}

	// hàm xóa sản phẩm
    public function del_product_cart() {
        $stmt = $this->db->prepare("DELETE FROM tbl_cartdetail WHERE cartId = :cartId AND productId = :productId");
        return $stmt->execute(['cartId' => $this->cartId, 'productId' => $this->productId]);
    }

    // hàm kiểm tra giỏ hàng
    public function check_cart() {
        $stmt = $this->db->prepare("SELECT * FROM tbl_cartdetail WHERE cartId = :cartId");
        $stmt->execute(['cartId' => $this->cartId]);
        return $stmt -> rowCount();
    }

	// hàm xóa toàn bộ sản phẩm
	public function del_all_cart() {
        $stmt = $this->db->prepare("DELETE FROM tbl_cartdetail WHERE cartId = :cartId");
        return $stmt->execute(['cartId' => $this->cartId]);
    }
          
}
