<?php
$tp2 = new template();
$tp2->add('talents/'.$character->classToString($character->class).'_new');
for($i=0;$i<3;$i++) 
   $tp2->assign('tl'.$i,$character->talentCount[$i]);
   

$script = '<script type="text/javascript">talentConf = "'.$character->talentLink.'";';
foreach($character->talentInfo as $key => $value) {
	$script .= "talentName[".$value['id']."] = '".$_SYSTEM->htmlcode($value['name'])."';";
	$script .= "talentDescription[".$value['id']."] = '".$_SYSTEM->htmlcode($value['description'])."';";
	$script .= "talentID[".$key."] = '".$value['id']."';";
	if($value['sBase']) $script .= "talentHID[".$value['sBase']."] = '".$value['id']."';";
}
$script .= 'talentLoad();</script>';
   
$tp->assign('talent_tree',$tp2->output.$script);
?>