<?php
require_once('init.php');

$c = new template();
$c->add('headers');
$c->assign('title','The Armory - '.$title);
$_LANGUAGE->translateJS($c);
$c->add('menu');


$lang_box = $config['language_change'] ? '<div class="langs">
	<a href="'.$_DOMAIN.'?set_lang=en"><img src="'.$_DOMAIN.'css/images/en_lang.jpg" width="30" height="20" alt=""></a>
	<a href="'.$_DOMAIN.'?set_lang=pl"><img src="'.$_DOMAIN.'css/images/pl_lang.jpg" width="30" height="20" alt=""></a>
    </div>' : '';
$c->assign('langs_box',$lang_box);

$c->assign('page_name','<img src="'.$_DOMAIN.'css/shadowText.php?text=Armory - '.$title.'" alt="Armory - '.$title.'">');


$c->assign('menu'.$_PAGE,'menu_selected');
$_LANGUAGE->translate($c);
$c->display();


include('sections/'.$inc.'.php');

//die("sdf".$mysql->QueryCount);
$_SYSTEM->printFooter();

?>