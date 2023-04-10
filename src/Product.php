<?php

namespace CT275\Project;

class Product
{
	private $db;
	private $productId = -1;
	public $productName;
	public $catId;
	public $product_desc;
	public $price;
	public $image;
	public $status_product;
	public $ngaynhap;
	
	private $errors = [];

	public function getId()
	{
		return $this->productId;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function fill(array $data, $file) {
		if (isset($data['productName'])) {
			$this->productName = trim($data['productName']);
		}
		if (isset($data['catId'])) {
			$this->catId = trim($data['catId']);
		}
		if (isset($data['product_desc'])) {
			$this->product_desc = trim($data['product_desc']);
		}
		if (isset($data['price'])) {
			$this->price = trim($data['price']);
		}
		if (isset($file)) {
			$this->image = $file["image"]["name"];
		}
		if (isset($data['status_product'])) {
			$this->status_product = trim($data['status_product']);
		}
		if (isset($data['ngaynhap'])) {
			$this->ngaynhap = trim($data['ngaynhap']);
		}
		return $this;
	}

	protected function fillFromDB(array $row)
	{
		[
		'productId' => $this->productId,
		'productName' => $this->productName,
		'catId' => $this->catId,
		'product_desc' => $this->product_desc,
		'price' => $this->price,
		'image' => $this->image,
		'status_product' => $this->status_product,
		'ngaynhap' => $this->ngaynhap
		] = $row;
		return $this;
	}

	public function validate() {
		if (!$this->productName) {
			$this->errors['productName'] = 'Chưa nhập tên nước!';
		} 
		else if ($this->db->query("select * from tbl_product where productName = '$this->productName' ")->rowCount() > 0) {
			$this->errors['productName'] = 'Tên nước đã có!';
		}
		if (!$this->catId) {
			$this->errors['catId'] = 'Chưa chọn danh mục!';
		} 
		if (!$this->product_desc) {
			$this->errors['product_desc'] = 'Chưa nhập mô tả nước!';
		} 
		if (!$this->price) {
			$this->errors['price'] = 'Chưa nhập giá nước!';
		} 
		if (!$this->image) {
			$this->errors['image'] = 'Chưa chọn hình ảnh nước!';
		} 
		if ($this->status_product == '') {
			$this->errors['status_product'] = 'Chưa chọn trạng thái nước!';
		} 
		return empty($this->errors);
	}

	public function getValidationErrors() {
		return $this->errors;
	}

	public function save() {
		$result = false;
		if ($this->productId >= 0) {
			$stmt = $this->db->prepare("UPDATE tbl_product 
										SET 
											productName = :productName, 
											catId = :catId, 
											product_desc = :product_desc,
											status_product = :status_product,
											price = :price, 
											image = :image
										WHERE productId = :productId");
			$result = $stmt->execute(['productName' => $this->productName,
										'catId' => $this->catId,
										'product_desc' => $this->product_desc,
										'status_product' => $this->status_product,
										'price' => $this->price,
										'image' => $this->image,
										
										'productId' => $this->productId]);
			$imgname = $this->image;
			move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$imgname);
		} else {
			$stmt = $this->db->prepare("INSERT INTO tbl_product(productName,catId,product_desc,price,status_product,image) 
										VALUES(:productName,:catId,:product_desc,:price,:status_product,:image)");
			$result = $stmt->execute(['productName' => $this->productName,
									'catId' => $this->catId,
									'product_desc' => $this->product_desc,	
									'price' => $this->price,
									'status_product' => $this->status_product,
									'image' => $this->image]);
			if ($result) {
				$this->productId = $this->db->lastInsertId();
			}
			$imgname = $this->image;
			move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$imgname);
		}
		return $result;
	}

	public function update(array $data,$file)
	{
		$this->fill($data,$file);
		if ($this->validate()) {
			return $this->save();
		}
		return false;
	}

	public function find($productId) {
		$stmt = $this->db->prepare('select * from tbl_product where productId = :productId');
		$stmt->execute(['productId' => $productId]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function all(){
		$products = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_product ORDER BY productId asc");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$product = new Product($this->db);
			$product->fillFromDB($row);
			$products[] = $product;
		}
		return $products;
	}

	public function all_new(){
		$products = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_product ORDER BY productId desc");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$product = new Product($this->db);
			$product->fillFromDB($row);
			$products[] = $product;
		}
		return $products;
	}

	public function del_product() {
		$stmt = $this->db->prepare(" DELETE FROM tbl_product where productId = :productId");
		return $stmt->execute(['productId' => $this->productId]);
	}

	public function search_product($tukhoa) {
		if($tukhoa != NULL) {
			$products = [];
			$stmt = $this->db->prepare("SELECT * FROM tbl_product WHERE productName like '%$tukhoa%' ");
			$stmt->execute();
			while ($row = $stmt->fetch()) {
				$product = new Product($this->db);
				$product->fillFromDB($row);
				$products[] = $product;
			}
			return $products;
		}
	}

	public function getproductbycatId($catId) {
		$products = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_product where catId = :catId and status_product ='1'");
		$stmt->execute(['catId' => $catId]);
		while ($row = $stmt->fetch()) {
			$product = new Product($this->db);
			$product->fillFromDB($row);
			$products[] = $product;
		}
		return $products;
	}


}
