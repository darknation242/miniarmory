<?php
function character($id) {
	$cache = new cache('character',(int)$id);
	if($cache->guid) {
		$character = $cache->object;
	}else{
		$character = new character((int)$id);
	}
	return $character;
}

function guild($id) {
	$cache = new cache('guild',(int)$id);
	if($cache->guid) {
		$guild = $cache->object;
	}else{
		$guild = new guild((int)$id);
	}
	return $guild;
}
function pvpladder($id=1) {
	$cache = new cache('pvp',(int)$id);
	if($cache->guid) {
		$pvp = $cache->object;
	}else{
		$pvp = new pvpladder();
	}
	return $pvp;
}

function uint32($val) {
	$int = "2147483648";
	if($val < 0) $val = bcmul($val, '-1');
	$mx = bcdiv($val, $int);
	if(bcmod($mx, '2') == 1) $mx = bcadd($mx,'1');
	$val = bcsub($val, bcmul($mx, $int));
	if($val < 0) $val = bcmul($val, '-1');
	return $val;
}

function getSpell($spell_id, $fields="*") {
  global $mysql,$config;
  //return $mysql->getRow("SELECT ".$fields." FROM `?2`.`spell` WHERE `id` = ?1", $spell_id,$config['armoryDB']);
}

function arrayName($text) {
	return addslashes(str_replace(' ','',$text));	
}

function getSpellDurationData($durationIndex) {
  global $mysql,$config;
  return $mysql->getRow("SELECT * FROM `?2`.`spellduration` WHERE `id` = ?1", $durationIndex,$config['armoryDB']);
}
function getRadius($durationIndex) {
  return 0;
}

function getTimeText($seconds) {
  $text = "";
  if ($seconds >=24*3600) {$text.= intval($seconds/(24*3600))." days"; if ($seconds%=24*3600) $text.=" ";}
  if ($seconds >=   3600) {$text.= intval($seconds/3600)." hours"; if ($seconds%=3600) $text.=" ";}
  if ($seconds >=     60) {$text.= intval($seconds/60)." min"; if ($seconds%=60) $text.=" ";}
  if ($seconds >       0) {$text.= $seconds." sec";}
  return $text;
}

function getSpellData($spell)
{
  // Basepoints
  $s1 = abs($spell['EffectBasePoints_1']+$spell['EffectBaseDice_1']);
  $s2 = abs($spell['EffectBasePoints_2']+$spell['EffectBaseDice_2']);
  $s3 = abs($spell['EffectBasePoints_3']+$spell['EffectBaseDice_3']);
  if ($spell['EffectDieSides_1']>$spell['EffectBaseDice_1']) $s1.=" - ".abs($spell['EffectBasePoints_1']+$spell['EffectDieSides_1']);
  if ($spell['EffectDieSides_2']>$spell['EffectBaseDice_2']) $s2.=" - ".abs($spell['EffectBasePoints_2']+$spell['EffectDieSides_2']);
  if ($spell['EffectDieSides_3']>$spell['EffectBaseDice_3']) $s3.=" - ".abs($spell['EffectBasePoints_3']+$spell['EffectDieSides_3']);

  $d  = 0;
  if ($spell['DurationIndex'])
   if ($spell_duration = getSpellDurationData($spell['DurationIndex']))
     $d = $spell_duration['duration_1']/1000;

  // Tick duration
  $t1 = $spell['EffectAmplitude_1'] ? $spell['EffectAmplitude_1']/1000 : 5;
  $t2 = $spell['EffectAmplitude_1'] ? $spell['EffectAmplitude_2']/1000 : 5;
  $t3 = $spell['EffectAmplitude_1'] ? $spell['EffectAmplitude_3']/1000 : 5;

  // Points per tick
  $o1 = @intval($s1*$d/$t1);
  $o2 = @intval($s2*$d/$t2);
  $o3 = @intval($s3*$d/$t3);

  $spellData['t1']=$t1;
  $spellData['t2']=$t2;
  $spellData['t3']=$t3;
  $spellData['o1']=$o1;
  $spellData['o2']=$o2;
  $spellData['o3']=$o3;
  $spellData['s1']=$s1;
  $spellData['s2']=$s2;
  $spellData['s3']=$s3;
  $spellData['m1']=$s1;
  $spellData['m2']=$s2;
  $spellData['m3']=$s3;
  $spellData['x1']= $spell['EffectChainTarget_1'];
  $spellData['x2']= $spell['EffectChainTarget_2'];
  $spellData['x3']= $spell['EffectChainTarget_3'];
  $spellData['i'] = $spell['MaxAffectedTargets'];
  $spellData['d'] = getTimeText($d);
  $spellData['d1']= getTimeText($d);
  $spellData['d2']= getTimeText($d);
  $spellData['d3']= getTimeText($d);
  $spellData['v'] = $spell['AffectedTargetLevel'];
  $spellData['u'] = $spell['StackAmount'];
  $spellData['a1']= getRadius($spell['EffectRadiusIndex_1']);
  $spellData['a2']= getRadius($spell['EffectRadiusIndex_2']);
  $spellData['a3']= getRadius($spell['EffectRadiusIndex_3']);
  $spellData['b1']= $spell['EffectPointsPerComboPoint_1'];
  $spellData['b2']= $spell['EffectPointsPerComboPoint_2'];
  $spellData['b3']= $spell['EffectPointsPerComboPoint_3'];
  $spellData['e'] = $spell['EffectMultipleValue_1'];
  $spellData['e1']= $spell['EffectMultipleValue_1'];
  $spellData['e2']= $spell['EffectMultipleValue_2'];
  $spellData['e3']= $spell['EffectMultipleValue_3'];
  $spellData['f1']= $spell['DmgMultiplier_1'];
  $spellData['f2']= $spell['DmgMultiplier_2'];
  $spellData['f3']= $spell['DmgMultiplier_3'];
  $spellData['q1']= $spell['EffectMiscValue_1'];
  $spellData['q2']= $spell['EffectMiscValue_2'];
  $spellData['q3']= $spell['EffectMiscValue_3'];
  $spellData['h'] = $spell['procChance'];
  $spellData['n'] = $spell['procCharges'];
  $spellData['z'] = "<home>";
  return $spellData;
}



function spellReplace($spell) {
	$text = $spell['Description'];
    $letter = array('${','}');
    $values = array( '[',']');
    $text = str_replace($letter, $values, $text);

	$signs = array('+', '-', '/', '*', '%', '^');
    $data = $text;
	$pos = 0;
    $npos = 0;
	$str = '';
    $lastCount = 1;
	while (false!==($npos=strpos($data, '$', $pos)))
	{
		if ($npos!=$pos)
			$str .= substr($data, $pos, $npos-$pos);
		$pos = $npos+1;
		if ('$' == substr($data, $pos, 1))
		{
			$str .= '$';
			$pos++;
			continue;
		}

		if (!preg_match('/^((([+\-\/*])(\d+);)?(\d*)(?:([lg].*?:.*?);|(\w\d*)))/', substr($data, $pos), $result))
			continue;
		$pos += strlen($result[0]);
		$op = $result[3];
		$oparg = $result[4];
		$lookup = $result[5]? $result[5]:$spell['id'];
		$var = $result[6] ? $result[6]:$result[7];
		if (!$var)
			continue;
        if ($var[0]=='l')
        {
            $select = explode(':', substr($var, 1));
            $str.=@$select[$lastCount==1 ? 0:1];
        }
        else if ($var[0]=='g')
        {
            $select = explode(':', substr($var, 1));
            $str.=$select[0];
        }
        else
        {
            $spellData = @$cacheSpellData[$lookup];
            if ($spellData == 0) {
                if ($lookup == $spell['id']) $cacheSpellData[$lookup] = getSpellData($spell);
                else                         $cacheSpellData[$lookup] = getSpellData(getSpell($lookup));
                $spellData = @$cacheSpellData[$lookup];
            }
            if ($spellData && $base = @$spellData[strtolower($var)]) {
                if ($op && is_numeric($oparg) && is_numeric($base))
                {
                     $equation = $base.$op.$oparg;
                     eval("\$base = $equation;");
		        }
                if (is_numeric($base)) $lastCount = $base;
            }
            else
                $base = $var;
            $str.=$base;
        }
	}
	$str.= substr($data, $pos);
	$str = @preg_replace_callback("/\[.+[+\-\/*\d]\]/", 'my_relpace', $str);
	return($str);
}
function my_relpace($matches) {
    $text = str_replace( array('[',']'), array('', ''), $matches[0]);
    eval("\$text = abs(".$text.");");
    return intval($text);
}

?>