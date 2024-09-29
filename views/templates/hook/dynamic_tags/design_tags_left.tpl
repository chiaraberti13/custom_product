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
<!-- tags -->
{if $type == 'txt'}
<li style="display: none"
class="cpd_layer cpd_layer_1000{$count|escape:'htmlall':'UTF-8'}"
id="cpd_layer_1000{$count|escape:'htmlall':'UTF-8'}"
data-id-design="{$_id_design|escape:'htmlall':'UTF-8'}"
data-id-tag="1000{$count|escape:'htmlall':'UTF-8'}"
data-type="text">
	{assign var='tag_price' value=($tagprice * $exchangeRate)}
<input class="tag_price" id="design-tag-1000{$count|escape:'htmlall':'UTF-8'}" data-id-tag="1000{$count|escape:'htmlall':'UTF-8'}" type="hidden" value="{if isset($tag_price) AND $tag_price}{$tag_price|escape:'htmlall':'UTF-8'}{/if}" name="tag_price[1000{$count|escape:'htmlall':'UTF-8'}]">
	<textarea
	id="left-tag-1000{$count|escape:'htmlall':'UTF-8'}"
	placeholder="{l s='write text here....' mod='customproductdesign'}"
	class="cpd_tag form-control"
	name="tag_1000{$count|escape:'htmlall':'UTF-8'}"
	data-id="1000{$count|escape:'htmlall':'UTF-8'}"
	data-design="{$_id_design|escape:'htmlall':'UTF-8'}"
	data-type="text"></textarea></li>
{elseif $type == 'img'}
<li style="display: none"
class="cpd_layer cpd_layer_1000{$count|escape:'htmlall':'UTF-8'}"
id="cpd_layer_1000{$count|escape:'htmlall':'UTF-8'}"
data-id-design="{$_id_design|escape:'htmlall':'UTF-8'}"
data-id-tag="1000{$count|escape:'htmlall':'UTF-8'}"
data-type="image">
	{assign var='tag_price' value=($tagprice * $exchangeRate)}
<input class="tag_price" id="design-tag-1000{$count|escape:'htmlall':'UTF-8'}" data-id-tag="1000{$count|escape:'htmlall':'UTF-8'}" type="hidden" value="{if isset($tag_price) AND $tag_price}{$tag_price|escape:'htmlall':'UTF-8'}{/if}" name="tag_price[1000{$count|escape:'htmlall':'UTF-8'}]">

</li><!--	<div class="image_tag">
		<a href="javascript:void(0);" title="{l s='After selecting the tag choose an image from Images Tab.' mod='customproductdesign'}">
			<img
			id="left-tag-1000{$count|escape:'htmlall':'UTF-8'}"
			class="cpd_tag imgm img-thumbnail"
			src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/empty_img.svg"
			alt="{l s='image tag' mod='customproductdesign'}"
			data-id="1000{$count|escape:'htmlall':'UTF-8'}"
			data-design="{$_id_design|escape:'htmlall':'UTF-8'}"
			data-type="image"
			width="48"
			height="48">
		</a>
	</div>-->
{/if}