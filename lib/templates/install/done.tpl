{extends file='main.tpl'}
{block name=title}
  <h1>Install done</h1>
{/block}
{block name=content}
  <div id="progressContainer">
    <div class="progressNumber">Done</div>
    <div class="progress">
      <div id="installprogress" class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>
  <div id="error" class="alert alert-danger d-none"></div>
  {$script}
{/block}
