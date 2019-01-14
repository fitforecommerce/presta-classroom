{if isset($status) }
  <div class="alert {$status.cssclass}" role="alert">
  	{foreach $status.message as $s}
  		<p>{$s}</p>
  	{/foreach}
  </div>
{/if}