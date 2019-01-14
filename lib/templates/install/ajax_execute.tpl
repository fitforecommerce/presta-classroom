{extends file='main.tpl'}
{block name=title}
  <h1>Execute Install</h1>
{/block}
{block name=content}
  <div id="spinner">
    <img src="/public/assets/img/loader.gif">
  </div>

  <div id="progressContainer">
    <div class="progressNumber">0 %</div>
    <div class="progress">
      <div id="installprogress" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>

  <div id="error" class="alert alert-danger d-none"></div>
  <div id="status" class="alert alert-warning"></div>

  {$script}
{/block}
