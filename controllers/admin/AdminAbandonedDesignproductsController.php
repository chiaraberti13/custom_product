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

class AdminAbandonedDesignproductsController extends AdminController
{
    protected $max_file_size;
    protected $max_image_size;

    protected $_category;
    /**
     * @var string name of the tab to display
     */
    protected $tab_display;
    protected $tab_display_module;

    /**
     * The order in the array decides the order in the list of tab. If an element's value is a number, it will be preloaded.
     * The tabs are preloaded from the smallest to the highest number.
     *
     * @var array product tabs
     */
    protected $available_tabs = [];

    protected $default_tab = 'Informations';

    protected $available_tabs_lang = [];

    protected $position_identifier = 'id_product';

    protected $submitted_tabs;

    protected $id_current_category;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'product';
        $this->className = 'Product';
        $this->lang = true;
        $this->explicitSelect = true;
        $this->context = Context::getContext();

        parent::__construct();

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?'),
            ],
        ];
        if (!Tools::getValue('id_product')) {
            $this->multishop_context_group = false;
        }

        $this->imageType = 'jpg';
        $this->_defaultOrderBy = $this->identifier;
        $this->max_file_size = (int) (Configuration::get('PS_LIMIT_UPLOAD_FILE_VALUE') * 1000000);
        $this->max_image_size = (int) Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE');
        $this->allow_export = true;

        // @since 1.5 : translations for tabs
        $this->available_tabs_lang = [
            'Informations' => $this->l('Information'),
            'Pack' => $this->l('Pack'),
            'VirtualProduct' => $this->l('Virtual Product'),
            'Prices' => $this->l('Prices'),
            'Seo' => $this->l('SEO'),
            'Images' => $this->l('Images'),
            'Associations' => $this->l('Associations'),
            'Shipping' => $this->l('Shipping'),
            'Combinations' => $this->l('Combinations'),
            'Features' => $this->l('Features'),
            'Customization' => $this->l('Customization'),
            'Attachments' => $this->l('Attachments'),
            'Quantities' => $this->l('Quantities'),
            'Suppliers' => $this->l('Suppliers'),
            'Warehouses' => $this->l('Warehouses'),
        ];

        $this->available_tabs = ['Quantities' => 6, 'Warehouses' => 14];
        if ($this->context->shop->getContext() != Shop::CONTEXT_GROUP) {
            $this->available_tabs = array_merge($this->available_tabs, [
                'Informations' => 0,
                'Pack' => 7,
                'VirtualProduct' => 8,
                'Prices' => 1,
                'Seo' => 2,
                'Associations' => 3,
                'Images' => 9,
                'Shipping' => 4,
                'Combinations' => 5,
                'Features' => 10,
                'Customization' => 11,
                'Attachments' => 12,
                'Suppliers' => 13,
            ]);
        }

        // Sort the tabs that need to be preloaded by their priority number
        asort($this->available_tabs, SORT_NUMERIC);

        /* Adding tab if modules are hooked */
        $modules_list = Hook::getHookModuleExecList('displayAdminProductsExtra');
        if (is_array($modules_list) && count($modules_list) > 0) {
            foreach ($modules_list as $m) {
                $this->available_tabs['Module' . Tools::ucfirst($m['module'])] = 23;
                $this->available_tabs_lang['Module' . Tools::ucfirst($m['module'])] = Module::getModuleName($m['module']);
            }
        }

        if (Tools::getValue('reset_filter_category')) {
            $this->context->cookie->id_category_products_filter = false;
        }
        if (Shop::isFeatureActive() && $this->context->cookie->id_category_products_filter) {
            $category = new Category((int) $this->context->cookie->id_category_products_filter);
            if (!$category->inShop()) {
                $this->context->cookie->id_category_products_filter = false;
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminProducts'));
            }
        }
        /* Join categories table */
        if ($id_category = (int) Tools::getValue('productFilter_cl!name')) {
            $this->_category = new Category((int) $id_category);
            $_POST['productFilter_cl!name'] = $this->_category->name[$this->context->language->id];
        } else {
            if ($id_category = (int) Tools::getValue('id_category')) {
                $this->id_current_category = $id_category;
                $this->context->cookie->id_category_products_filter = $id_category;
            } elseif ($id_category = $this->context->cookie->id_category_products_filter) {
                $this->id_current_category = $id_category;
            }
            if ($this->id_current_category) {
                $this->_category = new Category((int) $this->id_current_category);
            } else {
                $this->_category = new Category();
            }
        }

        $join_category = false;
        if (Validate::isLoadedObject($this->_category) && empty($this->_filter)) {
            $join_category = true;
        }

        if (Tools::version_compare(_PS_VERSION_, '1.6.1.0', '<')) {
            $this->_join .= '
            LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = a.`id_product`)
            LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sav ON (sav.`id_product` = a.`id_product` AND sav.`id_product_attribute` = 0
            ' . StockAvailable::addSqlShopRestriction(null, null, 'sav') . ') ';

            $alias = 'sa';
            $alias_image = 'image_shop';

            $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP ? (int) $this->context->shop->id : 'a.id_shop_default';
            $this->_join .= ' JOIN `' . _DB_PREFIX_ . 'product_shop` sa ON (a.`id_product` = sa.`id_product` AND sa.id_shop = ' . $id_shop . ')
                    LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (' . $alias . '.`id_category_default` = cl.`id_category` AND b.`id_lang` = cl.`id_lang` AND cl.id_shop = ' . $id_shop . ')
                    LEFT JOIN `' . _DB_PREFIX_ . 'shop` shop ON (shop.id_shop = ' . $id_shop . ') 
                    LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_image` = i.`id_image` AND image_shop.`cover` = 1 AND image_shop.id_shop = ' . $id_shop . ')';

            $this->_select .= 'shop.name as shopname, a.id_shop_default, ';
            $this->_select .= 'MAX(' . $alias_image . '.id_image) id_image, cl.name `name_category`, ' . $alias . '.`price`, 0 AS price_final, sav.`quantity` as sav_quantity, ' . $alias . '.`active`, IF(sav.`quantity`<=0, 1, 0 ) badge_danger';
        } else {
            $this->_join .= '
            LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sav ON (sav.`id_product` = a.`id_product` AND sav.`id_product_attribute` = 0
            ' . StockAvailable::addSqlShopRestriction(null, null, 'sav') . ') ';

            $alias = 'sa';
            $alias_image = 'image_shop';

            $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP ? (int) $this->context->shop->id : 'a.id_shop_default';
            $this->_join .= ' JOIN `' . _DB_PREFIX_ . 'product_shop` sa ON (a.`id_product` = sa.`id_product` AND sa.id_shop = ' . $id_shop . ')
                    LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (' . $alias . '.`id_category_default` = cl.`id_category` AND b.`id_lang` = cl.`id_lang` AND cl.id_shop = ' . $id_shop . ')
                    LEFT JOIN `' . _DB_PREFIX_ . 'shop` shop ON (shop.id_shop = ' . $id_shop . ')
                    LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = a.`id_product` AND image_shop.`cover` = 1 AND image_shop.id_shop = ' . $id_shop . ')
                    LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_image` = image_shop.`id_image`)
                    LEFT JOIN `' . _DB_PREFIX_ . 'product_download` pd ON (pd.`id_product` = a.`id_product` AND pd.`active` = 1)';

            $this->_select .= 'shop.`name` AS `shopname`, a.`id_shop_default`, ';
            $this->_select .= $alias_image . '.`id_image` AS `id_image`, cl.`name` AS `name_category`, ' . $alias . '.`price`, 0 AS `price_final`, a.`is_virtual`, pd.`nb_downloadable`, sav.`quantity` AS `sav_quantity`, ' . $alias . '.`active`, IF(sav.`quantity`<=0, 1, 0) AS `badge_danger`';
        }
        // custom query
        $this->_select .= ', cd.`date_add`';
        $this->_join .= 'LEFT JOIN `' . _DB_PREFIX_ . 'customized_cart_products` cd ON (a.`id_product` = cd.`cpd_id_product` AND cd.id_order = 0)';
        $this->_where .= 'AND a.`id_product` IN (SELECT `cpd_id_product` FROM `' . _DB_PREFIX_ . 'customized_cart_products` WHERE id_order = 0)
        AND TIME_TO_SEC(TIMEDIFF(NOW(), cd.`date_add`)) >= 86400';

        if ($join_category) {
            $this->_join .= ' INNER JOIN `' . _DB_PREFIX_ . 'category_product` cp ON (cp.`id_product` = a.`id_product` AND cp.`id_category` = ' . (int) $this->_category->id . ') ';
            $this->_select .= ' , cp.`position`, ';
        }

        $this->_use_found_rows = false;
        $this->_group = '';

        $this->fields_list = [];
        $this->fields_list['id_product'] = [
            'title' => $this->l('ID'),
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'type' => 'int',
        ];
        $this->fields_list['image'] = [
            'title' => $this->l('Image'),
            'align' => 'center',
            'image' => 'p',
            'orderby' => false,
            'filter' => false,
            'search' => false,
        ];
        $this->fields_list['name'] = [
            'title' => $this->l('Name'),
            'filter_key' => 'b!name',
        ];
        $this->fields_list['reference'] = [
            'title' => $this->l('Reference'),
            'align' => 'left',
        ];

        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            $this->fields_list['shopname'] = [
                'title' => $this->l('Default shop'),
                'filter_key' => 'shop!name',
            ];
        } else {
            $this->fields_list['name_category'] = [
                'title' => $this->l('Category'),
                'filter_key' => 'cl!name',
            ];
        }
        $this->fields_list['price'] = [
            'title' => $this->l('Base price'),
            'type' => 'price',
            'align' => 'text-right',
            'filter_key' => 'a!price',
        ];

        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $this->fields_list['sav_quantity'] = [
                'title' => $this->l('Quantity'),
                'type' => 'int',
                'align' => 'center',
                'filter_key' => 'sav!quantity',
                'orderby' => true,
                'badge_danger' => true,
            ];
        }

        $this->fields_list['active'] = [
            'title' => $this->l('Status'),
            'active' => 'status',
            'filter_key' => $alias . '!active',
            'align' => 'text-center',
            'type' => 'bool',
            'class' => 'fixed-width-sm',
            'orderby' => false,
        ];

        $this->fields_list['date_add'] = [
            'title' => $this->l('Date Added to cart'),
            'filter_key' => $alias . '!active',
            'align' => 'text-center',
            'type' => 'datetime',
            'class' => 'fixed-width-sm',
            'orderby' => false,
        ];

        if ($join_category && (int) $this->id_current_category) {
            $this->fields_list['position'] = [
                'title' => $this->l('Position'),
                'filter_key' => 'cp!position',
                'align' => 'center',
                'position' => 'position',
            ];
        }
    }

    public function initProcess()
    {
        if (Tools::getIsset('updateproduct') && Tools::getIsset('id_product') && (int) Tools::getValue('id_product')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminProducts') . '&updateproduct&id_product=' . (int) Tools::getValue('id_product'));
        }
        parent::initProcess();
    }

    public function renderKpis()
    {
        return false;
    }

    public function renderList()
    {
        $this->addRowAction('delete');
        $this->list_no_link = true;

        return parent::renderList();
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
        $this->displayWarning($this->l('This is the list of all unordered design products. We recommend you to remove all abandoned design products to maximize your shop performance.'));
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        $orderByPriceFinal = (empty($orderBy) ? ($this->context->cookie->__get($this->table . 'Orderby') ? $this->context->cookie->__get($this->table . 'Orderby') : 'id_' . $this->table) : $orderBy);
        $orderWayPriceFinal = (empty($orderWay) ? ($this->context->cookie->__get($this->table . 'Orderway') ? $this->context->cookie->__get($this->table . 'Orderby') : 'ASC') : $orderWay);
        if ($orderByPriceFinal == 'price_final') {
            $orderBy = 'id_' . $this->table;
            $orderWay = 'ASC';
        }
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);

        /* update product quantity with attributes ... */
        $nb = count($this->_list);
        if ($this->_list) {
            $context = $this->context->cloneContext();
            $context->shop = clone $context->shop;
            /* update product final price */
            for ($i = 0; $i < $nb; ++$i) {
                if (isset($this->_list[$i]['id_product']) && $this->_list[$i]['id_product']) {
                    if (Context::getContext()->shop->getContext() != Shop::CONTEXT_SHOP) {
                        $context->shop = new Shop((int) $this->_list[$i]['id_shop_default']);
                    }
                    $nothing = null;
                    // convert price with the currency from context
                    $this->_list[$i]['price'] = Tools::convertPrice($this->_list[$i]['price'], $this->context->currency, true, $this->context);
                    $this->_list[$i]['price_tmp'] = Product::getPriceStatic(
                        $this->_list[$i]['id_product'],
                        true,
                        null,
                        (int) Configuration::get('PS_PRICE_DISPLAY_PRECISION'),
                        null,
                        false,
                        true,
                        1,
                        true,
                        null,
                        null,
                        null,
                        $nothing,
                        true,
                        true,
                        $context
                    );
                }
            }
        }

        if ($orderByPriceFinal == 'price_final') {
            if (Tools::strtolower($orderWayPriceFinal) == 'desc') {
                uasort($this->_list, 'cmpPriceDesc');
            } else {
                uasort($this->_list, 'cmpPriceAsc');
            }
        }
        for ($i = 0; $this->_list && $i < $nb; ++$i) {
            if (isset($this->_list[$i]['price_tmp']) && $this->_list[$i]['price_tmp']) {
                $this->_list[$i]['price_final'] = $this->_list[$i]['price_tmp'];
                unset($this->_list[$i]['price_tmp']);
            }
        }
    }
}
