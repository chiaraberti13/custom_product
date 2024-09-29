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

include_once dirname(__FILE__) . '/classes/Designer.php';
include_once dirname(__FILE__) . '/classes/CpdTools.php';
include_once dirname(__FILE__) . '/classes/DesignTags.php';
include_once dirname(__FILE__) . '/include/FontInfo.class.php';
include_once dirname(__FILE__) . '/classes/ProductCustomization.php';
class CustomProductDesign extends Module
{
    public $id_shop;
    public $id_shop_group;
    public $field_labels = [];

    public function __construct()
    {
        $this->name = 'customproductdesign';
        $this->tab = 'front_office_features';
        $this->version = '3.2.2';
        $this->author = 'FMM Modules';
        $this->bootstrap = true;
        $this->module_key = 'fce6f1c4fff3b8abcf601b9a25cc2471';
        $this->author_address = '0xcC5e76A6182fa47eD831E43d80Cd0985a14BB095';

        parent::__construct();
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
        $this->displayName = $this->l('Custom Product Designs');
        $this->description = $this->l('This module allows your customers to customize a product.');
        $this->field_labels = [
            'print_material' => $this->l('Material'),
            'design_preview' => $this->l('Design'),
        ];

        if ($this->id_shop === null || !Shop::isFeatureActive()) {
            $this->id_shop = Shop::getContextShopID();
        } else {
            $this->id_shop = $this->context->shop->id;
        }
        if ($this->id_shop_group === null || !Shop::isFeatureActive()) {
            $this->id_shop_group = Shop::getContextShopGroupID();
        } else {
            $this->id_shop_group = $this->context->shop->id_shop_group;
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $this->initConfigurations();
        include dirname(__FILE__) . '/sql/install.php';
        if (!parent::install()
            || !$this->registerHook('header')
            || !$this->registerHook('newOrder')
            || !$this->registerHook('ModuleRoutes')
            || !$this->registerHook('productfooter')
            || !$this->registerHook('actionCartSave')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('actionProductDelete')
            || !$this->registerHook('actionAuthentication')
            || !$this->registerHook('actionCustomerAccountAdd')
            || !$this->registerHook('displayProductButtons')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionObjectDeleteAfter')
            || !$this->registerHook('actionBeforeCartUpdateQty')
            || !$this->registerHook('actionObjectProductUpdateAfter')
             /* GDPR compliant hooks */
            || !$this->registerHook('actionDeleteGDPRCustomer')
            || !$this->registerHook('actionExportGDPRData')
            || !$this->addAdminTabs()) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        $this->deleteConfiguration();
        include dirname(__FILE__) . '/sql/uninstall.php';
        $this->removeTab();
        if (parent::uninstall()
            && $this->unregisterHook('header')
            && $this->unregisterHook('newOrder')
            && $this->unregisterHook('ModuleRoutes')
            && $this->unregisterHook('productfooter')
            && $this->unregisterHook('actionCartSave')
            && $this->unregisterHook('backOfficeHeader')
            && $this->unregisterHook('actionProductDelete')
            && $this->unregisterHook('displayProductButtons')
            && $this->unregisterHook('displayAdminProductsExtra')
            && $this->unregisterHook('hookActionBeforeCartUpdateQty')
            && $this->unregisterHook('actionObjectProductUpdateAfter')
            && $this->unregisterHook('actionAuthentication')
            && $this->unregisterHook('actionObjectDeleteAfter')
            && $this->unregisterHook('actionCustomerAccountAdd')
            && $this->unregisterHook('actionDeleteGDPRCustomer')
            && $this->unregisterHook('actionExportGDPRData')
            && $this->removeAdminTabs()) {
            return true;
        }

        return false;
    }

    private function addTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminDesignBulkActions';
        $tab->id_parent = 0;
        $tab->module = $this->name;
        $tab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->displayName;
        $tab->add();

        $subtab = new Tab();
        $subtab->class_name = 'AdminDesignBulk';
        $subtab->id_parent = Tab::getIdFromClassName('AdminDesignBulkActions');
        $subtab->module = $this->name;
        $subtab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->displayName;
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $subtab->icon = 'loop';
        }
        $subtab->add();

        return true;
    }

    private function removeTab()
    {
        $res = true;
        $id_tab = Tab::getIdFromClassName('AdminDesignBulkActions');
        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $res &= $tab->delete();
        }

        $id_tab1 = Tab::getIdFromClassName('AdminDesignBulk');
        if ($id_tab1 != 0) {
            $tab = new Tab($id_tab1);
            $res &= $tab->delete();
        }

        return $res;
    }

    public function installOverrides()
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return true;
        } else {
            return parent::installOverrides();
        }
    }

    public function uninstallOverrides()
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return true;
        } else {
            return parent::uninstallOverrides();
        }
    }

    protected function addAdminTabs()
    {
        // Action Controller
        $languages = Language::getLanguages(true);
        $cpd_tab = new Tab();
        foreach ($languages as $lang) {
            $cpd_tab->name[$lang['id_lang']] = 'Action Product Designer';
        }
        $cpd_tab->class_name = 'CustomProductDesigner';
        $cpd_tab->id_parent = -1;
        $cpd_tab->active = 1;
        $cpd_tab->module = $this->name;

        // backward compatibility
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            $subtab = new Tab();
            $subtab->class_name = 'AdminAbandonedDesignproducts';
            $subtab->id_parent = Tab::getInstanceFromClassName('AdminProducts')->id_parent;
            $subtab->module = $this->name;
            $subtab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->l('Abandoned Designs');
            $subtab->add();
        }

        if (!$cpd_tab->add()) {
            return false;
        }

        return true;
    }

    private function removeAdminTabs()
    {
        $id_cpdTab = Tab::getIdFromClassName('CustomProductDesigner');
        if ($id_cpdTab != 0) {
            $cpdTab = new Tab($id_cpdTab);
            $cpdTab->delete();
        }
        // backward compatibility
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            $subtab = Tab::getIdFromClassName('AdminAbandonedDesignproducts');
            if ($subtab != 0) {
                $tab = new Tab($subtab);
                $tab->delete();
            }
        }

        return true;
    }

    protected function initConfigurations()
    {
        Configuration::updateValue('DESIGN_PREVIEW_WIDTH', 100, false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('DESIGN_PREVIEW_HEIGHT', 100, false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('LOGO_UPLOAD_EN_DS', 1, false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('LOGO_UPLOAD_URL', 1, false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('DEFAULT_CUSTOM_COLOR', 'default', false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('DEFAULT_CUSTOM_FONT', 'default', false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('CPD_WATERMARK_TEXTCLR', '#333333', false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('CPD_WATERMARK_SIZE', 50, false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('CPD_WATERMARK_ACTIVE', 1, false, $this->id_shop_group, $this->id_shop);
        Configuration::updateValue('CPD_WATERMARK_TEXT', [$this->context->language->id => Configuration::get('PS_SHOP_NAME')], $this->id_shop_group, $this->id_shop);

        return true;
    }

    protected function deleteConfiguration()
    {
        Configuration::deleteByName('DESIGN_PREVIEW_WIDTH');
        Configuration::deleteByName('DESIGN_PREVIEW_HEIGHT');
        Configuration::deleteByName('LOGO_UPLOAD_EN_DS');
        Configuration::deleteByName('LOGO_UPLOAD_URL');
        Configuration::deleteByName('DEFAULT_CUSTOM_COLOR');
        Configuration::deleteByName('DEFAULT_CUSTOM_FONT');
        Configuration::deleteByName('CPD_WATERMARK_TEXTCLR');
        Configuration::deleteByName('CPD_WATERMARK_SIZE');
        Configuration::deleteByName('CPD_WATERMARK_ACTIVE');
        Configuration::deleteByName('CPD_WATERMARK_TEXT');

        return true;
    }

    public function getContent()
    {
        $this->html = $this->display(__FILE__, 'views/templates/hook/info.tpl');
        if (Tools::isSubmit('add_new_color') || Tools::isSubmit('updatecolour')) {
            $color = '';
            $id_product = (int) Tools::getValue('id_product');
            if (Tools::isSubmit('updatecolour')) {
                $id_color = (int) Tools::getValue('id_colour');
                $color = ProductCustomization::getColorById($id_color);
            }

            $current_index = $this->context->link->getAdminLink('AdminModules', false);
            $current_token = Tools::getAdminTokenLite('AdminModules');
            $action_link = $current_index . '&configure=' . $this->name . '&token=' . $current_token . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
            $this->context->smarty->assign([
                'action_link' => $action_link,
                'id_product' => (int) $id_product,
                'color' => $color,
            ]);

            return $this->display(__FILE__, 'views/templates/admin/tab_content/add_colour.tpl');
        } elseif (Tools::isSubmit('add_new_material') || Tools::isSubmit('updatematerial')) {
            $material = '';
            $id_product = (int) Tools::getValue('id_product');
            $current_index = $this->context->link->getAdminLink('AdminModules', false);
            $current_token = Tools::getAdminTokenLite('AdminModules');
            $action_link = $current_index . '&configure=' . $this->name . '&token=' . $current_token . '&tab_module=' . $this->tab . '&module_name=' . $this->name;

            if (Tools::isSubmit('updatematerial')) {
                $id_material = (int) Tools::getValue('id_material');
                $material = ProductCustomization::getMaterialById($id_material);
            }
            $this->context->smarty->assign([
                'action_link' => $action_link,
                'id_product' => (int) $id_product,
                'material' => $material,
                'iso_code' => $this->context->currency->iso_code,
            ]);

            return $this->display(__FILE__, 'views/templates/admin/tab_content/add_material.tpl');
        } elseif (Tools::isSubmit('add_new_font')) {
            $current_index = $this->context->link->getAdminLink('AdminModules', false);
            $current_token = Tools::getAdminTokenLite('AdminModules');
            $action_link = $current_index . '&configure=' . $this->name . '&token=' . $current_token . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
            $this->context->smarty->assign([
                'action_link' => $action_link,
            ]);

            return $this->display(__FILE__, 'views/templates/admin/tab_content/add_font.tpl');
        } elseif (Tools::isSubmit('add_new_logo')) {
            $id_product = (int) Tools::getValue('id_product');
            $current_index = $this->context->link->getAdminLink('AdminModules', false);
            $current_token = Tools::getAdminTokenLite('AdminModules');
            $action_link = $current_index . '&configure=' . $this->name . '&token=' . $current_token . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
            $this->context->smarty->assign([
                'action_link' => $action_link,
                'id_product' => (int) $id_product,
            ]);

            return $this->display(__FILE__, 'views/templates/admin/tab_content/logo_upload.tpl');
        }
        $this->postProcess();

        return $this->html . $this->displayForm();
    }

    public function displayForm()
    {
        $color = '';
        $current_index = $this->context->link->getAdminLink('AdminModules', false);
        $current_token = Tools::getAdminTokenLite('AdminModules');
        if (Tools::isSubmit('updatecolour')) {
            $id_color = (int) Tools::getValue('id_colour');
            $color = ProductCustomization::getColorById($id_color);
        }

        $id_product = (int) Tools::getValue('id_product');
        $action_link = $current_index . '&configure=' . $this->name . '&token=' . $current_token . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $cover = Product::getCover($id_product);
        $product = new Product((int) $id_product, true, $this->context->language->id);
        $images = Image::getImages($this->context->language->id, $product->id);
        if (is_array($images)) {
            foreach ($images as $k => $image) {
                $images[$k]['src'] = $this->context->link->getImageLink($product->link_rewrite, $product->id . '-' . $image['id_image'], ImageType::getFormatedName('thickbox'));
            }
        }
        $dynamic_pricing = ProductCustomization::getDynamicPrices();
        $design_templates = Designer::getAllTemplates();
        $this->context->smarty->assign([
            'version' => _PS_VERSION_,
            'current_index' => $current_index,
            'current_token' => $current_token,
            'action_link' => $action_link,
            'images' => $images,
            'cover' => $cover,
            'color' => $color,
            'link' => $this->context->link,
            'id_lang' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'languages' => Language::getLanguages(false),
            'currency' => $this->context->currency,
            'fonts' => ProductCustomization::getFonts(),
            'logos' => ProductCustomization::getLogos(),
            'colors' => ProductCustomization::getColors(),
            'materials' => ProductCustomization::getMaterials(false, null, $this->id_shop, $this->id_shop_group),
            'CPD_WATERMARK_TEXT' => $this->getConfigFieldsValues(),
            'LOGO_UPLOAD_EN_DS' => (int) Configuration::get('LOGO_UPLOAD_EN_DS', null, $this->id_shop_group, $this->id_shop),
            'LOGO_UPLOAD_URL' => (int) Configuration::get('LOGO_UPLOAD_URL', null, $this->id_shop_group, $this->id_shop),
            'CPD_ENABLE_PRE_DESIGNS' => (int) Configuration::get('CPD_ENABLE_PRE_DESIGNS', null, $this->id_shop_group, $this->id_shop),
            'CPD_ENABLE_PRE_DESIGNS_RELATIVE' => (int) Configuration::get('CPD_ENABLE_PRE_DESIGNS_RELATIVE', null, $this->id_shop_group, $this->id_shop),
            'DEFAULT_CUSTOM_COLOR' => Configuration::get('DEFAULT_CUSTOM_COLOR', null, $this->id_shop_group, $this->id_shop),
            'DEFAULT_CUSTOM_FONT' => Configuration::get('DEFAULT_CUSTOM_FONT', null, $this->id_shop_group, $this->id_shop),
            'CPD_ENABLE_DYNAMIC_PRICING' => Configuration::get('CPD_ENABLE_DYNAMIC_PRICING', null, $this->id_shop_group, $this->id_shop),
            'CPD_ENABLE_LAYERS_SECTION' => Configuration::get('CPD_ENABLE_LAYERS_SECTION', null, $this->id_shop_group, $this->id_shop),
            'DESIGN_PREVIEW_WIDTH' => (int) Configuration::get('DESIGN_PREVIEW_WIDTH', null, $this->id_shop_group, $this->id_shop),
            'DESIGN_PREVIEW_HEIGHT' => (int) Configuration::get('DESIGN_PREVIEW_HEIGHT', null, $this->id_shop_group, $this->id_shop),
            'CPD_WATERMARK_TEXTCLR' => Configuration::get('CPD_WATERMARK_TEXTCLR', false, $this->id_shop_group, $this->id_shop),
            'CPD_WATERMARK_SIZE' => Configuration::get('CPD_WATERMARK_SIZE', false, $this->id_shop_group, $this->id_shop),
            'CPD_WATERMARK_ACTIVE' => Configuration::get('CPD_WATERMARK_ACTIVE', false, $this->id_shop_group, $this->id_shop),
            'CPD_HINTS_BLK' => Configuration::get('CPD_HINTS_BLK', false, $this->id_shop_group, $this->id_shop),
            'CPD_MATERIALS_MANDATORY' => Configuration::get('CPD_MATERIALS_MANDATORY', false, $this->id_shop_group, $this->id_shop),
            'font_list' => $this->displayTable($this->getFontParams()),
            'colors_list' => $this->displayTable($this->getColorParams()),
            'image_list' => $this->displayTable($this->getImageParams()),
            'material_list' => $this->displayTable($this->getMaterialParams()),
            'dynamic_pricing' => $dynamic_pricing,
            'design_templates' => $design_templates,
        ]
        );

        return $this->display(__FILE__, 'views/templates/admin/config.tpl');
    }

    private function postProcess()
    {
        $id_shop = (int) $this->context->shop->id;
        $current_index = $this->context->link->getAdminLink('AdminModules', false);
        $current_token = Tools::getAdminTokenLite('AdminModules');
        $redirect_link = $current_index . '&configure=' . $this->name . '&token=' . $current_token . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        if (Tools::isSubmit('upload_fonts')) {
            $ext = (isset($_FILES, $_FILES['fonts'])) ? (string) pathinfo($_FILES['fonts']['name'], PATHINFO_EXTENSION) : '';
            if (!empty($ext) && $ext == 'ttf') {
                $path = _PS_MODULE_DIR_ . 'customproductdesign/data/fonts/';
                $temp_name = $_FILES['fonts']['tmp_name'];
                $file_name = $_FILES['fonts']['name'];

                if (move_uploaded_file($temp_name, $path . $file_name)) {
                    $obj_font_info = new FontInfo($path . $file_name);
                    $font_name = (string) $obj_font_info->getFontName();

                    $fonts = [
                        'font_name' => (string) $font_name,
                        'font_path' => __PS_BASE_URI__ . 'modules/customproductdesign/data/fonts/' . $file_name,
                        'status' => 1,
                        'date_add' => date('Y-m-d H:i:s'),
                        'id_shop' => (int) $id_shop,
                    ];
                    if (ProductCustomization::addFonts($fonts)) {
                        Tools::redirectAdmin($redirect_link . '&conf=18&tab=fonts');
                    } else {
                        $this->context->controller->errors[] = $this->l('Upload error');
                    }
                } else {
                    $this->context->controller->errors[] = $this->l('Permission error..Access Denied');
                }
            } else {
                $this->context->controller->errors[] = $this->l('Invalid file type');
            }
        } elseif (Tools::isSubmit('upload_logo')) {
            $tags = Tools::getValue('tags');
            // dump($tags);exit;
            if (isset($_FILES['logo']) && is_array($_FILES['logo']['name'])) {
                foreach ($_FILES['logo']['name'] as $key => $image) {
                    $ext = (isset($image)) ? (string) pathinfo($image, PATHINFO_EXTENSION) : '';
                    if (($ext == 'jpg') || ($ext == 'jpeg') || ($ext == 'png') || ($ext == 'bmp')) {
                        $path = _PS_MODULE_DIR_ . 'customproductdesign/data/logo/';
                        $temp_name = $_FILES['logo']['tmp_name'][$key];
                        $file_name = $image;

                        if (move_uploaded_file($temp_name, $path . $file_name)) {
                            $logo_name = (string) pathinfo($image, PATHINFO_BASENAME);
                            $logo = [
                                'logo_name' => $logo_name,
                                'logo_path' => __PS_BASE_URI__ . 'modules/customproductdesign/data/logo/' . $file_name,
                                'status' => 1,
                                'date_add' => date('Y-m-d H:i:s'),
                                'id_shop' => (int) $id_shop,
                                'tags' => preg_replace('/\s+/', '', $tags[$key]),
                            ];
                            ProductCustomization::addLogo($logo);
                        }
                    }
                }
            } else {
                Tools::redirectAdmin($redirect_link . '&logo_res=0&tab=logo');
            }
        } elseif (Tools::isSubmit('upload_material')) {
            $material = [];
            $material_name = (string) Tools::getValue('material_name');
            $material_price = (float) Tools::getValue('price', 0);
            if (empty($material_name) || !$material_name || !Validate::isGenericName($material_name)) {
                return $this->context->controller->errors[] = $this->l('Invalid Material name');
            } elseif (!Validate::isUnsignedFloat($material_price)) {
                return $this->context->controller->errors[] = $this->l('Invalid Material price');
            } else {
                $id_material = (int) Tools::getValue('id_material');
                $conf = ($id_material) ? 4 : 3;
                $material['date_add'] = date('Y-m-d H:i:s');
                if (isset($_FILES) && $_FILES && isset($_FILES['material']['name']) && $_FILES['material']['name']) {
                    $ext = (string) pathinfo($_FILES['material']['name'], PATHINFO_EXTENSION);
                    if ($id_material > 0) {
                        ProductCustomization::removeMaterialPath($id_material);
                        $material['date_up'] = date('Y-m-d H:i:s');
                        unset($material['date_up']);
                    }

                    if ($ext && (($ext == 'jpg') || ($ext == 'jpeg') || ($ext == 'png') || ($ext == 'bmp'))) {
                        $path = _PS_MODULE_DIR_ . 'customproductdesign/data/material/';
                        $temp_name = $_FILES['material']['tmp_name'];
                        $file_name = $this->getFileName($_FILES['material']['name'], $material_name);

                        if (move_uploaded_file($temp_name, $path . $file_name)) {
                            $material['material_path'] = __PS_BASE_URI__ . 'modules/customproductdesign/data/material/' . $file_name;
                        }
                    }
                }
                $material['id_material'] = $id_material;
                $material['price'] = $material_price;
                $material['material_name'] = $material_name;
                $material['status'] = 1;
                $material['id_shop'] = (int) $this->id_shop;
                $material['id_shop_group'] = (int) $this->id_shop_group;
                if (ProductCustomization::addMaterial($material)) {
                    Tools::redirectAdmin($redirect_link . '&conf=' . $conf . '&tab=material');
                } else {
                    Tools::redirectAdmin($redirect_link . '&material_res=2&tab=material');
                }
            }
        } elseif (Tools::isSubmit('submitAddColor')) {
            $id_colour = (int) Tools::getValue('id_colour');
            $color_name = (string) Tools::getValue('color_name');
            $color_code = (string) Tools::getValue('color_code');
            $conf = ($id_colour) ? 4 : 3;

            if (isset($color_name) && !Validate::isGenericName($color_name)) {
                $this->context->controller->errors[] = $this->l('Invalid color name.');
            }
            if (!$color_code || !Validate::isColor($color_code)) {
                $this->context->controller->errors[] = $this->l('Invalid color code.');
            }

            $color = [
                'id_colour' => $id_colour,
                'colour_name' => $color_name,
                'colour_code' => $color_code,
                'id_shop' => (int) $id_shop,
            ];
            if ($id_colour) {
                $color['date_up'] = date('Y-m-d H:i:s');
            } else {
                $color['date_add'] = date('Y-m-d H:i:s');
            }

            if (!count($this->context->controller->errors) && ProductCustomization::addColor($color)) {
                Tools::redirectAdmin($redirect_link . '&conf=' . $conf . '&tab=color');
            }
        } elseif (Tools::isSubmit('saveConfiguration')) {
            if (!Tools::getIsset('DESIGN_PREVIEW_WIDTH') || !Validate::isUnsignedInt(Tools::getValue('DESIGN_PREVIEW_WIDTH'))) {
                $this->context->controller->errors[] = $this->l('Invalid design width value.');
            }

            if (!Tools::getIsset('DESIGN_PREVIEW_HEIGHT') || !Validate::isUnsignedInt(Tools::getValue('DESIGN_PREVIEW_HEIGHT'))) {
                $this->context->controller->errors[] = $this->l('Invalid design height value.');
            }

            if (!Validate::isUnsignedInt(Tools::getValue('CPD_WATERMARK_SIZE'))) {
                $this->context->controller->errors[] = $this->l('Invalid watermark text size value.');
            }

            if (!Tools::getIsset('CPD_WATERMARK_TEXTCLR') || !Validate::isColor(Tools::getValue('CPD_WATERMARK_TEXTCLR'))) {
                $this->context->controller->errors[] = $this->l('Invalid watermark color value.');
            }

            $cpd_watermark_text = [];
            foreach ($_POST as $key => $value) {
                if (preg_match('/CPD_WATERMARK_TEXT_/i', $key)) {
                    $id_lang = preg_split('/CPD_WATERMARK_TEXT_/i', $key);
                    if (!Validate::isGenericName($value)) {
                        $this->context->controller->errors[] = sprintf($this->l('Invalid watermark text value for language: %s.'), Language::getIsoById($id_lang[1]));
                    } else {
                        $cpd_watermark_text[(int) $id_lang[1]] = $value;
                    }
                }
            }

            if (!count($this->context->controller->errors)) {
                Configuration::updateValue('DESIGN_PREVIEW_WIDTH', (int) Tools::getValue('DESIGN_PREVIEW_WIDTH', 100), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('DESIGN_PREVIEW_HEIGHT', (int) Tools::getValue('DESIGN_PREVIEW_HEIGHT', 100), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('LOGO_UPLOAD_EN_DS', (int) Tools::getValue('LOGO_UPLOAD_EN_DS', 1), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('LOGO_UPLOAD_URL', (int) Tools::getValue('LOGO_UPLOAD_URL', 1), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('DEFAULT_CUSTOM_COLOR', (string) Tools::getValue('DEFAULT_CUSTOM_COLOR', 'default'), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('DEFAULT_CUSTOM_FONT', (string) Tools::getValue('DEFAULT_CUSTOM_FONT', 'default'), false, $this->id_shop_group, $this->id_shop);

                Configuration::updateValue('CPD_WATERMARK_TEXTCLR', pSQL(Tools::getValue('CPD_WATERMARK_TEXTCLR')), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_WATERMARK_SIZE', (int) Tools::getValue('CPD_WATERMARK_SIZE'), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_WATERMARK_ACTIVE', (int) Tools::getValue('CPD_WATERMARK_ACTIVE'), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_HINTS_BLK', (int) Tools::getValue('CPD_HINTS_BLK'), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_MATERIALS_MANDATORY', (int) Tools::getValue('CPD_MATERIALS_MANDATORY'), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_WATERMARK_TEXT', $cpd_watermark_text, true, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_ENABLE_PRE_DESIGNS', (int) Tools::getValue('CPD_ENABLE_PRE_DESIGNS', 1), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_ENABLE_PRE_DESIGNS_RELATIVE', (int) Tools::getValue('CPD_ENABLE_PRE_DESIGNS_RELATIVE', 1), false, $this->id_shop_group, $this->id_shop);
                Configuration::updateValue('CPD_ENABLE_LAYERS_SECTION', (int) Tools::getValue('CPD_ENABLE_LAYERS_SECTION', 1), false, $this->id_shop_group, $this->id_shop);
                Tools::redirectAdmin($redirect_link . '&conf=6&tab=general');
            }
        } elseif (Tools::isSubmit('deletefonts')) {
            $id_font = (int) Tools::getValue('id_font');
            if (ProductCustomization::deleteFontById($id_font)) {
                Tools::redirectAdmin($redirect_link . '&conf=1&tab=fonts');
            }
        } elseif (Tools::isSubmit('deletecolor')) {
            $id_colour = (int) Tools::getValue('id_colour');
            if (ProductCustomization::deleteColorById($id_colour)) {
                Tools::redirectAdmin($redirect_link . '&conf=1&tab=color');
            }
        } elseif (Tools::isSubmit('deletelogo')) {
            $id_logo = (int) Tools::getValue('id_logo');
            if (ProductCustomization::deleteLogoById($id_logo)) {
                Tools::redirectAdmin($redirect_link . '&conf=7&tab=logo');
            }
        } elseif (Tools::isSubmit('deletematerial')) {
            $id_material = (int) Tools::getValue('id_material');
            if (ProductCustomization::deleteMaterialById($id_material)) {
                Tools::redirectAdmin($redirect_link . '&conf=7&tab=material');
            }
        } elseif (Tools::isSubmit('change_font_status')) {
            $id_font = (int) Tools::getValue('id_font');
            if (ProductCustomization::updateStatus('fonts', 'id_font', $id_font)) {
                Tools::redirectAdmin($redirect_link . '&conf=5&tab=fonts');
            }
        } elseif (Tools::isSubmit('change_logo_status')) {
            $id_logo = (int) Tools::getValue('id_logo');
            if (ProductCustomization::updateStatus('logo', 'id_logo', $id_logo)) {
                Tools::redirectAdmin($redirect_link . '&conf=5&tab=logo');
            }
        } elseif (Tools::isSubmit('change_color_status')) {
            $id_colour = (int) Tools::getValue('id_colour');
            if (ProductCustomization::updateStatus('colour', 'id_colour', $id_colour)) {
                Tools::redirectAdmin($redirect_link . '&conf=5&tab=color');
            }
        } elseif (Tools::isSubmit('change_material_status')) {
            $id_material = (int) Tools::getValue('id_material');
            if (ProductCustomization::updateStatus('material', 'id_material', $id_material)) {
                Tools::redirectAdmin($redirect_link . '&conf=5&tab=material');
            }
        } elseif (Tools::isSubmit('submitBulkdeletefonts')) {
            $id_fontBox = Tools::getValue('id_fontBox');
            if (isset($id_fontBox) && is_array($id_fontBox)) {
                foreach ($id_fontBox as $id_font) {
                    ProductCustomization::deleteFontById($id_font);
                }
                Tools::redirectAdmin($redirect_link . '&conf=2&tab=fonts');
            } else {
                $this->context->controller->errors[] = $this->l('Please select a selection.');
            }
        } elseif (Tools::isSubmit('submitBulkdeletecolour')) {
            $id_colourBox = Tools::getValue('id_colourBox');
            if (isset($id_colourBox) && is_array($id_colourBox)) {
                foreach ($id_colourBox as $id_colour) {
                    ProductCustomization::deleteColorById($id_colour);
                }
                Tools::redirectAdmin($redirect_link . '&conf=2&tab=color');
            } else {
                $this->context->controller->errors[] = $this->l('Please select a selection.');
            }
        } elseif (Tools::isSubmit('submitBulkdeletelogo')) {
            $id_logoBox = Tools::getValue('id_logoBox');
            if (isset($id_logoBox) && is_array($id_logoBox)) {
                foreach ($id_logoBox as $id_logo) {
                    ProductCustomization::deleteLogoById($id_logo);
                }
                Tools::redirectAdmin($redirect_link . '&conf=2&tab=logo');
            } else {
                $this->context->controller->errors[] = $this->l('Please select a selection.');
            }
        } elseif (Tools::isSubmit('submitBulkdeletematerial')) {
            $id_materialBox = Tools::getValue('id_materialBox');
            if (isset($id_materialBox) && is_array($id_materialBox)) {
                foreach ($id_materialBox as $id_material) {
                    ProductCustomization::deleteMaterialById($id_material);
                }
                Tools::redirectAdmin($redirect_link . '&conf=7&tab=material');
            } else {
                $this->context->controller->errors[] = $this->l('Please select a selection.');
            }
        } elseif (Tools::isSubmit('savePricing')) {
            Db::getInstance()->delete('cpd_dynamic_pricing');
            $pricing = Tools::getValue('pricing');
            if (!empty($pricing)) {
                $pricing = array_chunk($pricing, 3, false);
                foreach ($pricing as $price) {
                    if (!empty($price['2'])) {
                        Db::getInstance()->insert(
                            'cpd_dynamic_pricing',
                            [
                                'qty_from' => $price['0'],
                                'qty_to' => $price['1'],
                                'price' => $price['2'],
                            ]
                        );
                    }
                }
            }
            Configuration::updateValue('CPD_ENABLE_DYNAMIC_PRICING', (int) Tools::getValue('CPD_ENABLE_DYNAMIC_PRICING', 1), false, $this->id_shop_group, $this->id_shop);
            Tools::redirectAdmin($redirect_link . '&tab=pricing');
        } elseif (Tools::isSubmit('deletetemplate')) {
            $id_template = (int) Tools::getValue('id_template');
            if (ProductCustomization::deleteTemplateById($id_template)) {
                Tools::redirectAdmin($redirect_link . '&conf=1&tab=templates');
            }
        }
    }

    public function hookModuleRoutes()
    {
        return [
            'module-customproductdesign-cpdesign' => [
                'controller' => 'cpdesign',
                'rule' => 'designer',
                'keywords' => [],
                'params' => [
                    'fc' => 'module',
                    'module' => 'customproductdesign',
                ],
            ],
        ];
    }

    public function hookHeader()
    {
        $this->context->controller->addJqueryPlugin('fancybox');

        $this->context->controller->addCSS($this->_path . 'views/css/resizable.css');

        $this->context->controller->addJS($this->_path . 'views/js/jquery.form.js');
        $this->context->controller->addJS($this->_path . 'views/js/jquery-ui.js');
        $this->context->controller->addJS($this->_path . 'views/js/jquery.ui.touch-punch.js');

        // new changes
        $this->context->controller->addCSS($this->_path . 'views/css/front/cpd_design.css');
        $this->context->controller->addCSS($this->_path . 'views/css/plugins/qtip/jquery.qtip.css');
        // jQuery-confirm css
        $this->context->controller->addCSS($this->_path . 'views/css/plugins/jquery-confirm.css');
        // range slider

        // rotation
        $this->context->controller->addJS($this->_path . 'views/js/plugins/jquery.multirotation-1.0.0.js');
        $this->context->controller->addJS($this->_path . 'views/js/plugins/jquery-timing.min.js');

        $this->context->controller->addJS($this->_path . 'views/js/plugins/rangeslider.js');
        $this->context->controller->addCSS($this->_path . 'views/css/plugins/rangeslider.css');

        // tooltip
        // $this->context->controller->addCSS($this->_path.'views/css/plugins/tooltipster.bundle.css');
        // $this->context->controller->addCSS($this->_path.'views/css/plugins/tooltipster-sideTip-shadow.min.css');
        // $this->context->controller->addJS($this->_path.'views/js/plugins/tooltipster.bundle.js');
        // qTip
        $this->context->controller->addJS($this->_path . 'views/js/plugins/qtip/jquery.qtip.js');
        // ddSlick
        $this->context->controller->addJS($this->_path . 'views/js/plugins/jquery.ddslick.js');
        // watermark
        $this->context->controller->addJS($this->_path . 'views/js/plugins/watermark.js');
        // jsPDF
        $this->context->controller->addJS($this->_path . 'views/js/plugins/jspdf.debug.js');
        // jQuery-confirm js
        $this->context->controller->addJS($this->_path . 'views/js/plugins/jquery-confirm.js');

        // design js
        $this->context->controller->addJS($this->_path . 'views/js/html2canvas.js');
        $this->context->controller->addJS($this->_path . 'views/js/front/cpd_design.js');
        // fancy box and color picker
        $this->context->controller->addJqueryPlugin(['colorpicker', 'fancybox']);

        $this->context->controller->addCSS($this->_path . 'views/css/custom_product.css');
        $this->context->controller->addCSS($this->_path . 'views/css/design.css');

        $cart = 'order';
        $params = [];
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            $cart = 'cart';
            $params = ['action' => 'show'];
            $this->context->controller->registerStylesheet('custom_design', 'modules/' . $this->name . '/views/css/custom_design.css', ['media' => 'all', 'priority' => 500]);
            $this->context->controller->registerStylesheet('product_editor', 'modules/' . $this->name . '/views/css/product_editor.css', ['media' => 'all', 'priority' => 501]);
        } else {
            $this->context->controller->addCSS($this->_path . 'views/css/custom_design.css');
            $this->context->controller->addCSS($this->_path . 'views/css/product_editor.css');
        }

        $this->context->smarty->assign([
            'ps_version' => _PS_VERSION_,
            'id_currency' => $this->context->cookie->id_currency,
            'search_ssl' => Tools::usingSecureMode(),
            'currencyISO' => $this->context->currency->iso_code, // backward compat
            'currencySign' => $this->context->currency->sign, // backward compat
            'currencyFormat' => $this->context->currency->format, // backward compat
            'currencyBlank' => $this->context->currency->blank, // backward compat
            'priceDisplayPrecision' => _PS_PRICE_DISPLAY_PRECISION_,
            'isLogged' => (int) $this->context->customer->isLogged(),
            'qry' => ((Configuration::get('PS_REWRITING_SETTINGS') == 0) ? '&' : '?'),
            'currencyRate' => (float) (($this->context->currency->id != (int) Configuration::get('PS_CURRENCY_DEFAULT')) ? $this->context->currency->conversion_rate : 1),
            'design_controller' => $this->context->link->getModuleLink('customproductdesign', 'customized'),
            'design_handler' => $this->context->link->getModuleLink('customproductdesign', 'cpdesign'),
            'cart_link' => $this->context->link->getPageLink($cart, true, null, $params, false),
            'CPD_WATERMARK_TEXT' => Configuration::get('CPD_WATERMARK_TEXT', $this->context->language->id, $this->id_shop_group, $this->id_shop),
            'CPD_WATERMARK_TEXTCLR' => Configuration::get('CPD_WATERMARK_TEXTCLR', false, $this->id_shop_group, $this->id_shop),
            'CPD_WATERMARK_SIZE' => Configuration::get('CPD_WATERMARK_SIZE', false, $this->id_shop_group, $this->id_shop),
            'CPD_WATERMARK_ACTIVE' => Configuration::get('CPD_WATERMARK_ACTIVE', false, $this->id_shop_group, $this->id_shop),
            'DEFAULT_CUSTOM_COLOR' => Configuration::get('DEFAULT_CUSTOM_COLOR', false, $this->id_shop_group, $this->id_shop),
            'DEFAULT_CUSTOM_FONT' => Configuration::get('DEFAULT_CUSTOM_FONT', false, $this->id_shop_group, $this->id_shop),
            'CPD_MATERIALS_MANDATORY' => Configuration::get('CPD_MATERIALS_MANDATORY', false, $this->id_shop_group, $this->id_shop),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/header.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (isset($this->context->controller) && $this->context->controller->controller_name == 'AdminProducts') {
            if (Tools::version_compare(_PS_VERSION_, '1.7.7', '<')) {
                $this->context->controller->addJquery();
                if (Tools::version_compare(_PS_VERSION_, '1.7.6.0', '>=')) {
                    $this->context->controller->addJS($this->_path . 'views/js/jquery.js');
                }
            }
            $this->context->controller->addJqueryPlugin(['fancybox']);
            $this->context->controller->addJqueryUI('ui.sortable');
            $this->context->controller->addJS($this->_path . 'views/js/tabcontent.js');
            $this->context->controller->addCSS($this->_path . 'views/css/tabcontent.css');
            $this->context->controller->addCSS($this->_path . 'views/css/pc_product.css');
            $this->context->controller->addCSS($this->_path . 'views/css/jquery-ui.css');
            $this->context->controller->addJS($this->_path . 'views/js/jquery-ui.js');

            // Qtip Plugin
            $this->context->controller->addCSS($this->_path . 'views/css/plugins/qtip/jquery.qtip.css');
            $this->context->controller->addCSS($this->_path . 'views/css/plugins/qtip_custom.css');

            $this->context->controller->addJS($this->_path . 'views/js/admin/cpd_main.js');
            $this->context->controller->addJS($this->_path . 'views/js/admin/cpd_group.js');
        }
    }

    public function getConfigFieldsValues()
    {
        $return = [];
        foreach (Language::getLanguages(false) as $lang) {
            $return['CPD_WATERMARK_TEXT'][(int) $lang['id_lang']] = Tools::getValue(
                'CPD_WATERMARK_TEXT_' . (int) $lang['id_lang'],
                Configuration::get(
                    'CPD_WATERMARK_TEXT',
                    (int) $lang['id_lang'],
                    $this->id_shop_group,
                    $this->id_shop
                )
            );
        }

        return $return;
    }

    public function hookActionAuthentication($params)
    {
        if (isset($params, $params['customer'])) {
            $customer = $params['customer'];
            $id_guest = ProductCustomization::getGuestId($customer->id);
            if ($id_guest) {
                $data = ['id_customer' => $customer->id];
                $where = 'id_guest = ' . (int) $id_guest;
                ProductCustomization::updateCustomerLogo($data, $where);
            }
        }
    }

    public function hookActionCustomerAccountAdd($params)
    {
        if (isset($params, $params['newCustomer'])) {
            $id_customer = (int) $params['newCustomer']->id;
            $id_guest = ProductCustomization::getGuestId($id_customer);
            if ($id_guest) {
                $data = ['id_customer' => $id_customer];
                $where = 'id_guest = ' . (int) $id_guest;
                ProductCustomization::updateCustomerLogo($data, $where);
            }
        }
    }

    public function hookActionCartSave($params)
    {
        $delete = (bool) Tools::getValue('delete');
        $id_product = (int) Tools::getValue('id_product');
        $id_customization = (int) Tools::getValue('id_customization', 0);
        $id_product_attribute = (int) ((Tools::getValue('ipa')) ? Tools::getValue('ipa', 0) : Tools::getValue('id_product_attribute', 0));
        $id_cart = (isset($this->context->cart)) ? $this->context->cart->id : 0;

        if (!$id_customization) {
            $customization = ProductCustomization::getProductCustomization($id_product, $id_product_attribute, $id_cart);
            if (isset($customization) && $customization) {
                $id_customization = (int) $customization['id_customization'];
            }
        }
        if ($delete
            && $id_product
            && $id_customization
            && ProductCustomization::checkCustomization($id_cart, $id_customization)) {
            $removed = ProductCustomization::deleteCartCustomization($id_product, $id_product_attribute, $id_cart, $id_customization);
            // backward compatibility
            if ($removed && Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
                $cpd_product = new Product((int) $id_product);
                $cpd_product->delete();
            }
        }
    }

    public function hookActionObjectDeleteAfter($params)
    {
        $customerObject = (isset($params) && isset($params['object']) ? $params['object'] : null);
        if (isset($customerObject) && $customerObject instanceof Customer) {
            $customerImages = ProductCustomization::getCustomerLogo($customerObject->id);
            if (isset($customerImages) && $customerImages && count($customerImages) >= 1) {
                foreach ($customerImages as $data) {
                    ProductCustomization::deleteLogoById($data['id_logo']);
                }
            }
        }
    }

    // backward compatibility
    public function hookActionBeforeCartUpdateQty($params)
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<') && isset($params['id_customization'])) {
            if ((int) $params['quantity'] <= 0 && ProductCustomization::checkCustomization($params['cart']->id, $params['id_customization'])) {
                ProductCustomization::deleteCartCustomization($params['product']->id, $params['id_product_attribute'], $params['cart']->id, $params['id_customization']);
                $cpd_product = new Product((int) $params['product']->id);
                $cpd_product->delete();
            }
        }
    }

    public function hookNewOrder($params)
    {
        $products = [];
        $order = $params['order'];
        $cart = $params['cart'];
        if (isset($params['cart'])) {
            $products = $params['cart']->getProducts();
        }
        if (isset($cart, $cart->id) && $cart->id) {
            if (ProductCustomization::customizationExists($cart->id_customer, $cart->id, false, false, false, $cart->id_shop, $cart->id_shop_group)) {
                $set = ProductCustomization::setOrderId($cart->id_customer, $cart->id, $order->id, $cart->id_shop, $cart->id_shop_group);
                // backward compatibility
                if ($set && isset($products) && $products && Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
                    foreach ($products as $product) {
                        if (ProductCustomization::customizationExists($cart->id_customer, $cart->id, $product['id_product'], $product['id_product_attribute'])) {
                            $ordered_cpd = new Product((int) $product['id_product']);
                            $ordered_cpd->visibility = 'none';
                            $ordered_cpd->active = false;
                            $ordered_cpd->update();
                        }
                    }
                }
            }
        }
    }

    public function hookDisplayProductButtons($params)
    {
        $designs = [];
        $id_shop = (int) $this->context->shop->id;
        $id_lang = (int) $this->context->language->id;
        $ps_ver = Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? 1 : 0;
        $id_product = (int) (isset($params) && isset($params['id_product']) ? $params['id_product'] : ((isset($params) && is_object($params['product'])) ? $params['product']->id : Tools::getValue('id_product')));
        if ($id_product && Validate::isLoadedObject($product = new Product($id_product))) {
            // if its a duplicate product redirect to parent(original)
            $parent = ProductCustomization::getParent($id_product);
            if (!Tools::getIsset('id_employee') && $parent && Validate::isLoadedObject($parentObj = new Product((int) $parent, true, (int) $id_lang, $id_shop))) {
                $parent_link = $this->context->link->getProductLink($parentObj);
                Tools::redirect($parent_link);
            } elseif (ProductCustomization::getTypeByProduct($id_product, 'status')) {
                $designs = Designer::getProductDesigns($id_product, $id_lang);
                $pc = new Category($product->id_category_default, $id_lang);
                $product_link = $this->context->link->getProductLink($product, null, $pc->link_rewrite, null, $id_lang, $id_shop);
                $this->context->smarty->assign([
                    'customization' => $designs,
                    'product_link' => $product_link,
                    'cpd_page' => $this->context->controller->php_self,
                    'pdf' => ProductCustomization::getTypeByProduct($id_product, 'pdf'),
                    'pdf_orientation' => ProductCustomization::getTypeByProduct($id_product, 'pdf_orientation'),
                    'cpd_design_link' => $product_link . ((strpos($product_link, '?')) ? '&' : '?') . 'cpd_mode=designer',
                    'ps_ver' => $ps_ver,
                ]);

                return $this->display($this->_path, 'views/templates/hook/cpd_button.tpl');
            }
        }
    }

    public function hookProductFooter($params)
    {
        $id_shop = (int) $this->context->shop->id;
        $id_lang = (int) $this->context->language->id;
        // If its admin creating template
        $id_employee = (int) Tools::getValue('id_employee');
        $id_design = (int) Tools::getValue('id_design');
        $id_product = (int) (isset($params) && isset($params['id_product']) ? $params['id_product'] : Tools::getValue('id_product'));
        $relative_templates = (int) Configuration::get('CPD_ENABLE_PRE_DESIGNS_RELATIVE', null, $this->id_shop_group, $this->id_shop);
        if ($relative_templates > 0) {
            $design_templates = Designer::getAllTemplatesRelatively($id_product);
        } else {
            $design_templates = Designer::getAllTemplates();
        }
        if ($id_product && Validate::isLoadedObject($product = new Product((int) $id_product, true, (int) $this->context->cookie->id_lang))) {
            if (ProductCustomization::getTypeByProduct($id_product, 'status')) {
                $customization = Designer::getProductDesigns($id_product, $id_lang);
                // $layers_collection = Designer::getProductDesignLayersColl($id_product, $id_lang);
                $combinations = [];
                if ($product->hasAttributes()) {
                    $combinations = $this->assignAttributesGroups($product);
                }
                if (!empty($customization)) {
                    foreach ($customization as &$design) {
                        $design['workplace'] = $this->getWorkplaceDesign($design['designs']->id_customized);
                    }
                }
                $product_link = $this->context->link->getProductLink($product, null, null, null, $id_lang, $id_shop);
                $defaultCurrency = new Currency((int) Configuration::get('PS_CURRENCY_DEFAULT'));
                $base_link = $this->getBaseLink();
                $this->context->smarty->assign([
                    'custom_product' => $product,
                    'id_product_old' => $id_product,
                    'product_link' => $product_link,
                    'version' => _PS_VERSION_,
                    'combinations' => $combinations,
                    'customization' => $customization,
                    // 'layers_collection'         => $layers_collection,
                    'currency' => $this->context->currency,
                    'has_attributes' => (int) $product->hasAttributes(),
                    'price_display_precision' => _PS_PRICE_DISPLAY_PRECISION_,
                    'exchangeRate' => $this->context->currency->conversion_rate,
                    'fonts' => ProductCustomization::getActiveFonts($id_product, $id_shop),
                    'colors' => ProductCustomization::getActiveColors($id_product, $id_shop),
                    'logos' => ProductCustomization::getActiveLogos($id_product, $id_shop),
                    'materials' => ProductCustomization::getMaterials(true, $id_product, $this->id_shop, $this->id_shop_group),
                    'LOGO_UPLOAD_EN_DS' => (int) Configuration::get('LOGO_UPLOAD_EN_DS', null, $this->id_shop_group, $this->id_shop),
                    'DESIGN_PREVIEW_WIDTH' => (int) Configuration::get('DESIGN_PREVIEW_WIDTH', null, $this->id_shop_group, $this->id_shop),
                    'DESIGN_PREVIEW_HEIGHT' => (int) Configuration::get('DESIGN_PREVIEW_HEIGHT', null, $this->id_shop_group, $this->id_shop),
                    'LOGO_UPLOAD_URL' => (int) Configuration::get('LOGO_UPLOAD_URL', null, $this->id_shop_group, $this->id_shop),
                    'CPD_ENABLE_PRE_DESIGNS' => (int) Configuration::get('CPD_ENABLE_PRE_DESIGNS', null, $this->id_shop_group, $this->id_shop),
                    'DEFAULT_CUSTOM_COLOR' => (string) Configuration::get('DEFAULT_CUSTOM_COLOR', null, $this->id_shop_group, $this->id_shop),
                    'DEFAULT_CUSTOM_FONT' => (string) Configuration::get('DEFAULT_CUSTOM_FONT', null, $this->id_shop_group, $this->id_shop),
                    'CPD_ENABLE_DYNAMIC_PRICING' => Configuration::get('CPD_ENABLE_DYNAMIC_PRICING', null, $this->id_shop_group, $this->id_shop),
                    'CPD_ENABLE_LAYERS_SECTION' => Configuration::get('CPD_ENABLE_LAYERS_SECTION', null, $this->id_shop_group, $this->id_shop),
                    'CPD_HINTS_BLK' => Configuration::get('CPD_HINTS_BLK', false, $this->id_shop_group, $this->id_shop),
                    'price_display' => Product::getTaxCalculationMethod((int) $this->context->cookie->id_customer),
                    'design_handler' => $this->context->link->getModuleLink('customproductdesign', 'cpdesign'),
                    '_id_employee' => $id_employee,
                    '_id_design' => $id_design,
                    '_base_link' => $base_link,
                    'design_templates' => $design_templates,
                    '_id_guest' => $this->context->cookie->id_guest,
                    '_id_customer' => $this->context->cookie->id_customer,
                ]);
                $this->context->smarty->assign('cpd_link', Context::getContext()->link);
                $this->context->smarty->assign('cpd_id_product', $id_product);

                return $this->display(__FILE__, 'views/templates/hook/custom_product.tpl');
            }
        }
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $id_shop = (int) $this->context->shop->id;
        $id_lang = (int) $this->context->language->id;
        $id_product = (isset($params, $params['id_product'])) ? $params['id_product'] : (int) Tools::getValue('id_product');
        if ($id_product) {
            $current_index = $this->context->link->getAdminLink('AdminModules', false);
            $current_token = Tools::getAdminTokenLite('AdminModules');
            $action_link = $current_index . '&configure=' . $this->name . '&token=' . $current_token . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
            $redirect_link = $this->context->link->getAdminLink('AdminProducts') . '&id_product=' . $id_product . '&updateproduct&key_tab=Modulecustomproductdesign';
            $cover = Product::getCover($id_product);
            $product = new Product((int) $id_product, true, $this->context->language->id);
            $images = Image::getImages($this->context->language->id, $product->id);
            if (is_array($images)) {
                foreach ($images as $k => $image) {
                    $images[$k]['src'] = $this->context->link->getImageLink($product->link_rewrite, $product->id . '-' . $image['id_image'], (true == Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) ? ImageType::getFormattedName('large') : ImageType::getFormatedName('thickbox'));
                }
            }
            $customization = ProductCustomization::getProductCustomizationById($id_product, $this->id_shop);
            $pc = new Category($product->id_category_default, $id_lang);
            $product_link = $this->context->link->getProductLink($product, null, $pc->link_rewrite, null, $id_lang, $id_shop);
            $id_employee = (int) Context::getContext()->cookie->id_employee;
            $this->context->smarty->assign([
                'action_link' => $action_link,
                'redirect_link' => $redirect_link,
                'id_product' => (int) $id_product,
                'images' => $images,
                'cover' => $cover,
                'product' => $product,
                'front_product_link' => $product_link,
                '_id_employee' => $id_employee,
                'ps_version' => _PS_VERSION_,
                'id_lang' => (int) Configuration::get('PS_LANG_DEFAULT'),
                'languages' => Language::getLanguages(false),
                'currency' => $this->context->currency,
                'fonts' => ProductCustomization::getFonts($id_shop, true),
                'logos' => ProductCustomization::getLogos($id_shop, true),
                'colors' => ProductCustomization::getColors($id_shop, true),
                'materials' => ProductCustomization::getMaterials(true, null, $this->id_shop, $this->id_shop_group),
                'customization' => $customization,
                'LOGO_UPLOAD_EN_DS' => (int) Configuration::get('LOGO_UPLOAD_EN_DS', null, $this->id_shop_group, $this->id_shop),
                'LOGO_UPLOAD_URL' => (int) Configuration::get('LOGO_UPLOAD_URL', null, $this->id_shop_group, $this->id_shop),
                'DESIGN_PREVIEW_WIDTH' => (int) Configuration::get('DESIGN_PREVIEW_WIDTH', null, $this->id_shop_group, $this->id_shop),
                'DESIGN_PREVIEW_HEIGHT' => (int) Configuration::get('DESIGN_PREVIEW_HEIGHT', null, $this->id_shop_group, $this->id_shop),
            ]);

            $designs = Designer::getProductDesigns($id_product, $id_lang, false);
            $status = (int) ProductCustomization::getTypeByProduct($id_product, 'status');
            $pdf = (int) ProductCustomization::getTypeByProduct($id_product, 'pdf');
            $pdf_orientation = ProductCustomization::getTypeByProduct($id_product, 'pdf_orientation');
            $selected_fonts = ProductCustomization::getTypeByProduct($id_product, 'selected_fonts');
            $selected_images = ProductCustomization::getTypeByProduct($id_product, 'selected_images');
            $selected_colors = ProductCustomization::getTypeByProduct($id_product, 'selected_colors');
            $selected_materials = ProductCustomization::getTypeByProduct($id_product, 'selected_materials');
            if (!empty($designs)) {
                foreach ($designs as &$design) {
                    $design['workplace'] = $this->getWorkplaceDesign($design['designs']->id_customized);
                }
            }
            $design_templates = Designer::getAllTemplatesRelatively($id_product);
            $this->context->smarty->assign([
                'error' => false,
                'pdf' => $pdf,
                'designs' => $designs,
                'status' => $status,
                'pdf_orientation' => $pdf_orientation,
                'admin_path' => dirname($_SERVER['PHP_SELF']),
                'selected_fonts' => ((isset($selected_fonts) && $selected_fonts) ? json_decode($selected_fonts) : []),
                'selected_images' => ((isset($selected_images) && $selected_images) ? json_decode($selected_images) : []),
                'selected_colors' => ((isset($selected_colors) && $selected_colors) ? json_decode($selected_colors) : []),
                'selected_materials' => ((isset($selected_materials) && $selected_materials) ? json_decode($selected_materials) : []),
                'controller_link' => $this->context->link->getAdminLink('CustomProductDesigner'),
                'PS_ATTACHMENT_MAXIMUM_SIZE' => (1000 * 1000 * Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE', false, $this->id_shop_group, $this->id_shop)),
                'front_product_link' => $product_link,
                'design_templates' => $design_templates,
                'the_link' => $this->context->link,
            ]);

            return $this->display(__FILE__, 'views/templates/admin/designer.tpl');
        }
    }

    public function hookActionProductDelete($params)
    {
        // remove associated desigs and customization of a product
        if (isset($params, $params['id_product']) && $params['id_product']) {
            $designs = Designer::getProductDesigns($params['id_product'], null, false);
            if (isset($designs) && $designs && is_array($designs)) {
                foreach ($designs as $design) {
                    $id_design = $design['designs']->id;
                    if ($design['designs']->delete()) {
                        DesignTags::deleteByDesign($id_design);
                        ProductCustomization::deleteProductSettings($params['id_product']);
                        ProductCustomization::deleteCartCustomization($params['id_product']);
                    }
                }
            }
        }
    }

    public function hookActionProductAttributeDelete($params)
    {
        // remove associated desigs and customization of an attribute
        if (isset($params, $params['id_product'], $params['id_product_attribute'])) {
            ProductCustomization::deleteCartCustomization($params['id_product'], $params['id_product_attribute']);
        }
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        // $key_tab = (string)Tools::getValue('key_tab');
        $fields = Tools::getValue('cpd_settings');
        $id_product = (int) (isset($params, $params['object'], $params['object']->id)) ? $params['object']->id : Tools::getValue('id_product');
        $types_simple = ['status', 'pdf', 'pdf_orientation'];
        $types_complex = ['selected_fonts', 'selected_colors', 'selected_images', 'selected_materials'];

        if (isset($fields) && $fields && $id_product && Validate::isLoadedObject($product = new Product((int) $id_product))) {
            // delete unselected settings
            $keys = array_flip(array_keys($fields));
            $exclude = array_diff_key(array_flip($types_complex), $keys);
            if (isset($exclude) && $exclude) {
                foreach ($exclude as $key => $value) {
                    ProductCustomization::deleteByType($product->id, $key);
                }
            }

            foreach ($fields as $key => $value) {
                $data = ['value' => '', 'type' => ''];
                if (in_array($key, $types_simple)) {
                    $data['value'] = pSQL($value);
                    $data['type'] = pSQL($key);
                } else {
                    $data['type'] = pSQL($key);
                    if (isset($value['data']) && $value['data']) {
                        $data['value'] = json_encode($value['data']);
                    }
                }
                // update general settings
                if (ProductCustomization::typeExist($product->id, $key)) {
                    $data['date_upd'] = date('Y-m-d H:i:s');
                    ProductCustomization::updateSettings($product->id, $data, $key);
                } else {
                    $data['id_product'] = (int) $product->id;
                    $data['date_add'] = date('Y-m-d H:i:s');
                    ProductCustomization::addSettings($data);
                }
            }
        }
    }

    /**
     * GDPR Compliance Hooks
     */
    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            $customerImages = ProductCustomization::getCustomerLogo($customer['id']);
            if (isset($customerImages) && count($customerImages) >= 1) {
                foreach ($customerImages as $data) {
                    ProductCustomization::deleteLogoById($data['id_logo']);
                }

                return json_decode(true);
            }

            return json_encode($this->l('Custom Product Design : Unable to delete customer data.'));
        }
    }

    public function hookActionExportGDPRData($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            $customerImages = ProductCustomization::getCustomerLogo($customer['id']);

            $customerData = [];
            $customfieldsData = [];
            if (isset($customerImages) && count($customerImages) >= 1) {
                foreach ($customerImages as $key => $data) {
                    $customerData[$key][$this->l('Image')] = (!empty($data['logo_path'])) ? $data['logo_path'] : '--';
                    $customerData[$key][$this->l('File Name')] = $data['logo_name'];
                    $customerData[$key][$this->l('Date Added')] = $data['date_add'];
                }
            }

            if (isset($customerData) && $customerData) {
                foreach ($customerData as $cdata) {
                    array_push($customfieldsData, $cdata);
                }
            }
            if (isset($customfieldsData) && $customfieldsData) {
                return json_encode($customfieldsData);
            }

            return json_encode($this->l('Custom Product Design : There is no data to export.'));
        }

        return json_encode($this->l('Custom Product Design : Unable to export customer data.'));
    }

    protected function readDir($dir, $file_type)
    {
        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $files = [];
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir($dir . $file) && $file != '.' && $file !== '..' && $this->getFileExtension($file) == $file_type) {
                        $files[] = $file;
                    }
                }
                closedir($dh);

                return $files;
            }
        }
    }

    protected function getFileExtension($file_name)
    {
        // returning file extension from file name (string)
        return Tools::substr(strrchr($file_name, '.'), 1);
    }

    public function updateCoverImage($id_product)
    {
        $id_image = ProductCustomization::getAttributeImage((int) $id_product);
        if (!empty($id_image)) {
            Image::deleteCover((int) $id_product);
            $img = new Image((int) $id_image['id_image']);
            $img->cover = 1;
            @unlink(_PS_TMP_IMG_DIR_ . 'product_' . (int) $img->id_product . '.jpg');
            @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $img->id_product . '_' . $this->context->shop->id . '.jpg');

            if ($img->update()) {
                return true;
            }
        }

        return false;
    }

    public function getGroupForm($params)
    {
        $id_lang = (int) Context::getContext()->language->id;
        $languages = Language::getLanguages(false);
        $error = false;
        $cpd_group = [];
        if (isset($params, $params['id_product']) && Validate::isLoadedObject($product = new Product($params['id_product'], false, $id_lang))) {
            $images = Image::getImages($this->context->language->id, $product->id);
            if (is_array($images)) {
                foreach ($images as $k => $image) {
                    $images[$k]['src'] = $this->context->link->getImageLink($product->link_rewrite, $product->id . '-' . $image['id_image'], (true == Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) ? ImageType::getFormattedName('large') : ImageType::getFormatedName('thickbox'));
                }
            }
            $cpd_group = new Designer();
            $cpd_group->id_product = $product->id;

            if (!$cpd_group->add()) {
                $error = $this->l('Design creation failed.');
            } else {
                if (isset($languages) && $languages) {
                    foreach ($languages as $lang) {
                        $cpd_group->design_title[$lang['id_lang']] = sprintf($this->l('Design %d'), $cpd_group->id);
                    }
                } else {
                    $cpd_group->design_title[$id_lang] = sprintf($this->l('Design %d'), $cpd_group->id);
                }
                $cpd_group->update();
            }
            $id_employee = (int) Context::getContext()->cookie->id_employee;
            $pc = new Category($product->id_category_default, $id_lang);
            $product_link = Context::getContext()->link->getProductLink($product, null, $pc->link_rewrite, null, $id_lang);
            $this->context->smarty->assign([
                'cpd_group' => $cpd_group,
                'id_lang' => $id_lang,
                'error' => $error,
                'front_product_link' => $product_link,
                'images' => $images,
                'ps_version' => _PS_VERSION_,
                'controller_link' => $this->context->link->getAdminLink('CustomProductDesigner'),
                '_id_employee' => $id_employee,
            ]
            );

            return [
                'id_design' => $cpd_group->id,
                'html' => $this->display(__FILE__, 'views/templates/admin/main/design/formGroups.tpl'),
            ];
        }
    }

    public function setDesignTitle($params)
    {
        $fields_form = [
            'form' => [
                'id_form' => 'design_title_from',
                'legend' => [
                    'title' => $this->l('Title'),
                    'icon' => 'icon-pencil',
                ],
                'input' => [
                    [
                        'type' => 'hidden',
                        'name' => 'id_design',
                    ],
                    [
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Design Title'),
                        'name' => 'design_title',
                        'class' => 'form-control m-b-1',
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $languages = $this->context->controller->getLanguages();
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $params['id_design'] . '_product_customized';
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitDesignTitle';
        $helper->currentIndex = $this->context->link->getAdminLink('CustomProductDesigner');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'uri' => $this->getPathUri(),
            'languages' => $languages,
            'id_language' => $this->context->language->id,
        ];
        $helper->fields_value['id_design'] = ((isset($params['id_design']) && $params['id_design']) ? $params['id_design'] : 0);
        $design = new Designer($params['id_design']);
        foreach ($languages as $language) {
            $helper->fields_value['design_title'] = $design->design_title;
        }

        return $helper->generateForm([$fields_form]);
    }

    public function updateTag($params)
    {
        $type = 'radio';
        if (true == Tools::version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
            $type = 'switch';
        }
        $fields_form = [
            'form' => [
                'id_form' => 'tag_form',
                'legend' => [
                    'title' => $this->l('Edit Tag'),
                    'icon' => 'icon-pencil',
                ],
                'input' => [
                    [
                        'type' => 'hidden',
                        'name' => 'id_tag',
                    ],
                    [
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Tag Title'),
                        'name' => 'tag_title',
                        'class' => 'form-control m-b-1',
                    ],
                    [
                        'type' => 'text',
                        'lang' => false,
                        'label' => $this->l('Price'),
                        'name' => 'price',
                        'class' => 'form-control m-b-1',
                        'prefix' => $this->context->currency->iso_code,
                    ],
                    [
                        'label' => $this->l('Moveable'),
                        'lang' => false,
                        'active' => 'status',
                        'name' => 'draggable',
                        'type' => $type,
                        'align' => 'center',
                        'class' => 't',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'draggable_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'draggable_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('Enabled/disabled moveable from front.'),
                    ],
                    [
                        'label' => $this->l('Resizable'),
                        'lang' => false,
                        'active' => 'status',
                        'name' => 'resizable',
                        'type' => $type,
                        'align' => 'center',
                        'class' => 't',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'resizable_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'resizable_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('Enabled/disabled resizable from front.'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Update'),
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $languages = $this->context->controller->getLanguages();
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $params['id_tag'] . '_product_customized_tags';
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUpdateTag';
        $helper->currentIndex = $this->context->link->getAdminLink('CustomProductDesigner');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'uri' => $this->getPathUri(),
            'languages' => $languages,
            'id_language' => $this->context->language->id,
        ];
        $helper->fields_value['id_tag'] = ((isset($params['id_tag']) && $params['id_tag']) ? $params['id_tag'] : 0);
        $tag = new DesignTags($params['id_tag']);
        $helper->fields_value['price'] = $tag->price;
        $helper->fields_value['length'] = $tag->length;
        $helper->fields_value['draggable'] = $tag->draggable;
        $helper->fields_value['resizable'] = $tag->resizable;
        foreach ($languages as $language) {
            $helper->fields_value['tag_title'] = $tag->tag_title;
        }

        if ($tag->type == 'text') {
            $fields_form['form']['input'][] = [
                'type' => 'text',
                'lang' => false,
                'label' => $this->l('No of characters'),
                'name' => 'length',
                'class' => 'form-control m-b-1',
            ];
        }

        return $helper->generateForm([$fields_form]);
    }

    public function getURLForm($params)
    {
        $this->context->smarty->assign('cpd_link', Context::getContext()->link);
        $this->context->smarty->assign('cpd_id_product', $params['id_product']);

        return [
            'html' => $this->display(__FILE__, 'views/templates/front/url_form.tpl'),
        ];
    }

    public function getImageDir()
    {
        return $this->local_path . 'data/logo/';
    }

    protected function getFileName($file, $name)
    {
        $extension = explode('.', $file);

        return Tools::str2url($name) . '.' . end($extension);
    }

    protected function assignAttributesGroups($product)
    {
        $groups = [];
        $attributes_groups = $product->getAttributesGroups($this->context->language->id);

        if (is_array($attributes_groups) && $attributes_groups) {
            foreach ($attributes_groups as $k => $row) {
                // Color management
                if (!isset($groups[$row['id_attribute_group']])) {
                    $groups[$row['id_attribute_group']] = [
                        'group_name' => $row['group_name'],
                        'name' => $row['public_group_name'],
                        'group_type' => $row['group_type'],
                        'default' => -1,
                    ];
                }

                $groups[$row['id_attribute_group']]['attributes'][$row['id_attribute']] = [
                    'name' => $row['attribute_name'],
                    'html_color_code' => $row['attribute_color'],
                    'texture' => (@filemtime(_PS_COL_IMG_DIR_ . $row['id_attribute'] . '.jpg')) ? _THEME_COL_DIR_ . $row['id_attribute'] . '.jpg' : '',
                ];

                if ($row['default_on'] && $groups[$row['id_attribute_group']]['default'] == -1) {
                    $groups[$row['id_attribute_group']]['default'] = (int) $row['id_attribute'];
                }
                if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']])) {
                    $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
                }
                $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] += (int) $row['quantity'];
                $groups[$row['id_attribute_group']]['minimal_quantity'][$row['id_attribute']] = (int) $row['minimal_quantity'];
            }

            // wash attributes list depending on available attributes depending on selected preceding attributes
            $current_selected_attributes = [];
            $count = 0;
            foreach ($groups as &$group) {
                ++$count;
                if ($count > 1) {
                    // find attributes of current group, having a possible combination with current selected
                    $id_attributes = Db::getInstance()->executeS('SELECT `id_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac2 
                        WHERE `id_product_attribute` IN (
                            SELECT pac.`id_product_attribute`
                                FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
                                WHERE id_product = ' . $product->id . ' AND id_attribute IN (' . implode(',', array_map('intval', $current_selected_attributes)) . ')
                                GROUP BY id_product_attribute
                                HAVING COUNT(id_product) = ' . count($current_selected_attributes) . '
                        ) AND id_attribute NOT IN (' . implode(',', array_map('intval', $current_selected_attributes)) . ')');
                    foreach ($id_attributes as $k => $row) {
                        $id_attributes[$k] = (int) $row['id_attribute'];
                    }
                    foreach ($group['attributes'] as $key => $attribute) {
                        if (!in_array((int) $key, $id_attributes)) {
                            unset($group['attributes'][$key]);
                            unset($group['attributes_quantity'][$key]);
                        }
                    }
                }
                // find selected attribute or first of group
                $index = 0;
                $current_selected_attribute = 0;
                foreach ($group['attributes'] as $key => $attribute) {
                    if ($index === 0) {
                        $current_selected_attribute = $key;
                    }
                }
                if ($current_selected_attribute > 0) {
                    $current_selected_attributes[] = $current_selected_attribute;
                }
            }

            // wash attributes list (if some attributes are unavailables and if allowed to wash it)
            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && Configuration::get('PS_DISP_UNAVAILABLE_ATTR') == 0) {
                foreach ($groups as &$group) {
                    foreach ($group['attributes_quantity'] as $key => &$quantity) {
                        if ($quantity <= 0) {
                            unset($group['attributes'][$key]);
                        }
                    }
                }
            }
        }

        return $groups;
    }

    public function displayTable($params)
    {
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->no_link = true;
        $helper->simple_header = false;
        $helper->show_toolbar = true;
        $helper->bulk_actions = true;
        $helper->module = $this;

        $helper->table = $params['table'];
        $helper->title = $params['title'];
        $helper->actions = $params['actions'];
        $helper->identifier = $params['identifier'];
        $helper->listTotal = $params['listTotal'];
        $helper->list_id = $params['identifier'];

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash',
            ],
        ];
        $helper->toolbar_btn = [
            'new' => [
                'desc' => $this->l('Add New'),
                'href' => $helper->currentIndex . '&add' . $this->name . '&token=' . $helper->token . '&' . $params['add_link'],
            ],
        ];

        return $helper->generateList($params['callback'], $params['fields_list']);
    }

    protected function getFontParams()
    {
        $fields_list = [
            'id_font' => [
                'align' => 'center',
                'title' => $this->l('ID'),
                'name' => 'id_font',
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ],
            'font_name' => [
                'title' => $this->l('Font Name'),
                'width' => 200,
                'type' => 'text',
                'orderby' => true,
                'name' => 'font_name',
                'callback' => 'getFontPreview',
                'callback_object' => $this,
                'search' => false,
            ],
            'status' => [
                'align' => 'center',
                'title' => $this->l('Status'),
                'width' => 20,
                'type' => 'bool',
                'active' => 'change_font_status&',
                'name' => 'status',
                'search' => false,
            ],
        ];

        return [
            'table' => 'fonts',
            'identifier' => 'id_font',
            'title' => $this->l('Fonts'),
            'add_link' => 'add_new_font',
            'actions' => ['delete'],
            'fields_list' => $fields_list,
            'callback' => ProductCustomization::getFonts(),
            'listTotal' => count(ProductCustomization::getFonts()),
        ];
    }

    protected function getColorParams()
    {
        $fields_list = [
            'id_colour' => [
                'align' => 'center',
                'title' => $this->l('ID'),
                'name' => 'id_colour',
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ],
            'colour_name' => [
                'title' => $this->l('Color Name'),
                'width' => 'auto',
                'type' => 'text',
                'name' => 'colour_name',
                'search' => false,
            ],
            'colour_code' => [
                'title' => $this->l('Preview'),
                'width' => 100,
                'type' => 'text',
                'orderby' => true,
                'name' => 'colour_code',
                'callback' => 'getColorPreview',
                'callback_object' => $this,
                'search' => false,
            ],
            'status' => [
                'align' => 'center',
                'title' => $this->l('Status'),
                'width' => 20,
                'type' => 'bool',
                'active' => 'change_color_status&',
                'name' => 'status',
                'search' => false,
            ],
        ];

        return [
            'table' => 'colour',
            'identifier' => 'id_colour',
            'title' => $this->l('Colors'),
            'add_link' => 'add_new_color',
            'actions' => ['edit', 'delete'],
            'fields_list' => $fields_list,
            'callback' => ProductCustomization::getColors(),
            'listTotal' => count(ProductCustomization::getColors()),
        ];
    }

    protected function getImageParams()
    {
        $fields_list = [
            'id_logo' => [
                'align' => 'center',
                'title' => $this->l('ID'),
                'name' => 'id_logo',
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ],
            'logo_path' => [
                'title' => $this->l('Preview'),
                'width' => 100,
                'type' => 'text',
                'orderby' => true,
                'name' => 'logo_path',
                'callback' => 'getImagePreview',
                'callback_object' => $this,
                'search' => false,
            ],
            'tags' => [
                'align' => 'center',
                'title' => $this->l('Tags'),
                'name' => 'tags',
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ],
            'status' => [
                'align' => 'center',
                'title' => $this->l('Status'),
                'width' => 20,
                'type' => 'bool',
                'active' => 'change_logo_status&',
                'name' => 'status',
                'search' => false,
            ],
        ];

        return [
            'table' => 'logo',
            'identifier' => 'id_logo',
            'title' => $this->l('Images'),
            'add_link' => 'add_new_logo',
            'actions' => ['delete'],
            'fields_list' => $fields_list,
            'callback' => ProductCustomization::getLogos(),
            'listTotal' => count(ProductCustomization::getLogos()),
        ];
    }

    protected function getMaterialParams()
    {
        $fields_list = [
            'id_material' => [
                'align' => 'center',
                'title' => $this->l('ID'),
                'name' => 'id_material',
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ],
            'material_name' => [
                'title' => $this->l('Material Name'),
                'width' => 'auto',
                'type' => 'text',
                'name' => 'material_name',
                'search' => false,
            ],
            'material_path' => [
                'title' => $this->l('Preview'),
                'width' => 100,
                'type' => 'text',
                'orderby' => true,
                'name' => 'material_path',
                'callback' => 'getMaterialPreview',
                'callback_object' => $this,
                'search' => false,
            ],
            'price' => [
                'title' => $this->l('Price'),
                'width' => 'auto',
                'type' => 'price',
                'name' => 'price',
                'search' => false,
            ],
            'status' => [
                'align' => 'center',
                'title' => $this->l('Status'),
                'width' => 20,
                'type' => 'bool',
                'active' => 'change_material_status&',
                'name' => 'status',
                'search' => false,
            ],
        ];

        return [
            'table' => 'material',
            'identifier' => 'id_material',
            'title' => $this->l('Print Material'),
            'add_link' => 'add_new_material',
            'actions' => ['edit', 'delete'],
            'fields_list' => $fields_list,
            'callback' => ProductCustomization::getMaterials(),
            'listTotal' => count(ProductCustomization::getMaterials()),
        ];
    }

    public function getFontPreview($tr, $font)
    {
        if (isset($font) && $font) {
            $this->smarty->assign([
                'fontName' => $font['font_name'],
            ]
            );

            return $this->display(dirname(__FILE__), 'views/templates/hook/previews/font.tpl');
        }
    }

    public function getColorPreview($tr, $color)
    {
        if (isset($color) && $color) {
            $this->smarty->assign([
                'colorCode' => $color['colour_code'],
            ]
            );

            return $this->display(dirname(__FILE__), 'views/templates/hook/previews/color.tpl');
        }
    }

    public function getImagePreview($tr, $img)
    {
        if (isset($img) && $img) {
            $this->smarty->assign([
                'logoPathImg' => $this->context->link->getMediaLink($img['logo_path']),
            ]
            );

            return $this->display(dirname(__FILE__), 'views/templates/hook/previews/img.tpl');
        }
    }

    public function getMaterialPreview($tr, $material)
    {
        if (isset($material) && $material) {
            $this->smarty->assign([
                'logoPathImgMaterial' => $this->context->link->getMediaLink($material['material_path']),
            ]
            );

            return $this->display(dirname(__FILE__), 'views/templates/hook/previews/img_material.tpl');
        }
    }

    public function getWorkplaceDesign($id)
    {
        $result = Db::getInstance()->executeS('
		SELECT *
		FROM `' . _DB_PREFIX_ . 'product_customized_workplace`
		WHERE `id_design` = ' . (int) $id);

        return array_shift($result);
    }

    public function getToolBox($params)
    {
        if (isset($params) && $params) {
            $customize = new ProductCustomization();
            $tags = $customize->getTagsForImgs($params['id_product']);
            $tags_clean = [];
            if (!empty($tags)) {
                $tags_clean = explode(',', $tags);
                $tags_clean = array_unique($tags_clean);
            }
            $id_shop = Context::getContext()->shop->id;
            $this->context->smarty->assign([
                'id_tag' => $params['id_tag'],
                'id_product_old' => $params['id_product'],
                'cpd_link' => Context::getContext()->link,
                '_tags' => $tags_clean,
                'logos' => $customize->getActiveLogos($params['id_product'], $id_shop, $this->context->cookie->id_guest),
                'fonts' => $customize->getActiveFonts($params['id_product'], $id_shop),
                'colors' => $customize->getActiveColors($params['id_product'], $id_shop),
                'LOGO_UPLOAD_EN_DS' => (int) Configuration::get('LOGO_UPLOAD_EN_DS', null, $this->id_shop_group, $this->id_shop),
                'LOGO_UPLOAD_URL' => (int) Configuration::get('LOGO_UPLOAD_URL', null, $this->id_shop_group, $this->id_shop),
                'CPD_ENABLE_PRE_DESIGNS' => (int) Configuration::get('CPD_ENABLE_PRE_DESIGNS', null, $this->id_shop_group, $this->id_shop),
                'DEFAULT_CUSTOM_COLOR' => (string) Configuration::get('DEFAULT_CUSTOM_COLOR', null, $this->id_shop_group, $this->id_shop),
                'DEFAULT_CUSTOM_FONT' => (string) Configuration::get('DEFAULT_CUSTOM_FONT', null, $this->id_shop_group, $this->id_shop),
                'design_handler' => $this->context->link->getModuleLink('customproductdesign', 'cpdesign'),
            ]);
            $this->context->smarty->assign('cpd_link', Context::getContext()->link);
            $this->context->smarty->assign('cpd_id_product', $params['id_product']);

            return $this->display(__FILE__, 'views/templates/hook/design/center_panel/tools/' . $params['type'] . '_box.tpl');
        } else {
            return false;
        }
    }

    public function getBaseLink($id_shop = null, $ssl = null, $relative_protocol = false)
    {
        static $force_ssl = null;

        if ($ssl === null) {
            if ($force_ssl === null) {
                $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
            }
            $ssl = $force_ssl;
        }

        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null) {
            $shop = new Shop($id_shop);
        } else {
            $shop = Context::getContext()->shop;
        }

        if ($relative_protocol) {
            $base = '//' . ($ssl && $this->ssl_enable ? $shop->domain_ssl : $shop->domain);
        } else {
            $base = (($ssl && $this->ssl_enable) ? 'https://' . $shop->domain_ssl : 'http://' . $shop->domain);
        }

        return $base . $shop->getBaseURI();
    }

    public function getTemplateHtml($params)
    {
        $id_shop = (int) $this->context->shop->id;
        $id_lang = (int) $this->context->language->id;
        // If its admin creating template
        $id_employee = 0;
        $id_design = 0;
        $idd = (int) $params['id_design'];
        $idt = (int) $params['id_template'];
        $id_product = (int) (isset($params) && isset($params['id_product']) ? $params['id_product'] : Tools::getValue('id_product'));
        $relative_templates = (int) Configuration::get('CPD_ENABLE_PRE_DESIGNS_RELATIVE', null, $this->id_shop_group, $this->id_shop);
        if ($relative_templates > 0) {
            $design_templates = Designer::getAllTemplatesRelatively($id_product);
        } else {
            $design_templates = Designer::getAllTemplates();
        }
        if ($id_product && Validate::isLoadedObject($product = new Product((int) $id_product, true, (int) $this->context->cookie->id_lang))) {
            if (ProductCustomization::getTypeByProduct($id_product, 'status')) {
                $customization = Designer::getProductDesignsForTemplate($id_product, $id_lang, $idd, $idt);

                $combinations = [];
                if ($product->hasAttributes()) {
                    $combinations = $this->assignAttributesGroups($product);
                }
                if (!empty($customization)) {
                    foreach ($customization as &$design) {
                        $design['workplace'] = $this->getWorkplaceDesign($design['designs']->id_customized);
                    }
                }
                $product_link = $this->context->link->getProductLink($product, null, null, null, $id_lang, $id_shop);
                $defaultCurrency = new Currency((int) Configuration::get('PS_CURRENCY_DEFAULT'));
                $base_link = $this->getBaseLink();
                $_link = Context::getContext()->link;
                $this->context->smarty->assign([
                    'custom_product' => $product,
                    'id_product_old' => $id_product,
                    'product_link' => $product_link,
                    'version' => _PS_VERSION_,
                    'combinations' => $combinations,
                    'customization' => $customization,
                    'currency' => $this->context->currency,
                    'has_attributes' => (int) $product->hasAttributes(),
                    'price_display_precision' => _PS_PRICE_DISPLAY_PRECISION_,
                    'exchangeRate' => $this->context->currency->conversion_rate,
                    'fonts' => ProductCustomization::getActiveFonts($id_product, $id_shop),
                    'colors' => ProductCustomization::getActiveColors($id_product, $id_shop),
                    'logos' => ProductCustomization::getActiveLogos($id_product, $id_shop),
                    'materials' => ProductCustomization::getMaterials(true, $id_product, $this->id_shop, $this->id_shop_group),
                    'LOGO_UPLOAD_EN_DS' => (int) Configuration::get('LOGO_UPLOAD_EN_DS', null, $this->id_shop_group, $this->id_shop),
                    'DESIGN_PREVIEW_WIDTH' => (int) Configuration::get('DESIGN_PREVIEW_WIDTH', null, $this->id_shop_group, $this->id_shop),
                    'DESIGN_PREVIEW_HEIGHT' => (int) Configuration::get('DESIGN_PREVIEW_HEIGHT', null, $this->id_shop_group, $this->id_shop),
                    'LOGO_UPLOAD_URL' => (int) Configuration::get('LOGO_UPLOAD_URL', null, $this->id_shop_group, $this->id_shop),
                    'CPD_ENABLE_PRE_DESIGNS' => (int) Configuration::get('CPD_ENABLE_PRE_DESIGNS', null, $this->id_shop_group, $this->id_shop),
                    'DEFAULT_CUSTOM_COLOR' => (string) Configuration::get('DEFAULT_CUSTOM_COLOR', null, $this->id_shop_group, $this->id_shop),
                    'DEFAULT_CUSTOM_FONT' => (string) Configuration::get('DEFAULT_CUSTOM_FONT', null, $this->id_shop_group, $this->id_shop),
                    'price_display' => Product::getTaxCalculationMethod((int) $this->context->cookie->id_customer),
                    'CPD_ENABLE_DYNAMIC_PRICING' => Configuration::get('CPD_ENABLE_DYNAMIC_PRICING', null, $this->id_shop_group, $this->id_shop),
                    'CPD_ENABLE_LAYERS_SECTION' => Configuration::get('CPD_ENABLE_LAYERS_SECTION', null, $this->id_shop_group, $this->id_shop),
                    'design_handler' => $this->context->link->getModuleLink('customproductdesign', 'cpdesign'),
                    '_id_employee' => $id_employee,
                    '_id_design' => $id_design,
                    '_base_link' => $base_link,
                    'design_templates' => $design_templates,
                    'link' => $_link,
                    'id_template' => $idt,
                    '_id_guest' => $this->context->cookie->id_guest,
                    '_id_customer' => $this->context->cookie->id_customer,
                ]);
                $this->context->smarty->assign('cpd_link', Context::getContext()->link);
                $this->context->smarty->assign('cpd_id_product', $id_product);

                return $this->display(__FILE__, 'views/templates/hook/template_design/custom_product.tpl');
            }
        }
    }

    public function getTagHtml($params, $part)
    {
        $idd = (int) $params['id_design'];
        $count = (int) $params['tag_count'];
        $type = $params['type'];
        $price = $params['price'];
        $this->context->smarty->assign([
            'currency' => $this->context->currency,
            'price_display_precision' => _PS_PRICE_DISPLAY_PRECISION_,
            'exchangeRate' => $this->context->currency->conversion_rate,
            'type' => $type,
            'count' => $count,
            'tagprice' => $price,
            '_id_design' => $idd,
        ]);
        if ($part == 'main') {
            return $this->display(__FILE__, 'views/templates/hook/dynamic_tags/design_tags.tpl');
        } elseif ($part == 'left') {
            return $this->display(__FILE__, 'views/templates/hook/dynamic_tags/design_tags_left.tpl');
        } elseif ($part == 'layer') {
            if ($type == 'img') {
                $type = 'image';
            } elseif ($type == 'txt') {
                $type = 'text';
            }
            $this->context->smarty->assign([
                'type' => $type,
            ]);

            return $this->display(__FILE__, 'views/templates/hook/dynamic_tags/design_tags_layer.tpl');
        }
    }

    public function getDesignPrice($id_product, $id_product_attribute, $id_cart, $id_customization)
    {
        return (float) ProductCustomization::getDesignPrice(
            $id_product,
            $id_product_attribute,
            $id_cart,
            $id_customization
        );
    }

    public function flushCache()
    {
        Tools::clearSmartyCache();
        Tools::clearXMLCache();
        Media::clearCache();
        Tools::generateIndex();
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')
            && is_callable(['Tools', 'clearAllCache'])) {
            Tools::clearAllCache();
        }
    }
}
