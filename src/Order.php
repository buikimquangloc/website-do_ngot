<?php

namespace CT275\Project;

class Order
{
	private $db;
	private $orderId = -1;

    public $cartId,$customer_id, $total, $status, $date_order;
    private $errors = [];
    
    public function getId()
	{
		return $this->orderId;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

    public function fill(array $data) {
		if (isset($data['cartId'])) {
			$this->cartId = trim($data['cartId']);
		}
		if (isset($data['customer_id'])) {
			$this->customer_id = trim($data['customer_id']);
		}
		if (isset($data['total'])) {
			$this->total = trim($data['total']);
		}
		if (isset($data['status'])) {
			$this->status = trim($data['status']);
		}
        if (isset($data['date_order'])) {
			$this->date_order = trim($data['date_order']);
		}
		return $this;
	}

    protected function fillFromDB(array $row)
	{
		[
		'orderId' => $this->orderId,
		'cartId' => $this->cartId,
        'customer_id' => $this->customer_id,
		'total' => $this->total,
		'status' => $this->status,
        'date_order' => $this->date_order
		] = $row;
		return $this;
	}
    
    public function save_customer() {
		$result = false;
		if ($this->orderId > 0) {
			$stmt = $this->db->prepare('UPDATE tbl_order SET status = 2 WHERE orderId = :orderId');
			$result = $stmt->execute(['orderId' => $this->orderId]);
		} else {
			$stmt = $this->db->prepare('INSERT INTO tbl_order(cartId,customer_id,total) 
							VALUES(:cartId,:customer_id,:total)');
			$result = $stmt->execute([  'cartId' => $this->cartId,
										'customer_id' => $this->customer_id,
										'total' => $this->total]);
			if ($result) {
				$this->orderId = $this->db->lastInsertId();
			}
		}
		return $result;
	}

    public function save_admin() {
		$result = false;
		if ($this->orderId >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_order SET status = 1 WHERE orderId = :orderId');
			$result = $stmt->execute(['orderId' => $this->orderId]);
		} 
		return $result;
	}

    public function find($orderId) {
		$stmt = $this->db->prepare('select * from tbl_order where orderId = :orderId');
		$stmt->execute(['orderId' => $orderId]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

    public function del_shifted_customer() {
		$stmt = $this->db->prepare('DELETE FROM tbl_order where orderId = :orderId');
		return $stmt->execute(['orderId' => $this->orderId]);
	}
   
    public function get_ordered($customer_id) {
        $orders = [];
        $stmt = $this->db->prepare("SELECT * FROM tbl_order WHERE customer_id = :customer_id ");
        $stmt->execute(['customer_id' => $customer_id]);
        while ($row = $stmt->fetch()) {
            $order = new Order($this->db);
            $order->fillFromDB($row);
            $orders[] = $order;
        }
        return $orders;
    }

	public function all() {
        $orders = [];
        $stmt = $this->db->prepare("SELECT * FROM tbl_order");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $order = new Order($this->db);
            $order->fillFromDB($row);
            $orders[] = $order;
        }
        return $orders;
    }

}
