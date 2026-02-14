{**
 * Sticky Add to Cart Button Template
 *}

<div id="sticky-add-to-cart" class="sticky-add-to-cart" style="display: none;" data-scroll-threshold="{$scroll_threshold}">
    <div class="sticky-container">
        {if $show_image && $product_image}
        <div class="sticky-product-image">
            <img src="{$product_image}" alt="{$product_name}" loading="lazy">
        </div>
        {/if}
        
        <div class="sticky-product-info">
            <span class="sticky-product-name">{$product_name|truncate:50:'...'}</span>
            {if $show_variations && $product_variations}
            <span class="sticky-product-variations">{$product_variations}</span>
            {/if}
            <span class="sticky-product-price">{$product_price}</span>
        </div>
        
        <div class="sticky-actions">
            <button 
                type="button" 
                class="btn btn-primary sticky-add-btn"
                data-product-id="{$product_id}"
                data-add-url="{$add_to_cart_url}"
            >
                <i class="material-icons shopping-cart">shopping_cart</i>
                <span class="sticky-btn-text">{$button_text}</span>
            </button>
        </div>
    </div>
    
    {* Success message *}
    <div class="sticky-success-message" style="display: none;">
        <i class="material-icons">check_circle</i>
        <span>{l s='Product added to cart!' mod='stickyaddtocart'}</span>
    </div>
</div>
