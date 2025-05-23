{if $products !== false}
<div id="blockcustom_container">
	<div id="blockcustom_title">
		<h1>{l s='OUR BOOKS' mod='blockproducts'}</h1>
	</div>
	{foreach from=$products item=product name=productLoop}
		<div class="blockcustom_book product col-12 col-md-6">
			<div class="row align-items-start">
    			<div class="blockcustom_picture col-12 col-md-4">
					<img src="{$product.image}" alt="{$product.name|strip_tags|escape:html:'UTF-8'}" />
				</div>
				<div class="blockcustom_products_info col-12 col-md-8">
					<div class="home_products_author">
						{$product.author|upper|strip_tags|escape:html:'UTF-8'}
					</div>
					<div class="blockcustom_products_title">
						{$product.name|strip_tags|escape:html:'UTF-8'}
					</div>
					<div class="blockcustom_products_description">
						{$product.desc nofilter}
					</div>
					<div class="blockcustom_products_price">
						${$product.price|string_format:"%.2f"}
					</div>
					<div class="blockcustom_products_openButton">
						<a class="btn btn-primary hidden-xs-down" href="{$product.link}" class="btn btn-inverse">
							{l s='View' mod='blockproducts'}
						</a>
					</div>
				</div>
			</div>
		</div>
	{/foreach}
</div>
{/if}