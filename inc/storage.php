<?php
Class Storage {
	public $db = "";
	public $dbsize = "";
	public $pageNum = "";
	public $pageSizeContent = "";
	
	public function __construct($path) {
		$this->pageSizeContent = (isset($_GET['size'])) ? $_GET['size'] : 15;
		$this->db = file($path);
		$this->dbsize = count($this->db);
		$this->pageNum = $this->dbsize/$this->pageSizeContent;
		$this->pageNum = ((int)$this->pageNum == $this->pageNum) ? $this->pageNum : (int)ceil($this->pageNum);
	}
}