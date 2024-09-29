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
<div class="inputstyle radioinput">
	<div class="inputstyle radioinput" id="select-color">
		<div class="fontboxcolor" style="display: block;">
            <div id="icp_color_0" class="boxcolor set_colour mColorPickerTrigger"
            data-mcolorpicker="true"
            data-color="custom"
            data-colorcode>
                <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/color-swatch.png">
                <span class="normal custom_colors">
                    <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/check.png">
                </span>
            </div>
		{foreach from=$colors item=color}
			<div class="boxcolor set_colour"
            data-color="{$color.colour_name|escape:'htmlall':'UTF-8'}"
            data-colorid="{$color.id_colour|escape:'htmlall':'UTF-8'}"
            data-colorcode="{$color.colour_code|escape:'htmlall':'UTF-8'}"
            style="background: {$color.colour_code|escape:'htmlall':'UTF-8'} none repeat scroll 0px 0px;">
                <span class="normal {if isset($DEFAULT_CUSTOM_COLOR) && $DEFAULT_CUSTOM_COLOR == $color.colour_code}highlighted{/if}">
                    <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/check.png">
                </span>
            </div>
		{/foreach}
		</div>
	</div>
	<div class="clearfix"></div>
</div>