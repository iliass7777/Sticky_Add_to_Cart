<div id="sticky-add-to-cart" class="sticky-add-to-cart" style="display: none;" data-scroll-threshold="{$scroll_threshold}">
    <div class="sticky-container">
        <!-- Left Section: Image and Info (Desktop) -->
        <div class="sticky-product-details">
            {if $show_image && $product_image}
            <div class="sticky-product-image">
                <img src="{$product_image}" alt="{$product_name}" loading="lazy">
            </div>
            {/if}
            
            <div class="sticky-product-info">
                {if $product_manufacturer}
                    <span class="sticky-product-brand">{$product_manufacturer}</span>
                {/if}
                <div class="sticky-name-variation">
                    <span class="sticky-product-name">{$product_name|truncate:50:'...'}</span>
                    {if $product_color || $product_variations}
                        <span class="sticky-separator">|</span>
                    {/if}
                    {if $product_color}
                        <span class="sticky-color-swatch" style="background-color: {$product_color}"></span>
                    {/if}
                    {if $product_variations}
                        <span class="sticky-product-variations">{$product_variations}</span>
                    {/if}
                </div>
            </div>
        </div>

        <!-- Center Section: Price (Desktop) -->
        <div class="sticky-product-pricing">
            <div class="sticky-price-stack">
                {if $product_regular_price}
                    <span class="sticky-regular-price">{$product_regular_price}</span>
                {/if}
                <div class="sticky-current-price-row">
                    <span class="sticky-current-price">{$product_price}</span>
                    <span class="sticky-price-tax">{l s='TTC' mod='stickyaddtocart'}</span>
                </div>
            </div>
        </div>
        
        <!-- Right Section: Actions (Desktop) / Unified Pill (Mobile) -->
        <div class="sticky-actions">
            <div class="sticky-unified-pill">
                <div class="sticky-qty-selector">
                    <button type="button" class="qty-btn qty-minus">-</button>
                    <input type="number" name="qty" value="1" min="1" class="sticky-qty-input">
                    <button type="button" class="qty-btn qty-plus">+</button>
                </div>

                <button 
                    type="button" 
                    class="btn btn-primary sticky-add-btn"
                    data-product-id="{$product_id}"
                    data-add-url="{$add_to_cart_url}"
                    data-token="{$token}"
                >
                    <div class="sticky-btn-content">
                        <i class="material-icons">local_mall</i>
                        <span class="sticky-btn-text">{l s='Ajouter Au Panier' mod='stickyaddtocart'}</span>
                        <span class="sticky-mobile-price">{$product_price}</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    {* Success message *}
    <div class="sticky-success-message" style="display: none;">
        <i class="material-icons">check_circle</i>
        <span>{l s='Product added to cart!' mod='stickyaddtocart'}</span>
    </div>
</div>
