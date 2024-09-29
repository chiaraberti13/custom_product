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

<script type="text/javascript" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}js/admin-products.js"></script>
<script type="text/javascript" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}js/jquery/plugins/jquery.colorpicker.js"></script>
<script type="text/javascript">
$.fn.mColorPicker.defaults.imageFolder = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/";
var id_lang_default = {$id_lang|escape:'htmlall':'UTF-8'};
var tab = 'general';
{if isset($smarty.get.tab) AND $smarty.get.tab}
    tab = "{$smarty.get.tab|escape:'htmlall':'UTF-8'}";
{/if}
$(document).ready(function() {
   hideOtherLanguage(id_lang_default);
   displayDesignTab(tab);
})

function displayDesignTab(tab) {
    $('.product_design').hide();
    $('.product_design_page').removeClass('selected');
    $('#productdesign_' + tab).show();
    $('#productdesign_link_' + tab).addClass('selected');
    $('#currentFormTab').val(tab);
}
</script>

<div class="col-lg-12 panel">
    <div class="toolbarBox pageTitle">
        <h3 class="tab card-header panel-heading"><i class="icon-star"></i> <span style="padding-left:10px">{l s='Configuration' mod='customproductdesign'}</span></h3>
    </div>
    <div class="alert alert-info">
        {l s='Go to \'Catalog => Products\' and Edit any Product then select \'Custom Product Designs\' tab for Design settings.' mod='customproductdesign'}
    </div>
</div>
<div class="clearfix"></div>
<div class="col-lg-2" id="product-design">
 	<div class="productTabs">
		<ul class="tab">
         <li class="tab-row">
                <a class="product_design_page selected" id="productdesign_link_general" href="javascript:displayDesignTab('general');">
                <i class="icon-cogs"></i> {l s='General' mod='customproductdesign'}
                </a>
            </li>
         
			<li class="tab-row">
				<a class="product_design_page" id="productdesign_link_fonts" href="javascript:displayDesignTab('fonts');">
                    <i class="icon-font"></i> {l s='Fonts' mod='customproductdesign'}
                </a>
			</li>
			<li class="tab-row">
				<a class="product_design_page" id="productdesign_link_color" href="javascript:displayDesignTab('color');">
                <i class="icon-circle"></i> {l s='Colours' mod='customproductdesign'}
                </a>
			</li>
			<li class="tab-row">
				<a class="product_design_page" id="productdesign_link_logo" href="javascript:displayDesignTab('logo');">
                <i class="icon-shield"></i> {l s='Image' mod='customproductdesign'}
                </a>
			</li>
            <li class="tab-row">
                <a class="product_design_page" id="productdesign_link_material" href="javascript:displayDesignTab('material');">
                <i class="icon-print"></i> {l s='Print Material' mod='customproductdesign'}
                </a>
            </li>
            <li class="tab-row">
                <a class="product_design_page" id="productdesign_link_pricing" href="javascript:displayDesignTab('pricing');">
                <i class="icon-money"></i> {l s='Dynamic Pricing' mod='customproductdesign'}
                </a>
            </li>
            <!--<li class="tab-row">
                <a class="product_design_page" id="productdesign_link_templates" href="javascript:displayDesignTab('templates');">
                <i class="icon-picture-o"></i> {l s='Design Templates' mod='customproductdesign'}
                </a>
            </li>-->
		</ul>
	</div>
</div>
<!-- Tab Content -->
<div class="col-lg-10">
    <input type="hidden" id="currentFormTab" name="currentFormTab" value="general" />
    <div id="productdesign_fonts" class="product_design tab-pane">
        {$font_list nofilter} <!-- html content -->
    </div><div class="clearfix"></div>

    <div id="productdesign_color" class="product_design tab-pane" style="display:none;">
        {$colors_list nofilter} <!-- html content -->
    </div><div class="clearfix"></div>

    <div id="productdesign_logo" class="product_design tab-pane" style="display:none;">
        {$image_list nofilter} <!-- html content -->
    </div><div class="clearfix"></div>

    <div id="productdesign_material" class="product_design tab-pane" style="display:none;">
        {$material_list nofilter} <!-- html content -->
    </div><div class="clearfix"></div>

   <div id="productdesign_pricing" class="product_design tab-pane panel" style="display:none;">
        <h3 class="tab"><i class="icon-money"></i> {l s='Dynamic Pricing' mod='customproductdesign'}</h3>
        {include file="./tab_content/pricing.tpl"}
    </div><div class="clearfix"></div>

   <!--<div id="productdesign_templates" class="product_design tab-pane panel" style="display:none;">
        <h3 class="tab"><i class="icon-picture-o"></i> {l s='Design Templates' mod='customproductdesign'}</h3>
        {*include file="./tab_content/templates.tpl"*}
    </div><div class="clearfix"></div>-->

    <div id="productdesign_general" class="product_design tab-pane panel" style="display:none;">
        <h3 class="tab"><i class="icon-cogs"></i> {l s='General' mod='customproductdesign'}</h3>
        {include file="./tab_content/general.tpl"}
    </div><div class="clearfix"></div>
</div>
<div class="clearfix"></div>
{include file="./tab_content/admin_css.tpl"}