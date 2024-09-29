{*
* Custom Product Design
*
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by Satoshi Brasileiro.
*
*  @author    Satoshi Brasileiro
*  @copyright 2021 Satoshi Brasileiro All right reserved
*  @license   Single domain
*}
{if isset($tags) AND $tags && count($tags) > 0}
	{foreach from=$tags item=tag name=tag}
		{assign var='tag_price' value=($tag.price * $exchangeRate)}
		<input class="tag_price" id="design-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" type="hidden" value="{if isset($tag.price) AND $tag.price}{$tag_price|escape:'htmlall':'UTF-8'}{/if}" name="tag_price[{$tag.id_design_tag|escape:'htmlall':'UTF-8'}]">
		<!-- tags -->
	<div class="clearfix{if $tag.type == 'text'} cpd_element_type_tag_txt{else} cpd_element_type_tag_img{/if}">
		<div class="cpd_tags_bundle">
			<span class="tag_title">	
				{if isset($tag.tag_title) AND $tag.tag_title}
					{$tag.tag_title|escape:'htmlall':'UTF-8'}
				{else}
					{l s='Tag' mod='customproductdesign'}&nbsp;{$smarty.foreach.tag.iteration|escape:'htmlall':'UTF-8'}
				{/if}
			</span>
			<span class="tag_price">
			{*l s='Price' mod='customproductdesign'*}
			{if isset($tag.price) AND $tag.price}{Tools::displayPrice($tag_price)|escape:'htmlall':'UTF-8'}{/if}
			</span>
		</div>
		{if $tag.type == 'text'}
			<textarea
			id="left-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
			class="cpd_tag form-control"
			name="tag_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
			data-id="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
			data-design="{$tag.id_design|escape:'htmlall':'UTF-8'}"
			data-type="{$tag.type|escape:'htmlall':'UTF-8'}"
			{if isset($tag.length) && $tag.length}maxlength={$tag.length|escape:'htmlall':'UTF-8'}{/if}></textarea>
		{elseif $tag.type == 'image'}
			<div class="image_tag">
				<a href="javascript:void(0);" title="{l s='After selecting the tag choose an image from Images Tab.' mod='customproductdesign'}">
					<img
					id="left-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
					class="cpd_tag imgm img-thumbnail"
					src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/empty_img.svg"
					alt="{l s='image tag' mod='customproductdesign'}"
					data-id="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
					data-design="{$tag.id_design|escape:'htmlall':'UTF-8'}"
					data-type="{$tag.type|escape:'htmlall':'UTF-8'}"
					width="48"
					height="48">
				</a>
			</div>
		{/if}
	</div>
	{/foreach}
{/if}