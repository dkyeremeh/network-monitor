{extends file="account.tpl"}

{block name="form"}
{if $content}
	<div class="small-content">{$content}</div>
	{else}
	<form class="ajax-form" action="">
		<label class="form-group">
			<div class="form-caption  required">Email</div>
			<input type="email" name="email" ng-model="email" required>
		</label>
		<label class="form-group required">
			<div class="form-caption">New Password</div>
			<input type="password" name="password" ng-model="newpass" required>
		</label>
		<label class="form-group">
			<div class="form-caption  required">Repeat Password</div>
			<input type="password" name="repassword" ng-model="repass" required>
			<div ng-show="newpass != repass"></div>
		</label>
		<div class="form-group">
			<button type="submit">Set Password</button>
		</div>
	</form>
{/if}

{/block}