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
<!-- general settings -->
<div class="panel">
    <div class="row">
        <div class="col-lg-4">
            <!-- active -->
            <div class="form-group">
                <label class="form-group control-label col-lg-12">
                    <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Customization will be enabled/disabled on this product.' mod='customproductdesign'}">{l s='Enable customization' mod='customproductdesign'}</span>
                </label>
                <div class="form-group margin-form ">
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg ps-switch">
                            <input id="customization_status_0" name="cpd_settings[status]" value="0" type="radio"{if ($status <= 0)} checked="checked"{/if}><label for="customization_status_0">{l s='No' mod='customproductdesign'}</label>
                            <input id="customization_status_1" name="cpd_settings[status]" value="1" type="radio"{if ($status > 0)} checked="checked"{/if}><label for="customization_status_1">{l s='Yes' mod='customproductdesign'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- pdf generation -->
            <div class="form-group">
                <label class="form-group control-label col-lg-12">
                    <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Customization will be available to downlaod in a pdf file.' mod='customproductdesign'}">{l s='Enable PDF Download' mod='customproductdesign'}</span>
                </label>
                <div class="form-group margin-form">
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg ps-switch">
                            <input id="customization_pdf_0" name="cpd_settings[pdf]" value="0" type="radio"{if ($pdf <= 0)} checked="checked"{/if}><label for="customization_pdf_0">{l s='No' mod='customproductdesign'}</label>
                            <input id="customization_pdf_1" name="cpd_settings[pdf]" value="1" type="radio"{if ($pdf > 0)} checked="checked"{/if}><label for="customization_pdf_1">{l s='Yes' mod='customproductdesign'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group margin-form">
                <label class="form-group control-label col-lg-12">
                    <span title="" data-toggle="tooltip" class="label-tooltip">{l s='PDF Orientation' mod='customproductdesign'}</span>
                </label>
                <div class="form-group margin-form ">
                    <div class="col-lg-6">
                        <select class="form-control" name="cpd_settings[pdf_orientation]">
                            <option value="p" {if isset($pdf_orientation) && $pdf_orientation == 'p'}selected="selected"{/if}>
                                {l s='Vertical' mod='customproductdesign'}
                            </option>
                            <option value="l" {if isset($pdf_orientation) && $pdf_orientation == 'l'}selected="selected"{/if}>
                                {l s='Horizontal' mod='customproductdesign'}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h2>{l s='Enable/Disable Colors,Fonts,Images,Material for this design' mod='customproductdesign'}</h2>
        </div>
        <div class="padding_bottom"></div>
        <div class="col-lg-6">
            <!-- fonts -->
            <div class="form-group">
                <label class="control-label col-lg-12">{l s='Select Fonts' mod='customproductdesign'}</label>
                {include file ='./select_fonts.tpl'}
            </div>
            <!-- colors -->
            <div class="form-group">
                <label class="control-label col-lg-12">{l s='Select Colors' mod='customproductdesign'}</label>
                {include file ='./select_colors.tpl'}
            </div>
        </div>
        <div class="col-lg-6">
            <!-- images -->
            <div class="form-group">
                <label class="control-label col-lg-12">{l s='Select Images' mod='customproductdesign'}</label>
                {include file ='./select_images.tpl'}
            </div>
            <!-- materials -->
            <div class="form-group">
                <label class="control-label col-lg-12">{l s='Select Printing Material' mod='customproductdesign'}</label>
                {include file ='./select_materials.tpl'}
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    {if Tools::version_compare($ps_version, '1.7.0.0', '<')}
    <div class="panel-footer">
        <a href="{$the_link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default">
            <i class="process-icon-cancel"></i> {l s='Cancel' mod='customproductdesign'}
        </a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right">
            <i class="process-icon-save"></i> {l s='Save' mod='customproductdesign'}
        </button>&nbsp;
        <button type="submit" name="submitAddproductAndStay" style="margin-right:10px" class="btn btn-default pull-right">
            <i class="process-icon-save"></i> {l s='Save and stay' mod='customproductdesign'}
        </button>&nbsp;
    </div>
    {/if}
</div>
