<!DOCTYPE html>
<html lang="ru">
    {$html_head}
	<body>	
		<div class="loader" style="z-index: -1; opacity: 0;"></div>
		<div class="container">
			<div id="page-content">
				{$header->render()}
			
				
				{if $message_stack}
					{$message_stack->render()}
				{/if}	
				
				{$content}
			
				{$footer->render()}
			</div>
		</div>
	</body>
</html>
