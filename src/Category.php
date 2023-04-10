<?php

namespace CT275\Project;

class Category
{
	private $db;
	private $catId = -1;

	public $catName;
	public $mode;

	private $errors = [];

	public function getId()
	{
		return $this->catId;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}
	
	public function fill(array $data) {
		if (isset($data['catName'])) {
			$this->catName = trim($data['catName']);
		}
		if (isset($data['mode'])) {
			$this->mode = trim($data['mode']);
		}
		return $this;
	}
	
	public function fillFromDB(array $row) {
		[
			'catId' => $this->catId,
			'catName' => $this->catName,
            'mode' => $this->mode,
		] = $row;
		return $this;
	}

	public function validate() {
		if (!$this->catName) {
			$this->errors['catName'] = 'Chưa nhập tên danh mục!';
		} 
		else if ($this->db->query("select * from tbl_category where catName = '$this->catName' ")->rowCount() > 0) {
			$this->errors['catName'] = 'Danh mục đã có!';
		}
		return empty($this->errors);
	}

	public function getValidationErrors() {
		return $this->errors;
	}

	public function save() {
		$result = false;
		if ($this->catId >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_category SET catName = :catName WHERE catId = :catId');

			$result = $stmt->execute(['catName' => $this->catName, 'catId' => $this->catId]);
		} else {
			$stmt = $this->db->prepare('INSERT INTO tbl_category(catName) 
							VALUES(:catName)');
			$result = $stmt->execute(['catName' => $this->catName]);
			if ($result) {
				$this->catId = $this->db->lastInsertId();
			}
		}
		return $result;
	}
	
	public function update(array $data)
	{
		$this->fill($data);
		if ($this->validate()) {
			return $this->save();
		}
		return false;
	}
	
	public function find($catId) {
		$stmt = $this->db->prepare('select * from tbl_category where catId = :catId');
		$stmt->execute(['catId' => $catId]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function all(){
		$cats = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_category order by catId desc");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$cat = new Category($this->db);
			$cat->fillFromDB($row);
			$cats[] = $cat;
		}
		return $cats;
	}

	public function del_category() {
		$stmt = $this->db->prepare('DELETE FROM tbl_category where catId = :catId');
		return $stmt->execute(['catId' => $this->catId]);
	}

	public function update_mode_category() {
		if($this->mode == 1) {
			$stmt = $this->db->prepare("UPDATE tbl_category SET mode = 0 where catId = :catId ");
			return $stmt->execute(['catId' => $this->catId]);
		}	
		else if ($this->mode == 0) {
			$stmt = $this->db->prepare("UPDATE tbl_category SET mode = 1 where catId = :catId ");
			return $stmt->execute(['catId' => $this->catId]);
		}
	}
	
	public function show_category_frontend(){
		$cats = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_category where mode = '1' order by catId desc");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$cat = new Category($this->db);
			$cat->fillFromDB($row);
			$cats[] = $cat;
		}
		return $cats;
	}

}
