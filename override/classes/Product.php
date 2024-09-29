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

class Product extends ProductCore
{
    /**
     * Price calculation / Get product price
     *
     * @param int $id_shop Shop id
     * @param int $id_product Product id
     * @param int $id_product_attribute Product attribute id
     * @param int $id_country Country id
     * @param int $id_state State id
     * @param string $zipcode
     * @param int $id_currency Currency id
     * @param int $id_group Group id
     * @param int $quantity Quantity Required for Specific prices : quantity discount application
     * @param bool $use_tax with (1) or without (0) tax
     * @param int $decimals Number of decimals returned
     * @param bool $only_reduc Returns only the reduction amount
     * @param bool $use_reduc Set if the returned amount will include reduction
     * @param bool $with_ecotax insert ecotax in price output
     * @param null $specific_price If a specific price applies regarding the previous parameters,
     *                             this variable is filled with the corresponding SpecificPrice object
     * @param bool $use_group_reduction
     * @param int $id_customer
     * @param bool $use_customer_price
     * @param int $id_cart
     * @param int $real_quantity
     *
     * @return float Product price
     **/
    public static function priceCalculation(
        $id_shop,
        $id_product,
        $id_product_attribute,
        $id_country,
        $id_state,
        $zipcode,
        $id_currency,
        $id_group,
        $quantity,
        $use_tax,
        $decimals,
        $only_reduc,
        $use_reduc,
        $with_ecotax,
        &$specific_price,
        $use_group_reduction,
        $id_customer = 0,
        $use_customer_price = true,
        $id_cart = 0,
        $real_quantity = 0,
        $id_customization = 0
    ) {
        $price = parent::priceCalculation(
            $id_shop,
            $id_product,
            $id_product_attribute,
            $id_country,
            $id_state,
            $zipcode,
            $id_currency,
            $id_group,
            $quantity,
            $use_tax,
            $decimals,
            $only_reduc,
            $use_reduc,
            $with_ecotax,
            $specific_price,
            $use_group_reduction,
            $id_customer,
            $use_customer_price,
            $id_cart,
            $real_quantity,
            $id_customization
        );
        if ($id_customization) {
            $designPrice = 0.0;
            if (Module::isInstalled('customproductdesign') && Module::isEnabled('customproductdesign')) {
                $designPrice = (float) Module::getInstanceByName('customproductdesign')->getDesignPrice(
                    $id_product,
                    $id_product_attribute,
                    $id_cart,
                    $id_customization
                );
            }
            $price += (float) $designPrice;
        }

        return $price;
    }

    /**
     * bug fix for PS 1.7.7.x
     */
    public static function getAllCustomizedDatas(
        $id_cart,
        $id_lang = null,
        $only_in_cart = true,
        $id_shop = null,
        $id_customization = null
    ) {
        if (true === (bool) Tools::version_compare(_PS_VERSION_, '1.7.7', '<')) {
            return parent::getAllCustomizedDatas(
                $id_cart,
                $id_lang,
                $only_in_cart,
                $id_shop,
                $id_customization
            );
        }
        if (!Customization::isFeatureActive()) {
            return false;
        }
        if (!$id_cart) {
            return false;
        }
        if ($id_customization === 0) {
            $product_customizations = (int) Db::getInstance()->getValue('
                SELECT COUNT(`id_customization`) FROM `' . _DB_PREFIX_ . 'cart_product`
                WHERE `id_cart` = ' . (int) $id_cart .
                ' AND `id_customization` != 0');
            if ($product_customizations) {
                return false;
            }
        }
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }
        if (Shop::isFeatureActive() && !$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT cd.`id_customization`, c.`id_address_delivery`, c.`id_product`, cfl.`id_customization_field`, c.`id_product_attribute`,
        cd.`type`, cd.`index`, cd.`value`, cd.`id_module`, cfl.`name`
        FROM `' . _DB_PREFIX_ . 'customized_data` cd
        NATURAL JOIN `' . _DB_PREFIX_ . 'customization` c';

        if (version_compare(_PS_VERSION_, '8.1.3', '<')) {
            $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . 'customization_field_lang` cfl ON (id_lang = ' . (int) $id_lang . ')';
        } else {
            $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . 'customization_field_lang` cfl ON (cfl.id_customization_field = cd.`index` AND id_lang = ' . (int) $id_lang .
            ($id_shop ? ' AND cfl.`id_shop` = ' . (int) $id_shop : '') . ')';
        }

        $sql .= 'WHERE c.`id_cart` = ' . (int) $id_cart .
        ($only_in_cart ? ' AND c.`in_cart` = 1' : '') .
        ((int) $id_customization ? ' AND cd.`id_customization` = ' . (int) $id_customization : '') . '
            ORDER BY `id_product`, `id_product_attribute`, `type`, `index`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }
        $customized_datas = [];
        foreach ($result as $row) {
            if ((int) $row['id_module'] && (int) $row['type'] == Product::CUSTOMIZE_TEXTFIELD) {
                $row['value'] = Hook::exec('displayCustomization', ['customization' => $row], (int) $row['id_module']);
            }
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['datas'][(int) $row['type']][] = $row;
        }
        if (!$result = Db::getInstance()->executeS(
            'SELECT `id_product`, `id_product_attribute`, `id_customization`, `id_address_delivery`, `quantity`, `quantity_refunded`, `quantity_returned`
            FROM `' . _DB_PREFIX_ . 'customization`
            WHERE `id_cart` = ' . (int) $id_cart .
            ((int) $id_customization ? ' AND `id_customization` = ' . (int) $id_customization : '') .
            ($only_in_cart ? ' AND `in_cart` = 1' : '')
        )) {
            return false;
        }
        foreach ($result as $row) {
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['quantity'] = (int) $row['quantity'];
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['quantity_refunded'] = (int) $row['quantity_refunded'];
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['quantity_returned'] = (int) $row['quantity_returned'];
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['id_customization'] = (int) $row['id_customization'];
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['name'] = 'File';
        }

        return $customized_datas;
    }
}
