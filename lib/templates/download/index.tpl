{extends file='main.tpl'}
{block name=title}
  <h1>Available Downloads</h1>
{/block}

{block name=content}
  <table class="table downloads">
    <thead>
      <tr>
        <th scope="col">Version</td>
        <th scope="col">Status</td>
      </tr>
    </thead>
    {foreach $available_downloads as $d}
      <tr class="">
        <th scope="row">{$d->version}</th>
        <td>
          {if $d->is_downloaded}
            <span class="text-success"><i class="fa fa-check"></i> Downloaded</span>
          {else}
            <button type="button" class="btn btn-default btn-light" data-toggle="dropdown">
              <a href="{$d->url}">
                <i class="fa fa-download"></i>
              </a>
              &nbsp;
              <a href="{$baseurl}/download/{$d->version}">
                Download
              </a>
            </button>
        {/if}
        </td>
      </tr>
    {/foreach}
  </table>
{/block}