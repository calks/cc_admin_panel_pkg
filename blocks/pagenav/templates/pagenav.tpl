


{strip}
					<div class="pagenav navbar">
						<span class="pages navbar-text navbar-left">Page {$current_page} of {$total_pages}</span>
						{foreach item=page from=$page_links}
							{if $page->type!='prev' && $page->type!='next'}
								{if $page->disabled}
									<span class="btn btn-primary disabled">
										{$page->caption}
									</span>
								{else}									
									<a class="btn btn-default" href="{$page->link}">
										{$page->caption}
									</a>
								{/if}	
							{/if}
						{/foreach}
					</div>
{/strip}

