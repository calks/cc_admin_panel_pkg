			
		
		{if $stack_messages}
			<div class="message-stack">
				{foreach item=item from=$stack_messages}
					{if $item.type=='error'}
						{assign var=alert_class value='danger'}
					{elseif $item.type=='warning'}
						{assign var=alert_class value='warning'}
					{elseif $item.type=='success'}
						{assign var=alert_class value='success'}
					{else}
						{assign var=alert_class value='info'}
					{/if}				
					<div class="alert alert-{$alert_class}">
						<button type="button" class="close">
							<span aria-hidden="true">&times;</span>
						</button>	
						<p>{$item.message}</p>
					</div>
				{/foreach}
			</div>
		{/if}
			
