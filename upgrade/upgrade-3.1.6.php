<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file.
 * You are not authorized to modify, copy or redistribute this file.
 * Permissions are reserved by Satoshi Brasileiro.
 *
 *  @author    Satoshi Brasileiro
 *  @copyright Satoshi Brasileiro 2021
 *  @license   Single domain
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_3_1_6($module)
{
    $tab = new Tab();
    $tab->class_name = 'AdminDesignBulkActions';
    $tab->id_parent = 0;
    $tab->module = 'customproductdesign';
    $tab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = 'Custom Product Designs';
    $tab->add();

    $subtab = new Tab();
    $subtab->class_name = 'AdminDesignBulk';
    $subtab->id_parent = Tab::getIdFromClassName('AdminDesignBulkActions');
    $subtab->module = 'customproductdesign';
    $subtab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = 'Custom Product Designs';
    if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
        $subtab->icon = 'loop';
    }
    $subtab->add();

    return true;
}
