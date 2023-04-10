<?php

namespace CT275\Project;

class Cartdetail
{
	private $db;
	private $cartId = -1;
    public $productId,$quantity;
    private $errors = [];
    
    public function getId()
	{
		return $this->cartId;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

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

	protected function fillFromDB(array $row)
	{
		[
		'cartId' => $this->cartId,
		'productId' => $this->productId,
		'quantity' => $this->quantity,
		] = $row;
		return $this;
	}

    public function validate() {
		if (!$this->quantity) {
			$this->errors['quantity'] = 'Chưa chọn số lượng!';
		} 
		return empty($this->errors);
	}
    public function getValidationErrors() {
		return $this->errors;
	}

    public function insert() {
		$result = false;
		$stmt = $this->db->prepare('INSERT INTO tbl_cartdetail(cartId,productId,quantity) 
						VALUES(:cartId,:productId,:quantity)');
		$result = $stmt->execute(['cartId' => $this->cartId,
								'productId' => $this->productId,
								'quantity' => $this->quantity]);
		if ($result) {
			$this->cartId = $this->db->lastInsertId();
		}
		return $result;
	}

	public function update_quantity_cart()
	{
		$result = false;
		$stmt = $this->db->prepare('UPDATE tbl_cartdetail SET quantity = :quantity WHERE cartId = :cartId AND productId = :productId');
		$result = $stmt->execute(['quantity' => $this->quantity, 'cartId' => $this->cartId, 'productId' => $this->productId]);
		return $result;
	}
    
    public function all_customer_cart($cartId) {
		$cartdetails = [];
        $stmt = $this->db->prepare("SELECT * FROM tbl_cartdetail WHERE cartId = ?");
        $stmt->execute([$cartId]);
        while ($row = $stmt->fetch()) {
            $cartdetail = new Cartdetail($this->db);
            $cartdetail->fillFromDB($row);
            $cartdetails[] = $cartdetail;
        }
        return $cartdetails;
	}

	public function find1($cartId) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_cartdetail WHERE cartId = :cartId');
		$stmt->execute(['cartId' => $cartId]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

    public function find2($productId) {
		$stmt = $this->db->prepare('select * from tbl_cartdetail where productId = :productId');
		$stmt->execute(['productId' => $productId]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

    public function del_product_cart() {
        $stmt = $this->db->prepare("DELETE FROM tbl_cartdetail WHERE cartId = :cartId AND productId = :productId");
        return $stmt->execute(['cartId' => $this->cartId, 'productId' => $this->productId]);
    }

         
    public function check_cart() {
        $stmt = $this->db->prepare("SELECT * FROM tbl_cartdetail WHERE cartId = :cartId");
        $stmt->execute(['cartId' => $this->cartId]);
        return $stmt -> rowCount();
    }

	public function del_all_cart() {
        $stmt = $this->db->prepare("DELETE FROM tbl_cartdetail WHERE cartId = :cartId");
        return $stmt->execute(['cartId' => $this->cartId]);
    }
          
}
