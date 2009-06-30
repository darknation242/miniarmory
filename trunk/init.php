<?php

require_once($_FPREFIX.'config.php');
@session_start();
/* Ładowanie klas */
if($dr = opendir($_FPREFIX.'includes')) {
    while (false !== ($file = readdir($dr))) { 
	    if($file=='.' || $file=='..' || strpos($file,'.php')===false || !is_file($_FPREFIX.'includes/'.$file)) continue;
        require_once($_FPREFIX.'includes/'.$file);
	}
    closedir($dr); 
}

switch($_GET['set_lang']) {
	case 'pl': setcookie('language','pl',time()+60*60*24*30); header('Location: '.$_DOMAIN); exit;
	case 'en': setcookie('language','en',time()+60*60*24*30); header('Location: '.$_DOMAIN); exit;
	default: break;
}
if( ($_COOKIE['language']=='en' || $_COOKIE['language']=='pl') && $config['language_change']) 
   $config['language'] = $_COOKIE['language'];

/* Tworzenie obiektów */
$mysql = new mysql;
$_SYSTEM = new system;
$_LANGUAGE = new language($config['language']);

foreach($_GET as $key => $value) 
   $_GET[$key] = $_SYSTEM->escape($value);
$_PAGE = $_GET['act']; 

if(!$_GET['category']) $_GET['category'] = 'profile';

switch($_PAGE) {
  case 'characters': $inc='characters';$title='{$LGcharacter_search}';break;
  case 'guilds': $inc='guilds';$title='{$LGguild_list}';break;
  case 'pvp': $inc='pvp';$title='{$LGhonor_ranking}';break;
  case 'arenateams': $inc='arenateams';$title='{$LGarenateams}';break;
  //case 'stats': $inc='characters';break; // Maybe someday :)
  default: 
    $inc='home';$title='{$LGnews}';	
}
if($_GET['character']) {
	$inc='character';
	$title='{$LGcharacter_profile}';
	if(!(int)$_GET['character']) {
		$_SEARCH = new search_character($_GET['character']);
		$_SEARCH->Realm = $_SYSTEM->Realm;
		$data = $_SEARCH->start();
		$_GET['character'] = (int)$data[0]['guid'];
	}
}
if($_GET['guild']>0) {
	$inc='guild';
	$title='{$LGguild_information}';
}

?>