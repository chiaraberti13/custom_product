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
<div style="" class="row" id="font-upload">
	<div class="panel col-lg-12">
		<form id="form-font-upload" class="form-font-upload form-horizontal" enctype="multipart/form-data" method="post" action="{$action_link|escape:'htmlall':'UTF-8'}">
			<div class="card">
			<h3 class="card-header panel-heading"><i class="material-icons">spellcheck</i> {l s='Add a new font' mod='customproductdesign'}</h3>
			<div class="alert alert-warning">
				<p class="alert-text">{l s='TTF format fonts are supported, please select .ttf file' mod='customproductdesign'}</p>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3">
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Upload a font file from your computer.' mod='customproductdesign'}">
						{l s='Select fonts file' mod='customproductdesign'}
					</span>
				</label>
				<div class="col-lg-9">
					<input type="file" name="fonts" class="control-label btn btn-default">
				</div>
			</div>
			<div class="clearfix"></div>
			<br/>
			<div class="form-group">
				<div class="col-lg-9 col-lg-push-3">
					<button name="upload_fonts" type="submit" class="btn btn-default button">
						<i class="icon-upload-alt"></i>
						{l s='Upload file' mod='customproductdesign'}
					</button>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="panel-footer">
				<a class="btn btn-default button" name="submitCancel" href="{$action_link|escape:'htmlall':'UTF-8'}">
					<i class="process-icon-cancel"></i> {l s='Cancel' mod='customproductdesign'}
				</a>
			</div>
			</div>
		</form>
	</div>
</div>
