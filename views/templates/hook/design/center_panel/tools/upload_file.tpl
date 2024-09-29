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

{if isset($LOGO_UPLOAD_URL) AND $LOGO_UPLOAD_URL == 1}
	{assign var=query value=['action' => 'getURLForm']}
	<div class="upload_link">		
		<a id="url-image" class="link btn btn-primary button" href="{$link->getModuleLink('customproductdesign', 'cpdesign', $query)|escape:'htmlall':'UTF-8'}">
			{l s='Upload image from Link' mod='customproductdesign'}
		</a>
	</div>
{/if}

{if isset($LOGO_UPLOAD_EN_DS) AND $LOGO_UPLOAD_EN_DS == 1}
	{assign var=params value=['action' => 'upload_logo']}
	<form id="logo-upload-front" class="form-logo-upload" method="post" action="{$link->getModuleLink('customproductdesign', 'cpdesign', $params)|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data">
		<p id="alert-1" class="alert alert-success" style="display:none;">{l s='Image uploaded successfully' mod='customproductdesign'}</p>
		<p id="alert-2" class="alert alert-error" style="display:none;">{l s='Operation failed' mod='customproductdesign'}</p>
		<p id="alert-3" class="alert alert-danger" style="display:none;">{l s='Invalid file' mod='customproductdesign'}</p>
		<label>{l s='Upload image from file' mod='customproductdesign'}</label>
		<input type="hidden" name="id_product" value="{$id_product_old|escape:'htmlall':'UTF-8'}">
		<div class="form-group" style="display:none;">
			<div class="col-lg-7">
				<input type="file" name="logo" id="logo" class="control-label">
			</div>
		</div>
		<div class="form-group">
			<div class="upload_container">
				<a id="browse_logo" type="submit" class="btn btn-primary">
					<i class="icon-upload-alt"></i> {l s='Browse' mod='customproductdesign'}
				</a>
				<button name="upload_logo" type="submit" class="btn btn-success">
					<i class="icon-upload-alt"></i> {l s='Upload' mod='customproductdesign'}
				</button>
			</div>
			<div class="clearfix"></div>
		</div>
	</form>
{/if}
<!-- imagges list -->
<div id="upload_logo_front">
{if isset($logos) && $logos}
	{foreach from=$logos item=logo}
		<div logoname="{$logo.logo_name|escape:'htmlall':'UTF-8'}" logoid="{$logo.id_logo|escape:'htmlall':'UTF-8'}" class="logo_container boxlogosq">
			<img src="{$logo.logo_path|escape:'htmlall':'UTF-8'}" class="logos" width="50px" height="50px" logo-name="{$logo.logo_name|escape:'htmlall':'UTF-8'}">
		</div>
	{/foreach}
{/if}
</div>