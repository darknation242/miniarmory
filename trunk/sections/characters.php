<?php

//$c->add('table');

$tp = new template();
//$toolTip->add('tooltip');
//$tp->add('table');

$tp->add('chars_table');
$tp->assign('search_name', $_GET['searchQuery']);
$_LANGUAGE->translate($tp);
$tp->display();



?>