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
<!-- loading available fonts -->
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

<!-- fonts -->
{if count($fonts) > 0}
    <div class="col-lg-12 design_settings">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="fixed-width-xs">
                        <span class="title_box">
                            <input type="checkbox" onclick="checkDelBoxes(this.form, 'cpd_settings[selected_fonts][data][]', this.checked)" id="checkme-selected_fonts" name="checkme">
                        </span>
                    </th>
                    <th class="center">
                        <span class="title_box">{l s='Fonts' mod='customproductdesign'}</span>
                    </th>
                </tr>
            </thead>
            <tbody>

                {foreach from=$fonts item=font}
                <tr>
                    <td>
                        <input type="checkbox" value="{$font.id_font|escape:'htmlall':'UTF-8'}" id="font_{$font.id_font|escape:'htmlall':'UTF-8'}" class="selected_fonts" name="cpd_settings[selected_fonts][data][]" {if isset($selected_fonts) AND $selected_fonts}{if in_array($font.id_font, $selected_fonts)}checked="checked"{/if}{/if}>
                    </td>
                    <td class="center" style="font-family:{$font.font_name|escape:'htmlall':'UTF-8'};">
                        <label for="font_{$font.id_font|escape:'htmlall':'UTF-8'}">
                        	<span style="width:100%;font-size:16px;" class="img-thumbnail btn btn-primary">{if isset($font.font_name) AND $font.font_name}{$font.font_name|escape:'htmlall':'UTF-8'}{/if}</span>
                        </label>
                    </td>
                </tr>
                {/foreach}

            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>
{else}
<div class="alert alert-info">
	{l s='You must add/enable font(s) first. Click' mod='customproductdesign'}&nbsp;<a href="{$action_link|escape:'htmlall':'UTF-8'}&tab=fonts">{l s='here' mod='customproductdesign'}</a>&nbsp;{l s='to add fonts.' mod='customproductdesign'}
</div>
{/if}
