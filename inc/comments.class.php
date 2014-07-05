<?php
Class Comments {
	public function retrive_comments ($db) {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$pageSizeContent = (isset($_GET['size'])) ? $_GET['size'] : 15;
		$db = array_reverse($db);
		$startIndex = ($page - 1)*$pageSizeContent;
		$pageItems = array_slice($db, $startIndex, $pageSizeContent);
		$html = '<ul class="comments">';
		for ($i=0; $i < count($pageItems) ; $i++) { 
			$tmp = explode(';', $pageItems[$i]);
			$rand = rand(1,7);
			$html .= "<li><div><span>$tmp[0]</span><span class='date'>$tmp[3]</span><span class='avatar'><img src='images/$rand.png' width='60' height='60'></span></div><p>$tmp[2]</p></li>";
		}
		$html .= '</ul>';
		unset($tmp);
		return $html;
	}
}