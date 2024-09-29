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
<div id="select-font" class="inputstyle radioinput">
	<select class="font_select" name="text_fonts" {if $DEFAULT_CUSTOM_FONT}style="font-family:{$DEFAULT_CUSTOM_FONT|escape:'htmlall':'UTF-8'}{/if}">
		<option value="0">{l s='Default' mod='customproductdesign'}</option>
		{foreach from=$fonts item=font}
			<option id="{$font.id_font|escape:'htmlall':'UTF-8'}" value="{$font.font_name|escape:'htmlall':'UTF-8'}" onchange="set_fonts('{$font.font_name|escape:'htmlall':'UTF-8'}')" style="font-family:{$font.font_name|escape:'htmlall':'UTF-8'};font-size:14px;" {if $font.font_name == $DEFAULT_CUSTOM_FONT}selected="selected"{/if}>{$font.font_name|escape:'htmlall':'UTF-8'}</option>
		{/foreach}
	</select>
</div>