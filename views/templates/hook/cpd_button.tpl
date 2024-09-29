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
{if isset($customization) AND $customization AND count($customization) > 0 AND $cpd_page == 'product'}

{assign var=params value=['action' => 'getPrice']}
<script type="text/javascript">
//<![CDATA[
var pdf             = "{$pdf|escape:'htmlall':'UTF-8'}";
var pdf_orientation = "{$pdf_orientation|escape:'htmlall':'UTF-8'}";
//]]>
</script>
    <p class="customize_product buttons_bottom_block" id="fmm_cpd_btn">
    	<a id="customize_product" href="{$cpd_design_link|escape:'htmlall':'UTF-8'}" class="btn btn-primary">
    		{if $ps_ver > 0}<i class="material-icons">brush</i>{else}<i class="icon-magic"></i>{/if} {l s='Design Product' mod='customproductdesign'}
    	</a>
    </p>
{/if}
