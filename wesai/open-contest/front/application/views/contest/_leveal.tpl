 <div class="icon-list">
      {foreach from=$CONTEST_LEVEL_LIST key=Lkey item=Litem}
         {if ($contestData->level & $Lkey) == true}
          <p><span class="icon-span icon{$Lkey}">{$Litem[1]}</span>{$Litem[0]}</p>
         {/if}
      {/foreach}
    </div>