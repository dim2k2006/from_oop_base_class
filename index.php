<?php
session_start();
$html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>';
require_once 'inc/funcs.php';
require_once 'tpl/head.php';
$html .= '<body>';
require_once 'tpl/form.php';
require_once 'tpl/comments.php';
require_once 'tpl/paginator.php';	
$html .= '</body></html>';
echo $html;