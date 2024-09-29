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
<!-- colors -->
{if count($colors) > 0}
	<div class="col-lg-12 design_settings">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="fixed-width-xs">
                        <span class="title_box">
                            <input type="checkbox" onclick="checkDelBoxes(this.form, 'cpd_settings[selected_colors][data][]', this.checked)" id="checkme-selected_colors" name="checkme">
                        </span>
                    </th>
                    <th class="center">
                        <span class="title_box">{l s='Color Name' mod='customproductdesign'}</span>
                    </th>
                    <th class="center">
                        <span class="title_box">{l s='Colors' mod='customproductdesign'}</span>
                    </th>
                </tr>
            </thead>
            <tbody>

                {foreach from=$colors item=color}
                <tr>
                    <td>
                        <input type="checkbox" value="{$color.id_colour|escape:'htmlall':'UTF-8'}" id="color_{$color.id_colour|escape:'htmlall':'UTF-8'}" class="selected_colors" name="cpd_settings[selected_colors][data][]" {if isset($selected_colors) AND $selected_colors}{if in_array($color.id_colour, $selected_colors)}checked="checked"{/if}{/if}>
                    </td>
                    <td class="center">
                        <label for="color_{$color.id_colour|escape:'htmlall':'UTF-8'}">{if isset($color.colour_name) AND $color.colour_name}{$color.colour_name|escape:'htmlall':'UTF-8'}{/if}</label>
                    </td>
                    <td class="center">
                        <label for="color_{$color.id_colour|escape:'htmlall':'UTF-8'}">
							<div class="dcolor-preview img-thumbnail" style="background-color:{$color.colour_code|escape:'htmlall':'UTF-8'};height: 32px; width: 32px;">&nbsp;</div>
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
    	{l s='You must add/enable font(s) first. Click' mod='customproductdesign'}&nbsp;<a href="{$action_link|escape:'htmlall':'UTF-8'}&tab=color">{l s='here' mod='customproductdesign'}</a>&nbsp;{l s='to add colors.' mod='customproductdesign'}
    </div>
{/if}
