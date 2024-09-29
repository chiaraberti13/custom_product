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
<div id="design-container">
	{include file='./header/head.tpl'}
	<input type="hidden" id="cpd_dynamic_layer_count" value="0" />
	<div id="design_content">
		<div id="inner-content" class="design_wrap" style="max-width: 100%;">
			<div id="main-design">
				<div id="sub-design">
					<div class="entry-content">
						<div class="cpd-container c_p_d-responsive-mode">
							<div class="cpd-editor-wrap">
								<div class="cpd-editor-col" id="cpd_supernav">
									<div id="cpd_left_basic_nav">
										<ul>
											<li><a data-action="txt" class="cpd_origin_text" data-origin="cpd_img_txt_blox"><i class="material-icons">text_fields</i>
											{l s='Text' mod='customproductdesign'}</a></li>
											<li><a data-action="img" class="cpd_origin_image" data-origin="cpd_img_txt_blox"><i class="material-icons">wallpaper</i>
											{l s='Images' mod='customproductdesign'}</a></li>
											{if $CPD_ENABLE_PRE_DESIGNS > 0}{if $_id_employee <= 0 && $_id_design <= 0}<li><a data-action="ideas" data-origin="templates-designs-panel"><i class="material-icons">collections</i>
											{l s='Design Ideas' mod='customproductdesign'}</a></li>{/if}{/if}
											{if $_id_employee <= 0 && $_id_design <= 0}<li><a data-action="designs" data-origin="designs-panel" class="cpd_origin_mydesigns"><i class="material-icons">style</i>
											{l s='My Designs' mod='customproductdesign'}</a></li>{/if}
											<li><a data-action="layers" data-origin="cpd_layers_section"><i class="material-icons">layers</i>
											{l s='Layers' mod='customproductdesign'}</a></li>
										</ul>
									</div>
									<div id="cpd-tools-box-container" class="DesignTab">
										{include file='./left_panel/img_txt_blocks.tpl'}
										{if $_id_employee > 0 && $_id_design > 0}
											{include file='./left_panel/left_column.tpl'}
										{else}
											{include file='./left_panel/left_column.tpl'}
										{/if}
										{*if $CPD_ENABLE_LAYERS_SECTION > 0*}
										{if $_id_employee > 0 && $_id_design > 0}
											{include file='./left_panel/layers.tpl'}
										{else}
											{include file='./left_panel/layers.tpl'}
										{/if}
									</div>
									{*/if*}
						        </div>
				                <div id="custom-design-center-column" class="cpd-editor-col-2">
									{if $_id_employee > 0 && $_id_design > 0}
										{include file='./center_panel/center_column_employee.tpl'}
									{else}
										{include file='./center_panel/center_column.tpl'}
								   {/if}
				                </div>
				                <div class="cpd-editor-col right" id="cpd_secondary_nav">
									{if $_id_employee > 0 && $_id_design > 0}
										{include file='./right_panel/right_column_employee.tpl'}
									{else}
										{include file='./right_panel/right_column.tpl'}
									{/if}
				                </div>
						    </div>
						</div>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="cart_loader" style="display:none;"></div>
	{if $_id_employee <= 0 && $_id_design <= 0}
	{include file='./footer/foot.tpl'}
	{/if}
</div>
{include file='./fonts_css.tpl'}