<?php
class guild {
	public $id;
	public $leader_guid;
	public $leader,$leader_race,$leader_class,$leader_gender;
	public $faction;
	public $members;
	public $name;
	public $realm,$realmID;
	
	function __construct($id) {
		global $config,$mysql,$_SYSTEM;
		$this->id = -1;
		$r = $mysql->getRow("SELECT guild.guildid as id,guild.name,guild.leaderguid,characters.race,characters.class,characters.name as leader,characters.guid as leader_guid,mid(lpad( hex( CAST(substring_index(substring_index(characters.data,' ',23),' ',-1) as unsigned) ),8,'0'),4,1) as gender FROM `guild` inner join `characters` on guild.leaderguid = characters.guid where guild.guildid = ?1",$id,'char');
		if($r['id']) $this->id = $r['id'];
		else return;
		
		$this->name = $r['name'];
		$this->leader_guid = $r['leader_guid'];
		$this->leader = $r['leader'];
		$this->faction = character::getAlliance($r['race']);
		$this->leader_race = $r['race'];
		$this->leader_class = $r['class'];
		$this->leader_gender = $r['gender'];
		$this->realm = $_SYSTEM->Realms[$_SYSTEM->Realm];
		$this->realmID = $_SYSTEM->Realm;
		$this->members = mysql_result($mysql->query("SELECT count(*) FROM `guild` WHERE guildid = ?1",$this->id,'char'),0) or $this->members = 0;
		
	}
}

?>