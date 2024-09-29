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

{if isset($fonts) AND $fonts}
	{literal}
		<style type="text/css">
		{/literal}{foreach from=$fonts item=font}{literal}
		 @font-face {
		    font-family: "{/literal}{$font.font_name|escape:'htmlall':'UTF-8'}{literal}";
		    src: url("{/literal}{$font.font_path|escape:'htmlall':'UTF-8'}{literal}");
			}
		{/literal}{/foreach}{literal}
		</style>
 	{/literal}
{/if}

{literal}
<style type="text/css">

	{/literal}{if isset($customization) AND $customization.tag1_font_sz}{literal}
		#sponsored_1 p.sp1name{
		font-size: {/literal}{$customization.tag1_font_sz|escape:'htmlall':'UTF-8'}{literal}px!important;
		}
	{/literal}{/if}

	{if isset($customization) AND $customization.tag2_font_sz}{literal}
		#sponsored_2 p.sp2name{
		font-size: {/literal}{$customization.tag2_font_sz|escape:'htmlall':'UTF-8'}{literal}px!important;
		}
	{/literal}{/if}

	{if isset($customization) AND $customization.tag1_line_height}{literal}
		#sponsored_2 p.sp1name{
		line-height: {/literal}{$customization.tag1_line_height|escape:'htmlall':'UTF-8'}{literal}px!important;
		}
	{/literal}{/if}

	{if isset($customization) AND $customization.tag2_line_height}{literal}
		#sponsored_2 p.sp2name{
		line-height: {/literal}{$customization.tag2_line_height|escape:'htmlall':'UTF-8'}{literal}px!important;
		}
	{/literal}{/if}

	{if isset($customization) AND $customization.text_line_height}{literal}
		#player_number p.selected_number{
		line-height: {/literal}{$customization.text_line_height|escape:'htmlall':'UTF-8'}{literal}px!important;
		}
	{/literal}{/if}

	{if isset($customization) AND $customization.numbers_line_height}{literal}
		#player_name p.pname{
		line-height: {/literal}{$customization.numbers_line_height|escape:'htmlall':'UTF-8'}{literal}px!important;
		}
	{/literal}{/if}{literal}

 </style>
 {/literal}