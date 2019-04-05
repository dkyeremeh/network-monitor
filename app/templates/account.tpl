{extends file="index.tpl"}

{block name="content"}
	<div class="section bg-whitesmoke" >
		<div class="container">
			<div class="col-md-9">
				{block name="form"}
					{$content}
				{/block}
			</div>
		</div>
	</div>
{/block}