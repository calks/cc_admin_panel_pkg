

	<div class="nav navbar-nav">
		<ul class="nav navbar-nav">
		
			{foreach item=menu_item_link key=menu_item_caption from=$menu}
				{if $menu_item_link|@is_array}
					<li class="dropdown">
	          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{$menu_item_caption} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							{foreach item=submenu_item_link key=submenu_item_caption from=$menu_item_link}				        	
					           	<li><a href="{$submenu_item_link}">{$submenu_item_caption}</a></li>
							{/foreach}
						</ul>	
				    </li>
				{else}
					<li><a href="{$menu_item_link}">{$menu_item_caption}</a></li>
				{/if}
			{/foreach}
			
		</ul>
	</div>

