<?php

//$c->add('table');

$tp = new template();
//$toolTip->add('tooltip');
//$tp->add('table');
$tp->add('pvp_table');

$s = new search_character();
$s->set_sort('hk',1);
$s->per_page = 50;
$s->Realm = $_SYSTEM->Realm;
$data = $s->start();
$realms = '<h2>PvP Top 50: <i>'.$_SYSTEM->Realms[$_SYSTEM->Realm].'</i></h2>
<span class="page-subheader">(Realms: ';
foreach($_SYSTEM->Realms as $key => $value) {
	$realms .= '<a href="'.$_DOMAIN.'index.php?act=pvp&amp;Realm='.$value.'">'.$value.'</a> |';	
}

$tp->assign('realms',substr($realms,0,-1).')</span>');

$i=1;
foreach($data as $char) {
  $add .= '<tr class="csearch-results-table-item"><td class="">'.($i++).'.</td>
  <td class=""><img alt="" src="'.$_DOMAIN.'images/icons/'.(character::getAlliance($char['race'])).'.png"> <a href="'.$_DOMAIN.'index.php?character='.$char['guid'].'&Realm='.$char['realm'].'">'.$char['name'].'</a></td>
  <td class="">'.$char['level'].'</td>
  <td class="rightalign nopadding">
  <img onMouseOut="tooltip_hide()" onMouseOver="tooltip(\''.$_LANGUAGE->text[character::raceToString($char['race'])].'\')" alt="" src="'.$_DOMAIN.'images/icons/race/'.$char['race'].'-'.$char['gender'].'.gif"></td>
  <td class="leftalign nopadding">
  <img onMouseOut="tooltip_hide()" onMouseOver="tooltip(\''.$_LANGUAGE->text[character::classToString($char['class'])].'\')" alt="" src="'.$_DOMAIN.'images/icons/class/'.$char['class'].'.gif"></td>
  <td class="">'.($char['guildid']?'<a href="'.$_DOMAIN.'index.php?guild='.$char['guildid'].'">':'').$char['guild'].($char['guildid']?'</a>':'').'</td>
  <td class="">'.$char['hk'].'</td>
  <td class="">'.$char['honor'].'</td>
  
  </tr>';	
}

$tp->assign('ranking',$add);

$_LANGUAGE->translate($tp);
$tp->display();



?>