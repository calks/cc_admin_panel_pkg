

		<nav class="navbar navbar-default">
			<p class="navbar-text logged-as">
				{Application::gettext('You logged as %s', $user_logged->user_name)} <a href="{$logout_link}">{Application::gettext('Logout')}</a>
			</p>
		</nav>