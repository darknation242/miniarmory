<?php

$tp = new template();
$tp->add('guild');
$guild = guild($_GET['guild']);
if($guild->id==-1) $_SYSTEM->error('Guild not found!');

$tp->assign('name',$guild->name);
$tp->assign('gm_id',$guild->leader_id);
$tp->assign('gm',$guild->leader);
$tp->assign('faction',$guild->faction ? $_LANGUAGE->text['horde'] : $_LANGUAGE->text['alliance']);
$tp->assign('alliance',$guild->faction);
$tp->assign('gender_nr',$guild->leader_gender);
$tp->assign('members',$guild->members);
$tp->assign('race_nr',$guild->leader_race);
$tp->assign('class_nr',$guild->leader_class);
$tp->assign('realm',$guild->realm);
$tp->assign('realmid',$guild->realmID);
$tp->assign('race',$_LANGUAGE->text[character::raceToString($guild->leader_race)]);
$tp->assign('class',$_LANGUAGE->text[character::classToString($guild->leader_class)]);

$_LANGUAGE->translate($tp);
$tp->display();



?>