<?php

namespace CT275\Project;

class Cart
{
	private $db;
	private $cartId = -1;
    public $customer_id;
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
		if (isset($data['customer_id'])) {
			$this->customer_id = trim($data['customer_id']);
		}
		return $this;
	}

	protected function fillFromDB(array $row)
	{
		[
		'cartId' => $this->cartId,
		'customer_id' => $this->customer_id
		] = $row;
		return $this;
	}
    
    public function find($cartId) {
		$stmt = $this->db->prepare('select * from tbl_cart where cartId = :cartId');
		$stmt->execute(['cartId' => $cartId]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

    public function find3($customer_id) {
		$stmt = $this->db->prepare('select * from tbl_cart where customer_id = :customer_id');
		$stmt->execute(['customer_id' => $customer_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function save() {
		$result = false;
		if ($this->cartId >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_cart SET status = 1 WHERE cartId = :cartId');
			$result = $stmt->execute(['cartId' => $this->cartId]);
		} else {
			$stmt = $this->db->prepare('INSERT INTO tbl_cart(customer_id) 
							VALUES(:customer_id)');
			$result = $stmt->execute(['customer_id' => $this->customer_id]);
			if ($result) {
				$this->cartId = $this->db->lastInsertId();
			}
		}
		return $result;
	}
	
    public function del_product_cart() {
        $stmt = $this->db->prepare(" DELETE FROM tbl_cart where cartId = :cartId");
        $stmt->execute(['cartId' => $this->cartId]);
    }

    public function all() {
        $carts = [];
        $stmt = $this->db->prepare("SELECT * FROM tbl_cart where status = 0");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $cart = new Cart($this->db);
            $cart->fillFromDB($row);
            $carts[] = $cart;
        }
        return $carts;
    }  

    public function check_cart() {
        $customer_id = $_SESSION['customer_id'];
        $stmt = $this->db->prepare("SELECT * FROM tbl_cart WHERE customer_id = :customer_id AND status = 0");
        $stmt->execute(['customer_id' => $customer_id]);
        return $stmt -> rowCount();
    }
          
}