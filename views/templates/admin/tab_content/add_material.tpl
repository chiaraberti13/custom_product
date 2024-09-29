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
<div class="panel">
<form class="form-horizontal" action="{$action_link|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
	<h3 class="panel-heading"><i class="icon-star-half-empty"></i> {l s='Add Material' mod='customproductdesign'}</h3>
	<input type="hidden" value="{if isset($material) AND $material}{$material.id_material|escape:'htmlall':'UTF-8'}{else}0{/if}" name="id_material">
	<div class="form-group">
		<label class="col-lg-3 control-label">{l s='Material Name' mod='customproductdesign'}</label>
		<div class="col-lg-6">
			<input id="material_name" class="form-control" type="text" name="material_name" value="{if isset($material) AND $material}{$material.material_name|escape:'htmlall':'UTF-8'}{/if}">
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 control-label">{l s='Price' mod='customproductdesign'}</label>
		<div class="col-lg-3">
		    <div class="input-group m-b-1">
		        <span class="input-group-addon">{$iso_code|escape:'htmlall':'UTF-8'}</span>
		        <input id="material_price" class="form-control" type="text" name="price" value="{if isset($material) AND $material}{$material.price|escape:'htmlall':'UTF-8'}{/if}">
		    </div>
		    <p class="info-help help-block">{l s='Enter price without currency sign.' mod='customproductdesign'}</p>
		</div>
		<div class="clearfix"></div>
	</div>

	
	{if isset($material) AND isset($material.material_path)}
	<div class="form-group">
		<label class="col-lg-3 control-label">{l s='Material Image' mod='customproductdesign'}</label>
		<div class="col-lg-9">
			<img class="img-thumbnail" src="{$material.material_path|escape:'htmlall':'UTF-8'}" alt="{$material.material_name|escape:'htmlall':'UTF-8'}" width="200">
		</div>
		<div class="clearfix"></div>
	</div>
	{/if}

	<div class="form-group">
		<label class="control-label col-lg-3" for="material">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Upload material image' mod='customproductdesign'}">
				{l s='Select an image for material' mod='customproductdesign'}
			</span>
		</label>
		<div class="col-lg-9">
			<input type="file" name="material" id="material" class="control-label btn btn-default">
		</div>
	</div>
	<div class="clearfix"></div>
	
	<div class="clearfix"></div>
	<div class="panel-footer">
		<button class="btn btn-default pull-right" type="submit" name="upload_material">
			<i class="process-icon-save"></i>
			{if isset($material) AND $material}
				{l s='Update Material' mod='customproductdesign'}
			{else}
				{l s='Add Material' mod='customproductdesign'}
			{/if}
		</button>
		<a class="btn btn-default" name="submitCancel" href="{$action_link|escape:'htmlall':'UTF-8'}">
			<i class="process-icon-cancel"></i> {l s='Cancel' mod='customproductdesign'}
		</a>
	</div>
</form>
</div>
