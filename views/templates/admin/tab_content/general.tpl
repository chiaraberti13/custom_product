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

<!-- general listing -->
<div class="general_settings">
	<form action="{$action_link|escape:'htmlall':'UTF-8'}&amp;saveConfiguration" name="customproductdesign_form" method="post" enctype="multipart/form-data" class="form-horizontal">

		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='The specified image width will be set for design preview in cart' mod='customproductdesign'}">{l s='Preview Image Width' mod='customproductdesign'}</span>
			</label>
			<div class="col-lg-6">
				<div class="input-group">
					<input type="text" name="DESIGN_PREVIEW_WIDTH" id="DESIGN_PREVIEW_WIDTH" value="{if isset ($DESIGN_PREVIEW_WIDTH) AND $DESIGN_PREVIEW_WIDTH}{$DESIGN_PREVIEW_WIDTH|escape:'htmlall':'UTF-8'}{/if}"/>
					<span class="input-group-addon">px</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='The specified image height will be set for design preview in cart' mod='customproductdesign'}">{l s='Preview Image Height' mod='customproductdesign'}</span>
			</label>
			<div class="col-lg-6">
				<div class="input-group">
					<input type="text" name="DESIGN_PREVIEW_HEIGHT" id="DESIGN_PREVIEW_HEIGHT" value="{if isset ($DESIGN_PREVIEW_HEIGHT) AND $DESIGN_PREVIEW_HEIGHT}{$DESIGN_PREVIEW_HEIGHT|escape:'htmlall':'UTF-8'}{/if}"/>
					<span class="input-group-addon">px</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<!-- Enable/disable image upload from front -->
		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Enable/disable logo upload from file' mod='customproductdesign'}">{l s='Enable Image Upload from File' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-7">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="LOGO_UPLOAD_EN_DS" id="LOGO_UPLOAD_EN_DS_on" value="1" {if isset ($LOGO_UPLOAD_EN_DS) AND $LOGO_UPLOAD_EN_DS == 1}checked="checked"{/if}/>
					<label class="t" for="LOGO_UPLOAD_EN_DS_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
						<input type="radio" name="LOGO_UPLOAD_EN_DS" id="LOGO_UPLOAD_EN_DS_off" value="0" {if isset ($LOGO_UPLOAD_EN_DS) AND $LOGO_UPLOAD_EN_DS == 0}checked="checked"{/if}/>
					<label class="t" for="LOGO_UPLOAD_EN_DS_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<!-- Enable/disable image upload from front -->
		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Enable/disable logo upload from url' mod='customproductdesign'}">{l s='Enable Image Upload from URL' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-7">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="LOGO_UPLOAD_URL" id="LOGO_UPLOAD_URL_on" value="1" {if isset ($LOGO_UPLOAD_URL) AND $LOGO_UPLOAD_URL == 1}checked="checked"{/if}/>
					<label class="t" for="LOGO_UPLOAD_URL_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
						<input type="radio" name="LOGO_UPLOAD_URL" id="LOGO_UPLOAD_URL_off" value="0" {if isset ($LOGO_UPLOAD_URL) AND $LOGO_UPLOAD_URL == 0}checked="checked"{/if}/>
					<label class="t" for="LOGO_UPLOAD_URL_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip">{l s='Default Color' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-4">
					<select name="DEFAULT_CUSTOM_COLOR" {if $DEFAULT_CUSTOM_COLOR}style="background:{$DEFAULT_CUSTOM_COLOR|escape:'htmlall':'UTF-8'}"{/if} onchange="$(this).css('background-color', $(this).val().toString());">
						<option value="#ffffff" {if $DEFAULT_CUSTOM_COLOR == '#ffffff'}selected="selected"{/if} style="background:#ffffff">{l s='Default 1' mod='customproductdesign'}</option>
						<option value="#000000" {if $DEFAULT_CUSTOM_COLOR == '#000000'}selected="selected"{/if} style="background:#000000">{l s='Default 2' mod='customproductdesign'}</option>
						{if isset($colors) AND $colors}
							{foreach from=$colors item=color}
							<option value="{$color.colour_code|escape:'htmlall':'UTF-8'}" {if $DEFAULT_CUSTOM_COLOR == $color.colour_code}selected="selected"{/if} style="background:{$color.colour_code|escape:'htmlall':'UTF-8'};" class="dcolor-preview">&nbsp;</option>
							{/foreach}
						{/if}
					</select>
					<p class="help-block">{l s='Please create Colors from tab in left section.' mod='customproductdesign'}</p>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip">{l s='Default Fonts' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-4">
					<select name="DEFAULT_CUSTOM_FONT" {if $DEFAULT_CUSTOM_FONT}style="font-family:{$DEFAULT_CUSTOM_FONT|escape:'htmlall':'UTF-8'}{/if}" onchange="$(this).css('font-family', $(this).val().toString());">
						<option value="default" {if $DEFAULT_CUSTOM_FONT == 'default'}selected="selected"{/if}>{l s='Default 2' mod='customproductdesign'}</option>
						{if isset($fonts) AND $fonts}
							{foreach from=$fonts item=font}
							<option value="{$font.font_name|escape:'htmlall':'UTF-8'}" {if $DEFAULT_CUSTOM_FONT == $font.font_name}selected="selected"{/if} style="font-family:{$font.font_name|escape:'htmlall':'UTF-8'};">{$font.font_name|escape:'htmlall':'UTF-8'}
							</option>
							{/foreach}
						{/if}
					</select>
					<p class="help-block">{l s='Please create Fonts from tab in left section.' mod='customproductdesign'}</p>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		
		<!-- Enable/disable Hints Section on front -->
		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Enable/disable Hints Block' mod='customproductdesign'}">{l s='Enable Hints on Front' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-7">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="CPD_HINTS_BLK" id="CPD_HINTS_BLK_on" value="1" {if isset ($CPD_HINTS_BLK) AND $CPD_HINTS_BLK == 1}checked="checked"{/if}/>
					<label class="t" for="CPD_HINTS_BLK_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
						<input type="radio" name="CPD_HINTS_BLK" id="CPD_HINTS_BLK_off" value="0" {if isset ($CPD_HINTS_BLK) AND $CPD_HINTS_BLK == 0}checked="checked"{/if}/>
					<label class="t" for="CPD_HINTS_BLK_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		
		<!-- Enable/disable Hints Section on front -->
		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Materials are mandatory for user?' mod='customproductdesign'}">{l s='Material Selection Mandatory' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-7">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="CPD_MATERIALS_MANDATORY" id="CPD_MATERIALS_MANDATORY_on" value="1" {if isset ($CPD_MATERIALS_MANDATORY) AND $CPD_MATERIALS_MANDATORY == 1}checked="checked"{/if}/>
					<label class="t" for="CPD_MATERIALS_MANDATORY_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
						<input type="radio" name="CPD_MATERIALS_MANDATORY" id="CPD_MATERIALS_MANDATORY_off" value="0" {if isset ($CPD_MATERIALS_MANDATORY) AND $CPD_MATERIALS_MANDATORY == 0}checked="checked"{/if}/>
					<label class="t" for="CPD_MATERIALS_MANDATORY_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		
		<hr style="visibility: hidden;">
		<h3 class="tab card-header panel-heading"><i class="icon-picture-o"></i> {l s='Pre Made Designs' mod='customproductdesign'}</h3>
<!-- Design Template settings -->
		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Enable/disable Pre-Made Designs' mod='customproductdesign'}">{l s='Enable Pre-Made Designs' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-7">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="CPD_ENABLE_PRE_DESIGNS" id="CPD_ENABLE_PRE_DESIGNS_on" value="1" {if isset ($CPD_ENABLE_PRE_DESIGNS) AND $CPD_ENABLE_PRE_DESIGNS == 1}checked="checked"{/if}/>
					<label class="t" for="CPD_ENABLE_PRE_DESIGNS_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
						<input type="radio" name="CPD_ENABLE_PRE_DESIGNS" id="CPD_ENABLE_PRE_DESIGNS_off" value="0" {if isset ($CPD_ENABLE_PRE_DESIGNS) AND $CPD_ENABLE_PRE_DESIGNS == 0}checked="checked"{/if}/>
					<label class="t" for="CPD_ENABLE_PRE_DESIGNS_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
			<div class="clearfix"></div>
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Show Design templates relative to product' mod='customproductdesign'}">{l s='Show Designs Relative to Product?' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-7">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="CPD_ENABLE_PRE_DESIGNS_RELATIVE" id="CPD_ENABLE_PRE_DESIGNS_RELATIVE_on" value="1" {if isset ($CPD_ENABLE_PRE_DESIGNS_RELATIVE) AND $CPD_ENABLE_PRE_DESIGNS_RELATIVE == 1}checked="checked"{/if}/>
					<label class="t" for="CPD_ENABLE_PRE_DESIGNS_RELATIVE_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
						<input type="radio" name="CPD_ENABLE_PRE_DESIGNS_RELATIVE" id="CPD_ENABLE_PRE_DESIGNS_RELATIVE_off" value="0" {if isset ($CPD_ENABLE_PRE_DESIGNS_RELATIVE) AND $CPD_ENABLE_PRE_DESIGNS_RELATIVE == 0}checked="checked"{/if}/>
					<label class="t" for="CPD_ENABLE_PRE_DESIGNS_RELATIVE_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
						<a class="slide-button btn"></a>
					</span>
					<div class="clearfix"></div>
					<small class="form-text help-block">{l s='If this option is enabled than designs made on the product will be shown only otherwise all will be visible.' mod='customproductdesign'}</small>
				</div>
			</div>
		</div>
<div class="clearfix"></div>
		<hr style="visibility: hidden;">
		<h3 class="tab card-header panel-heading"><i class="icon-picture-o"></i> {l s='Watermark Settings' mod='customproductdesign'}</h3>
		<!-- Design Template settings -->
				<!--<div class="col-lg-12 form-group margin-form">
					<label class="form-group control-label col-lg-4">
						<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Enable Layers Section on Front' mod='customproductdesign'}">{l s='Enable Layers Section' mod='customproductdesign'}</span>
					</label>
					<div class="form-group margin-form ">
						<div class="col-lg-7">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="CPD_ENABLE_LAYERS_SECTION" id="CPD_ENABLE_LAYERS_SECTION_on" value="1" {if isset ($CPD_ENABLE_LAYERS_SECTION) AND $CPD_ENABLE_LAYERS_SECTION == 1}checked="checked"{/if}/>
							<label class="t" for="CPD_ENABLE_LAYERS_SECTION_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
								<input type="radio" name="CPD_ENABLE_LAYERS_SECTION" id="CPD_ENABLE_LAYERS_SECTION_off" value="0" {if isset ($CPD_ENABLE_LAYERS_SECTION) AND $CPD_ENABLE_LAYERS_SECTION == 0}checked="checked"{/if}/>
							<label class="t" for="CPD_ENABLE_LAYERS_SECTION_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>
				</div>-->
		<!--<div class="clearfix"></div>
		<hr>-->
		<!-- watermark settings -->
		{include file='./watermark.tpl'}

		{if $version >= 1.6}
	    <div class="panel-footer">
	        <button class="btn btn-default pull-right" name="saveConfiguration" type="submit">
	            <i class="process-icon-save"></i>
	            {l s='Save' mod='customproductdesign'}
	        </button>
	    </div>
	    {else}
	    <div style="text-align:center">
	        <input type="submit" value="{l s='Save' mod='customproductdesign'}" class="button" name="saveConfiguration"/>
	    </div>
	    {/if}
	    <div class="clearfix"></div>
	</form>
</div>
