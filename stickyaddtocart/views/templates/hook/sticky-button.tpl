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
                    {if $product_color || $variation_image}
                        <span class="sticky-separator">|</span>
                        {if $product_color}
                            <span class="sticky-swatch sticky-color-swatch" style="background-color: {$product_color}"></span>
                        {elseif $variation_image}
                            <span class="sticky-swatch sticky-image-swatch">
                                <img src="{$variation_image}" alt="variation">
                            </span>
                        {/if}
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
                
                {* Mobile Quantity Dropdown *}
                <div class="sticky-qty-dropdown" style="display: none;">
                    <div class="sticky-qty-dropdown-list">
                        {for $i=1 to 10}
                            <div class="sticky-qty-option" data-qty="{$i}">{$i}</div>
                        {/for}
                    </div>
                </div>

                <button 
                    type="button" 
                    class="btn btn-primary sticky-add-btn"
                    data-product-id="{$product_id}"
                    data-add-url="{$add_to_cart_url}"
                    data-token="{$token}"
                >
                    <div class="sticky-btn-content">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0;">
                            <path d="M19 7H16V6C16 4.93913 15.5786 3.92172 14.8284 3.17157C14.0783 2.42143 13.0609 2 12 2C10.9391 2 9.92172 2.42143 9.17157 3.17157C8.42143 3.92172 8 4.93913 8 6V7H5C4.73478 7 4.48043 7.10536 4.29289 7.29289C4.10536 7.48043 4 7.73478 4 8V19C4 19.7956 4.31607 20.5587 4.87868 21.1213C5.44129 21.6839 6.20435 22 7 22H17C17.7956 22 18.5587 21.6839 19.1213 21.1213C19.6839 20.5587 20 19.7956 20 19V8C20 7.73478 19.8946 7.48043 19.7071 7.29289C19.5196 7.10536 19.2652 7 19 7ZM10 6C10 5.46957 10.2107 4.96086 10.5858 4.58579C10.9609 4.21071 11.4696 4 12 4C12.5304 4 13.0391 4.21071 13.4142 4.58579C13.7893 4.96086 14 5.46957 14 6V7H10V6ZM18 19C18 19.2652 17.8946 19.5196 17.7071 19.7071C17.5196 19.8946 17.2652 20 17 20H7C6.73478 20 6.48043 19.8946 6.29289 19.7071C6.10536 19.5196 6 19.2652 6 19V9H8V10C8 10.2652 8.10536 10.5196 8.29289 10.7071C8.48043 10.8946 8.73478 11 9 11C9.26522 11 9.51957 10.8946 9.70711 10.7071C9.89464 10.5196 10 10.2652 10 10V9H14V10C14 10.2652 14.1054 10.5196 14.2929 10.7071C14.4804 10.8946 14.7348 11 15 11C15.2652 11 15.5196 10.8946 15.7071 10.7071C15.8946 10.5196 16 10.2652 16 10V9H18V19Z" fill="white"/>
                        </svg>
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
