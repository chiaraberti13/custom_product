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
<div class="dynamic_pricing">
	<form action="{$action_link|escape:'htmlall':'UTF-8'}&amp;savePricing" name="customproductdesign_form" method="post" enctype="multipart/form-data" class="form-horizontal">

		<!-- Enable/disable image upload from front -->
		<div class="col-lg-12 form-group margin-form">
			<label class="form-group control-label col-lg-4">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Enable Layer addition on Front' mod='customproductdesign'}">{l s='Enable Layer addition on Front' mod='customproductdesign'}</span>
			</label>
			<div class="form-group margin-form ">
				<div class="col-lg-7">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="CPD_ENABLE_DYNAMIC_PRICING" id="CPD_ENABLE_DYNAMIC_PRICING_on" value="1" {if isset ($CPD_ENABLE_DYNAMIC_PRICING) AND $CPD_ENABLE_DYNAMIC_PRICING == 1}checked="checked"{/if}/>
					<label class="t" for="CPD_ENABLE_DYNAMIC_PRICING_on">{if $version < 1.6}<img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled" />{else}{l s='Yes' mod='customproductdesign'}{/if}</label>
						<input type="radio" name="CPD_ENABLE_DYNAMIC_PRICING" id="CPD_ENABLE_DYNAMIC_PRICING_off" value="0" {if isset ($CPD_ENABLE_DYNAMIC_PRICING) AND $CPD_ENABLE_DYNAMIC_PRICING == 0}checked="checked"{/if}/>
					<label class="t" for="CPD_ENABLE_DYNAMIC_PRICING_off">{if $version < 1.6}<img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled" />{else}{l s='No' mod='customproductdesign'}{/if}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="alert alert-info" role="alert">
			<span class="alert-text">
				{l s='Add interval rules below for dynamic pricing of elements the user will add on front.' mod='customproductdesign'}
			</span>
		</div>
		<div class="col-lg-12 form-group margin-form" id="fmm_ps_field_wrapper">
			<div class="col-lg-10" id="fmm_ps_field_holder">
				{if isset($dynamic_pricing) && !empty($dynamic_pricing)}
					{foreach item=dp from=$dynamic_pricing}
					<div class="col-lg-12 fmm_ps_field_wrap">
						<div class="col-lg-3">{l s='Qty From' mod='customproductdesign'}: <input type="text" name="pricing[]" value="{$dp.qty_from|escape:'htmlall':'UTF-8'}" /></div>
						<div class="col-lg-3">{l s='Qty To' mod='customproductdesign'}: <input type="text" name="pricing[]" value="{$dp.qty_to|escape:'htmlall':'UTF-8'}" /></div>
						<div class="col-lg-3">{l s='Price' mod='customproductdesign'}: <input type="text" name="pricing[]" value="{$dp.price|escape:'htmlall':'UTF-8'}" /> {$currency->sign|escape:'htmlall':'UTF-8'}</div>
						<div class="col-lg-3"><i class="icon-trash pull-right" onclick="dumpThisField(this);"></i></div>
					</div>
					{/foreach}
				{else}
				<div class="col-lg-12 fmm_ps_field_wrap">
					<div class="col-lg-3">{l s='Qty From' mod='customproductdesign'}: <input type="text" name="pricing[]" placeholder="0" /></div>
					<div class="col-lg-3">{l s='Qty To' mod='customproductdesign'}: <input type="text" name="pricing[]" placeholder="0" /></div>
					<div class="col-lg-3">{l s='Price' mod='customproductdesign'}: <input type="text" name="pricing[]" placeholder="{$currency->sign|escape:'htmlall':'UTF-8'}" /> {$currency->sign|escape:'htmlall':'UTF-8'}</div>
					<div class="col-lg-3"><i class="icon-trash pull-right" onclick="dumpThisField(this);"></i></div>
				</div>
				{/if}
			</div>
			<div class="col-lg-2 pull-right">
				<button type="button" class="btn btn-default pull-right" onclick="addFieldUrls();"><i class="icon-plus"></i> {l s='Add Interval' mod='customproductdesign'}</button>
			</div>
		</div>

		{if $version >= 1.6}
	    <div class="panel-footer">
	        <button class="btn btn-default pull-right" name="savePricing" type="submit">
	            <i class="process-icon-save"></i>
	            {l s='Save' mod='customproductdesign'}
	        </button>
	    </div>
	    {else}
	    <div style="text-align:center">
	        <input type="submit" value="{l s='Save' mod='customproductdesign'}" class="button" name="savePricing"/>
	    </div>
	    {/if}
	    <div class="clearfix"></div>
	</form>
<style type="text/css">{literal}
#fmm_ps_field_wrapper { padding-top: 6px;}
#fmm_ps_field_wrapper:after { content: "."; clear: both; display: block; visibility: hidden; height: 0px;}
#fmm_ps_field_holder input { margin:0; display: inline-block; width: 50%; vertical-align: middle;}
#fmm_ps_field_holder i { display: inline-block; cursor: pointer;}
#fmm_ps_field_wrapper .pull-right { margin: 5px 0;}
.fmm_ps_field_wrap { padding: 10px 0; background: #edeaea; margin-top: 1px;}
#fmm_ps_field_wrapper .icon-trash { padding: 3px 0;}

</style>
<script type="text/javascript">
var _currency = "{/literal}{$currency->sign|escape:'htmlall':'UTF-8'}";
var _trs_qty_from = "{l s='Qty From' mod='customproductdesign'}";
var _trs_qty_to = "{l s='Qty To' mod='customproductdesign'}";
var _trs_price = "{l s='Price' mod='customproductdesign'}{literal}";
function addFieldUrls() {
            $('#fmm_ps_field_holder').append('<div class="col-lg-12 fmm_ps_field_wrap"><div class="col-lg-3">'+_trs_qty_from+': <input type="text" name="pricing[]" placeholder="0" /></div><div class="col-lg-3">'+_trs_qty_to+': <input type="text" name="pricing[]" placeholder="0" /></div><div class="col-lg-3">'+_trs_price+': <input type="text" name="pricing[]" placeholder="'+_currency+'" /> '+_currency+'</div><div class="col-lg-3"><i class="icon-trash pull-right" onclick="dumpThisField(this);"></i></div></div>');
}
function dumpThisField(el) {
            $(el).parent().parent().remove();
}
</script>
{/literal}
</div>
