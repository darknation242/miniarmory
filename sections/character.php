<?php

$tp = new template();
$tp->add('character');
$character = character($_GET['character']);
$tp->assign('category',$_GET['category']);
$tp->assign('lastupdate',date("H:i d-m-Y",$character->lastupdate));

if($character->guid==-1) $_SYSTEM->error('Character not found!');

// Ustawianie reputacji
include('sections/char-reputation.php');

// teraz skili
include('sections/char-skills.php');

// Talenty
include('sections/char-talents.php');

// Achievements :)
include('sections/char-achievements.php');

// Ustawianie itemkow
for($i=0;$i<19;$i++) {
	$todo='<div class="item-bg" onMouseOut="tooltip_hide();" onMouseOver="tooltip(\''.$_SYSTEM->htmlcode($character->item_tooltips[$i]).'\');">';
	if($character->get_item_icon($i)!==false) $todo .= '<img src="'.$character->get_item_icon($i).'" alt="">';
	$todo.='</div>';
	$tp->assign('ITEM_SLOT_'.$i,$todo);
}
// Podstawowe informacje
$tp->assign('guid',$character->guid);
$tp->assign('name',$character->name);
$tp->assign('guid',$character->guid);
$tp->assign('level',$character->level);
$tp->assign('alliance',$character->getAlliance());
$tp->assign('race',$_LANGUAGE->text[$character->raceToString($character->race)]);
$tp->assign('class',$_LANGUAGE->text[$character->classToString($character->class)]);
$tp->assign('gender_nr',$character->gender);
$tp->assign('race_nr',$character->race);
$tp->assign('class_nr',$character->class);
$tp->assign('guild', $character->guild_id ? '<a href="'.$_DOMAIN.'guild/'.$character->guild_id.'">{$LGguild}: '.$character->guild.'</a>' : '');
$tp->assign('guild_name', $character->guild_id ? $character->guild : $_LANGUAGE->text['none']);
$tp->assign('guild_rank', $character->guild_rank ? $character->guild_rank : $_LANGUAGE->text['unknown']);
$tp->assign('realm',$character->realm);
$tp->assign('honor',$character->honor);
$tp->assign('hk',$character->hk);
$tp->assign('arenapoints',$character->arenapoints);
$tp->assign('power_type_l',strtolower($character->getPowerType()));
$tp->assign('power_type',$character->getPowerType());

foreach($character->stats as $key => $value) {
	$tp->assign($key ,$value);
}
// Skills
/*
$skills='<table><tr><td><table>';
for($i=0;$i<count($character->skills)/2;$i++) {
	    $prof = $character->skills[$i];
		$skills .= '<tr><td colspan="3" style="font-size:11px;margin:0;padding:0 8px;text-align:center;text-transform:uppercase;">'.$prof[1].'</td></tr><tr><td class="skill-icon"></td><td class="skill-bar" onMouseOut="tooltip_hide();" onMouseOver="tooltip(\''.$prof[1].'\')">';
		$width = (int)(($prof[2]*100)/$prof[3]);
		$skills .= '<div style="width:'.$width.'%;max-width:'.$width.'%;"><span>'.$prof[2].' / '.$prof[3].'</span></div>';
		$skills .= '</td><td></td></tr>';
}
$skills.='</table></td><td><table>';
for(;$i<count($character->skills);$i++) {
	    $prof = $character->skills[$i];
		$skills .= '<tr><td colspan="3" style="font-size:11px;margin:0;padding:0 8px;text-align:center;text-transform:uppercase;">'.$prof[1].'</td></tr><tr><td class="skill-icon"></td><td class="skill-bar" onMouseOut="tooltip_hide();" onMouseOver="tooltip(\''.$prof[1].'\')">';
		$width = (int)(($prof[2]*100)/$prof[3]);
		$skills .= '<div style="width:'.$width.'%;max-width:'.$width.'%;"><span>'.$prof[2].' / '.$prof[3].'</span></div>';
		$skills .= '</td><td></td></tr>';
}
$tp->assign('skills',$_SYSTEM->htmlcode($skills.'</table></td></tr></table>'));
*/
// Gold

$tp->assign('gold',$_SYSTEM->htmlcode($character->getGold()));

// Ustawianie profesji

// Primary

$i=2;
foreach($character->prof_1 as $prof) {
	$primary .= '<tr><td class="prof-icon" rowspan="2" style="vertical-align:middle;"><img src="'.$_DOMAIN.'images/icons/professions/'.strtolower($prof[1]).'-sm.gif" alt=""></td><td>'.$prof[1].'</td>';
	$width = (int)(($prof[2]*100)/$prof[3]);
	$primary .= '<td></td></tr><tr><td class="prof-bar" onMouseOut="tooltip_hide();" onMouseOver="tooltip(\''.$prof[1].'\')"><div style="width:'.$width.'%;max-width:'.$width.'%;">
	<span>'.$prof[2].' / '.$prof[3].'</span></div></td><td></td></tr>';
	$i--;
	if($i<=0) break;
}
while($i-->0) {
	$primary .= '<tr><td class="prof-icon" rowspan="2" style="vertical-align:middle;"><img src="'.$_DOMAIN.'images/icons/professions/none.gif" alt=""></td><td>'.$_LANGUAGE->text['none'].'</td>';
	$width = 0;
	$primary .= '<td></td></tr><tr><td class="prof-bar" onMouseOut="tooltip_hide();" onMouseOver="tooltip(\''.$_LANGUAGE->text['none'].'\')"><div style="width:'.$width.'%;max-width:'.$width.'%;">
	<span>N/A</span></div></td><td></td></tr>';
}


// Secondary
/*
$i=3;
foreach($character->prof_2 as $prof) {
	$secondary .= '<tr><td class="prof-icon"><img height="26" src="'.$_DOMAIN.'images/icons/professions/'.strtolower($prof[1]).'-sm.gif" alt=""></td><td class="prof-bar" onMouseOut="tooltip_hide();" onMouseOver="tooltip(\''.$prof[1].'\')">';
	$width = (int)(($prof[2]*100)/$prof[3]);
	$secondary .= '<div style="width:'.$width.'%;max-width:'.$width.'%;">
	<span>'.$prof[2].' / '.$prof[3].'</span></div>';
	$secondary  .= '</td><td></td></tr>';
	$i--;
}

while($i-->0) {
	$secondary .= '<tr><td class="prof-icon"><img height="26" src="'.$_DOMAIN.'images/icons/professions/none.gif" alt=""></td><td class="prof-bar" onMouseOut="tooltip_hide();" onMouseOver="tooltip(\''.$_LANGUAGE->text['none'].'\')">';
	$width = 0;
	$secondary .= '<div style="width:'.$width.'%;max-width:'.$width.'%;">
	<span>0 / 0</span></div>';
	$secondary .= '</td><td></td></tr>';
}
*/
$tp->assign('profs_1',$primary);
$tp->assign('profs_2',$secondary);

// Talenty
$src = $character->talentSpec['nr'] ? $_DOMAIN.'images/icons/class/'.$character->class.'/talents/'.$character->talentSpec['nr'].'.png' : $_DOMAIN.'images/icons/class/talents/untalented.gif';
$tal = '<tr><td rowspan="2" style="vertical-align:middle;"><img src="'.$src.'" alt=""></td><td colspan="2" style="text-transform: uppercase;font-size:16px;">'.$character->talentSpec['name'].'</td></tr>
<tr><td colspan="2" style="font-size:12px;">'.$character->talentCount[0]." / ".$character->talentCount[1]." / ".$character->talentCount[2].'</td></tr>';

$tp->assign('talents',$tal);

// Ustawianie statystyk
function color($bonus,$stat) {
	if($bonus>0) return '<span style="color:lime;">'.($bonus+$stat).'</span>';
	else if($bonus<0) return '<span style="color:red;">'.($bonus+$stat).'</span>';
	else return $stat;
}
$base_stats .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['strength'].' '.($character->stats['strength_base']).' + '.color($character->stats['strength_bonus'],0).'</span><br><span class="description">'.$_LANGUAGE->text['increases'].' '.$_LANGUAGE->text['attack_power'].' '.$_LANGUAGE->text['by'].' '.$character->stats['melee_ap_mod'].'</span>').'\')">
'.$_LANGUAGE->text['strength'].':</span></td><td class="stats_right">'.color($character->stats['strength_bonus'],$character->stats['strength_base']).'</td></tr>';

$base_stats .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['agility'].' '.($character->stats['agility_base']).' + '.color($character->stats['agility_bonus'],0).'</span><br><span class="description">'.$_LANGUAGE->text['increases'].' '.$_LANGUAGE->text['armor'].' '.$_LANGUAGE->text['by'].' '.$character->stats['armor_mod'].'<br>'.$_LANGUAGE->text['increases'].' '.$_LANGUAGE->text['attack_power'].' '.$_LANGUAGE->text['by'].' '.$character->stats['melee_ap_mod_agility'].'</span>').'\')">
'.$_LANGUAGE->text['agility'].':</span></td><td class="stats_right">'.color($character->stats['agility_bonus'],$character->stats['agility_base']).'</td></tr>';

$base_stats .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['stamina'].' '.($character->stats['stamina_base']).' + '.color($character->stats['stamina_bonus'],0).'</span><br><span class="description">'.$_LANGUAGE->text['increases'].' '.$_LANGUAGE->text['health'].' '.$_LANGUAGE->text['by'].' '.$character->stats['health_mod_stamina'].'</span>').'\')">
'.$_LANGUAGE->text['stamina'].':</span></td><td class="stats_right">'.color($character->stats['stamina_bonus'],$character->stats['stamina_base']).'</td></tr>';
$base_stats .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['intellect'].' '.($character->stats['intellect_base']).' + '.color($character->stats['intellect_bonus'],0).'</span><br><span class="description">'.$_LANGUAGE->text['increases'].' '.$_LANGUAGE->text['mana'].' '.$_LANGUAGE->text['by'].' '.$character->stats['mana_mod_intellect'].'</span>').'\')">
'.$_LANGUAGE->text['intellect'].':</span></td><td class="stats_right">'.color($character->stats['intellect_bonus'],$character->stats['intellect_base']).'</td></tr>';

$base_stats .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['spirit'].' '.($character->stats['spirit_base']).' + '.color($character->stats['spirit_bonus'],0).'</span>').'\')">
'.$_LANGUAGE->text['spirit'].':</span></td><td class="stats_right">'.color($character->stats['spirit_bonus'],$character->stats['spirit_base']).'</td></tr>';

$base_stats .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['armor'].' '.($character->stats['armor_base']).' + '.color($character->stats['armor_bonus'],0).'</span><br><span class="description">'.$_LANGUAGE->text['reduces'].' '.$_LANGUAGE->text['ps_damage_taken'].' '.$_LANGUAGE->text['by'].' '.$character->stats['armor_mod'].'%</span>').'\')">
'.$_LANGUAGE->text['armor'].':</span></td><td class="stats_right">'.color($character->stats['armor_bonus'],$character->stats['armor_base']).'</td></tr>';

// Damage

if($character->items[15]) {
	$tip.='<span class="tooltip-header">'.$_LANGUAGE->text['main_hand'].'</span><br><span class="description">';
	$tip.=$_LANGUAGE->text['speed'].': '.$character->stats['melee_speed'].'<br>';
	$tip.=$_LANGUAGE->text['damage'].': '.$character->stats['melee_damage_min'] .' - '.$character->stats['melee_damage_max'].'<br>';
	if(!$character->stats['melee_speed']) $character->stats['melee_speed'] = 1;
	$tip.=$_LANGUAGE->text['dps'].': '.round((($character->stats['melee_damage_min']+$character->stats['melee_damage_max'])/2)/$character->stats['melee_speed'],1).'<br>';
	$tip.= '</span>';
}
if($character->items[16]) {
	$tip.='<span class="tooltip-header">'.$_LANGUAGE->text['off_hand'].'</span><br><span class="description">';
	$tip.=$_LANGUAGE->text['speed'].': '.$character->stats['melee_speed_off'].'<br>';
	$tip.=$_LANGUAGE->text['damage'].': '.$character->stats['melee_damage_min_off'] .' - '.$character->stats['melee_damage_max_off'].'<br>';
	if(!$character->stats['melee_speed_off']) $character->stats['melee_speed_off'] = 1;
	$tip.=$_LANGUAGE->text['dps'].': '.round((($character->stats['melee_damage_min_off']+$character->stats['melee_damage_max_off'])/2)/$character->stats['melee_speed_off'],1).'<br>';
	$tip.= '</span>';
}
$melee .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode($tip).'\')">
'.$_LANGUAGE->text['damage'].':</span></td><td class="stats_right">'.$character->stats['melee_damage_min'] .' - '.$character->stats['melee_damage_max'] .'</td></tr>';

// Speed
$tip='';
if($character->items[15]) {
	$tip.= $character->stats['melee_speed'];
}
if($character->items[16]&&$character->items[15]) $tip.=' / ';
if($character->items[16]) {
	$tip.= $character->stats['melee_speed_off'];
}
$melee .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['speed'].' '.$tip.'</span>').'\')">
'.$_LANGUAGE->text['speed'].':</span></td><td class="stats_right">'.$tip.'</td></tr>';

// Power

$tip=$character->stats['melee_ap_base'].' + '.color($character->stats['melee_ap_bonus'],0);
$tip2 = '<span class="description">'.$_LANGUAGE->text['increases'].' '.$_LANGUAGE->text['damage'].' '.$_LANGUAGE->text['by'].' '.round(($character->stats['melee_ap_bonus']+$character->stats['melee_ap_base'])/14,1).' '.$_LANGUAGE->text['dps'].'</span>';

$melee .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['attack_power'].' '.$tip.'</span><br>'.$tip2).'\')">
'.$_LANGUAGE->text['attack_power'].':</span></td><td class="stats_right">'.color($character->stats['melee_ap_bonus'],$character->stats['melee_ap_base']).'</td></tr>';


$melee .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['hit_rating'].' '.$character->stats['melee_hit_rating'].'</span>').'\')">'.$_LANGUAGE->text['hit_rating'].':</span></td><td class="stats_right">'.$character->stats['melee_hit_rating'].'</td></tr>';

$tip='';
if($character->items[15]) {
	$tip.= $character->stats['melee_crit'].'%';
}
if($character->items[16]&&$character->items[15]) $tip.=' / ';
if($character->items[16]) {
	$tip.= $character->stats['melee_crit_off'].'%';
}
$melee .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['crit_chance'].' '.$tip.'</span>').'\')">'.$_LANGUAGE->text['crit_chance'].':</span></td><td class="stats_right">'.$tip.'</td></tr>';


$tip='';
if($character->items[15]) {
	$tip.= $character->stats['melee_expertise'];
}
if($character->items[16]&&$character->items[15]) $tip.=' / ';
if($character->items[16]) {
	$tip.= $character->stats['melee_expertise_off'];
}
$tip2=$tip;
$tip.='</span><br><span class="description">'.$_LANGUAGE->text['reduces'].' '.$_LANGUAGE->text['chance_to_be_p_o_d'].'  '.$_LANGUAGE->text['by'].' ';
if($character->items[15]) {
	$tip.= $character->stats['melee_expertise_proc'].'%';
}
if($character->items[16]&&$character->items[15]) $tip.=' / ';
if($character->items[16]) {
	$tip.= $character->stats['melee_expertise_proc_off'].'%';
}
$melee .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['expertise'].' '.$tip.'</span>').'\')">'.$_LANGUAGE->text['expertise'].':</span></td><td class="stats_right">'.$tip2.'</td></tr>';


// Ranged
$tip='';
$tip.='<span class="tooltip-header">'.$_LANGUAGE->text['ranged'].'</span><br><span class="description">';
	$tip.=$_LANGUAGE->text['speed'].': '.($character->items[17] ? $character->stats['ranged_speed'] : 'N/A').'<br>';
	$tip.=$_LANGUAGE->text['damage'].': '.($character->items[17] ? $character->stats['ranged_damage_min'] .' - '.$character->stats['ranged_damage_max'] : 'N/A').'<br>';
	$tip.=$_LANGUAGE->text['dps'].': '.($character->items[17] ? round((($character->stats['ranged_damage_min']+$character->stats['ranged_damage_max'])/2)/$character->stats['ranged_speed'],1) : 'N/A').'<br>';
	$tip.= '</span>';
$ranged .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode($tip).'\')">
'.$_LANGUAGE->text['damage'].':</span></td><td class="stats_right">'.($character->items[17] ? $character->stats['ranged_damage_min'] .' - '.$character->stats['ranged_damage_max'] : 'N/A').'</td></tr>';

// Speed
$tip='';
$tip.= ($character->items[17] ? $character->stats['ranged_speed'] : 'N/A');
$ranged .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['speed'].' '.$tip.'</span>').'\')">
'.$_LANGUAGE->text['speed'].':</span></td><td class="stats_right">'.$tip.'</td></tr>';

// Power

$tip=$character->stats['ranged_ap_base'].' + '.color($character->stats['ranged_ap_bonus'],0);
$tip2 = '<span class="description">'.$_LANGUAGE->text['increases'].' '.$_LANGUAGE->text['damage'].' '.$_LANGUAGE->text['by'].' '.round(($character->stats['ranged_ap_bonus']+$character->stats['ranged_ap_base'])/14,1).' '.$_LANGUAGE->text['dps'].'</span>';

$ranged .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['attack_power'].' '.$tip.'</span><br>'.$tip2).'\')">
'.$_LANGUAGE->text['attack_power'].':</span></td><td class="stats_right">'.color($character->stats['ranged_ap_bonus'],$character->stats['ranged_ap_base']).'</td></tr>';


$ranged .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['hit_rating'].' '.$character->stats['ranged_hit_rating'].'</span>').'\')">'.$_LANGUAGE->text['hit_rating'].':</span></td><td class="stats_right">'.$character->stats['ranged_hit_rating'].'</td></tr>';

$tip='';
$tip.= $character->stats['ranged_crit'].'%';
$ranged .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['crit_chance'].' '.$tip.'</span>').'\')">'.$_LANGUAGE->text['crit_chance'].':</span></td><td class="stats_right">'.$tip.'</td></tr>';


// Spell

$tmp = array('arcane','fire','frost','nature','shadow');
$tip='<span class="description">';
foreach($tmp as $tt)
$tip.='<img alt="" width="15" height="15" src="'.$_DOMAIN.'images/res-'.$tt.'.gif"> '.$_LANGUAGE->text[$tt].' '.$character->stats['spell_bonus_'.$tt].'<br>';
$tip.='</span>';
$spell .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['bonus_damage'].' '.$character->stats['spell_bonus'].'</span><br>'.$tip).'\')">'.$_LANGUAGE->text['bonus_damage'].':</span></td><td class="stats_right">'.$character->stats['spell_bonus'].'</td></tr>';


$spell .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['bonus_healing'].' '.$character->stats['spell_bonus_healing'].'</span>').'\')">'.$_LANGUAGE->text['bonus_healing'].':</span></td><td class="stats_right">'.$character->stats['spell_bonus_healing'].'</td></tr>';

$spell .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['hit_rating'].' '.$character->stats['spell_hit_rating'].'</span>').'\')">'.$_LANGUAGE->text['hit_rating'].':</span></td><td class="stats_right">'.$character->stats['spell_hit_rating'].'</td></tr>';

$tmp = array('arcane','fire','frost','nature','shadow');
$tip='<span class="description">';
foreach($tmp as $tt)
$tip.='<img alt="" width="15" height="15" src="'.$_DOMAIN.'images/res-'.$tt.'.gif"> '.$_LANGUAGE->text[$tt].' '.$character->stats['spell_crit_'.$tt].'%<br>';
$tip.='</span>';

$spell .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['crit_rating'].' '.$character->stats['spell_crit_rating'].'</span><br>'.$tip).'\')">'.$_LANGUAGE->text['crit_chance'].':</span></td><td class="stats_right">'.$character->stats['spell_crit'].'%</td></tr>';


$spell .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['mana_regen'].' '.$character->stats['mana_regen'].'</span><br><span class="description">'.$character->stats['mana_regen'].' '.$_LANGUAGE->text['mana_regen_p_5_s_w_n_t'].'</span>').'\')">'.$_LANGUAGE->text['mana_regen'].':</span></td><td class="stats_right">'.$character->stats['mana_regen'].'</td></tr>';

// Defense
$defense .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['armor'].' '.($character->stats['armor_base']).' + '.color($character->stats['armor_bonus'],0).'</span><br><span class="description">'.$_LANGUAGE->text['reduces'].' '.$_LANGUAGE->text['ps_damage_taken'].' '.$_LANGUAGE->text['by'].' '.$character->stats['armor_mod'].'%</span>').'\')">
'.$_LANGUAGE->text['armor'].':</span></td><td class="stats_right">'.color($character->stats['armor_bonus'],$character->stats['armor_base']).'</td></tr>';


$defense .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['dodge'].' '.$character->stats['dodge'].'%</span>').'\')">'.$_LANGUAGE->text['dodge'].':</span></td><td class="stats_right">'.$character->stats['dodge'].'%</td></tr>';
$defense .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['parry'].' '.$character->stats['parry'].'%</span>').'\')">'.$_LANGUAGE->text['parry'].':</span></td><td class="stats_right">'.$character->stats['parry'].'%</td></tr>';
$defense .= '<tr><td class="stats_left"><span class="description" onMouseOut="tooltip_hide();"
onMouseOver="tooltip(\''.$_SYSTEM->htmlcode('<span class="tooltip-header">'.$_LANGUAGE->text['block'].' '.$character->stats['block'].'%</span>').'\')">'.$_LANGUAGE->text['block'].':</span></td><td class="stats_right">'.$character->stats['block'].'%</td></tr>';

$tp->assign('base_stats',$base_stats);
$tp->assign('melee',$melee);
$tp->assign('ranged',$ranged);
$tp->assign('spell',$spell);
$tp->assign('defense',$defense);

$_LANGUAGE->translate($tp);
$tp->display();

?>