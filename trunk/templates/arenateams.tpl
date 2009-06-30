<h2>{$type} Arena Team Ladder.</h2>
<h4>Realm: {$realm}</h4>
<span class="page-subheader">(Arena Team type: <a href="{$DOMAIN}index.php?act=arenateams&amp;ArenaType=2v2&amp;Realm={$realm}">2 vs. 2</a>, <a href="{$DOMAIN}index.php?act=arenateams&amp;ArenaType=3v3&amp;Realm={$realm}">3 vs. 3</a>, <a href="{$DOMAIN}index.php?act=arenateams&amp;ArenaType=5v5&amp;Realm={$realm}">5 vs. 5</a>)</span>
{$realms}
<table class="contener">
<tr><td style="text-align:right">
</td></tr>
<tr><td>

    <table border="0" class="csearch-results-table">
      <tbody id="table">
      <tr>
      <td width="5%" class="csearch-results-table-header no-sort">{$LGrank}</td>
      <td width="25%" class="csearch-results-table-header no-sort">{$LGteamname} </td>
    <td width="10%" class="csearch-results-table-header no-sort">{$LGrating} </td>
    <td width="10%" class="csearch-results-table-header no-sort">{$LGwonweek}</td>
    <td width="10%" class="csearch-results-table-header no-sort">{$LGlostweek}</td>
    <td width="10%" class="csearch-results-table-header no-sort">{$LGwonseason} </td>
    <td width="10%" class="csearch-results-table-header no-sort">{$LGlostseason}</td>
    </tr>
    {$ranking}
    <tr><td class="csearch-results-table-header" colspan="9"></td></tr>
    </tbody>
    </table>
    
</td></tr>
<tr><td style="text-align:right">
   
</td></tr>
</table>
