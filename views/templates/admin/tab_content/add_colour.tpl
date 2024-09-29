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
<script type="text/javascript">
	$(function(){
		$.fn.mColorPicker.defaults.imageFolder = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/";
	})
</script>
<script type="text/javascript" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}js/jquery/plugins/jquery.colorpicker.js"></script>
<div class="panel">
<form class="form-horizontal" action="{$action_link|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
	<h3 class="panel-heading"><img width="16" src="../img/admin/color.png"> {l s='Add Color' mod='customproductdesign'}</h3>
	<input type="hidden" value="{if !empty($color) AND $color}{$color.id_colour|escape:'htmlall':'UTF-8'}{else}0{/if}" name="id_colour">
	<div class="form-group">
		<label class="col-lg-3 control-label">{l s='Color Name' mod='customproductdesign'}</label>
		<div class="col-lg-6">
			<input id="color_name" class="form-control" type="text" name="color_name" value="{if !empty($color) AND $color}{$color.colour_name|escape:'htmlall':'UTF-8'}{/if}">
		</div>
		<div class="clearfix"></div>
	</div>
	
	
	<div class="form-group">
		<label class="col-lg-3 control-label">{l s='Color Code' mod='customproductdesign'}</label>
		<div class="col-lg-9">
			<div class="col-lg-8">
				<div class="input-group">
					<input type="text" class="mColorPicker" id="color_0" name="color_code" data-hex="true" value="{if !empty($color) AND $color}{$color.colour_code|escape:'htmlall':'UTF-8'}{/if}" style="background:{if !empty($color) AND $color}{$color.colour_code|escape:'htmlall':'UTF-8'}{/if};"/>
					<span id="icp_color_0" class="input-group-addon mColorPickerTrigger" data-mcolorpicker="true">
						<img src="../img/admin/color.png"/>
					</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
	
	<div class="clearfix"></div>
	<div class="panel-footer">
		<button class="btn btn-default pull-right" type="submit" name="submitAddColor">
			<i class="process-icon-save"></i> {l s='Add Color' mod='customproductdesign'}
		</button>
		<a class="btn btn-default" name="submitCancel" href="{$action_link|escape:'htmlall':'UTF-8'}">
			<i class="process-icon-cancel"></i> {l s='Cancel' mod='customproductdesign'}
		</a>
	</div>
</form>
</div>
