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
<div style="" class="row" id="logo-upload">
	<div class="panel card col-lg-12">
		<form class="form-logo-upload form-horizontal" enctype="multipart/form-data" method="post" action="{$action_link|escape:'htmlall':'UTF-8'}&conf=4&tab=logo">
			<h3 class="card-header panel-heading"><i class="material-icons">image</i> {l s='Add new image' mod='customproductdesign'}</h3>
			{if isset($smarty.get.logo_res) && $smarty.get.logo_res == 0}
				<p class="alert alert-danger">{l s='Invalid file' mod='customproductdesign'}</p>
			{/if}
			{if isset($smarty.get.logo_res) && $smarty.get.logo_res == 1}
				<p class="alert alert-success">{l s='Image uploaded successfully' mod='customproductdesign'}</p>
			{/if}
			{if isset($smarty.get.logo_res) && $smarty.get.logo_res == 2}
				<p class="alert alert-error">{l s='Operation failed' mod='customproductdesign'}</p>
			{/if}
			<div class="form-group">
				<div class="col-lg-9" id="cpd_img_upload_bulk">
					<div class="col-lg-12 fmm_ps_field_wrap">
						<div class="col-lg-3"><label class="control-label">{l s='Select an image' mod='customproductdesign'}</label></div>
						<div class="col-lg-8"><div class="col-lg-6 overflow_hidden"><input type="file" name="logo[]" class="control-label btn btn-default"></div>
						<div class="col-lg-6"><input type="text" placeholder="{l s='Enter comma separated tags' mod='customproductdesign'}" class="form-control" name="tags[]"/></div></div>
						<div class="col-lg-1"><i class="icon-trash pull-right" onclick="dumpThisField(this);"></i></div>
					</div>
				</div>
				<div class="col-lg-3 pull-right">
					<button type="button" class="btn btn-default pull-right" onclick="addFieldUrls();"><i class="icon-plus"></i> {l s='Add More Images' mod='customproductdesign'}</button>
				</div>
			</div>
			<div class="clearfix"></div>
			<br/>
			<div class="form-group">
				<div class="col-lg-9 col-lg-push-3">
					<button name="upload_logo" type="submit" class="btn btn-default button">
						<i class="icon-upload-alt"></i>
						{l s='Upload Images' mod='customproductdesign'}
					</button>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="panel-footer">
				<a class="btn btn-default button" name="submitCancel" href="{$action_link|escape:'htmlall':'UTF-8'}">
					<i class="process-icon-cancel"></i> {l s='Cancel' mod='customproductdesign'}
				</a>
			</div>
		</form>
	</div>
<style type="text/css">{literal}
.fmm_ps_field_wrap input { margin:0; display: inline-block; vertical-align: middle;}
.fmm_ps_field_wrap i { display: inline-block; cursor: pointer;}
.fmm_ps_field_wrap .pull-right { margin: 9px 0 0px;}
.fmm_ps_field_wrap { padding: 4px 0; background: #edeaea; margin-top: 1px; line-height: 26px;}
.fmm_ps_field_wrap .icon-trash { padding: 3px 0;}
.overflow_hidden { overflow: hidden;}
</style>
<script type="text/javascript">
var _lbl_select_img = "{/literal}{l s='Select an image' mod='customproductdesign'}{literal}";
var _lbl_select_tags= "{/literal}{l s='Enter comma separated tags' mod='customproductdesign'}{literal}";
function addFieldUrls() {
            $('#cpd_img_upload_bulk').append('<div class="col-lg-12 fmm_ps_field_wrap"><div class="col-lg-3"><label class="control-label">'+_lbl_select_img+'</label></div><div class="col-lg-8"><div class="col-lg-6 overflow_hidden"><input type="file" name="logo[]" class="control-label btn btn-default"></div><div class="col-lg-6"><input type="text" placeholder="'+_lbl_select_tags+'" class="form-control" name="tags[]"/></div></div><div class="col-lg-1"><i class="icon-trash pull-right" onclick="dumpThisField(this);"></i></div></div>');
}
function dumpThisField(el) {
            $(el).parent().parent().remove();
}
</script>
{/literal}
</div>
