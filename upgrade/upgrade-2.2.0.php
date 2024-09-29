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

function upgrade_module_2_2_0($module)
{
    if (columnExist('id_customer') && columnExist('id_guest')) {
        return true;
    } else {
        Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'logo`
            ADD `id_customer`   INT(10) NOT NULL DEFAULT 0,
            ADD `id_guest`      INT(10) NOT NULL DEFAULT 0'
        );
    }

    return $module->registerHook('actionAuthentication')
        && $module->registerHook('actionCustomerAccountAdd')
        && $module->registerHook('actionObjectDeleteAfter')
        && $module->registerHook('actionDeleteGDPRCustomer')
        && $module->registerHook('actionExportGDPRData');
}

function columnExist($column_name)
{
    $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
        WHERE table_schema = "' . _DB_NAME_ . '" AND table_name = "' . _DB_PREFIX_ . 'logo"');
    if (isset($columns) && $columns) {
        foreach ($columns as $column) {
            if ($column['COLUMN_NAME'] == $column_name) {
                return true;
            }
        }
    }

    return false;
}
