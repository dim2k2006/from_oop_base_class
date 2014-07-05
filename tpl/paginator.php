<?php
$pg = new Pagination();
$result_per_page = $st->pageSizeContent;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$total = $st->dbsize;

$offset = ($current_page*$results_per_page)-$results_per_page;
   
   
$pg->setNumberOfPages($total,$result_per_page); 
$url = $_SERVER['PHP_SELF'];
$pg->draw($current_page,$url,$result_per_page);

switch($result_per_page) {
	case 15:
		$active15 = "class=activeNum";
		break;
	case 30:
		$active30 = "class=activeNum";
		break;
	case 45:
		$active45 = "class=activeNum";
		break;
}

$html .= '<div class="paginator">';
$html .= $pg->pagination;
$html .= "<span>Кол-во записей:<a $active15 href=\"?page=1&size=15\">15</a><a $active30 href=\"?page=1&size=30\">30</a><a $active45 href=\"?page=1&size=45\">45</a></span>";
$html .= '</div>';
?>
