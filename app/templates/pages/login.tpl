{extends file="account.tpl"}

{block name="form"}
	<form id="login" class="row" ng-submit="accountLogin(credentials)" myshop-module="{$module}">
		<label class="form-group required col-xs-12" title="The ID of your store and user account">
			<div class="form-caption">Username</div>
			<div><input class="full-width" class="full-width" type="text" ng-model="credentials.username" placeholder="Username" required/></div>
		</label>
		<label class="form-group required col-xs-12">
			<div class="form-caption">Password</div>
			<div><input class="full-width" type="password" ng-model="credentials.password" placeholder="Password" required/></div>
		</label>
		<div class="form-group col-xs-12 col-sm-6">
			<label class="form-caption">CAPTCHA Code</label>
			<captcha ng-model="credentials.captcha.id"></captcha>
		</div>
		<label class="form-group required col-xs-12 col-sm-6">
			<div class="form-caption">Enter CAPTCHA Code</div>
			<div>
				<input class="full-width" type="text" ng-model="credentials.captcha['input']" placeholder="CAPTCHA" required/>
				<input class="full-width" type="hidden" ng-model="credentials.captcha['id']"/>
			</div>
		</label>
		<div class="form-group required col-xs-12">
			<button type="submit">Login</button> &nbsp; 
			<a href="#" data-toggle="#reset_pass">Forgotten Password?</a>
		</div>
	</form>

	<form id="reset_pass" class="ajax-form modal hide" action="/api/session/reset">
		<h3 class="color">Request Account Info</h3>
		<label class="form-group">
			<div class="form-caption required">Username / Email</div>
			<input name="username_email" required />
		</label>
		<div class="br">
			<input type="hidden" name="module" value="{$module}" />
			<button type="submit">Place Request</button>
		</div>
	</form>
{/block}