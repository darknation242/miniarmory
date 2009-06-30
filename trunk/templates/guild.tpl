<table cellspacing="0" cellpadding="0" class="profile-header-table">
<tbody><tr>
<td width="50%" valign="top" class="profile-header-{$alliance}">
<table class="profile-header-nametext-table">
<tbody><tr>
<td class="profile-header-portrait">
<img height="72" width="72" onmouseout="tooltip_hide()" onmouseover="tooltip('<span class=\'tooltip-whitetext\'>{$gm}: {$race} {$class}</span>')" class="profile-header-portrait-img-{$alliance}" src="{$DOMAIN}images/portraits/wow-80/{$gender_nr}-{$race_nr}-{$class_nr}.gif" alt="">
</td>
<td valign="top" class="profile-header-title">
<span class="profile-header-title-name">{$name}</span><br>
<span class="profile-header-title-guild"><a href="{$DOMAIN}character/{$gm_id}">{$LGguild_master}: {$gm}</a></span><br>
<span class="profile-header-title-info">{$realm}, {$faction}</span>
</td>
</tr>
</tbody></table>
</td>
<td width="50%" valign="top" class="profile-header-{$alliance}-right">
<span>{$LGmembers}: {$members} </span><br><br>
{$LGrealm}: {$realm}<br></td>
</tr>
</tbody></table>
<table border="0" class="csearch-results-table">
      <tbody id="char_table">
      <td width="25%" class="csearch-results-table-header"><a onclick="set_sort('name');" href="#">{$LGname}</a> </td>
<td width="5%" class="csearch-results-table-header"><a onclick="set_sort('level');" href="#">{$LGlevel}</a> </td>
<td width="7%" class="csearch-results-table-header rightalign"><a onclick="set_sort('race');" href="#">{$LGrace}</a></td>
<td width="7%" class="csearch-results-table-header leftalign"><a onclick="set_sort('class');" href="#">{$LGclass}</a></td>
<td width="25%" class="csearch-results-table-header"><a onclick="set_sort('guild');" href="#">{$LGrank}</a> </td>
<td width="10%" class="csearch-results-table-header leftalign"><a onclick="set_sort('honor');" href="#">{$LGhonor}</a> </td>
<td width="30%" class="csearch-results-table-header"><a onclick="set_sort('realm');" href="#">{$LGhk}</a> </td>
</tr>
</tbody>
</table>
<script type="text/javascript">guildid = 1;RealmID = {$realmid};search_character_start(); </script>