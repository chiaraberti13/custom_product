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

{if isset($customization) AND $customization AND count($customization) > 0}
    {*include file='./product_design_css.tpl'*}
    	{assign var='product_price' value=$custom_product->getPrice(false, $smarty.const.NULL, $price_display_precision)}

<script type="text/javascript">
//<![CDATA[
var ps_version = "{$version|escape:'htmlall':'UTF-8'}";
//]]>
</script>

<div id="personalization" style="background: #fff; 
    width: 100%;
    display:none;
    border-top: 2px solid #ff8400;
    border-left: 1px solid #e4e4e4;
    border-right: 1px solid #e4e4e4;
    ">
    {include file='./design/custom_design.tpl'}
</div>
{/if}
