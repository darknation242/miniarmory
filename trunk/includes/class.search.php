<?php
class search_character {
	public $per_page;
	public $order;
	
	public $guid;
	public $name;
	public $lvl_down;
	public $lvl_up;
	public $race;
	public $class;
	public $guild;
	
	public $page;
	public $Realm;
	
	private $result;
	public $count = 0;
	
	function __construct($name='',$lvl_down=1,$lvl_up=80,$guild=0,$class=0,$page=0,$gender=-1,$guid=-1) {
		if($lvl_up>80) $lvl_up=80;
		if($lvl_down>80) $lvl_down=80;
		if($lvl_up<1) $lvl_up=1;
		if($lvl_down<1) $lvl_down=1;
		
		if($guid>0) $this->guid = (int)$guid;
		
		$this->name = $name;
		$this->lvl_down = $lvl_down;
		$this->lvl_up = $lvl_up;
		$this->race = $race;
		$this->guild = $guild;
		$this->class = $class;
		$this->Realm = -1;
		$this->page=$page;
		
		$this->per_page = 200;
	}
	
	function set_sort($sort_by,$sort_asc=0) {
		switch($sort_by) {
	       case 'level': $mysql_sort.="ORDER BY `level`"; 
			 break;
		   case 'name': $mysql_sort.="ORDER BY `name`"; 
			 break;
		   case 'class': $mysql_sort.="ORDER BY `class`"; 
		     break;
		   case 'race': $mysql_sort.="ORDER BY `race`"; 
			 break;
		   case 'guild': $mysql_sort.="ORDER BY ".SQL_template(CHAR_GUILD_OFFSET); 
			 break;
		   case 'honor': $mysql_sort.="ORDER BY `honor`"; 
			 break; 
		   case 'hk': $mysql_sort.="ORDER BY `hk`"; 
			 break;
		   case 'online': $mysql_sort.="ORDER BY `online`"; 
			 break;  
		   default: $mysql_sort='';
	   }
	   if($mysql_sort!='') {
		   switch ($sort_asc) { 
			    case 1: $mysql_sort.=" DESC"; 
				 break;
				case 0: $mysql_sort.=" ASC"; 
				 break;  
				 default: $mysql_sort.='';
	   		 }
	   }
	   $this->order=$mysql_sort;
	}
	
	function start() {
		global $mysql,$_SYSTEM;
		
		$WHERE = "WHERE ";
		if($this->guild>0) $WHERE .= SQL_template(CHAR_GUILD_OFFSET).' = '.$this->guild.' AND '; 
		if($this->guid>0) $WHERE .= 'characters.guid = '.$this->guid.' AND '; 
		if($this->class!=0) $WHERE .= 'characters.class = '.$this->class.' AND ';
		if($this->name!='') $WHERE .= 'name LIKE \'%'.$this->name.'%\' AND ';
		$WHERE .= '1=1';
		$il=$this->per_page+1;
		$st=$this->per_page*$this->page;
		$LIMIT = 'LIMIT '.$st.', '.$il;
		$data = array();
		$i=0;
		foreach($_SYSTEM->Realms as $rID => $rName) {
			if($this->Realm !=-1 && $rID!=$this->Realm) continue;
			$d = $mysql->getRows("SELECT characters.name,characters.race,characters.class,characters.guid,characters.online,
				guild_rank.rname,?1 AS level,?2 AS guild,?3 AS honor,?4 AS hk,?5 as gender
                FROM `characters` left join `guild_rank` on ?6 = guild_rank.rid and ?7 = guild_rank.guildid
				{$WHERE} {$this->order} {$LIMIT}",SQL_template(CHAR_LEVEL_OFFSET),SQL_template(CHAR_GUILD_OFFSET),SQL_template(CHAR_HONOR_OFFSET),SQL_template(CHAR_HK_OFFSET),CHAR_GENDER_OFFSET,SQL_template(CHAR_GUILD_OFFSET+1),SQL_template(CHAR_GUILD_OFFSET),'char_'.$rID);
			if(!$d) continue;
			$c = $mysql->query("select count(*) from characters {$WHERE}",'char_'.$rID);
			$this->count += mysql_result($c,0);
			
			foreach($d as $char) {
				foreach($char as $key=>$value)
					$data[$i][$key] = $value;
				
				if($data[$i]['guild']==0) {
				$data[$i]['guild'] = 'None';
				$data[$i]['guildid'] = 0;
				}else {
					$g = guild($data[$i]['guild']);
					$data[$i]['guild'] = $g->name;
					$data[$i]['guildid'] = $g->id;
				}
				$data[$i]['race_string'] = character::raceToString($data[$i]['race']);
				$data[$i]['class_string'] = character::classToString($data[$i]['class']);
				if($data[$i]['honor']>2000000000) $data[$i]['honor']=0;
				$data[$i]['realm']=$rName;
				$i++;
			}
		}
		return $data;
	}
	
}




class search_guild {
	public $per_page;
	public $order, $order_asc;
	
	public $guid;
	public $name;

	public $page;
	
	private $result;
	public $count = 0;
	
	function __construct($name='',$page=0) {
		if($guid>0) $this->guid = (int)$guid;
		
		$this->name = $name;
		$this->page=$page;
		
		$this->per_page = 200;
	}
	
	function set_sort($sort_by,$sort_asc=0) {
	   $this->order_asc=$sort_asc;
	   $this->order=$sort_by;
	}
	
	function start() {
		global $config,$SQL,$mysql,$_SYSTEM;
		$WHERE = 'WHERE ';
		if($this->name!='') $WHERE .= 'guild.name LIKE \'%'.$this->name.'%\' AND ';
		$WHERE .= '1=1';
		$il=$this->per_page+1;
		$st=$this->per_page*$this->page;
		$LIMIT = 'LIMIT '.$st.', '.$il;
		$data = array();
		$i=0;
		foreach($_SYSTEM->Realms as $rID => $rName) {
			$d = $mysql->getRows("SELECT guild.guildid as id,guild.name,guild.leaderguid,characters.race,characters.name as leader,characters.guid as leader_guid
                FROM `guild` inner join `characters` on guild.leaderguid = characters.guid
				{$WHERE}
                {$LIMIT}",'char_'.$rID);
			if(!$d) continue;
			$this->count += mysql_result($mysql->query("SELECT count(*) FROM `guild` {$WHERE}",'char_'.$rID),0) or $this->count = 0;
			foreach($d as $r) {
				foreach($r as $key => $value) 
			     	$data[$i][$key] = $value;
				$ids .= $data[$i]['id'].',';
				$data[$i]['members'] = 0;
				$pos[$data[$i]['id']] = $i;
				$data[$i]['realm']=$rName;
				$data[$i]['faction'] = character::getAlliance($data[$i]['race']);
				$i++;
			}
			if($r = $mysql->getRows("select * from `guild_member` where guildid in (?1-1)",
							$ids,'char_'.$rID))
			foreach($r as $row) {
				$data[$pos[$row['guildid']]]['members']++;
			}
			
		}
		return $data;
	}
	
}

?>