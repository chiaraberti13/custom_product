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

<!-- general listing -->
<div class="cpd_templates">
	<a id="cpd_close_premades">{if $ps_version < 1.7}<i class="icon-delete"></i>{else}<i class="material-icons">close</i>{/if}</a>
		{if empty($design_templates)}
			<p>{l s='No design templates found.' mod='customproductdesign'}</p>
			<div class="alert alert-info">
				{l s='Go to \'Catalog - Products\' and Edit any Product then select \'Custom Product Designs\' tab for creating design templates.' mod='customproductdesign'}
			</div>
		{else}
			<div class="table-responsive-row clearfix">
				<table class="table">
					<thead>
						<tr class="nodrag nodrop">
							<th class="center fixed-width-xs">
								<span class="title_box">ID</span>
							</th>
							<th class="center fixed-width-xs">
								<span class="title_box">{l s='ID Design' mod='customproductdesign'}</span>
							</th>
							<th class="center">
								<span class="title_box">{l s='Preview' mod='customproductdesign'}</span>
							</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$design_templates item=tmp}
						<tr id="premade_{$tmp.id_cpd_saved_templates|escape:'htmlall':'UTF-8'}">
							<td class="center">
								{$tmp.id_cpd_saved_templates|escape:'htmlall':'UTF-8'}
							</td>
							<td class="center">
								{$tmp.id_design|escape:'htmlall':'UTF-8'}
							</td>
							<td class="center">
								<img src="{$tmp.base_img|escape:'htmlall':'UTF-8'}" width="120" />
							</td>
							<td class="text-right">
								<div class="btn-group pull-right">
									<a title="{l s='Delete' mod='customproductdesign'}" class="delete_cpd_template" data-id="{$tmp.id_cpd_saved_templates|escape:'htmlall':'UTF-8'}">
										{if $ps_version < 1.7}<i class="icon-delete"></i>{else}<i class="material-icons">delete</i>{/if}</a>
								</div>
							</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		{/if}
</div>
