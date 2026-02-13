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
        return parent::uninstall();
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
    }

    /**
     * Hook: displayFooterProduct
     * Display the sticky button on product pages
     **/
    public function hookDisplayFooterProduct($params)
    {
        // Get product information
        $product = $params['product'];

        // Assign variables to template
        $this->context->smarty->assign([
            'product_id' => $product['id_product'],
            'product_name' => $product['name'],
            'product_price' => $product['price'],
            'product_price_amount' => $product['price_amount'],
            'product_currency' => $this->context->currency->sign,
            'product_url' => $product['url'],
            'add_to_cart_url' => $this->context->link->getPageLink('cart', true, null, [
                'add' => 1,
                'id_product' => $product['id_product'],
                'action' => 'update'
            ]),
            'cart_url' => $this->context->link->getPageLink('cart', true),
        ]);

        // Return the template
        return $this->display(__FILE__, 'views/templates/hook/sticky-button.tpl');
    }
}
