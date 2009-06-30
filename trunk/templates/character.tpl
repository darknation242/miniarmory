<div id="base_stats" class="hidden"><table width="100%"><tbody>{$base_stats}</tbody></table></div>
<div id="melee" class="hidden"><table width="100%"><tbody>{$melee}</tbody></table></div>
<div id="ranged" class="hidden"><table width="100%"><tbody>{$ranged}</tbody></table></div>
<div id="spell" class="hidden"><table width="100%"><tbody>{$spell}</tbody></table></div>
<div id="defense" class="hidden"><table width="100%"><tbody>{$defense}</tbody></table></div>

<table cellspacing="0" cellpadding="0" class="profile-header-table">
<tbody><tr>
<td width="400" valign="top" class="profile-header-{$alliance}">
<table class="profile-header-nametext-table">
<tbody><tr>
<td class="profile-header-portrait">
<img height="72" width="72" onmouseout="tooltip_hide()" onmouseover="tooltip('<span class=\'tooltip-whitetext\'>{$race} {$class}</span>')" class="profile-header-portrait-img-{$alliance}" src="{$DOMAIN}images/portraits/wow-80/{$gender_nr}-{$race_nr}-{$class_nr}.gif" alt="">
</td>
<td valign="top" class="profile-header-title">
<span class="profile-header-title-name">{$name}</span><br>
<span class="profile-header-title-guild">{$guild}</span><br>
<span class="profile-header-title-info">Level {$level} {$race} {$class}</span><br>
</td>
</tr>
</tbody></table>
</td>
<td width="100%" valign="top" class="profile-header-{$alliance}-right">
<span onmouseout="tooltip_hide()" onmouseover="tooltip('<span class=\'profile-tooltip-header\'>Guild - {$guild_name}</span><br><span class=\'profile-tooltip-description\'>Guild Rank: {$guild_rank}</span>')">{$LGguild}: {$guild_name} </span><br><br>
{$LGrealm}: {$realm}<br></td>
</tr>
<tr><td colspan="2">
<div class="switch-buttons-c">
<div class="char-sheet" onClick="characterSwitchTo('profile');"><div class="smallframe-a"></div>
<div class="smallframe-b" id="switch_profile">{$LGprofile}</div>
<div class="smallframe-c"></div></div>
<div class="char-sheet" onClick="characterSwitchTo('reputation');">
<div class="smallframe-a"></div>
<div class="smallframe-b" id="switch_reputation">{$LGreputation}</div>
<div class="smallframe-c"></div></div>
<div class="char-sheet" onClick="characterSwitchTo('skills');">
<div class="smallframe-a"></div>
<div class="smallframe-b" id="switch_skills">{$LGskills}</div>
<div class="smallframe-c"></div></div>
<div class="char-sheet" onClick="characterSwitchTo('talents');">
<div class="smallframe-a"></div>
<div class="smallframe-b" id="switch_talents">{$LGtalents}</div>
<div class="smallframe-c"></div></div>
{$achiButton}
</div></td></tr>
</tbody></table>
<div style="max-width:880px; text-align:center;">
<div id="reputation-contener" style="display:none;">
<center>
<div class="profile-header">{$LGreputation}</div>
{$rep}
</center>
</div>
<div id="achievements-contener" style="display:none;">
<center>
<div class="profile-header">{$LGachievements}</div>
{$achievements}
</center>
</div>
<div id="skills-contener" style="display:none;">
<center>
<div class="profile-header">{$LGskills}</div>
{$skills}
</center>
</div>
<div id="talents-contener" style="display:none;">
<center>
<div class="profile-header">{$LGtalents}</div>
{$talent_tree}
</center>
</div>
<div id="profile-contener" style="display:none;">
<center><div class="profile-header">{$LGprofile}</div>
<table width="403" class="character-table">
<tr><td width="67"></td>
  <td width="52" valign="top">
    <table width="52" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="56">{$ITEM_SLOT_0}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_1}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_2}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_4}</td>
      </tr>
      <tr>
        <td height="55">{$ITEM_SLOT_3}</td>
      </tr>
      <tr>
        <td height="55">{$ITEM_SLOT_18}</td>
      </tr>
      <tr>
        <td height="56"></td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_8}</td>
      </tr>
    </table>
    </td>
  <td width="379" valign="top">
  <table style="height:446px;widht:379px;border:none;" cellpadding="0" cellspacing="0" class="character-info">
    <tr>
      <td height="217" valign="top"><table style="height:217px;width:376px; border:none;" cellpadding="0" cellspacing="0">
        <tr>
          <td width="346" style="padding-left:5px;padding-top:5px;">
          
          <table style="height:32px;width:98%;" class="profile-mini-data">
          <tr><td></td><td class="hp-bar power-bar">{$LGhealth}: {$max_health}</td><td></td></tr>
          <tr><td></td><td class="{$power_type_l}-bar power-bar">{$power_type}: {$max_power}</td><td></td></tr>
          </table>
          <table style="width:98%; height:115px; max-height:115px; " class="profs-table">
          <tr>
              <td>
              <table style="height:100%; width:100%;" class="profile-mini-data">
              <tr><td colspan="3" height="20"><h4>{$LGprimary_profs}</h4></td></tr>
              {$profs_1}
              <tr height="100%"><td colspan="3"></td></tr>
              </table>
              </td>
              <td>
              <table style="height:50%; width:100%;" class="profile-mini-data talents">
              <tr><td colspan="3" height="20"><h4>Talent Specialization</h4></td></tr>
              {$talents}
              <tr height="100%"><td colspan="3"></td></tr>
              </table>
              <table style=" margin-top:5px;width:100%; height:30px;" class="profile-mini-data more-info">
          <tr><td colspan="4"><img onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$gold}')" alt="" src="{$DOMAIN}images/icons/gold.png" class="gold-icon"></td></tr><tr>
          <td><span onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGhonor}: {$honor}')">{$LGhonor}</span></td>
          <td><span onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGhk}: {$hk}')">HK</span></td>
          <td><span onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGarenapoints}: {$arenapoints}')">Arena</span></td>
          <td width="100%"></td>
          </tr>
          </table>
              </td>
          </tr>
          </table>
          
          </td>
          <td width="31"><div style="height:200px;" class="profile-mini-data"><table width="31" border="0" cellspacing="0" cellpadding="0" style="text-align:center;color:#FFF;font-size:11px;">
            <tr>
              <td height="29" onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGarcane_res}: <b>{$arcane_res}</b>')" style="background:url({$DOMAIN}images/res-arcane.gif);">{$arcane_res}</td>
            </tr>
            <tr>
              <td height="29" onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGfire_res}: <b>{$fire_res}</b>')"style="background:url({$DOMAIN}images/res-fire.gif);">{$fire_res}</td>
            </tr>
            <tr>
              <td height="29" onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGfrost_res}: <b>{$frost_res}</b>')"style="background:url({$DOMAIN}images/res-frost.gif);">{$frost_res}</td>
            </tr>
            <tr>
              <td height="29" onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGnature_res}: <b>{$nature_res}</b>')"style="background:url({$DOMAIN}images/res-nature.gif);">{$nature_res}</td>
            </tr>
            <tr>
              <td height="29" onMouseOut="tooltip_hide()" onMouseOver="tooltip('{$LGshadow_res}: <b>{$shadow_res}</b>')"style="background:url({$DOMAIN}images/res-shadow.gif);">{$shadow_res}</td>
            </tr>
          </table></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22"><table width="100%"><tr>
      <td>
      	<select class="stats" id="stats_1" style="width:100%" onChange="document.getElementById('stats_1_target').innerHTML=document.getElementById(this.value).innerHTML;">
        <option value="base_stats">{$LGbase_stats}</option>
        <option value="melee">{$LGmelee}</option>
        <option value="ranged">{$LGranged}</option>
        <option value="spell">{$LGspell}</option>
        <option value="defense">{$LGdefense}</option>
        </select>
      </td>
      <td>
        <select class="stats" id="stats_2" style="width:100%" onChange="document.getElementById('stats_2_target').innerHTML=document.getElementById(this.value).innerHTML;">
        <option value="base_stats">{$LGbase_stats}</option>
        <option value="melee" selected>{$LGmelee}</option>
        <option value="ranged">{$LGranged}</option>
        <option value="spell">{$LGspell}</option>
        <option value="defense">{$LGdefense}</option>
        </select>
      </td></tr></table></td>
    </tr>
    <tr>
      <td height="140" valign="top"><table width="379" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="191" valign="top" align="center">
           <div id="stats_1_target" class="profile-mini-data" style="width:90%">
            </div>
            </td>
          <td width="188" valign="top" align="center">
          <div id="stats_2_target" class="profile-mini-data" style="width:90%">
           </div>
          </td>
        </tr>
      </table>
      </td>
    </tr>
    <tr>
      <td height="57" align="center"><table width="203" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="55">{$ITEM_SLOT_15}</td>
          <td width="56">{$ITEM_SLOT_16}</td>
          <td width="92">{$ITEM_SLOT_17}</td>
        </tr>
      </table></td>
    </tr>
  </table></td><td width="52" valign="top">
  <table width="52" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="56">{$ITEM_SLOT_9}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_5}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_6}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_7}</td>
      </tr>
      <tr>
        <td height="55">{$ITEM_SLOT_10}</td>
      </tr>
      <tr>
        <td height="55">{$ITEM_SLOT_11}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_12}</td>
      </tr>
      <tr>
        <td height="56">{$ITEM_SLOT_13}</td>
      </tr>
    </table>
    </td><td width="69"></td></tr>
</table></center>
</div>
</div>
<center><span style="font-size:9pt;color:#666;">{$LGlastupdate}: {$lastupdate}</span></center>
<script type="text/javascript">document.getElementById('stats_1_target').innerHTML=document.getElementById('base_stats').innerHTML;document.getElementById('stats_2_target').innerHTML=document.getElementById('melee').innerHTML;
characterSwitchTo('{$category}');GUID = {$guid};</script>
