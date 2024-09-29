{*
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by Satoshi Brasileiro.
*
*  @author    Satoshi Brasileiro
*  @copyright Satoshi Brasileiro 2021
*  @license   Single domain
*}

<!-- watermark listing -->

<!-- Enable/disable watermarking -->
<div class="col-lg-12 form-group margin-form">
	<label class="form-group control-label col-lg-4">
		<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Enable/disable wtermarking on  Downloaded PDF' mod='customproductdesign'}">{l s='Enable Watermark on PDF' mod='customproductdesign'}</span>
	</label>
	<div class="form-group margin-form ">
		<div class="col-lg-7">
			<span class="switch prestashop-switch fixed-width-lg">
				<input type="radio" name="CPD_WATERMARK_ACTIVE" id="CPD_WATERMARK_ACTIVE_on" value="1" {if isset ($CPD_WATERMARK_ACTIVE) AND $CPD_WATERMARK_ACTIVE == 1}checked="checked"{/if}/>
			<label class="t" for="CPD_WATERMARK_ACTIVE_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="{l s='Enabled' mod='customproductdesign'}" title="{l s='Enabled' mod='customproductdesign'}" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
				<input type="radio" name="CPD_WATERMARK_ACTIVE" id="CPD_WATERMARK_ACTIVE_off" value="0" {if isset ($CPD_WATERMARK_ACTIVE) AND $CPD_WATERMARK_ACTIVE == 0}checked="checked"{/if}/>
			<label class="t" for="CPD_WATERMARK_ACTIVE_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="{l s='Disabled' mod='customproductdesign'}" title="{l s='Disabled' mod='customproductdesign'}" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>
</div>
<div class="clearfix"></div>

<!-- watermark text -->
<div class="col-lg-12 form-group">
	<label class="control-label col-lg-4 required" for="name_{$id_lang|escape:'htmlall':'UTF-8'}">
		<span class="label-tooltip" data-toggle="tooltip" title="{l s='Invalid characters:' mod='customproductdesign'} &lt;&gt;;=#{}">
			{l s='Watermark Text' mod='customproductdesign'}
		</span>
	</label>
	<div class="col-lg-8">
		{include file="./input_text_lang.tpl"
			languages=$languages
			input_class=""
			input_value=$CPD_WATERMARK_TEXT.CPD_WATERMARK_TEXT
			input_name="CPD_WATERMARK_TEXT"
		}
	</div>
</div>
<div class="clearfix"></div>

<!-- text size -->
<div class="col-lg-12 form-group">
	<label class="control-label col-lg-4 required" for="name_{$id_lang|escape:'htmlall':'UTF-8'}">
		<span class="label-tooltip" data-toggle="tooltip" title="{l s='Invalid characters:' mod='customproductdesign'} &lt;&gt;;=#{}">
			{l s='Watermark text size' mod='customproductdesign'}
		</span>
	</label>
	<div class="col-lg-8">
		<div class="col-lg-9">
			<div class="input-group col-lg-6">
				<input type="text" value="{if isset($CPD_WATERMARK_SIZE) && CPD_WATERMARK_SIZE}{$CPD_WATERMARK_SIZE|escape:'htmlall':'UTF-8'}{else}50{/if}" name="CPD_WATERMARK_SIZE" class="input-mini" id="CPD_WATERMARK_SIZE">
				<span class="input-group-addon">px</span>
			</div>
		</div><div class="clearfix"></div>
		<p class="help-block"> {l s='Default size is 50.' mod='customproductdesign'}</p>
	</div>
</div>
<div class="clearfix"></div>

<!-- text color -->
<div class="form-group">
	<label class="col-lg-4 control-label">{l s='Watermark text color' mod='customproductdesign'}</label>
	<div class="col-lg-8">
		<div class="col-lg-5">
			<div class="input-group">
				<input type="text" class="mColorPicker" id="color_0" name="CPD_WATERMARK_TEXTCLR" data-hex="true" value="{if !empty($CPD_WATERMARK_TEXTCLR) AND $CPD_WATERMARK_TEXTCLR}{$CPD_WATERMARK_TEXTCLR|escape:'htmlall':'UTF-8'}{else}#333333{/if}" style="background:{if !empty($CPD_WATERMARK_TEXTCLR) AND $CPD_WATERMARK_TEXTCLR}{$CPD_WATERMARK_TEXTCLR|escape:'htmlall':'UTF-8'}{else}#333333{/if};"/>
				<span id="icp_color_0" class="input-group-addon mColorPickerTrigger" data-mcolorpicker="true">
					<img src="../img/admin/color.png"/>
				</span>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
