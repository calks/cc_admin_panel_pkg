


	<div class="col-sm-4"  id="login-form">

		<form class="panel panel-primary" action="{$form_action}" method="post">
			<div class="panel-heading">
				{$module->gettext('Management panel login')}
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="email" class="control-label">{$module->gettext('Email')}</label>
					<input type="email" class="form-control" name="login_form[login]" value="{$login_form.login}">
				</div>
				<div class="form-group">	
					<label for="password" class="control-label">{$module->gettext('Password')}</label>
					<input type="password" class="form-control" name="login_form[pass]" value="{$login_form.pass}">
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" name="enter" value="{$module->gettext('Enter')}">					
				</div>
			</div>		
		</form>
		
		<div class="messages">
			{$message_stack_block->render()}
		</div>	

	</div>	
	
	
