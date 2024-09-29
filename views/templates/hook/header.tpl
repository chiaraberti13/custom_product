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

{if isset($static_token) AND $static_token}
	{assign var='params' value=['ajax' => true, 'token' => $static_token]}
{else}
	{assign var='params' value=['ajax' => true, 'token' => $current_token]}
{/if}

<script>
//<![CDATA[
var ps_version = parseFloat("{$ps_version|escape:'htmlall':'UTF-8'}");
var id_currency = parseInt("{$id_currency|escape:'htmlall':'UTF-8'}");
var isLogged = "{$isLogged|escape:'htmlall':'UTF-8'}";
var design_controller = "{$design_controller|escape:'htmlall':'UTF-8'}";
var design_handler = "{$design_handler|escape:'htmlall':'UTF-8'}";
var cart_link = "{$cart_link|escape:'htmlall':'UTF-8'}";
var handle = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/rot.png";
var print_ico = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/print.png";
var basket_ico = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/basket.png";
var cimg_dir = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/";
var cpd_img_path = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/";

/* labels */
var minimum_qty = "{l s='Please add a design to list' mod='customproductdesign' js=1}";
var add_to_cart_label = "{l s='Add to Cart' mod='customproductdesign' js=1}";
var continue_label = "{l s='Continue shopping' mod='customproductdesign' js=1}";
var proceed_label = "{l s='Proceed to checkout.' mod='customproductdesign' js=1}";
var _size_label = "{l s='Size' mod='customproductdesign' js=1}";
var _name_label = "{l s='Text' mod='customproductdesign' js=1}";
var _number_label = "{l s='Number' mod='customproductdesign' js=1}";
var _none_label = "{l s='none' mod='customproductdesign' js=1}";
var delete_label = "{l s='Delete' mod='customproductdesign' js=1}";
var no_label = "{l s='No' mod='customproductdesign' js=1}";
var yes_label = "{l s='Yes' mod='customproductdesign' js=1}";
var current_label = "{l s='Reset Current' mod='customproductdesign' js=1}";
var all_label = "{l s='Reset All' mod='customproductdesign' js=1}";
var cancel_label = "{l s='Cancel' mod='customproductdesign' js=1}";
var _deploy_label = "{l s='Deploy' mod='customproductdesign' js=1}";
var reset_label = "{l s='Reset Customization' mod='customproductdesign' js=1}";
var reset_text_label = "{l s='Please select customization(s) to reset.' mod='customproductdesign' js=1}";
var reset_all_label = "{l s='Reset all your customization?' mod='customproductdesign' js=1}";
var reset_label_and_deploy_template = "{l s='Leave current design and deploy this?' mod='customproductdesign' js=1}";
var reset_current_label = "{l s='Reset active customization?' mod='customproductdesign' js=1}";
var error_label = "{l s='Error!!' mod='customproductdesign' js=1}";
var delete_para = "{l s='Do you want to delete this?' mod='customproductdesign' js=1}";
var loading_label = "{l s='Loading please wait' mod='customproductdesign' js=1}......";
var empty_design_label = "{l s='Please customize the design first.' mod='customproductdesign' js=1}";
var delete_design_label = "{l s='Customization deleted successfully.' mod='customproductdesign' js=1}";
var preview_label = "{l s='Preview' mod='customproductdesign' js=1}";
var close_label = "{l s='Close' mod='customproductdesign' js=1}";
var printmaterial_label = "{l s='Select Print material' mod='customproductdesign' js=1}";
var first_design_label_l = "{l s='Please add design ' mod='customproductdesign' js=1}";
var first_design_label_r = "{l s=' first to continue' mod='customproductdesign' js=1}";

/*watermark*/
var cpd_watermark_text = "{$CPD_WATERMARK_TEXT|escape:'htmlall':'UTF-8'}";
var cpd_watermark_textclr = "{$CPD_WATERMARK_TEXTCLR|escape:'htmlall':'UTF-8'}";
var cpd_watermark_size = parseInt("{$CPD_WATERMARK_SIZE|escape:'htmlall':'UTF-8'}");
var cpd_watermark_active = parseInt("{$CPD_WATERMARK_ACTIVE|escape:'htmlall':'UTF-8'}");

/* cpd default settings*/
var DEFAULT_CUSTOM_COLOR = "{if isset($DEFAULT_CUSTOM_COLOR) AND $DEFAULT_CUSTOM_COLOR}{$DEFAULT_CUSTOM_COLOR|escape:'htmlall':'UTF-8'}{else}inherit{/if}";
var DEFAULT_CUSTOM_FONT = "{if isset($DEFAULT_CUSTOM_FONT) AND $DEFAULT_CUSTOM_FONT}{$DEFAULT_CUSTOM_FONT|escape:'htmlall':'UTF-8'}{else}inherit{/if}";
var CPD_MATERIALS_MANDATORY = parseInt("{$CPD_MATERIALS_MANDATORY|escape:'htmlall':'UTF-8'}");
//]]>
</script>
{if Tools::version_compare($ps_version, '1.7.0.0', '<') == true}
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
{/if}