<?php

foreach($character->reputation as $key => $value) {
	if(!count($value['data'])) continue;
	$rep.= "<div class=\"inner-cont\">
	<table class=\"iht\">
	<tr>
	<td class=\"ihl\"><span class=\"faction-".$value['faction']."\">
	<p>".$value['name']."</p>
	</span></td><td class=\"ihrc\"></td>
	</tr>
	</table>
	<table>
	<tr>
	<td class=\"il\"></td><td class=\"ibg\">
	<div class=\"profile-wrapper\">";
	foreach($value['data'] as $value2) {
		$rep.= "<div class=\"rep".$value2['rank']."\">
		<div class=\"rep-lbg\">
		<div class=\"rep-lr\">
		<div class=\"rep-ll\">
		<ul>
		<li class=\"faction-name\">
		<a href=\"#\" ".($value2["description"]?"onMouseOut=\"tooltip_hide();\" onMouseOver=\"tooltip('".addslashes(str_replace("\"","'",$value2["description"]))."');\"":'').">".$value2["name"]."</a>
		</li>
		<li class=\"faction-bar\">
		<a class=\"rep-data\">".$value2['rep']."/".$value2['rep_cap']."</a>
		<div class=\"bar-color\" style=\" width: ".(100*$value2['rep']/$value2['rep_cap'])."%\"></div>
		</li>
		<li class=\"faction-level\">
		<p class=\"rep-icon\">".$value2['rank_name']."</p>
		</li>
		</ul>
		</div>
		</div>
		</div>
		</div>";
	}
	$rep.="</div>
	</td><td class=\"ir\"></td>
	</tr>
	<tr>
	<td class=\"ibl\"></td><td class=\"ib\"></td><td class=\"ibr\"></td>
	</tr>
	</table>
	</div>";

}
$tp->assign('rep',$rep);

//print_r($character->reputation);
?>