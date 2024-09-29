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
{if (isset($fonts) AND $fonts|@count > 0)}
    <style type="text/css">
    {foreach from=$fonts item=font}
        {literal}
         @font-face {
            font-family: "{/literal}{$font.font_name|escape:'htmlall':'UTF-8'}{literal}";
            src: url("{/literal}{$font.font_path|escape:'htmlall':'UTF-8'}{literal}");
            }
        {/literal}
    {/foreach}
    </style>
{/if}