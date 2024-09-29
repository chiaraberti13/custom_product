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
{if isset($customization) AND count($customization) >= 1}
<li>
    <a href="javascript:void(0);" title="{$design->design_title|escape:'htmlall':'UTF-8'}">
    	<img width="98"
        id="mini_thumb_{$design->id|escape:'htmlall':'UTF-8'}"
        class="img-thumbnail mini_preview {if $index == 1}selected{/if}"
        alt="{$design->design_title|escape:'htmlall':'UTF-8'}"
        src="{if isset($design->id) && isset($design->path) && $design->path}{$design->path|escape:'htmlall':'UTF-8'}{else}{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/front.png{/if}"
        data-id="{$design->id|escape:'htmlall':'UTF-8'}">
        <span class="cpd_thumbs_bottom_title">{$design->design_title|escape:'htmlall':'UTF-8'}</span>
    </a>
</li>
{/if}