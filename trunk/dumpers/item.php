<?php
set_time_limit(0);

$_DOMAIN = str_replace('dumpers/','',$_DOMAIN);
$_FPREFIX = '../';
include('../init.php');
 $r=$mysql->getRow("select entry from `item_template` LIMIT {$_GET['stat']}, 1",'world');
 echo 'ID: '.$r['entry'];
 if($r['entry']) {if($_SYSTEM->update_icon_db($r['entry'],true))
 $_GET['stat']++;}
 else echo mysql_error();
 echo '<META HTTP-EQUIV=Refresh CONTENT="0;url=item.php?stat='.$_GET['stat'].'">';
?>