<?php

if($config['mangos_version']!=0) $tp->assign('achiButton','<div class="char-sheet" onClick="characterSwitchTo(\'achievements\');">
<div class="smallframe-a"></div>
<div class="smallframe-b" id="switch_achievements">{$LGachievements}</div>
<div class="smallframe-c"></div></div>');

$a .= '<table cellspacing="0" cellpadding="0" class="achievement"><tbody>
<tr><td id="a_category" class="a_cat"><div class="a_topcat"></div>';
$a .= '<div class=a_bodycat><a id=ach_0 href="javascript:void(0)" onclick=\'selectCat(0);\'>Summary</a></div>';;
$bcat = $mysql->getRows("SELECT * FROM `achievement_category` WHERE `parent` = '-1' AND `id` <> 1 ORDER BY `sortOrder`",'armory');
foreach ($bcat as $cat) {
    $a .= '<div class=a_bodycat>'.
	'<a id="ach_'.$cat['id'].'" href="javascript:void(0)" onclick="selectCat('.$cat['id'].');">'.$cat['name'].'</a>';
    $scat = $mysql->getRows("SELECT * FROM `achievement_category` WHERE `parent` = ?1 ORDER BY `sortOrder`",$cat['id'],'armory');
		if($scat) 
		   foreach ($scat as $sub)
		     $a .= '<a id="ach_'.$sub['id'].'" class=sub href="javascript:void(0)" onclick="selectCat('.$sub['id'].');">'.$sub['name'].'</a>';
    $a .= '</div>';
  }
  $a .= '</div><div class=a_bottomcat></div></td>
<td class="a_data"><div class="a_topdata"></div><div id="a_data" class="a_bdydata"></div><div class="a_btmdata"></div></td></tr></tbody></table>';

$tp->assign('achievements',$a);

//print_r($character->reputation);
?>