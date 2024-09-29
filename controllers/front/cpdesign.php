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

include_once _PS_MODULE_DIR_ . 'customproductdesign/classes/CpdTools.php';
class CustomProductDesignCpdesignModuleFrontController extends ModuleFrontController
{
    private $action;
    private $id_product;
    private $tags = [
        'text',
        'image',
    ];
    private $extensions = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'svg',
    ];

    public function init()
    {
        parent::init();
        $this->context = Context::getContext();
        $this->action = Tools::getValue('action');
        $this->id_product = (int) Tools::getValue('id_product');
        if (!isset($this->context->currency)) {
            $this->context->currency = Tools::setCurrency($this->context->cookie);
        }
    }

    public function initContent()
    {
        switch ($this->action) {
            case 'getURLForm':
                $this->getURLForm($this->action);
                break;
            case 'getToolBox':
                $this->getToolBox();
                break;
            case 'upload_logo':
                $this->processUplopadFile();
                break;
            case 'ajaxUploadImage':
                $this->processUploadFromURL();
                break;
            case 'getFormattedPrice':
                $this->processFormatePrice();
                break;
            case 'getAttribute':
                $this->getAttributePrice();
                break;
            case 'getDesignPrice':
                $this->getDesignPrice();
                break;
            case 'getFlatPriceFresh':
                $this->getFlatPriceFresh();
                break;
            case 'getSaveTemplate':
                $this->getSaveTemplate();
                break;
            case 'getSaveTemplateElements':
                $this->getSaveTemplateElements();
                break;
            case 'getTemplateToDeploy':
                $this->getTemplateToDeploy();
                break;
            case 'getTagToDeploy':
                $this->getTagToDeploy();
                break;
            case 'add_to_cart_explict':
                if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                    $this->addDesignsToCartOneSeven();
                } else {
                    $this->addDesignsToCartOneSix();
                }
                break;
            default:
                (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) ? exit($this->ajaxRender(json_encode(['hasError' => true, 'msg' => $this->module->l('Unknown Operation')]))) : exit(json_encode(['hasError' => true, 'msg' => $this->module->l('Unknown Operation')]));
        }
    }

    protected function getURLForm($action)
    {
        $result = ['hasError' => false, 'msg' => ''];
        $params = ['id_product' => (int) Tools::getValue('id_product')];
        if (method_exists($this->module, $action)) {
            $result = call_user_func([$this->module, $action], $params);
            $result['msg'] = $this->module->l('Operation Successful.', 'cpdesign');
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->module->l('Invalid action.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function getSaveTemplate()
    {
        $result = ['hasError' => false, 'msg' => '', 'id' => 0];
        $id_design = (int) Tools::getValue('id_design');
        $img = Tools::getValue('img');
        $return = $this->SaveTemplate($id_design, $img);
        $result['id'] = $return;
        $result['msg'] = $this->module->l('Operation Successful.', 'cpdesign');
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function getSaveTemplateElements()
    {
        $result = ['hasError' => false, 'msg' => '', 'id' => 0];
        $id_template = (int) Tools::getValue('id_template');
        $id_tag = (int) Tools::getValue('id_tag');
        $type = Tools::getValue('type');
        $style = Tools::getValue('style');
        $child_style = Tools::getValue('child_style');
        $value = Tools::getValue('value');
        $return = $this->SaveTemplateElements($id_template, $id_tag, $type, $style, $child_style, $value);
        $result['id'] = $return;
        $result['msg'] = $this->module->l('Operation Successful.', 'cpdesign');
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function getToolBox()
    {
        $result = ['hasError' => false, 'msg' => '', 'html' => ''];
        $type = trim((string) Tools::getValue('type'));
        if (Tools::getIsset('id_product') && !empty($type) && in_array($type, $this->tags)) {
            $params = [
                'id_product' => Tools::getValue('id_product'),
                'type' => $type,
                'id_tag' => Tools::getValue('id_tag'),
            ];
            $html = $this->module->getToolBox($params);
            $result['html'] = $html;
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->module->l('Invalid request.', 'cpdesign');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function processUplopadFile()
    {
        $id_product = (int) Tools::getValue('id_product');
        $allowed = ['jpg', 'jpeg', 'png', 'bmp'];
        $result = ['up_state' => false, 'logo_res' => 3];
        if ($id_product && Validate::isLoadedObject($product = new Product((int) $id_product, true, (int) $this->context->cookie->id_lang))) {
            $ext = (isset($_FILES, $_FILES['logo'])) ? (string) pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION) : null;
            $id_shop = $this->context->shop->id;
            $id_customer = (isset($this->context->customer->id)) ? $this->context->customer->id : (isset($this->context->cookie->id_customer) ? $this->context->cookie->id_customer : 0);
            $id_guest = (isset($this->context->cookie->id_guest)) ? $this->context->cookie->id_guest : 0;
            if ($id_guest <= 0) {
                Guest::setNewGuest($this->context->cookie);
            }
            $id_guest = $this->context->cookie->id_guest;
            if (isset($ext) && in_array($ext, $allowed)) {
                $path = _PS_MODULE_DIR_ . 'customproductdesign/data/logo/';
                $temp_name = $_FILES['logo']['tmp_name'];
                $file_name = $_FILES['logo']['name'];
                if (move_uploaded_file($temp_name, $path . $file_name)) {
                    $logo_name = (string) pathinfo($_FILES['logo']['name'], PATHINFO_BASENAME);
                    $logo = [
                        'logo_name' => $logo_name,
                        'logo_path' => __PS_BASE_URI__ . 'modules/customproductdesign/data/logo/' . $file_name,
                        'status' => 1,
                        'date_add' => date('Y-m-d H:i:s'),
                        'id_shop' => (int) $id_shop,
                        'id_customer' => (int) $id_customer,
                        'id_guest' => (int) $id_guest,
                    ];
                    $id_logo = ProductCustomization::addLogo($logo);
                    if ($id_logo) {
                        $logo = ProductCustomization::getLogoById($id_logo, $id_shop);
                        $selected_images = ProductCustomization::getSettingsByType($product->id, 'selected_images');
                        $selected_values = (isset($selected_images, $selected_images['value']) && $selected_images['value']) ? json_decode($selected_images['value']) : [];
                        array_push($selected_values, (string) $id_logo);
                        $data = [
                            'value' => json_encode($selected_values),
                            'type' => 'selected_images',
                        ];
                        // associate uploaded image to current product
                        if (ProductCustomization::typeExist($product->id, $data['type'])) {
                            $data['date_upd'] = date('Y-m-d H:i:s');
                            ProductCustomization::updateSettings($product->id, $data, $data['type']);
                        } else {
                            $data['id_product'] = (int) $product->id;
                            $data['date_add'] = date('Y-m-d H:i:s');
                            ProductCustomization::addSettings($data);
                        }

                        $result = [
                            'up_state' => true,
                            'logo_name' => $logo['logo_name'],
                            'logo_path' => $logo['logo_path'],
                            'id_logo' => $logo['id_logo'],
                            'logo_res' => 1,
                        ];
                    } else {
                        $result = ['up_state' => false, 'logo_res' => 2];
                    }
                }
            }
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function processUploadFromURL()
    {
        $return = ['hasError' => false, 'name' => '', 'id_logo' => 0, 'msg' => ''];
        $image_url = Tools::getValue('image_url');
        $id_product = (int) Tools::getValue('id_product');
        if (!Validate::isUrl($image_url)) {
            $return['hasError'] = true;
            $return['msg'] = $this->module->l('Invalid image url.', 'cpdesign');
        } else {
            $mime = [
                'image/png' => 'png',
                'image/jpg' => 'jpg',
                'image/jpeg' => 'jpeg',
                'image/gif' => 'gif',
            ];

            $ch = curl_init($image_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $content = curl_exec($ch);
            $curl_info = curl_getinfo($ch);
            curl_close($ch);

            $http_code = $curl_info['http_code'];
            if ($http_code != 200) {
                $return['hasError'] = true;
                $return['msg'] = $this->module->l('Encounter an error while trying to upload image from URL.', 'cpdesign');
                if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
                    exit($this->ajaxRender(json_encode($return)));
                } else {
                    exit(Tools::jsonEncode($return));
                }
            }

            $content_type = $curl_info['content_type'];
            if (!isset($mime[$content_type])) {
                $return['hasError'] = true;
                $return['msg'] = $this->module->l('Invalid image/image content not found.', 'cpdesign');
                if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
                    exit($this->ajaxRender(json_encode($return)));
                } else {
                    exit(Tools::jsonEncode($return));
                }
            }

            $size = (float) Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1000000;
            $curl_length = $curl_info['download_content_length'];

            if ($curl_length > $size) {
                $return['hasError'] = true;
                $return['msg'] = $this->module->l('Please upload a file with size less than', 'cpdesign');
                if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
                    exit($this->ajaxRender(json_encode($return)));
                } else {
                    exit(Tools::jsonEncode($return));
                }
            }

            $extension = $mime[$content_type];
            if (!in_array(Tools::strtolower($extension), $this->extensions)) {
                $return['hasError'] = true;
                $return['msg'] = $this->module->l('Image type not allowed.', 'cpdesign');
                if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
                    exit($this->ajaxRender(json_encode($return)));
                } else {
                    exit(Tools::jsonEncode($return));
                }
            }

            $filename = $this->getRandName($extension);

            $path = $this->getUploadPath($filename);
            if (!file_put_contents($path, $content)) {
                $path = realpath($path);
            }

            $filename = basename($path);
            $logo = [
                'logo_name' => $filename,
                'logo_path' => __PS_BASE_URI__ . 'modules/customproductdesign/data/logo/' . $filename,
                'status' => 1,
                'date_add' => date('Y-m-d H:i:s'),
                'id_shop' => $this->context->shop->id,
            ];

            $id_logo = ProductCustomization::addLogo($logo);
            if ($id_logo) {
                $logo = ProductCustomization::getLogoById($id_logo, $this->context->shop->id);

                $selected_images = ProductCustomization::getSettingsByType($id_product, 'selected_images');
                $selected_values = (isset($selected_images, $selected_images['value']) && $selected_images['value']) ? json_decode($selected_images['value']) : [];
                array_push($selected_values, (string) $id_logo);
                $data = [
                    'value' => json_encode($selected_values),
                    'type' => 'selected_images',
                ];
                // associate uploaded image to current product
                if (ProductCustomization::typeExist($id_product, $data['type'])) {
                    $data['date_upd'] = date('Y-m-d H:i:s');
                    ProductCustomization::updateSettings($id_product, $data, $data['type']);
                } else {
                    $data['id_product'] = (int) $id_product;
                    $data['date_add'] = date('Y-m-d H:i:s');
                    ProductCustomization::addSettings($data);
                }

                $return = [
                    'hasError' => false,
                    'name' => $logo['logo_name'],
                    'path' => $logo['logo_path'],
                    'id' => $logo['id_logo'],
                    'msg' => $this->module->l('Image successfully uploaded from given url.', 'cpdesign'),
                ];
            }
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($return)));
        } else {
            exit(Tools::jsonEncode($return));
        }
    }

    protected function getRandName($extension)
    {
        return md5(time() . $extension) . '.' . $extension;
    }

    protected function getUploadPath($filename)
    {
        return $this->module->getImageDir() . $filename;
    }

    protected function processFormatePrice()
    {
        $result = ['success' => false, 'total' => 0.0];
        $price = Tools::getValue('price');
        $price = Tools::ps_round($price, 2);
        if (Validate::isFloat($price)) {
            $result = ['success' => true, 'total' => Tools::displayPrice(Tools::getValue('price'), $this->context->currency)];
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function getAttributePrice()
    {
        $result = ['success' => false, 'price' => 0.0, 'msg' => ''];
        $id_product = (int) Tools::getValue('cpd_product');
        if (!$id_product || !Validate::isLoadedObject($product = new Product($id_product))) {
            $result['success'] = false;
            $result['msg'] = $this->module->l('Customized Product not found');
        } else {
            $id_product_attribute = ProductCustomization::getIdProductAttributesByIdAttributes($product->id, Tools::getValue('cpd_group', ''), true);
            $price = $product->getPrice(true, $id_product_attribute);
            $result = ['success' => true, 'price' => Tools::displayPrice($price, $this->context->currency)];
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function getDesignPrice()
    {
        $result = ['success' => false, 'price' => 0.0, 'msg' => ''];
        $id_product = (int) Tools::getValue('cpd_product');
        $qty = (int) Tools::getValue('qty');
        $price = (float) Tools::getValue('price');
        $index = (int) Tools::getValue('index');
        if (!$id_product || !Validate::isLoadedObject($product = new Product($id_product))) {
            $result['success'] = false;
            $result['msg'] = $this->module->l('Customized Product not found');
        } else {
            $id_product_attribute = 0;
            $min_qty = 1;
            if (Tools::getValue('has_attributes')) {
                $id_product_attribute = ProductCustomization::getIdProductAttributesByIdAttributes($product->id, Tools::getValue('cpd_group', ''), true);
            }
            if ($index <= 1) {
                $price += $product->getPrice(true, $id_product_attribute, 6, null, false, true, $qty);
            }
            $result = ['success' => true, 'price' => Tools::displayPrice($price, $this->context->currency), 'currency' => $this->context->currency->iso_code];
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function getFlatPriceFresh()
    {
        $result = ['success' => false, 'price' => 0.0, 'msg' => ''];
        $id_product = (int) Tools::getValue('cpd_product');
        $price = '00.00';
        $index = (int) Tools::getValue('index');
        $qty = (int) Tools::getValue('qty');
        if (!$id_product || !Validate::isLoadedObject($product = new Product($id_product))) {
            $result['success'] = false;
            $result['msg'] = $this->module->l('Customized Product not found');
        } else {
            $id_product_attribute = 0;
            $min_qty = 1;
            if (Tools::getValue('has_attributes')) {
                $id_product_attribute = ProductCustomization::getIdProductAttributesByIdAttributes($product->id, Tools::getValue('cpd_group', ''), true);
            }
            if ($index <= 1) {
                $price += $product->getPrice(true, $id_product_attribute, 6, null, false, true, $qty);
            }
            $result = ['success' => true, 'price' => Tools::displayPrice($price, $this->context->currency), 'currency' => $this->context->currency->iso_code];
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function setDesignCart()
    {
        if (!$this->context->cookie->id_guest) {
            Guest::setNewGuest($this->context->cookie);
        }

        if (!$this->context->cart->id) {
            if ($this->context->cookie->id_guest) {
                $guest = new Guest(Context::getContext()->cookie->id_guest);
                $this->context->cart->mobile_theme = $guest->mobile_theme;
            }

            $this->context->cart->add();
            if ($this->context->cart->id) {
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
            }
        }
        $this->context->cart = $this->context->cart;
    }

    protected function getCustomization($data)
    {
        if (!isset($data) || !is_array($data) && !$data) {
            return false;
        }

        Db::getInstance()->insert('customization', $data);

        return (int) Db::getInstance()->Insert_ID();
    }

    protected function addCustomizationData($data)
    {
        return (bool) Db::getInstance()->insert('customized_data', $data);
    }

    protected function addDesignsToCartOneSeven()
    {
        $result = ['success' => false, 'msg' => '', 'result' => 0];
        $ki = 1;
        $postValues = CpdTools::getAllValues();
        if (isset($postValues, $postValues['cpd_product']) && $postValues['cpd_product'] && Validate::isLoadedObject($product = new Product((int) $postValues['cpd_product']))) {
            $wanted_qty = (int) $postValues['cpd_qty_wanted'];
            $quantity = $product->minimal_quantity;
            if ($quantity <= 0 && $wanted_qty <= 0) {
                $quantity = 1;
            } else {
                $quantity = $wanted_qty;
            }
            $this->setDesignCart();
            $cart = $this->context->cart;
            // setting default values
            $params = [
                'price' => 0,
                'id_order' => 0,
                'id_customization' => 0,
                'cart_qty' => $quantity,
                'id_attribute_product' => 0,
                'date_add' => date('Y-m-d H:i:s'),
                'cpd_id_product' => (int) $product->id,
                'id_cart' => (isset($cart) ? $cart->id : 0),
                'id_shop' => (int) $this->context->shop->id,
                'currency' => pSQL($this->context->currency->iso_code),
                'id_shop_group' => (int) $this->context->shop->id_shop_group,
                'id_guest' => (isset($this->context->cookie) ? $this->context->cookie->id_guest : 0),
                'id_customer' => (isset($this->context->cookie) ? $this->context->cookie->id_customer : 0),
            ];

            $cu_params = [
                'in_cart' => 1,
                'quantity' => $quantity,
                'id_cart' => $cart->id,
                'id_product_attribute' => 0,
                'id_product' => $product->id,
                'id_address_delivery' => $cart->id_address_delivery,
            ];

            $custom_product_fields = ['material' => null, 'preview' => null];
            if (isset($postValues['customized_design']) && $postValues['customized_design']) {
                foreach ($postValues['customized_design'] as $design) {
                    $id_attribute_product = 0;
                    $customization_field_ids = [];
                    if ($postValues['has_attributes'] && isset($postValues['has_attributes'], $design['cpd_group'])) {
                        $id_attribute_product = ProductCustomization::getIdProductAttributesByIdAttributes($product->id, $design['cpd_group'], true);
                    }

                    // print matreial
                    if (isset($design['print_material']) && $design['print_material']) {
                        $material = ProductCustomization::getMaterialById($design['print_material'], $this->context->shop->id, $this->context->shop->id_shop_group);
                        if (isset($material) && $material) {
                            $custom_product_fields['material'] = $material['material_name'];
                        }
                    }

                    // cart preview for cart
                    if (isset($design['design_image']) && $design['design_image']) {
                        $file_name = md5(uniqid(rand(), true));
                        $width = (Configuration::get('DESIGN_PREVIEW_WIDTH') > 0) ? (int) Configuration::get('DESIGN_PREVIEW_WIDTH') : 300;
                        $height = (Configuration::get('DESIGN_PREVIEW_HEIGHT') > 0) ? (int) Configuration::get('DESIGN_PREVIEW_HEIGHT') : 300;

                        $name = Tools::passwdGen(16);
                        $name = _PS_UPLOAD_DIR_ . $name . '.png';
                        $preview = str_replace('data:image/png;base64,', '', $design['design_image']);
                        file_put_contents($name, base64_decode($preview));

                        ImageManager::resize($name, _PS_UPLOAD_DIR_ . $file_name);
                        ImageManager::resize($name, _PS_UPLOAD_DIR_ . $file_name . '_small', $width, $height);
                        @chmod(_PS_UPLOAD_DIR_ . $file_name, 0777);
                        @chmod(_PS_UPLOAD_DIR_ . $file_name . '_small', 0777);
                        @unlink($name);

                        $custom_product_fields['preview'] = $file_name;
                    }
                    $base_price_product = $product->getPrice(true, $id_attribute_product, 6, null, false, true, $quantity);
                    if ($ki > 1) {
                        $params['price'] = (float) $design['price'] - $base_price_product;
                    } else {
                        $params['price'] = (float) $design['price'];
                    }
                    $params['id_attribute_product'] = (int) $id_attribute_product;
                    $cu_params['id_product_attribute'] = (int) $id_attribute_product;

                    if ($id_attribute_product) {
                        $combination = new Combination($id_attribute_product);
                        $params['cart_qty'] = (int) $quantity;
                        $quantity = (int) $quantity;
                    }

                    if (isset($custom_product_fields, $customization_field_ids)) {
                        $id_customization = (int) $this->getCustomization($cu_params);
                        $params['id_customization'] = (int) $id_customization;

                        $added = false;
                        foreach ($custom_product_fields as $key => $field_value) {
                            if (isset($field_value) && $field_value) {
                                $type = Product::CUSTOMIZE_TEXTFIELD;
                                if ($key == 'preview') {
                                    $type = Product::CUSTOMIZE_FILE;
                                }

                                if ((true === Tools::version_compare(_PS_VERSION_, '1.7.8.0', '>=')) && (true === Tools::version_compare(_PS_VERSION_, '8.0', '<'))) {
                                    $id_insert = $this->insertBaseCustomField($product->id, $type);
                                    $this->insertBaseCustomFieldLang($id_insert, $this->context->shop->id, (int) $this->context->cookie->id_lang, $this->module->l('Design'));
                                    $cu_data = [
                                        'type' => $type,
                                        'value' => $field_value,
                                        'index' => $id_insert,
                                        'id_customization' => $id_customization,
                                    ];
                                } else {
                                    $cu_data = [
                                        'type' => $type,
                                        'value' => $field_value,
                                        'index' => 0, // $customization_field_ids[$counter],
                                        'id_customization' => $id_customization,
                                    ];
                                }

                                if ($this->addCustomizationData($cu_data)) {
                                    $added = true;
                                }
                            }
                        }
                        // adding designer product to cart
                        if ($added && $cart->updateQty($quantity, $product->id, $id_attribute_product, $id_customization, 'up', $cart->id_address_delivery)) {
                            ProductCustomization::addToCartExplict($params);
                            ++$result['result'];
                            $result['success'] = true;
                            ++$ki;
                        }
                    } else {
                        $result['msg'] = $this->module->l('Something went wrong. Please contact us if issue persists.');
                    }
                }
                $result['msg'] = sprintf($this->module->l('%s designs successfully added to cart.'), $result['result']);
            }
        } else {
            $result['msg'] = $this->module->l('Object not found.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function insertBaseCustomField($id, $type)
    {
        Db::getInstance()->insert('customization_field',
            [
                'id_product' => (int) $id,
                'type' => (int) $type,
                'required' => true,
                'is_deleted' => false,
                'is_module' => false]
        );

        return (int) Db::getInstance()->Insert_ID();
    }

    protected function insertBaseCustomFieldLang($id, $id_shop, $id_lang, $name)
    {
        Db::getInstance()->insert('customization_field_lang',
            [
                'id_customization_field' => (int) $id,
                'id_lang' => (int) $id_lang,
                'id_shop' => (int) $id_shop,
                'name' => $name,
            ]
        );

        return (int) Db::getInstance()->Insert_ID();
    }

    protected function addDesignsToCartOneSix()
    {
        $result = ['success' => false, 'msg' => '', 'result' => 0];
        $ki = 1;
        $postValues = CpdTools::getAllValues();
        if (isset($postValues, $postValues['cpd_product']) && $postValues['cpd_product'] && Validate::isLoadedObject($product_old = new Product((int) $postValues['cpd_product']))) {
            $quantity = $product_old->minimal_quantity;
            $this->setDesignCart();
            $cart = $this->context->cart;
            // setting default values
            $params = [
                'price' => 0,
                'id_order' => 0,
                'id_customization' => 0,
                'cart_qty' => $quantity,
                'id_attribute_product' => 0,
                'date_add' => date('Y-m-d H:i:s'),
                'parent' => (int) $product_old->id,
                'id_cart' => (isset($cart) ? $cart->id : 0),
                'id_shop' => (int) $this->context->shop->id,
                'currency' => pSQL($this->context->currency->iso_code),
                'id_shop_group' => (int) $this->context->shop->id_shop_group,
                'id_guest' => (isset($this->context->cookie) ? $this->context->cookie->id_guest : 0),
                'id_customer' => (isset($this->context->cookie) ? $this->context->cookie->id_customer : 0),
            ];

            $cu_params = [
                'in_cart' => 1,
                'quantity' => 0,
                'id_cart' => $cart->id,
                'id_product_attribute' => 0,
                'id_product' => $product_old->id,
                'id_address_delivery' => $cart->id_address_delivery,
            ];

            $custom_product_fields = ['material' => null, 'preview' => null];
            if (isset($postValues['customized_design']) && $postValues['customized_design']) {
                foreach ($postValues['customized_design'] as $design) {
                    $id_product_attribute_new = 0;
                    $id_product_attribute_old = 0;
                    $customization_field_ids = [];
                    if ($postValues['has_attributes'] && isset($postValues['has_attributes'], $design['cpd_group'])) {
                        $id_product_attribute_old = ProductCustomization::getIdProductAttributesByIdAttributes($product_old->id, $design['cpd_group'], true);
                    }

                    // creating new custom product
                    $id_product_new = $this->createCustomizedProduct($product_old->id, $id_product_attribute_old);
                    if (!$id_product_new || !Validate::isLoadedObject($product_new = new Product((int) $id_product_new))) {
                        continue;
                    }

                    $params['cpd_id_product'] = (int) $product_new->id;
                    $cu_params['id_product'] = (int) $product_new->id;

                    $price_old = 0.00;
                    $price_display = Product::getTaxCalculationMethod((int) $this->context->cookie->id_customer);
                    if (!$price_display || $price_display == 2) {
                        $price_old = $product_old->getPrice(true, $id_product_attribute_old, _PS_PRICE_DISPLAY_PRECISION_);
                    } elseif ($price_display == 1) {
                        $price_old = $product_old->getPrice(false, $id_product_attribute_old, _PS_PRICE_DISPLAY_PRECISION_);
                    }

                    $combs = Product::getProductAttributesIds($id_product_new);
                    if (isset($combs) && is_array($combs)) {
                        $combs = array_shift($combs);
                        $id_product_attribute_new = $combs['id_product_attribute'];
                        if ($id_product_attribute_new) {
                            $combination = new Combination($id_product_attribute_new);
                            $product_new->deleteDefaultAttributes();
                            $product_new->setDefaultAttribute((int) $id_product_attribute_new);

                            $params['cart_qty'] = (int) $combination->minimal_quantity;
                            $quantity = (int) $combination->minimal_quantity;
                        }
                    }
                    if ($ki > 1) {
                        $product_new->price = (float) $design['price'];
                    } else {
                        $product_new->price = (float) $price_old + (float) $design['price'];
                    }
                    $stock_qty = Product::getQuantity($product_old->id, $id_product_attribute_old);
                    StockAvailable::setQuantity($product_new->id, (int) $id_product_attribute_new, (int) $stock_qty);
                    $product_new->update();

                    // print matreial
                    if (isset($design['print_material']) && $design['print_material']) {
                        $material = ProductCustomization::getMaterialById($design['print_material'], $this->context->shop->id, $this->context->shop->id_shop_group);
                        if (isset($material) && $material) {
                            $custom_product_fields['material'] = $material['material_name'];
                        }
                    }
                    // cart preview for cart
                    if (isset($design['design_image']) && $design['design_image']) {
                        $file_name = md5(uniqid(rand(), true));
                        $width = (Configuration::get('DESIGN_PREVIEW_WIDTH') > 0) ? (int) Configuration::get('DESIGN_PREVIEW_WIDTH') : (int) Configuration::get('PS_PRODUCT_PICTURE_WIDTH');
                        $height = (Configuration::get('DESIGN_PREVIEW_HEIGHT') > 0) ? (int) Configuration::get('DESIGN_PREVIEW_HEIGHT') : (int) Configuration::get('PS_PRODUCT_PICTURE_HEIGHT');

                        $name = Tools::passwdGen(16);
                        $name = _PS_UPLOAD_DIR_ . $name . '.png';
                        $preview = str_replace('data:image/png;base64,', '', $design['design_image']);
                        file_put_contents($name, base64_decode($preview));

                        // set product cover image
                        $image = new Image();
                        $image->id_product = (int) $product_new->id;
                        $image->position = Image::getHighestPosition($product_new->id) + 1;
                        $image->cover = true;
                        if ($image->add()) {
                            $image->associateTo(Context::getContext()->shop->id);
                            if (!$this->setCoverImage($product_new->id, $image->id, $name)) {
                                $image->delete();
                            }
                        }

                        ImageManager::resize($name, _PS_UPLOAD_DIR_ . $file_name);
                        ImageManager::resize($name, _PS_UPLOAD_DIR_ . $file_name . '_small', $width, $height);
                        @chmod(_PS_UPLOAD_DIR_ . $file_name, 0777);
                        @chmod(_PS_UPLOAD_DIR_ . $file_name . '_small', 0777);
                        @unlink($name);

                        $custom_product_fields['preview'] = $file_name;
                    }
                    $params['price'] = (float) $design['price'];
                    $params['id_attribute_product'] = (int) $id_product_attribute_new;
                    $cu_params['id_product_attribute'] = (int) $id_product_attribute_new;

                    if (isset($custom_product_fields, $customization_field_ids)) {
                        $id_customization = (int) $this->getCustomization($cu_params);
                        $params['id_customization'] = (int) $id_customization;

                        $added = false;
                        foreach ($custom_product_fields as $key => $field_value) {
                            if (isset($field_value) && $field_value) {
                                $type = Product::CUSTOMIZE_TEXTFIELD;
                                if ($key == 'preview') {
                                    $type = Product::CUSTOMIZE_FILE;
                                }

                                $cu_data = [
                                    'type' => $type,
                                    'value' => $field_value,
                                    'index' => 0,
                                    'id_customization' => $id_customization,
                                ];

                                if ($this->addCustomizationData($cu_data)) {
                                    $added = true;
                                }
                            }
                        }
                        // adding designer product to cart
                        if ($added && $cart->updateQty($quantity, $id_product_new, $id_product_attribute_new, $id_customization, 'up', $cart->id_address_delivery)) {
                            ProductCustomization::addToCartExplict($params);
                            ++$result['result'];
                            $result['success'] = true;
                            ++$ki;
                        }
                    } else {
                        $result['msg'] = $this->module->l('Something went wrong. Please contact us if issue persists.');
                    }
                }
                $result['msg'] = sprintf($this->module->l('%s designs successfully added to cart.'), $result['result']);
            }
        } else {
            $result['msg'] = $this->module->l('Object not found.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    // backward compatibility
    protected function createCustomizedProduct($id_product, $id_product_attribute)
    {
        if (Validate::isLoadedObject($product = new Product((int) $id_product))) {
            $id_product_old = $product->id;
            $date_add = $product->date_add;
            if (empty($product->price) && Shop::getContext() == Shop::CONTEXT_GROUP) {
                $shops = ShopGroup::getShopsFromGroup(Shop::getContextShopGroupID());
                foreach ($shops as $shop) {
                    if ($product->isAssociatedToShop($shop['id_shop'])) {
                        $product_price = new Product($id_product_old, false, null, $shop['id_shop']);
                        $product->price = $product_price->price;
                    }
                }
            }
            // getting old product carriers
            $carriers_flat = [];
            $product_carriers = $product->getCarriers();
            if (isset($product_carriers) && $product_carriers) {
                foreach ($product_carriers as $carrier) {
                    array_push($carriers_flat, $carrier['id_reference']);
                }
            }

            unset($product->id);
            unset($product->id_product);
            $product->id_tax_rules_group = 0;
            $product->customizable = 1;
            $product->indexed = 0;
            $product->active = 1;
            $product->visibility = 'none';
            $product->date_add = $date_add;

            if ($product->add()
            && Category::duplicateProductCategories($id_product_old, $product->id)
            && $this->duplicateSingleAttributes($id_product_old, $product->id, $id_product_attribute)
            && Product::duplicateAccessories($id_product_old, $product->id)
            && Product::duplicateFeatures($id_product_old, $product->id)
            && Pack::duplicate($id_product_old, $product->id)
            && Product::duplicateCustomizationFields($id_product_old, $product->id)
            && Product::duplicateTags($id_product_old, $product->id)
            && Product::duplicateDownload($id_product_old, $product->id)) {
                if (isset($carriers_flat) && $carriers_flat) {
                    // setting carriers
                    $product->setCarriers($carriers_flat);
                    $product->update();
                    StockAvailable::setQuantity(
                        $product->id,
                        $id_product_attribute,
                        StockAvailable::getStockAvailableIdByProductId($id_product_old, $id_product_attribute)
                    );
                }

                return $product->id;
            }
        }

        return false;
    }

    // backward compatibility
    public static function duplicateSingleAttributes($id_product_old, $id_product_new, $id_product_attribute_old)
    {
        $return = true;
        if (!$id_product_attribute_old) {
            return $return;
        }

        $result = Db::getInstance()->executeS('SELECT pa.*, product_attribute_shop.* FROM `' . _DB_PREFIX_ . 'product_attribute` pa ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
            WHERE pa.`id_product` = ' . (int) $id_product_old . ' AND pa.`id_product_attribute` = ' . (int) $id_product_attribute_old);
        $combinations = [];
        foreach ($result as $row) {
            $id_product_attribute_old = (int) $row['id_product_attribute'];
            if (empty($combinations[$id_product_attribute_old])) {
                $id_combination = null;
                $id_shop = null;
                $result2 = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute_combination` WHERE `id_product_attribute` = ' . (int) $id_product_attribute_old);
            } else {
                $id_combination = (int) $combinations[$id_product_attribute_old];
                $id_shop = (int) $row['id_shop'];
                $context_old = Shop::getContext();
                $context_shop_id_old = Shop::getContextShopID();
                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
            }

            $row['id_product'] = (int) $id_product_new;
            unset($row['id_product_attribute']);
            unset($row['id_shop']);

            $combination = new Combination($id_combination, null);
            foreach ($row as $k => $v) {
                $combination->$k = $v;
            }

            $combination->quantity = 1;
            $combination->default_on = 1;

            $return &= $combination->save();

            $id_product_attribute_new = (int) $combination->id;

            if (empty($combinations[$id_product_attribute_old])) {
                $combinations[$id_product_attribute_old] = (int) $id_product_attribute_new;
                foreach ($result2 as $row2) {
                    $row2['id_product_attribute'] = (int) $id_product_attribute_new;
                    $return &= Db::getInstance()->insert('product_attribute_combination', $row2);
                }
            } else {
                Shop::setContext($context_old, $context_shop_id_old);
            }
        }

        $impacts = ProductCustomization::getAttributesImpacts($id_product_old);

        if (is_array($impacts) && count($impacts) && $impacts) {
            $impact_sql = 'INSERT INTO `' . _DB_PREFIX_ . 'attribute_impact` (`id_product`, `id_attribute`, `weight`, `price`) VALUES ';

            foreach ($impacts as $id_attribute) {
                $impact_sql .= '(' . (int) $id_product_new . ', ' . (int) $id_attribute . ', 0.0, 0.0),';
            }

            $impact_sql = substr_replace($impact_sql, '', -1);
            $impact_sql .= ' ON DUPLICATE KEY UPDATE `price` = VALUES(price), `weight` = VALUES(weight)';
            $return &= Db::getInstance()->execute($impact_sql);
        }

        return !$return ? false : true;
    }

    protected static function setCoverImage($id_product, $id_image = null, $url = null)
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'cpd_design_' . Tools::passwdGen(8));
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));
        $image_obj = new Image($id_image);
        $path = $image_obj->getPathForCreation();
        $url = str_replace(' ', '%0', trim($url));
        if (!ImageManager::checkImageMemoryLimit($url)) {
            return false;
        }

        if (Tools::copy($url, $tmpfile)) {
            ImageManager::resize($tmpfile, $path . '.jpg');
            $images_types = ImageType::getImagesTypes('products');
            foreach ($images_types as $image_type) {
                ImageManager::resize($tmpfile, $path . '-' . Tools::stripslashes($image_type['name']) . '.jpg', $image_type['width'], $image_type['height']);
            }

            if (in_array($image_type['id_image_type'], $watermark_types)) {
                Hook::exec('actionWatermark', ['id_image' => $id_image, 'id_product' => $id_product]);
            }
        } else {
            unlink($tmpfile);

            return false;
        }
        unlink($tmpfile);

        return true;
    }

    public static function SaveTemplate($id_design, $img)
    {
        Db::getInstance()->insert('cpd_saved_templates',
            [
                'id_design' => (int) $id_design,
                'base_img' => pSQL($img, true)]
        );
        $last_id = (int) Db::getInstance()->Insert_ID();

        return $last_id;
    }

    public static function SaveTemplateElements($id_template, $id_tag, $type, $style, $child_style, $value)
    {
        Db::getInstance()->insert('cpd_saved_templates_elements',
            [
                'id_cpd_saved_templates' => (int) $id_template,
                'id_element' => (int) $id_tag,
                'type' => pSQL($type, true),
                'style' => pSQL($style, true),
                'child_style' => pSQL($child_style, true),
                'value' => pSQL($value, true)]
        );
        $last_id = (int) Db::getInstance()->Insert_ID();

        return $last_id;
    }

    public function getTemplateToDeploy()
    {
        $html = '';
        $module = new CustomProductDesign();
        $result = ['hasError' => false, 'msg' => '', 'html' => ''];
        $id_product = (int) Tools::getValue('cpd_product');
        $id_design = (int) Tools::getValue('id_design');
        $id_template = (int) Tools::getValue('id_template');
        $params = [
            'id_product' => $id_product,
            'id_design' => $id_design,
            'id_template' => $id_template,
        ];
        if ($id_product) {
            $html = $module->getTemplateHtml($params);
            $html .= '<script>$("#cpd-tools-box-container").accordion({
                                active:false,
                                collapsible: true,
                                icons:false,
                                heightStyle: "content",
                                header: ".DesignPanel > .DesignPanelTab",
                            });</script>';
            $result['html'] = $html;
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->module->l('Invalid request.', 'cpdesign');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    public function getTagToDeploy()
    {
        $html = '';
        $html_left = '';
        $html_layer = '';
        $module = new CustomProductDesign();
        $result = ['hasError' => false, 'msg' => '', 'html_left' => $html_left, 'html_main' => $html, 'price' => null, 'layer' => $html_layer];
        $type = Tools::getValue('type');
        $id_design = (int) Tools::getValue('id_design');
        $tag_count = (int) Tools::getValue('count');
        if ($tag_count <= 0) {
            $price = self::getFirstTagPrice();
        } else {
            $price = self::getTagPricing($tag_count);
        }
        $params = [
            'type' => $type,
            'tag_count' => $tag_count,
            'id_design' => $id_design,
            'price' => $price,
        ];
        if ($id_design) {
            $html = $module->getTagHtml($params, 'main');
            $html_left = $module->getTagHtml($params, 'left');
            $html_layer = $module->getTagHtml($params, 'layer');
            $result['html_main'] = $html;
            $result['html_left'] = $html_left;
            $result['layer'] = $html_layer;
            $result['tag_count'] = $tag_count;
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->module->l('Invalid request.', 'cpdesign');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    private function getFirstTagPrice()
    {
        return Db::getInstance()->getValue('SELECT `price`
        FROM `' . _DB_PREFIX_ . 'cpd_dynamic_pricing`
        WHERE `qty_from` <= 1');
    }

    private function getTagPricing($qty)
    {
        return Db::getInstance()->getValue('SELECT `price`
        FROM `' . _DB_PREFIX_ . 'cpd_dynamic_pricing`
        WHERE `qty_from` <= ' . (int) $qty . ' OR `qty_to` <= ' . (int) $qty . '
        ORDER BY `id_cpd_dynamic_pricing` DESC');
    }
}
