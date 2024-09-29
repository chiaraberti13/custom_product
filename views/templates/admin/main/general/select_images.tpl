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
{if count($logos) > 0}
    <div class="col-lg-12 design_settings">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="fixed-width-xs">
                        <span class="title_box">
                            <input type="checkbox" onclick="checkDelBoxes(this.form, 'cpd_settings[selected_images][data][]', this.checked)" id="checkme-selected_images" name="checkme">
                        </span>
                    </th>
                    <th class="center">
                        <span class="title_box">{l s='Label' mod='customproductdesign'}</span>
                    </th>
                    <th class="center">
                        <span class="title_box">{l s='Images' mod='customproductdesign'}</span>
                    </th>
                </tr>
            </thead>
            <tbody>

                {foreach from=$logos item=image}
                <tr>
                    <td>
                        <input type="checkbox" value="{$image.id_logo|escape:'htmlall':'UTF-8'}" id="images_{$image.id_logo|escape:'htmlall':'UTF-8'}" class="selected_images" name="cpd_settings[selected_images][data][]" {if isset($selected_images) AND $selected_images}{if in_array($image.id_logo, $selected_images)}checked="checked"{/if}{/if}>
                    </td>
                    <td class="center">
                        <label for="images_{$image.id_logo|escape:'htmlall':'UTF-8'}">{if isset($image.logo_name) AND $image.logo_name}{$image.logo_name|escape:'htmlall':'UTF-8'}{/if}</label>
                    </td>
                    <td class="center">
                        <label for="images_{$image.id_logo|escape:'htmlall':'UTF-8'}">
                        	<span>
								<img src="{$the_link->getMediaLink($image.logo_path)|escape:'htmlall':'UTF-8'}" width="48" class="img-thumbnail">
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
	{l s='You must add/enable font(s) first. Click' mod='customproductdesign'}&nbsp;<a href="{$action_link|escape:'htmlall':'UTF-8'}&tab=logo">{l s='here' mod='customproductdesign'}</a>&nbsp;{l s='to add images.' mod='customproductdesign'}
</div>
{/if}
