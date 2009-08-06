<?php
class character {

  public $lastupdate;

  public $guid;
  public $name;
  public $race;
  public $class;
  public $gender;
  public $level;
  public $guild_id,$guild,$guild_rank;
  public $played;
  public $items;
  public $realm;
  public $gold;
  public $honor;
  public $hk;
  public $items_names;
  public $arenapoints;

  public $item_tooltips;
  public $item_instance;

  public $prof_1;
  public $prof_2;
  public $skills;
  public $talentCount, $talentSpec, $talentLink, $talentInfo = array();

  public $achievement = array(),$achievement_progress = array();

  public $data;

  public $stats; // No i mamy sobie statystyki. Ale jakie...?
  /* strength_bonus, agility_bonus, stamina_bonus, intellect_bonus, spirit_bonus, strength_base, agility_base, stamina_base, intellect_base, spirit_base, armor_bonus, armor_base, defence_rating, dodge, parry, block, holy_res, fire_res, frost_res, shadow_res, nature_res, arcane_res, melee_damage_min, melee_damage_max, melee_damage_min_off, melee_damage_max_off, melee_speed, melee_speed_off, melee_ap_base, melee_ap_bonus, meele_hit_rating, meele_crit_rating, melee_crit, melee_crit_off, ranged_damage_min, ranged_damage_max, ranged_speed, ranged_ap_base, ranged_ap_bonus, ranged_hit_rating, ranged_crit_rating, ranged_crit, spell_bonus_holy, spell_bonus_fire, spell_bonus_nature, spell_bonus_frost, spell_bonus_shadow, spell_bonus_arcane, spell_bonus, spell_bonus_healing, spell_hit_rating, spell_crit_rating, spell_crit_holy, spell_crit_fire, spell_crit_nature, spell_crit_frost, spell_crit_shadow, spell_crit_arcane, spell_crit */ // Takie :)

  public $online;

  function __construct($guid) {
	  global $config,$SQL,$mysql,$_SYSTEM;
	  $this->guid=-1;

	  $r = $mysql->getRow("SELECT name,race,class,totaltime,data,online,?1 as gender,?3 as guildid,?4 as guildrank
	  FROM `characters` WHERE guid = ?2",CHAR_GENDER_OFFSET,$guid,SQL_template(CHAR_GUILD_OFFSET),SQL_template(CHAR_GUILD_OFFSET+1),'char');
	  if(!$r) $_SYSTEM->error('Character not found!');
	  if($r['guildid'])
	      $r2 = $mysql->getRow("select * from `guild_rank` where guildid = ?1 and rid = ?2",$r['guildid'],$r['guildrank'],'char');
	  // Ustawienie bazowych informacji
	  if($r2) $this->guild_rank = $r2['rname'];
	  else $r2['rname'] = 'Unknown';
	  $this->guid = $guid;
	  $this->name = $r['name'];
	  $this->class = $r['class'];
	  $this->race = $r['race'];
	  $this->online = $r['online'];
	  $this->gender = $r['gender'];
	  $this->guild_id = $r['guildid'];
	  $this->guild = guild($r['guildid']);
	  $this->guild = $this->guild->name;
	  $this->data = explode(' ', $r['data']);
	  $this->honor = $this->data[CHAR_HONOR_OFFSET];
	  $this->hk = $this->data[CHAR_HK_OFFSET];
	  $this->arenapoints = $this->data[CHAR_ARENAPOINTS_OFFSET];
	  $this->gold = $this->data[MONEY_OFFSET];
	  $this->level = $this->data[CHAR_LEVEL_OFFSET];
	  $t_time = $r['totaltime'];
  	  $t_days = (int)($t_time/86400);
      $t_time = $t_time - ($t_days*86400);
      $t_hours = (int)($t_time/3600);
  	  $t_time = $t_time - ($t_hours*3600);
  	  $t_min = (int)($t_time/60);
	  $this->played[0] = $t_days;
	  $this->played[1] = $t_hours;
	  $this->played[2] = $t_min;
	  $this->realm = $_SYSTEM->Realms[$_SYSTEM->Realm];
	  $this->guild_id = $this->data[CHAR_GUILD_ID_OFFSET];

	  $this->stats = $this->read_stats();

	  $this->read_skills();

	  $this->sort_skills();

	  $this->read_items();

	  $this->load_reputation();

	  $this->load_tooltips();

	  $this->load_talents();

	  $this->load_achievements();

	  $this->lastupdate = time();

	  return true;
  }

  function load_achievements() {
	  global $mysql,$config;
	  $r = $mysql->getRows("select * from character_achievement where guid = ?1",$this->guid,'char');
	  if($r) foreach($r as $row) {
		  $this->achievement[$row['achievement']] = $row['date'];
	  }
	  $r = $mysql->getRows("select * from character_achievement_progress where guid = ?1",$this->guid,'char');
	  if($r) foreach($r as $row) {
		  $this->achievement_progress[$row['criteria']]['date'] = $row['date'];
		  $this->achievement_progress[$row['criteria']]['counter'] = $row['counter'];
	  }

  }

  function load_talent_info($list,$base) {
	  global $mysql,$config;
	  $d = $mysql->getRows("SELECT * FROM `spell` WHERE `id` IN (?1-1)",$list,'armory');
	  if(!$d) return;
	  foreach($d as $spell) {
		  $this->talentInfo[$base[$spell['id']][1]]['description'] = spellReplace($spell);
		  $this->talentInfo[$base[$spell['id']][1]]['name'] = $spell['SpellName'];
		  $this->talentInfo[$base[$spell['id']][1]]['id'] = $spell['id'];
		  $this->talentInfo[$base[$spell['id']][1]]['sBase'] = $base[$spell['id']][0];
	  }
  }

  function load_talents() {
	global $mysql,$config;
	$talentLink = '';
	$this->talentSpec['name'] = 'untalented';
	$this->talentSpec['nr'] = false;
	$max = 0;
	$specs = $mysql->getRows("SELECT id,name FROM `talenttab` WHERE `refmask_chrclasses` = '?1' order by tab_number",
						 pow(2,($this->class-1)),'armory');
	$spells = $mysql->getRows("SELECT `spell` FROM `character_spell` WHERE `guid` = '?1' AND `disabled` = '0'",
								  $this->guid,'char');
	for($i=0; $i<3; $i++) {
		$c=0;
		$spec = $specs[$i];
		$talents = $mysql->getRows("SELECT rank1, rank2, rank3, rank4, rank5 FROM `talent` WHERE `ref_tab` = '?1' order by row,col",
									   $spec['id'],'armory');
		foreach($talents as $key => $value) {
			$ids .= $value['rank1'].',';
		}
		foreach($spells as $k => $v) {
			$spell_ids.=$v['spell'].',';
		}
		$r = $mysql->getRows("SELECT id,SpellName FROM `spell` WHERE `id` IN (?1-1)",$ids.$spell_ids,'armory');

		foreach($r as $row) {
			$SpellNames.="'".addslashes($row['SpellName']).'\',';
			$NameToId[arrayName($row['SpellName'])] = $row['id'];
		}
		$r = $mysql->getRows("SELECT id,SpellName,Rank FROM `spell` WHERE Rank LIKE 'Rank %' and `SpellName` IN (?1)", substr($SpellNames,0,-1),'armory');
		//die($mysql->Query);
  		if($r) {
  			foreach($r as $row) {
				$Ranks[$NameToId[arrayName($row['SpellName'])]][3] = $row['SpellName'];
				if($row['Rank']=='Rank 1') $Ranks[$NameToId[arrayName($row['SpellName'])]][0] = $row['id'];
				else {
					$row['Rank'] = explode(' ',$row['Rank']);
					$row['Rank'] = $row['Rank'][1];
					if($row['Rank']>$Ranks[$NameToId[arrayName($row['SpellName'])]][2]) {
						$Ranks[$NameToId[arrayName($row['SpellName'])]][2] = $row['Rank'];
						$Ranks[$NameToId[arrayName($row['SpellName'])]][1] = $row['id'];
					}
				}
			}
		}
		if($spells) {

			foreach($talents as $key => $value) {
				$add='0';
				$rank_1 = $Ranks[$value['rank1']][1];
				$crr = $value['rank1'];
				foreach($spells as $k => $v) {
					if(in_array($v['spell'],$value)) {
						switch(array_search($v['spell'], $value)) {
							case "rank1": $c += 1;$add='1'; break;
							case "rank2": $c += 2;$add='2'; break;
							case "rank3": $c += 3;$add='3'; break;
							case "rank4": $c += 4;$add='4'; break;
							case "rank5": $c += 5;$add='5'; break;
						}
						$crr = $value['rank'.$add];
					}else if(in_array($Ranks[$v['spell']][0],$value)) {
						$c += 1;$add='1';
						$crr=$v['spell'];
						$rank_1 = $Ranks[$v['spell']][1];
					}
				}
				$talentList .= $crr.',';  // Najwyzszy rank lub pierwszy jesli nie istnieje.
				$baseList[$crr] = array($rank_1,$value['rank1']); // Potrzebujemy tablice ID pierwszych rankow.
				$talentLink.=$add;

			}
		}

		//die($talentList);
		$this->talentCount[$i] = $c;
		if($this->talentCount[$i]>$max) {
			$this->talentSpec['name'] = $spec['name'];
			$this->talentSpec['nr'] = $i+1;
			$max = $this->talentCount[$i];
		}
	}
	$this->talentLink = $talentLink;
	$this->load_talent_info($talentList, $baseList);

	//die($this->talentLink);

  }

  function sort_skills() {
	  global $mysql,$config;
	  $new_skills = array();
	  $cat = $mysql->getRows("select * from `skilllinecategory` order by `display_order`",'armory');
	  foreach($cat as $c) {
		  $new_skills[$c['id']]['category_name'] = $c['name'];
		  $new_skills[$c['id']]['data'] = array();
	  }
	  // Zbieramy idiki
	  foreach($this->skills as $value)
		  $id_list.=$value[0].',';
	  $skill_data = $mysql->getRows("select * from `skillline` where id in(?1-1)",$id_list,'armory');
	  foreach($this->skills as $value) {
		  foreach($skill_data as $skill)
		  	if($skill['id']==$value[0]) $data = $skill;// Malo wydajne ale krotkie. Lepiej wpieprzc do tablicy ale mi sie nie chce...
		  if($data['ref_category'] == 7 || $data['ref_category'] == 8 || $data['ref_category'] == 10) $value[2] = $value[3] = 0;
	  	  array_push($new_skills[$data['ref_category']]['data'], array($value[0], $data['name'], $value[2], $value[3], $data['description']));
	  }
	  $this->skills = $new_skills;
  }

  function load_reputation() {
	  global $mysql,$config;
	    // Na poczatek kilka definicji
		$faction_ihl = array(
			1118 => "classic",
			469 => "alliance",
			891 => "allianceforces",
			1037 => "classic",
			67 => "horde",
			892 => "hordeforces",
			1052 => "classic",
			936 => "shattrathcity",
			1117 => "classic",
			169 => "steamwheedlecartel",
			980 => "outland",
			1097 => "classic",
			0 => "zother"
		);
	    $reputation_rank = array(
			0 => "Hated",
			1 => "Hostile",
			2 => "Unfriendly",
			3 => "Neutral",
			4 => "Friendly",
			5 => "Honored",
			6 => "Revered",
			7 => "Exalted"
		);
		$reputation_rank_length = array(36000, 3000, 3000, 3000, 6000, 12000, 21000, 999);
		$reputation_cap    =  42999;
		$reputation_bottom = -42000;
		$MIN_REPUTATION_RANK = 0;
		$MAX_REPUTATION_RANK = 8;

		// Wczytajmy i ustawmy nazwy bazowych frakcji
	    foreach($faction_ihl as $key => $faction) $fc_list.=$key.',';
		$fc = $mysql->getRows("SELECT id,name FROM `faction` WHERE `id` IN (?1-1)", $fc_list,'armory');
		foreach($fc as $f) $faction_name[$f['id']] = $f['name'];
		$fc_list='';
		foreach($faction_ihl as $key => $value) {
			$this->reputation[$key]['faction'] = $value;
			$this->reputation[$key]['name'] = $faction_name[$key];
		}



	  // Wczytujemy informacje o reputacji
	  $rep = $mysql->getRows("SELECT `faction`, `standing` FROM `character_reputation` WHERE `guid` ='?1' AND (`flags` & 1 = 1)", $this->guid,'char'); // Rep bohatera
	  foreach($rep as $faction) $fc_list.=$faction['faction'].',';
	  $fc = $mysql->getRows("SELECT * FROM `faction` WHERE `id` IN (?1-1)", $fc_list,'armory'); // Informacje o frakcjach

	  foreach($rep as $faction) {
		$stan = $faction['standing'];
		foreach($fc as $f)
		   if($f['id']==$faction['faction']) $fc_data = $f;
		 for ($i = 0; $i < 4; $i++){
			if ($fc_data["base_ref_chrraces_".$i] & (1 << ($this->race-1))) {
				$stan += $fc_data["base_modifier_".$i];
				break;
			}
		 }
		 $rep_rank = $MIN_REPUTATION_RANK;
		 $rep = 0;
		 $limit = $reputation_cap;
		 // Wyznaczamy range oraz reputacje w tej randze
		 for($i = $MAX_REPUTATION_RANK-1; $i >= $MIN_REPUTATION_RANK; --$i) {
			$limit -= $reputation_rank_length[$i];
			if($stan >= $limit) {
				$rep_rank = $i;
				$rep = $stan - $limit;
				break;
			}
		}
		// Zapisywanie danych
		$rep_rank_name = $reputation_rank[$rep_rank];
		$rep_cap = $reputation_rank_length[$rep_rank];
		$data['rank'] = $rep_rank;
		$data['description'] = $fc_data['description'];
		$data['name'] = $fc_data['name'];
		$data['rank_name'] = $rep_rank_name;
		$data['rep'] = $rep;
		$data['rep_cap'] = $rep_cap;

		$this->reputation[$fc_data["ref_faction_parent"]]['data'][count($this->reputation[$fc_data["ref_faction_parent"]]['data'])] = $data; // Moze jakis array_push...?
	}


  }

  function load_tooltips() {
	global $SQL,$EQ_SLOT;
	$tooltip = new tooltip($this->items,$this->item_instance,$this->guid,$this->data);
	for($i=0;$i<19;$i++) {
		$tooltip->transform($i);
		$this->item_tooltips[$i] = $tooltip->output;
	}

  }

  function read_items() {
	  global $SQL,$mysql,$config;
	  //die($this->data[HEAD_EQU_0_OFFSET]);
	  $r = $mysql->getRows("select * from `character_inventory` where `guid` = ?1 and `slot` < 18 and bag = 0",
						   $this->guid,'char');
	  foreach($r as $row) {
		  $this->items[$row['slot']] = $row['item_template'];
		  $this->item_instance[$row['slot']] = $row['item'];
	  }

	  foreach($this->items as $key => $value) {
		$this->items_names[$key] = $this->get_item_name($value);
	  }
  }

  function get_item_name($id) {
	     global $config,$mysql;
	     $r = $mysql->getRow("select name from `item_template` where entry = ?1",$id,'armory');
		 return $r['name'];
  }

  function get_item_icon($slot) {
	 global $config,$_SYSTEM,$_DOMAIN,$mysql;
	 if($this->items[$slot]) {
		 $r = $mysql->getRow("SELECT `itemicon` FROM `itemicon` WHERE itemnumber = ?1",
											$this->items[$slot],'armory');
			 if(!$r || !file_exists('images/icon/'.strtolower(basename($r['itemicon'])).'.jpg') || basename($r['itemicon'])=='') {
				 //('images/icon/'.str_replace('.png','',strtolower(basename($r['itemicon']))).'.jpg');
			     if($_SYSTEM->update_icon_db($this->items[$slot]))
				    return $this->get_item_icon($slot);
				 else return $_DOMAIN.'images/icon/inv_misc_questionmark.jpg'; // Aktualizacja nie udana :(
			 }else return $_DOMAIN.'images/icon/'.strtolower(basename($r['itemicon'])).'.jpg';
			 		//return 'http://wow.allakhazam.com/images/icons/'.$r['itemicon'];
	 }
	 return false;
  }

  function read_skills() {
	global $_LANGUAGE, $SQL;
	$prof_1_array = array();
    $prof_2_array = array();
    $skill_array = array();

    $skill_rank_array = array( // Tlumaczenie przed zapisaniem do cache to debilny pomysl -.-
	  75 => $_LANGUAGE->text['apprentice'],
	  150 => $_LANGUAGE->text['journeyman'],
	  225 => $_LANGUAGE->text['expert'],
	  300 => $_LANGUAGE->text['artisan'],
	  375 => $_LANGUAGE->text['master'],
	  450 => $_LANGUAGE->text['grand_master']
	);

    for($i = SKILL_INFO_OFFSET; $i <= SKILL_INFO_OFFSET+384 ; $i+=3){
       if(($this->data[$i]) && ($this->get_skill_name($this->data[$i] & 0x0000FFFF ))){
           $temp = unpack("S", pack("L", $this->data[$i+1]));
           $skill = ($this->data[$i] & 0x0000FFFF); // Maska 0x0000FFFF skróci liczbe do "usint32"

		if( $skill == 185 || $skill == 129 || $skill == 356) {
		  $max = ($temp[1] <= 75) ? 75 : (($temp[1] <= 150) ? 150 : (($temp[1] <= 225) ? 225 : (($temp[1] <= 300) ? 300 : (($temp[1] <= 375) ? 375 : (($temp[1] <= 450) ? 450 : 0)))));
		  array_push($skill_array , array($skill, $this->get_skill_name($skill), $temp[1],$max));
		}else if( $skill == 171 || $skill == 182 || $skill == 186 ||
         $skill == 197 || $skill == 202 || $skill == 333 ||
         $skill == 393 || $skill == 755 || $skill == 164 ||
         $skill == 165) {
			$max = ($temp[1] <= 75) ? 75 : (($temp[1] <= 150) ? 150 : (($temp[1] <= 225) ? 225 : (($temp[1] <= 300) ? 300 : (($temp[1] <= 375) ? 375 : (($temp[1] <= 450) ? 450 : 0)))));
			if($skill == 333 && $this->race == 10) { $temp[1]+=10; $max+=10; } // Krwawe elfy +10 enchanting
			array_push($prof_1_array , array($skill, $this->get_skill_name($skill), $temp[1],$max));
		}else{

      		array_push($skill_array , array($skill, $this->get_skill_name($skill), $temp[1]>$this->level*5?$this->level*5:$temp[1], $skill==762?$temp[1]:$this->level*5));
    	}
      }
    }
	// Zapisywanie
    $this->prof_1 = $prof_1_array;
    $this->prof_2 = $prof_2_array;
    $this->skills = $skill_array;
  }

  function getGold($g=-1) {
	global $_DOMAIN;
	$_DOMAIN = str_replace('ajax/','',$_DOMAIN);
	if($g==-1) $g=$this->gold;
	// Palowo ale skutecznie...
	$gold = floor($g/10000);
	if($gold>0) $ret .= $gold.' <img alt="" src="'.$_DOMAIN.'images/money_gold.gif"> ';
	$gold = $g-($gold*10000);
	$silver = floor($gold/100);
	if($silver>0 || $ret!='') $ret .= $silver.' <img alt="" src="'.$_DOMAIN.'images/money_silver.gif"> ';
	$copper = $gold - $silver*100;
	$ret .= $copper.' <img alt="" src="'.$_DOMAIN.'images/money_copper.gif">';
	return $ret;
  }

  function read_stats() {
	  global $SQL;
	  // Statysyki glówne
	  $stats['strength_bonus'] = $this->cstat($this->data[STRENGTH_POS_OFFSET]) - $this->cstat($this->data[STRENGTH_NEG_OFFSET]);
	  $stats['agility_bonus'] = $this->cstat($this->data[AGILITY_POS_OFFSET]) - $this->cstat($this->data[AGILITY_NEG_OFFSET]);
	  $stats['stamina_bonus'] = $this->cstat($this->data[STAMINA_POS_OFFSET]) - $this->cstat($this->data[STAMINA_NEG_OFFSET]);
	  $stats['intellect_bonus'] = $this->cstat($this->data[INTELLECT_POS_OFFSET]) - $this->cstat($this->data[INTELLECT_NEG_OFFSET]);
	  $stats['spirit_bonus'] = $this->cstat($this->data[SPIRIT_POS_OFFSET]) - $this->cstat($this->data[SPIRIT_NEG_OFFSET]);

	  $stats['strength_base'] = $this->data[STRENGTH_BASE_OFFSET] - $stats['strength_bonus'];
	  $stats['agility_base'] = $this->data[AGILITY_BASE_OFFSET] - $stats['agility_bonus'];
	  $stats['stamina_base'] = $this->data[STAMINA_BASE_OFFSET] - $stats['stamina_bonus'];
	  $stats['intellect_base'] = $this->data[INTELLECT_BASE_OFFSET] - $stats['intellect_bonus'];
	  $stats['spirit_base'] = $this->data[SPIRIT_BASE_OFFSET] - $stats['spirit_bonus'];
	  $stats['mana_mod_intellect'] = ($stats['intellect_bonus']+$stats['intellect_base']-20 >=0 ? ($stats['intellect_bonus']+$stats['intellect_base']-20)*15 : 0) + ($stats['intellect_bonus']+$stats['intellect_base']>=20 ? 20 : $stats['intellect_bonus']+$stats['intellect_base']);

	  // Obrona
	  $stats['armor_bonus'] =  $this->cstat($this->data[ARMOR_POS_OFFSET]) -  $this->cstat($this->data[ARMOR_NEG_OFFSET]);
	  $stats['armor_base'] = $this->data[ARMOR_BASE_OFFSET] - $stats['armor_bonus'];
	  $stats['armor_mod'] =2*($stats['agility_base']+$stats['agility_bonus']);
	  $stats['defence_rating'] = $this->data[DEFENCE_RATING_OFFSET]; // Cosik nie dziala? :(
	  $stats['dodge'] = round($this->cstat($this->data[DODGE_OFFSET]),2);
	  $stats['parry'] = round($this->cstat($this->data[PARRY_OFFSET]),2);
	  $stats['block'] = round($this->cstat($this->data[BLOCK_OFFSET]),2);

	  $stats['armor_mod'] = round($this->level < 60 ? (($stats['armor_bonus']+$stats['armor_base'])/(($stats['armor_bonus']+$stats['armor_base']) + 400 + 85 * $this->level)) : (($stats['armor_bonus']+$stats['armor_base']) / (($stats['armor_bonus']+$stats['armor_base']) + 400 + 85 * ($this->level + 4.5 * ($this->level - 59)))),4);
	  $stats['armor_mod'] = ($stats['armor_mod'] > 0.75 ? 0.75 : $stats['armor_mod'])*100;

	  $stats['health_mod_stamina'] = 10*($stats['stamina_bonus']+$stats['stamina_base']);

	  // Odpornosci
	  $stats['holy_res'] = $this->data[HOLY_RES_OFFSET];
	  $stats['fire_res'] = $this->data[FIRE_RES_OFFSET];
	  $stats['frost_res'] = $this->data[FROST_RES_OFFSET];
	  $stats['shadow_res'] = $this->data[SHADOW_RES_OFFSET];
	  $stats['nature_res'] = $this->data[NATURE_RES_OFFSET];
	  $stats['arcane_res'] = $this->data[ARCANE_RES_OFFSET];

	  // Melee
	  $stats['melee_damage_min'] = $this->cstat($this->data[MELEE_DAMAGE_MIN_OFFSET],0,-1); //Floor
	  $stats['melee_damage_max'] = $this->cstat($this->data[MELEE_DAMAGE_MAX_OFFSET],0,1); //Ceil
	  $stats['melee_damage_min_off'] = $this->cstat($this->data[MELEE_DAMAGE_MIN_OFF_OFFSET]);
	  $stats['melee_damage_max_off'] = $this->cstat($this->data[MELEE_DAMAGE_MAX_OFF_OFFSET]);
	  $stats['melee_speed'] = round($this->cstat($this->data[MELEE_SPEED_OFFSET])/1000,2);
	  $stats['melee_speed_off'] = round($this->cstat($this->data[MELEE_SPEED_OFF_OFFSET])/1000,2);
	  $stats['melee_ap_base'] = $this->data[MELEE_AP_OFFSET];
	  $stats['melee_ap_bonus'] = $this->data[MELEE_AP_MOD_OFFSET];
	  $stats['melee_ap_mod'] = in_array($this->class,array(1,2,6,11)) ? 2*($stats['strength_base']+$stats['strength_bonus']) : ($stats['strength_base']+$stats['strength_bonus']);
	  $stats['melee_ap_mod_agility'] = in_array($this->class,array(3,4,7)) ? ($stats['agility_base']+$stats['agility_bonus']) : 0;

	  $stats['melee_hit_rating'] = $this->data[MELEE_HIT_RATING_OFFSET];
	  $stats['melee_crit_rating'] = $this->data[MELEE_CRIT_RATING_OFFSET];
	  $stats['melee_crit'] = round($this->cstat($this->data[MELEE_CRIT_OFFSET],2),2);
	  $stats['melee_crit_off'] = round($this->cstat($this->data[MELEE_CRIT_OFF_OFFSET],2),2);
	  $stats['melee_expertise'] = $this->data[MELEE_EXPERTISE_OFFSET];
	  $stats['melee_expertise_off'] = $this->data[MELEE_EXPERTISE_OFF_OFFSET];
	  $stats['melee_expertise_proc'] = $this->data[MELEE_EXPERTISE_OFFSET]*0.25;
	  $stats['melee_expertise_proc_off'] = $this->data[MELEE_EXPERTISE_OFF_OFFSET]*0.25;

	  // Ranged
	  $stats['ranged_damage_min'] = $this->cstat($this->data[RANGED_DAMAGE_MIN_OFFSET],0,-1);//Floor
	  $stats['ranged_damage_max'] = $this->cstat($this->data[RANGED_DAMAGE_MAX_OFFSET],0,1);//Ceil
	  $stats['ranged_speed'] = round($this->cstat($this->data[RANGED_SPEED_OFFSET])/1000,2);
	  $stats['ranged_ap_base'] = $this->data[RANGED_AP_OFFSET];
	  $stats['ranged_ap_bonus'] = $this->data[RANGED_AP_MOD_OFFSET];
	  $stats['ranged_hit_rating'] = $this->data[RANGED_HIT_RATING_OFFSET];
	  $stats['ranged_crit_rating'] = $this->data[RANGED_CRIT_RATING_OFFSET];
	  $stats['ranged_crit'] = round($this->cstat($this->data[RANGED_CRIT_OFFSET],2),2);

	  // Spell
	  $stats['spell_bonus_holy'] = $this->data[SPELL_BONUS_HOLY_OFFSET];
	  $stats['spell_bonus_fire'] = $this->data[SPELL_BONUS_FIRE_OFFSET];
	  $stats['spell_bonus_nature'] = $this->data[SPELL_BONUS_NATURE_OFFSET];
	  $stats['spell_bonus_frost'] = $this->data[SPELL_BONUS_FROST_OFFSET];
	  $stats['spell_bonus_shadow'] = $this->data[SPELL_BONUS_SHADOW_OFFSET];
	  $stats['spell_bonus_arcane'] = $this->data[SPELL_BONUS_ARCANE_OFFSET];
	  $stats['spell_bonus'] = 99999999999999;
	  foreach($stats as $key => $value)
		if(strpos($key,'spell_bonus_')!==FALSE && $value<$stats['spell_bonus']) $stats['spell_bonus'] = $value;
	  $stats['spell_bonus_healing'] = $this->data[SPELL_BONUS_HEALING_OFFSET];
	  $stats['spell_hit_rating'] = $this->data[SPELL_HIT_RATING_OFFSET];
	  $stats['spell_crit_rating'] = $this->data[SPELL_CRIT_RATING_OFFSET];
	  $stats['spell_crit_holy'] = round($this->cstat($this->data[SPELL_CRIT_HOLY_OFFSET],2),2);
	  $stats['spell_crit_fire'] = round($this->cstat($this->data[SPELL_CRIT_FIRE_OFFSET],2),2);
	  $stats['spell_crit_nature'] = round($this->cstat($this->data[SPELL_CRIT_NATURE_OFFSET],2),2);
	  $stats['spell_crit_frost'] = round($this->cstat($this->data[SPELL_CRIT_FROST_OFFSET],2),2);
	  $stats['spell_crit_shadow'] = round($this->cstat($this->data[SPELL_CRIT_SHADOW_OFFSET],2),2);
	  $stats['spell_crit_arcane'] = round($this->cstat($this->data[SPELL_CRIT_ARCANE_OFFSET],2),2);
	  $stats['spell_crit'] = 99999999999999;
	  foreach($stats as $key => $value)
		if(strpos($key,'spell_crit_')!==FALSE && $value<$stats['spell_crit']) $stats['spell_crit'] = $value;
	  $stats['mana_regen'] = $this->cstat( $this->data[MANA_REGEN_OFFSET] )*5;


	  $stats['max_health'] = $this->data[MAX_HEALTH_OFFSET];
	  $stats['max_mana'] = $this->data[MAX_MANA_OFFSET];
	  $stats['max_rage'] = $this->data[MAX_RAGE_OFFSET];
	  $stats['max_energy'] = $this->data[MAX_ENERGY_OFFSET];
	  $stats['max_focus'] = $this->data[MAX_FOCUS_OFFSET];

	  $this->class==1 ? $stats['max_power'] = $stats['max_rage'] : ($this->class==4 || $this->class==6 ? $stats['max_power'] = $stats['max_energy'] : $stats['max_power'] = $stats['max_mana']);
	  return $stats;
  }

  function getPowerType() {
	  global $_LANGUAGE;
	  // I znow tlumaczenie przed zapisem do cache -.- Ja naprawde az taki glupi jestem...?
	  if($this->class==1) return $_LANGUAGE->text['rage'];
	  elseif($this->class==4) return $_LANGUAGE->text['energy'];
	  elseif($this->class==6) return $_LANGUAGE->text['runic'];
	  else return $_LANGUAGE->text['mana'];
  }

  function getAlliance($race=-1) {
	  if($race==-1) $race = $this->race;
	  if(in_array($race,array(1,3,4,7,11))) return 0;
	  return 1;
  }

  function cstat($stat,$r=0,$u=0) {
	$tmp = unpack("f", pack("L",$stat));
	if($u==0) return round($tmp[1],$r);
	else if($u==-1) return floor($tmp[1]);
	else return ceil($tmp[1]);
   }

  function raceToString($race) {
	switch ($race) {
	    case 1: $rOut='human';
		 break;
		 case 2: $rOut='orc';
		 break;
		 case 3: $rOut='dwarf';
		 break;
		 case 4: $rOut='nightelf';
		 break;
		 case 5: $rOut='undead';
		 break;
		 case 6: $rOut='tauren';
		 break;
		 case 7: $rOut='gnome';
		 break;
		 case 8: $rOut='troll';
		 break;
		 case 10: $rOut='bloodelf';
		 break;
		 case 11: $rOut='draenei';
		 break;
    }
   return $rOut;
   }

	function classToString($class) {
		switch ($class) {
			case 1: $rOut='warrior';
			 break;
			 case 2: $rOut='paladin';
			 break;
			 case 3: $rOut='hunter';
			 break;
			 case 4: $rOut='rogue';
			 break;
			 case 5: $rOut='priest';
			 break;
			 case 6: $rOut='deathknight';
			 break;
			 case 7: $rOut='shaman';
			 break;
			 case 8: $rOut='mage';
			 break;
			 case 9: $rOut='warlock';
			 break;
			 case 11: $rOut='druid';
			 break;
	 }
	 return $rOut;
	}

	function get_skill_name($id) {
		global $_LANGUAGE;
		$skill_id = Array(
			773 => array(773, 'SKILL_INSCRIPTION'),
			762 => array(762, 'SKILL_RIDING'),
			759 => array(759, 'SKILL_LANG_DRAENEI'),
			755 => array(755, 'SKILL_JEWELCRAFTING'),
			713 => array(713, 'SKILL_RIDING_KODO'),
			673 => array(673, 'SKILL_LANG_GUTTERSPEAK'),
			633 => array(633, 'SKILL_LOCKPICKING'),
			613 => array(613, 'SKILL_DISCIPLINE'),
			593 => array(593, 'SKILL_DESTRUCTION'),
			574 => array(574, 'SKILL_BALANCE'),
			554 => array(554, 'SKILL_RIDING_UNDEAD_HORSE'),
			553 => array(553, 'SKILL_RIDING_MECHANOSTRIDER'),
			533 => array(533, 'SKILL_RIDING_RAPTOR'),
			473 => array(473, 'SKILL_FIST_WEAPONS'),
			433 => array(433, 'SKILL_SHIELD'),
			415 => array(415, 'SKILL_CLOTH'),
			414 => array(414, 'SKILL_LEATHER'),
			413 => array(413, 'SKILL_MAIL'),
			393 => array(393, 'SKILL_SKINNING'),
			375 => array(375, 'SKILL_ELEMENTAL_COMBAT'),
			374 => array(374, 'SKILL_RESTORATION'),
			373 => array(373, 'SKILL_ENHANCEMENT'),
			356 => array(356, 'SKILL_FISHING'),
			355 => array(355, 'SKILL_AFFLICTION'),
			354 => array(354, 'SKILL_DEMONOLOGY'),
			333 => array(333, 'SKILL_ENCHANTING'),
			315 => array(315, 'SKILL_LANG_TROLL'),
			313 => array(313, 'SKILL_LANG_GNOMISH'),
			293 => array(293, 'SKILL_PLATE_MAIL'),
			270 => array(270, 'SKILL_PET_TALENTS'),
			261 => array(261, 'SKILL_BEAST_TRAINING'),
			257 => array(257, 'SKILL_PROTECTION'),
			256 => array(256, 'SKILL_FURY'),
			253 => array(253, 'SKILL_ASSASSINATION'),
			237 => array(237, 'SKILL_ARCANE'),
			229 => array(229, 'SKILL_POLEARMS'),
			228 => array(228, 'SKILL_WANDS'),
			227 => array(227, 'SKILL_SPEARS'),
			226 => array(226, 'SKILL_CROSSBOWS'),
			222 => array(222, 'SKILL_WEAPON_TALENTS'),
			202 => array(202, 'SKILL_ENGINERING'),
			197 => array(197, 'SKILL_TAILORING'),
			186 => array(186, 'SKILL_MINING'),
			185 => array(185, 'SKILL_COOKING'),
			184 => array(184, 'SKILL_RETRIBUTION'),
			182 => array(182, 'SKILL_HERBALISM'),
			176 => array(176, 'SKILL_THROWN'),
			173 => array(173, 'SKILL_DAGGERS'),
			172 => array(172, 'SKILL_2H_AXES'),
			171 => array(171, 'SKILL_ALCHEMY'),
			165 => array(165, 'SKILL_LEATHERWORKING'),
			164 => array(164, 'SKILL_BLACKSMITHING'),
			163 => array(163, 'SKILL_MARKSMANSHIP'),
			162 => array(162, 'SKILL_UNARMED'),
			160 => array(160, 'SKILL_2H_MACES'),
			150 => array(150, 'SKILL_RIDING_TIGER'),
			152 => array(152, 'SKILL_RIDING_RAM'),
			149 => array(149, 'SKILL_RIDING_WOLF'),
			148 => array(148, 'SKILL_RIDING_HORSE'),
			141 => array(141, 'SKILL_LANG_OLD_TONGUE'),
			140 => array(140, 'SKILL_LANG_TITAN'),
			139 => array(139, 'SKILL_LANG_DEMON_TONGUE'),
			138 => array(138, 'SKILL_LANG_DRACONIC'),
			137 => array(137, 'SKILL_LANG_THALASSIAN'),
			136 => array(136, 'SKILL_STAVES'),
			134 => array(134, 'SKILL_FERAL_COMBAT'),
			129 => array(129, 'SKILL_FIRST_AID'),
			118 => array(118, 'SKILL_DUAL_WIELD'),
			115 => array(115, 'SKILL_LANG_TAURAHE'),
			113 => array(113, 'SKILL_LANG_DARNASSIAN'),
			111 => array(111, 'SKILL_LANG_DWARVEN'),
			109 => array(109, 'SKILL_LANG_ORCISH'),
			98 => array(98, 'SKILL_LANG_COMMON'),
			95 => array(95, 'SKILL_DEFENSE'),
			78 => array(78, 'SKILL_SHADOW'),
			55 => array(55, 'SKILL_2H_SWORDS'),
			56 => array(56, 'SKILL_HOLY'),
			54 => array(54, 'SKILL_MACES'),
			51 => array(51, 'SKILL_SURVIVAL'),
			50 => array(50, 'SKILL_BEAST_MASTERY'),
			46 => array(46, 'SKILL_GUNS'),
			45 => array(45, 'SKILL_BOWS'),
			44 => array(44, 'SKILL_AXES'),
			43 => array(43, 'SKILL_SWORDS'),
			40 => array(40, 'SKILL_POISONS'),
			39 => array(39, 'SKILL_SUBTLETY'),
			38 => array(38, 'SKILL_COMBAT'),
			26 => array(26, 'SKILL_ARMS'),
			8 => array(8, 'SKILL_FIRE'),
			6 => array(6, 'SKILL_FROST')
		);
		// I znow... KU**A! Na liste TODO z tym...
		return $_LANGUAGE->text[$skill_id[$id][1]];
	}

	function debug() {
	   echo 'GUID -> '.$this->guid.'<br>';
	   echo 'NAME -> '.$this->name.'<br>';
	   echo 'LEVEL -> '.$this->level.'<br>';
	   echo 'RACE -> '.$this->raceToString($this->race).' ('.$this->race.')<br>';
	   echo 'CLASS -> '.$this->classToString($this->class).' ('.$this->class.')<br>';
	   echo 'GENDER -> '.$this->gender.'<br>';

	   echo 'PLAYED -> '.$this->played[0].' '.$this->played[1].' '.$this->played[2].'<br>';

	   echo 'GUILD_ID -> '.$this->guild_id.'<br>';
	   //echo 'DATA -> '.print_r($this->data).'<br>';
	   echo '<hr><br>';
	   echo 'STATS -> <br>';
	   foreach($this->stats as $key => $value) {
		  echo $key.' -> '.$value.'<br>';
	   }
	   echo '<hr><br>';
	   echo 'PRIMARY PROFS -> <br>';
	   foreach($this->prof_1 as $value) {
		 echo '     - ID: '.$value[0].', NAME: '.$value[1].' SKILL: '.$value[2].' / '.$value[3].'<br>';
	   }
	   echo '<hr><br>';
	   echo 'SECONDARY PROFS -> <br>';
	   foreach($this->prof_2 as $value) {
		 echo '     - ID: '.$value[0].', NAME: '.$value[1].' SKILL: '.$value[2].' / '.$value[3].'<br>';
	   }
	   echo '<hr><br>';
	   echo 'SKILLS -> <br>';
	   foreach($this->skills as $value) {
		 echo '     - ID: '.$value[0].', NAME: '.$value[1].' SKILL: '.$value[2].' / '.$value[3].'<br>';
	   }

	}
}

?>