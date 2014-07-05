<?php
$st = new Storage('data/db.txt');
$cs = new Comments();

$html .= $cs->retrive_comments($st->db);
