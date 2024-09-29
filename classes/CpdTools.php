<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file.
 * You are not authorized to modify, copy or redistribute this file.
 * Permissions are reserved by Satoshi Brasileiro.
 *
 *  @author    Satoshi Brasileiro
 *  @copyright Satoshi Brasileiro 2024
 *  @license   Single domain
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class CpdTools
{
    /**
     * Get all values from $_POST/$_GET
     *
     * @return mixed
     */
    public static function getAllValues()
    {
        return $_POST + $_GET;
    }

    /**
     * Adds suppliers from old product onto a newly duplicated product
     *
     * @param int $id_product_old
     * @param int $id_product_new
     */
    public static function duplicateSuppliers($id_product_old, $id_product_new)
    {
        $result = Db::getInstance()->executeS('
        SELECT *
        FROM `' . _DB_PREFIX_ . 'product_supplier`
        WHERE `id_product` = ' . (int) $id_product_old . ' AND `id_product_attribute` = 0');

        foreach ($result as $row) {
            unset($row['id_product_supplier']);
            $row['id_product'] = $id_product_new;
            if (!Db::getInstance()->insert('product_supplier', $row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return price converted
     *
     * @param float $price Product price
     * @param object|array $currency Current currency object
     * @param bool $to_currency convert to currency or from currency to default currency
     * @param Context $context
     *
     * @return float Price
     */
    public static function convertPrice($price, $currency = null, $to_currency = true, Context $context = null)
    {
        static $default_currency = null;

        if ($default_currency === null) {
            $default_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        }

        if (!$context) {
            $context = Context::getContext();
        }
        if ($currency === null) {
            $currency = $context->currency;
        } elseif (is_numeric($currency)) {
            $currency = Currency::getCurrencyInstance($currency);
        }

        $c_id = (is_array($currency) ? $currency['id_currency'] : $currency->id);
        $c_rate = (is_array($currency) ? $currency['conversion_rate'] : $currency->conversion_rate);

        if ($c_id != $default_currency) {
            if ($to_currency) {
                $price *= $c_rate;
            } else {
                $price /= $c_rate;
            }
        }

        return $price;
    }
}
