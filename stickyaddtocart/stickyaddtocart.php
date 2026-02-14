<?php

/**
 * Sticky Add to Cart Module for PrestaShop 
 *
 * @author    iliass haidi
 * @copyright Copyright (c) 2026
 * @license   MIT License
 * @version   1.0.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class StickyAddToCart extends Module
{

    public $name;
    public $tab;
    public $version;
    public $author;
    public $need_instance;
    public $ps_versions_compliancy;
    public $bootstrap;
    public $context;
    public $displayName;
    public $description;
    public $confirmUninstall;



    public function __construct()
    {
        $this->name = 'stickyaddtocart';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Iliass Haidi';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Sticky Add to Cart');
        $this->description = $this->l('Adds a sticky "Add to Cart" button on product pages that stays visible while scrolling.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
    }

    /**
     * Install module
     *
     * @return bool
     */
    public function install()
    {
        // Install default configuration values
        $defaultConfig = [
            'STICKY_ATC_ENABLE_MOBILE' => 1,
            'STICKY_ATC_ENABLE_TABLET' => 1,
            'STICKY_ATC_ENABLE_DESKTOP' => 1,
            'STICKY_ATC_ENABLE_SIMPLE' => 1,
            'STICKY_ATC_ENABLE_PACK' => 1,
            'STICKY_ATC_ENABLE_VIRTUAL' => 1,
            'STICKY_ATC_BUTTON_TEXT' => 'Add to Cart',
            'STICKY_ATC_BG_COLOR' => '#ffffff',
            'STICKY_ATC_BUTTON_COLOR' => '#25b9d7',
            'STICKY_ATC_BUTTON_HOVER' => '#1fa3bf',
            'STICKY_ATC_TEXT_COLOR' => '#ffffff',
            'STICKY_ATC_PRICE_COLOR' => '#25b9d7',
            'STICKY_ATC_SHOW_IMAGE' => 1,
            'STICKY_ATC_SHOW_VARIATIONS' => 1,
            'STICKY_ATC_EXCLUDED_CATEGORIES' => '',
            'STICKY_ATC_EXCLUDED_PRODUCTS' => '',
            'STICKY_ATC_CUSTOM_CSS' => '',
            'STICKY_ATC_SCROLL_THRESHOLD' => 300,
        ];

        foreach ($defaultConfig as $key => $value) {
            Configuration::updateValue($key, $value);
        }

        return parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayFooterProduct');
    }

    /**
     * Uninstall module
     *
     * @return bool
     */
    public function uninstall()
    {
        // Delete all configuration values
        $configKeys = [
            'STICKY_ATC_ENABLE_MOBILE',
            'STICKY_ATC_ENABLE_TABLET',
            'STICKY_ATC_ENABLE_DESKTOP',
            'STICKY_ATC_ENABLE_SIMPLE',
            'STICKY_ATC_ENABLE_PACK',
            'STICKY_ATC_ENABLE_VIRTUAL',
            'STICKY_ATC_BUTTON_TEXT',
            'STICKY_ATC_BG_COLOR',
            'STICKY_ATC_BUTTON_COLOR',
            'STICKY_ATC_BUTTON_HOVER',
            'STICKY_ATC_TEXT_COLOR',
            'STICKY_ATC_PRICE_COLOR',
            'STICKY_ATC_SHOW_IMAGE',
            'STICKY_ATC_SHOW_VARIATIONS',
            'STICKY_ATC_EXCLUDED_CATEGORIES',
            'STICKY_ATC_EXCLUDED_PRODUCTS',
            'STICKY_ATC_CUSTOM_CSS',
            'STICKY_ATC_SCROLL_THRESHOLD',
        ];

        foreach ($configKeys as $key) {
            Configuration::deleteByName($key);
        }

        return parent::uninstall();
    }

    /**
     * Module Configuration Page
     *
     * @return string HTML content
     */
    public function getContent()
    {
        $output = '';

        // Process form submission
        if (Tools::isSubmit('submitStickyAddToCart')) {
            $this->postProcess();
            $output .= $this->displayConfirmation($this->l('Settings updated successfully!'));
        }

        // Display configuration form
        $output .= $this->renderForm();

        return $output;
    }

    /**
     * Process configuration form
     */
    protected function postProcess()
    {
        $configValues = [
            'STICKY_ATC_ENABLE_MOBILE' => (int)Tools::getValue('STICKY_ATC_ENABLE_MOBILE'),
            'STICKY_ATC_ENABLE_TABLET' => (int)Tools::getValue('STICKY_ATC_ENABLE_TABLET'),
            'STICKY_ATC_ENABLE_DESKTOP' => (int)Tools::getValue('STICKY_ATC_ENABLE_DESKTOP'),
            'STICKY_ATC_ENABLE_SIMPLE' => (int)Tools::getValue('STICKY_ATC_ENABLE_SIMPLE'),
            'STICKY_ATC_ENABLE_PACK' => (int)Tools::getValue('STICKY_ATC_ENABLE_PACK'),
            'STICKY_ATC_ENABLE_VIRTUAL' => (int)Tools::getValue('STICKY_ATC_ENABLE_VIRTUAL'),
            'STICKY_ATC_BUTTON_TEXT' => pSQL(Tools::getValue('STICKY_ATC_BUTTON_TEXT')),
            'STICKY_ATC_BG_COLOR' => pSQL(Tools::getValue('STICKY_ATC_BG_COLOR')),
            'STICKY_ATC_BUTTON_COLOR' => pSQL(Tools::getValue('STICKY_ATC_BUTTON_COLOR')),
            'STICKY_ATC_BUTTON_HOVER' => pSQL(Tools::getValue('STICKY_ATC_BUTTON_HOVER')),
            'STICKY_ATC_TEXT_COLOR' => pSQL(Tools::getValue('STICKY_ATC_TEXT_COLOR')),
            'STICKY_ATC_PRICE_COLOR' => pSQL(Tools::getValue('STICKY_ATC_PRICE_COLOR')),
            'STICKY_ATC_SHOW_IMAGE' => (int)Tools::getValue('STICKY_ATC_SHOW_IMAGE'),
            'STICKY_ATC_SHOW_VARIATIONS' => (int)Tools::getValue('STICKY_ATC_SHOW_VARIATIONS'),
            'STICKY_ATC_EXCLUDED_CATEGORIES' => pSQL(Tools::getValue('STICKY_ATC_EXCLUDED_CATEGORIES')),
            'STICKY_ATC_EXCLUDED_PRODUCTS' => pSQL(Tools::getValue('STICKY_ATC_EXCLUDED_PRODUCTS')),
            'STICKY_ATC_CUSTOM_CSS' => Tools::getValue('STICKY_ATC_CUSTOM_CSS'),
            'STICKY_ATC_SCROLL_THRESHOLD' => (int)Tools::getValue('STICKY_ATC_SCROLL_THRESHOLD'),
        ];

        foreach ($configValues as $key => $value) {
            Configuration::updateValue($key, $value);
        }
    }

    /**
     * Render configuration form
     *
     * @return string HTML form
     */
    protected function renderForm()
    {
        $fieldsForm = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Sticky Add to Cart Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    // Device Settings
                    [
                        'type' => 'html',
                        'name' => 'section_device',
                        'html_content' => '<h4><i class="icon-mobile"></i> ' . $this->l('Device Settings') . '</h4><hr>',
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable on Mobile'),
                        'name' => 'STICKY_ATC_ENABLE_MOBILE',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable on Tablet'),
                        'name' => 'STICKY_ATC_ENABLE_TABLET',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable on Desktop'),
                        'name' => 'STICKY_ATC_ENABLE_DESKTOP',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    // Product Type Settings
                    [
                        'type' => 'html',
                        'name' => 'section_product',
                        'html_content' => '<h4><i class="icon-barcode"></i> ' . $this->l('Product Type Settings') . '</h4><hr>',
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable for Simple Products'),
                        'name' => 'STICKY_ATC_ENABLE_SIMPLE',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable for Product Packs'),
                        'name' => 'STICKY_ATC_ENABLE_PACK',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable for Virtual Products'),
                        'name' => 'STICKY_ATC_ENABLE_VIRTUAL',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    // Appearance Settings
                    [
                        'type' => 'html',
                        'name' => 'section_appearance',
                        'html_content' => '<h4><i class="icon-paint-brush"></i> ' . $this->l('Appearance Settings') . '</h4><hr>',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Button Text'),
                        'name' => 'STICKY_ATC_BUTTON_TEXT',
                        'desc' => $this->l('Text displayed on the add to cart button (e.g., "Buy Now", "Add to Cart")'),
                        'size' => 50,
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show Product Image'),
                        'name' => 'STICKY_ATC_SHOW_IMAGE',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show Product Variations'),
                        'name' => 'STICKY_ATC_SHOW_VARIATIONS',
                        'desc' => $this->l('Display selected product attributes (size, color, etc.)'),
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                            ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                        ],
                    ],
                    // Color Settings
                    [
                        'type' => 'html',
                        'name' => 'section_colors',
                        'html_content' => '<h4><i class="icon-tint"></i> ' . $this->l('Color Settings') . '</h4><hr>',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Background Color'),
                        'name' => 'STICKY_ATC_BG_COLOR',
                        'desc' => $this->l('Background color of the sticky bar'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Button Color'),
                        'name' => 'STICKY_ATC_BUTTON_COLOR',
                        'desc' => $this->l('Background color of the button'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Button Hover Color'),
                        'name' => 'STICKY_ATC_BUTTON_HOVER',
                        'desc' => $this->l('Button color on hover'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Button Text Color'),
                        'name' => 'STICKY_ATC_TEXT_COLOR',
                        'desc' => $this->l('Color of the button text'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Price Color'),
                        'name' => 'STICKY_ATC_PRICE_COLOR',
                        'desc' => $this->l('Color of the product price'),
                    ],
                    // Behavior Settings
                    [
                        'type' => 'html',
                        'name' => 'section_behavior',
                        'html_content' => '<h4><i class="icon-gears"></i> ' . $this->l('Behavior Settings') . '</h4><hr>',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Scroll Threshold (px)'),
                        'name' => 'STICKY_ATC_SCROLL_THRESHOLD',
                        'desc' => $this->l('Number of pixels to scroll before showing the sticky bar'),
                        'class' => 'fixed-width-sm',
                        'suffix' => 'px',
                    ],
                    // Exclusions
                    [
                        'type' => 'html',
                        'name' => 'section_exclusions',
                        'html_content' => '<h4><i class="icon-ban"></i> ' . $this->l('Exclusions') . '</h4><hr>',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Excluded Categories'),
                        'name' => 'STICKY_ATC_EXCLUDED_CATEGORIES',
                        'desc' => $this->l('Category IDs separated by commas (e.g., 3,5,8)'),
                        'size' => 50,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Excluded Products'),
                        'name' => 'STICKY_ATC_EXCLUDED_PRODUCTS',
                        'desc' => $this->l('Product IDs separated by commas (e.g., 12,25,47)'),
                        'size' => 50,
                    ],
                    // Custom CSS
                    [
                        'type' => 'html',
                        'name' => 'section_advanced',
                        'html_content' => '<h4><i class="icon-code"></i> ' . $this->l('Advanced Settings') . '</h4><hr>',
                    ],
                    [
                        'type' => 'textarea',
                        'label' => $this->l('Custom CSS'),
                        'name' => 'STICKY_ATC_CUSTOM_CSS',
                        'desc' => $this->l('Add your custom CSS code here'),
                        'rows' => 10,
                        'cols' => 80,
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save Settings'),
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitStickyAddToCart';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fieldsForm]);
    }

    /**
     * Get configuration values for form
     *
     * @return array
     */
    protected function getConfigFormValues()
    {
        return [
            'STICKY_ATC_ENABLE_MOBILE' => Configuration::get('STICKY_ATC_ENABLE_MOBILE'),
            'STICKY_ATC_ENABLE_TABLET' => Configuration::get('STICKY_ATC_ENABLE_TABLET'),
            'STICKY_ATC_ENABLE_DESKTOP' => Configuration::get('STICKY_ATC_ENABLE_DESKTOP'),
            'STICKY_ATC_ENABLE_SIMPLE' => Configuration::get('STICKY_ATC_ENABLE_SIMPLE'),
            'STICKY_ATC_ENABLE_PACK' => Configuration::get('STICKY_ATC_ENABLE_PACK'),
            'STICKY_ATC_ENABLE_VIRTUAL' => Configuration::get('STICKY_ATC_ENABLE_VIRTUAL'),
            'STICKY_ATC_BUTTON_TEXT' => Configuration::get('STICKY_ATC_BUTTON_TEXT'),
            'STICKY_ATC_BG_COLOR' => Configuration::get('STICKY_ATC_BG_COLOR'),
            'STICKY_ATC_BUTTON_COLOR' => Configuration::get('STICKY_ATC_BUTTON_COLOR'),
            'STICKY_ATC_BUTTON_HOVER' => Configuration::get('STICKY_ATC_BUTTON_HOVER'),
            'STICKY_ATC_TEXT_COLOR' => Configuration::get('STICKY_ATC_TEXT_COLOR'),
            'STICKY_ATC_PRICE_COLOR' => Configuration::get('STICKY_ATC_PRICE_COLOR'),
            'STICKY_ATC_SHOW_IMAGE' => Configuration::get('STICKY_ATC_SHOW_IMAGE'),
            'STICKY_ATC_SHOW_VARIATIONS' => Configuration::get('STICKY_ATC_SHOW_VARIATIONS'),
            'STICKY_ATC_EXCLUDED_CATEGORIES' => Configuration::get('STICKY_ATC_EXCLUDED_CATEGORIES'),
            'STICKY_ATC_EXCLUDED_PRODUCTS' => Configuration::get('STICKY_ATC_EXCLUDED_PRODUCTS'),
            'STICKY_ATC_CUSTOM_CSS' => Configuration::get('STICKY_ATC_CUSTOM_CSS'),
            'STICKY_ATC_SCROLL_THRESHOLD' => Configuration::get('STICKY_ATC_SCROLL_THRESHOLD'),
        ];
    }

    /**
     * Hook: displayHeader
     * Load CSS and JS files in the header
     *
     * @return void
     */
    public function hookDisplayHeader()
    {
        // Only load on product pages
        if ($this->context->controller->php_self !== 'product') {
            return;
        }

        // Check if should display based on device
        if (!$this->shouldDisplayOnDevice()) {
            return;
        }

        // Register CSS
        $this->context->controller->registerStylesheet(
            'module-stickyaddtocart-style',
            'modules/' . $this->name . '/views/css/stickyaddtocart.css',
            [
                'media' => 'all',
                'priority' => 150,
            ]
        );

        // Register JS
        $this->context->controller->registerJavascript(
            'module-stickyaddtocart-script',
            'modules/' . $this->name . '/views/js/stickyaddtocart.js',
            [
                'position' => 'bottom',
                'priority' => 150,
            ]
        );

        // Add dynamic CSS variables
        $customCSS = $this->getCustomCSS();
        $this->context->smarty->assign('sticky_custom_css', $customCSS);
        
        // Add inline CSS
        $inlineCSS = '<style>' . $customCSS . '</style>';
        $this->context->controller->addCSS($this->_path . 'views/css/stickyaddtocart.css', 'all');
        return $inlineCSS;
    }

    /**
     * Generate custom CSS from configuration
     *
     * @return string
     */
    protected function getCustomCSS()
    {
        $bgColor = Configuration::get('STICKY_ATC_BG_COLOR');
        $buttonColor = Configuration::get('STICKY_ATC_BUTTON_COLOR');
        $buttonHover = Configuration::get('STICKY_ATC_BUTTON_HOVER');
        $textColor = Configuration::get('STICKY_ATC_TEXT_COLOR');
        $priceColor = Configuration::get('STICKY_ATC_PRICE_COLOR');
        $customCSS = Configuration::get('STICKY_ATC_CUSTOM_CSS');

        $css = "
            .sticky-add-to-cart {
                background: {$bgColor} !important;
            }
            .sticky-add-btn {
                background-color: {$buttonColor} !important;
                color: {$textColor} !important;
            }
            .sticky-add-btn:hover {
                background-color: {$buttonHover} !important;
            }
            .sticky-product-price {
                color: {$priceColor} !important;
            }
            {$customCSS}
        ";

        return $css;
    }

    /**
     * Check if module should display based on current device
     *
     * @return bool
     */
    protected function shouldDisplayOnDevice()
    {
        $context = Context::getContext();
        
        // Detect device type
        if ($context->isMobile()) {
            return (bool)Configuration::get('STICKY_ATC_ENABLE_MOBILE');
        } elseif ($context->isTablet()) {
            return (bool)Configuration::get('STICKY_ATC_ENABLE_TABLET');
        } else {
            return (bool)Configuration::get('STICKY_ATC_ENABLE_DESKTOP');
        }
    }

    /**
     * Hook: displayFooterProduct
     * Display the sticky button on product pages
     **/
    public function hookDisplayFooterProduct($params)
    {
        // Get product information
        $product = $params['product'];
        $productId = (int)$product['id_product'];

        // Check if product is excluded
        if ($this->isProductExcluded($productId)) {
            return '';
        }

        // Check if product category is excluded
        if ($this->isProductCategoryExcluded($productId)) {
            return '';
        }

        // Check if product type is enabled
        if (!$this->isProductTypeEnabled($product)) {
            return '';
        }

        // Get product image
        $productImage = '';
        if (Configuration::get('STICKY_ATC_SHOW_IMAGE') && isset($product['cover'])) {
            $imageId = (int)$product['cover']['id_image'];
            $link = $this->context->link;
            $productImage = $link->getImageLink(
                $product['link_rewrite'],
                $imageId,
                'small_default'
            );
        }

        // Get product variations (if enabled and product has combinations)
        $variations = '';
        if (Configuration::get('STICKY_ATC_SHOW_VARIATIONS') && isset($product['attributes'])) {
            $variations = $this->getProductVariations($product);
        }

        // Get button text from configuration
        $buttonText = Configuration::get('STICKY_ATC_BUTTON_TEXT');
        if (empty($buttonText)) {
            $buttonText = $this->l('Add to Cart');
        }

        // Get scroll threshold
        $scrollThreshold = (int)Configuration::get('STICKY_ATC_SCROLL_THRESHOLD');

        // Assign variables to template
        $this->context->smarty->assign([
            'product_id' => $productId,
            'product_name' => $product['name'],
            'product_price' => $product['price'],
            'product_price_amount' => $product['price_amount'],
            'product_currency' => $this->context->currency->sign,
            'product_url' => $product['url'],
            'product_image' => $productImage,
            'product_variations' => $variations,
            'button_text' => $buttonText,
            'show_image' => Configuration::get('STICKY_ATC_SHOW_IMAGE'),
            'show_variations' => Configuration::get('STICKY_ATC_SHOW_VARIATIONS'),
            'scroll_threshold' => $scrollThreshold,
            'add_to_cart_url' => $this->context->link->getPageLink('cart', true, null, [
                'add' => 1,
                'id_product' => $productId,
                'action' => 'update'
            ]),
            'cart_url' => $this->context->link->getPageLink('cart', true),
        ]);

        // Return the template
        return $this->display(__FILE__, 'views/templates/hook/sticky-button.tpl');
    }

    /**
     * Check if product is excluded
     *
     * @param int $productId
     * @return bool
     */
    protected function isProductExcluded($productId)
    {
        $excludedProducts = Configuration::get('STICKY_ATC_EXCLUDED_PRODUCTS');
        if (empty($excludedProducts)) {
            return false;
        }

        $excludedArray = array_map('trim', explode(',', $excludedProducts));
        return in_array($productId, $excludedArray);
    }

    /**
     * Check if product's category is excluded
     *
     * @param int $productId
     * @return bool
     */
    protected function isProductCategoryExcluded($productId)
    {
        $excludedCategories = Configuration::get('STICKY_ATC_EXCLUDED_CATEGORIES');
        if (empty($excludedCategories)) {
            return false;
        }

        $excludedArray = array_map('intval', array_map('trim', explode(',', $excludedCategories)));
        
        // Get product categories
        $product = new Product($productId);
        $productCategories = $product->getCategories();

        // Check if any product category is in excluded list
        foreach ($productCategories as $categoryId) {
            if (in_array((int)$categoryId, $excludedArray)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if product type is enabled in configuration
     *
     * @param array $product
     * @return bool
     */
    protected function isProductTypeEnabled($product)
    {
        $productObj = new Product((int)$product['id_product']);

        // Check if it's a pack
        if ($productObj->pack) {
            return (bool)Configuration::get('STICKY_ATC_ENABLE_PACK');
        }

        // Check if it's a virtual product
        if ($productObj->is_virtual) {
            return (bool)Configuration::get('STICKY_ATC_ENABLE_VIRTUAL');
        }

        // Default: simple product
        return (bool)Configuration::get('STICKY_ATC_ENABLE_SIMPLE');
    }

    /**
     * Get product variations text
     *
     * @param array $product
     * @return string
     */
    protected function getProductVariations($product)
    {
        if (empty($product['attributes'])) {
            return '';
        }

        $variations = [];
        foreach ($product['attributes'] as $attribute) {
            if (isset($attribute['name']) && isset($attribute['value'])) {
                $variations[] = $attribute['name'] . ': ' . $attribute['value'];
            }
        }

        return implode(', ', $variations);
    }
}
