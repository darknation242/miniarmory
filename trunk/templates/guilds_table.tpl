<form id="search_guild" onSubmit="search_guild_start();return false;" action=""><table width="100%" border="0">
<tbody><tr>
<td><span class="csearch-results-header">{$LGguild} {$LGname}:</span></td>
</tr>
<tr>
<td class="csearch-input"><input type="text" value="" size="49" name="name"/></td>
<td><input type="image" onclick="search_guild_start();" src="{$DOMAIN}css/images/spacer.gif" value="" class="csearch-button"/></td>
</tr>
</tbody></table>
</form>
<table class="contener">
<tr><td style="text-align:right">
</td></tr>
<tr><td>
<div class="paging-top" id="res-top"></div>
    <table border="0" class="csearch-results-table" id="res_table">
      <tbody id="guild_table">
      <tr>
      <td width="25%" class="csearch-results-table-header" onclick="set_sort('name');">{$LGname}</td>
<td width="10%" class="csearch-results-table-header centeralign" onclick="set_sort('faction');" >{$LGfaction}</td>
<td width="20%" class="csearch-results-table-header" onclick="set_sort('leader');">{$LGleader}</td>
<td width="10%" class="csearch-results-table-header centeralign" onclick="set_sort('members');">{$LGmembers}</td>
<td width="20%" class="csearch-results-table-header" onclick="set_sort('realm');">{$LGrealm}</td>
</tr>
</tbody>
</table>
<script type="text/javascript">preproces();search_guild_start();</script>
    
</td></tr>
<tr><td style="text-align:right">
   
</td></tr>
</table>