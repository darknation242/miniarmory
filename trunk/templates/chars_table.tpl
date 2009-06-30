
<form id="search_character" onSubmit="search_character_start();return false;" action=""><table width="100%" border="0">
<tbody><tr>
<td><span class="csearch-results-header">{$LGcharacter} {$LGname}:</span></td>
</tr>
<tr>
<td class="csearch-input"><input type="text" value="" size="49" name="name"/></td>
<td><input type="image" onclick="search_character_start();" src="{$DOMAIN}css/images/spacer.gif" value="" class="csearch-button"/></td>
</tr>
<tr><td style="text-align:right; font-size:11px;">
<a href="javascript:addProvider('{$DOMAIN}?searchplugin=true',false)">Search Plugin for your browser.</a>
</td></tr>
</tbody></table>
</form>

<table class="contener">
<tr><td style="text-align:right">
</td></tr>
<tr><td>
	<div class="paging-top" id="res-top"></div>
    <table border="0" class="csearch-results-table" id="res_table">
      <tbody id="char_table">
      <tr class="csearch-results-table-header">
      <td width="25%" id="name" class="csearch-results-table-header" onclick="set_sort('name');">{$LGname}</td>
    <td width="5%" id="level" class="csearch-results-table-header" onclick="set_sort('level');">{$LGlevel}</td>
    <td width="7%" id="race" class="csearch-results-table-header rightalign" onclick="set_sort('race');">{$LGrace}</td>
    <td width="7%" id="class" class="csearch-results-table-header leftalign" onclick="set_sort('class');">{$LGclass}</td>
    <td width="25%" id="guild" class="csearch-results-table-header" onclick="set_sort('guild');">{$LGguild}</td>
    <td width="10%" id="honor" class="csearch-results-table-header leftalign" onclick="set_sort('honor');">{$LGhonor}</td>
    <td width="30%" id="realm" class="csearch-results-table-header" onclick="set_sort('realm');">{$LGrealm}</td>
</tr>
</tbody>
</table>
    
</td></tr>
<tr><td style="text-align:right">
   
</td></tr>
</table>
<script type="text/javascript">preproces();document.getElementById('search_character').name.value='{$search_name}';search_character_start();</script>