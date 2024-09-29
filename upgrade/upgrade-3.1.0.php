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

function upgrade_module_3_1_0($module)
{
    if (columnExist('tags', 'logo')) {
        return true;
    } else {
        return Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'logo`
            ADD `tags` varchar(255) DEFAULT NULL'
        );
    }
}

function columnExist($column_name, $table)
{
    $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
        WHERE table_schema = "' . _DB_NAME_ . '" AND table_name = "' . _DB_PREFIX_ . $table . '"');
    if (isset($columns) && $columns) {
        foreach ($columns as $column) {
            if ($column['COLUMN_NAME'] == $column_name) {
                return true;
            }
        }
    }

    return false;
}
