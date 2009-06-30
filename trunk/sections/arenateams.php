<?php

//$c->add('table');
$tp = new template();
$tp->add('arenateams');

switch($_GET['ArenaType']) {
	case '2v2': $type = '2 vs. 2';$sqlType=2;$_GET['ArenaType']='2v2';break;
	case '3v3': $type = '3 vs. 3';$sqlType=3;$_GET['ArenaType']='3v3';break;
	case '5v5': $type = '5 vs. 5';$sqlType=5;$_GET['ArenaType']='5v5';break;
	default: $type = '2 vs. 2';$sqlType=2;$_GET['ArenaType']='2v2';;
}
$tp->assign('type',$type);

$data = pvpladder();
$data->ArenaType = $sqlType;

$realms = '<br>
<span class="page-subheader">(Realms: ';
foreach($_SYSTEM->Realms as $key => $value) {
	$realms .= '<a href="'.$_DOMAIN.'index.php?act=arenateams&amp;ArenaType='.$_GET['ArenaType'].'&amp;Realm='.$value.'">'.$value.'</a> |';	
}

$tp->assign('realms',substr($realms,0,-1).')</span>');
$tp->assign('realm',$_SYSTEM->Realms[$_SYSTEM->Realm]);

if(!count($data->ArenaTeams[$sqlType])) {
	$tp->assign('ranking','<tr><td colspan="7" class="csearch-results-table-item">'.$_LANGUAGE->text['noresults'].'</td></tr>');
}else{
 	$Arena = $data->ArenaTeams[$sqlType];
	foreach($Arena as $Team) {
		$tip = '<table class="csearch-results-table" style="width:auto !important;"><tr><td align="center"  colspan="6" class="tooltip-header">'.$_LANGUAGE->text['members'].'</td></tr> <tr class="csearch-results-table-header"><td>'.$_LANGUAGE->text['name'].'</td><td></td><td>'.$_LANGUAGE->text['level'].'</td><td>'.$_LANGUAGE->text['rating'].'</td><td>'.$_LANGUAGE->text['wonseason'].'</td><td>'.$_LANGUAGE->text['lostseason'].'</td></tr>';
		foreach($Team['members'] as $member) {
			$tip .= '<tr>';
			$tip .= '<td><strong style="color: '.($member['guid']!=$Team['captainguid']?'#F93':'lime').';">'.$member['name'].'</strong></td>';
			$tip .= '<td><img src="'.$_DOMAIN.'images/icons/race/'.$member['race'].'-'.$member['gender'].'.gif" alt=""><img src="'.$_DOMAIN.'images/icons/class/'.$member['class'].'.gif" alt=""></td>';
			$tip .= '<td class="centeralign">'.$member['level'].'</td>';
			$tip .= '<td class="centeralign">'.$member['personal_rating'].'</td>';
			//$tip .= '<td>'.$member['wons_week'].'</td>';
			//$tip .= '<td>'.($member['played_week']-$member['wons_week']).'</td>';
			$tip .= '<td class="centeralign">'.$member['wons_season'].'</td>';
			$tip .= '<td class="centeralign">'.($member['played_season']-$member['wons_season']).'</td>';
			$tip .= '</tr>';
		}
		$tip .= '</table>';
		
		$res .= '<tr class="csearch-results-table-item">
		<td class="centeralign"><img src="'.$_DOMAIN.'images/icons/pvp/rank'.$Team['rank'].'.png" alt=""></td>';
		$res .= '<td><img src="'.$_DOMAIN.'images/icons/'.$Team['faction'].'.png" alt="">
		<a onMouseOut="tooltip_hide()" onMouseOver="tooltip(\''.$_SYSTEM->htmlcode($tip).'\')" href="javascript:void(0)" >'.$Team['name'].'</a></td>';
		$res .= '<td class="csearch-results-table-item-ordered">'.$Team['rating'].'</td>';
		$res .= '<td>'.$Team['wins'].'</td>';
		$res .= '<td>'.($Team['games']-$Team['wins']).'</td>';
		$res .= '<td>'.$Team['wins2'].'</td>';
		$res .= '<td>'.($Team['played']-$Team['wins2']).'</td>';
		$res .= '</tr>';
	}
	$tp->assign('ranking',$res);
}

$_LANGUAGE->translate($tp);
$tp->display();

?>