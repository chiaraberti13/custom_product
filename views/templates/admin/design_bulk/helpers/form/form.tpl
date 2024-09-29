{*
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    Satoshi Brasileiro
*  @copyright 2022 Satoshi Brasileiro
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{extends file="helpers/form/form.tpl"}
{block name="input"}
{if $input.name == 'categories'}
<div class="col-lg-10 rcg_max_height">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th> </th>
                <th>
                    <span class="title_box">
                        {l s='ID' mod='customproductdesign'}
                    </span>
                </th>
                <th>
                    <span class="title_box">
                        {l s='Name' mod='customproductdesign'}
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            {if !isset($categories) || empty($categories)}
            <tr>
                <td>{l s='No categories found.' mod='customproductdesign'}</td>
            </tr>
            {else}
            {foreach from=$categories item=category}
                <tr>
                <td>
                    <input type="checkbox" name="category[]" value="{$category.id_category|escape:'htmlall':'UTF-8'}" />
                </td>
                <td>
                    {$category.id_category|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$category.name|escape:'htmlall':'UTF-8'}
                </td>
                </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>
    <p class="help-block"><b>*</b> {l s='Please Note: All selected category products will be used as targets.' mod='customproductdesign'}</p>
</div>
{else}
{$smarty.block.parent}
{/if}
{/block}