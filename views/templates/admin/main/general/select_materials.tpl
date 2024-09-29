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
<!-- fonts -->
{if count($materials) > 0}
    <div class="col-lg-12 design_settings">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="fixed-width-xs">
                        <span class="title_box">
                            <input type="checkbox" onclick="checkDelBoxes(this.form, 'cpd_settings[selected_materials][data][]', this.checked)" id="checkme-selected_materials" name="checkme">
                        </span>
                    </th>
                    <th class="center">
                        <span class="title_box">{l s='Material' mod='customproductdesign'}</span>
                    </th>
                    <th class="center">
                        <span class="title_box">{l s='Images' mod='customproductdesign'}</span>
                    </th>
                </tr>
            </thead>
            <tbody>

                {foreach from=$materials item=material}
                <tr>
                    <td>
                        <input type="checkbox" value="{$material.id_material|escape:'htmlall':'UTF-8'}" id="material_{$material.id_material|escape:'htmlall':'UTF-8'}" class="selected_materials" name="cpd_settings[selected_materials][data][]" {if isset($selected_materials) AND $selected_materials}{if in_array($material.id_material, $selected_materials)}checked="checked"{/if}{/if}>
                    </td>
                    <td class="center">
                        <label for="material_{$material.id_material|escape:'htmlall':'UTF-8'}">{if isset($material.material_name) AND $material.material_name}{$material.material_name|escape:'htmlall':'UTF-8'}{/if}</label>
                    </td>
                    <td class="center">
                        <label for="material_{$material.id_material|escape:'htmlall':'UTF-8'}">
                        	<span>
								<img src="{$the_link->getMediaLink($material.material_path)|escape:'htmlall':'UTF-8'}" width="48" class="img-thumbnail">
							</span>
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
	{l s='You must add/enable material(s) first. Click' mod='customproductdesign'}&nbsp;<a href="{$action_link|escape:'htmlall':'UTF-8'}&tab=material">{l s='here' mod='customproductdesign'}</a>&nbsp;{l s='to add material.' mod='customproductdesign'}
</div>
{/if}
