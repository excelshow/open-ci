<div class="pannerl-info">
    <h3 class="m-title" onclick="location.href='{$BASE_SITE_URL}/contest/detail?cid={$contestData->pk_contest}';">{$contestData->name}</h3>
    <div class="local-time">
      <p class="local"><img src="{'img/s.png'|cdnurl}" class="sprite">
      <span>{$contestData->location}</span></p>
      <p class="time"><img src="{'img/s.png'|cdnurl}" class="sprite">
      <span>{$contestData->sdate|date_format:"%Y年%m月%d日"}</span></p>
    </div>
</div>
