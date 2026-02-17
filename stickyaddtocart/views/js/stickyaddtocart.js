/**
 * Sticky Add to Cart - JavaScript
 * PrestaShop 1.7.x Module
 */

(function () {
  "use strict";

  // Wait for DOM to be ready
  document.addEventListener("DOMContentLoaded", function () {
    const stickyBar = document.getElementById("sticky-add-to-cart");
    const stickyBtn = document.querySelector(".sticky-add-btn");
    const successMessage = document.querySelector(".sticky-success-message");
    const qtyInput = document.querySelector(".sticky-qty-input");
    const qtyMinus = document.querySelector(".qty-minus");
    const qtyPlus = document.querySelector(".qty-plus");

    if (!stickyBar || !stickyBtn) {
      return;
    }

    // Configuration - Get scroll threshold from data attribute or use default
    const SCROLL_THRESHOLD =
      parseInt(stickyBar.getAttribute("data-scroll-threshold")) || 300;
    const SUCCESS_MESSAGE_DURATION = 3000; // 3 seconds

    /**
     * Show/hide sticky bar based on scroll position
     */
    function handleScroll() {
      const scrollPosition =
        window.pageYOffset || document.documentElement.scrollTop;

      if (scrollPosition > SCROLL_THRESHOLD) {
        stickyBar.style.display = "block";
        // Small delay to trigger CSS transition
        setTimeout(function () {
          stickyBar.classList.add("visible");
        }, 10);
      } else {
        stickyBar.classList.remove("visible");
        setTimeout(function () {
          if (!stickyBar.classList.contains("visible")) {
            stickyBar.style.display = "none";
          }
        }, 300); // Match CSS transition duration
      }
    }

    /**
     * Quantity Selector Logic
     */
    if (qtyMinus && qtyPlus && qtyInput) {
      qtyMinus.addEventListener("click", function () {
        const val = parseInt(qtyInput.value);
        if (val > 1) qtyInput.value = val - 1;
      });
      qtyPlus.addEventListener("click", function () {
        const val = parseInt(qtyInput.value);
        qtyInput.value = val + 1;
      });
    }

    /**
     * Mobile Quantity Dropdown Logic
     */
    const qtySelector = document.querySelector(".sticky-qty-selector");
    const qtyDropdown = document.querySelector(".sticky-qty-dropdown");
    const qtyOptions = document.querySelectorAll(".sticky-qty-option");

    // Check if we're on mobile (max-width: 767px)
    function isMobile() {
      return window.innerWidth <= 767;
    }

    // Open dropdown on mobile when clicking qty selector
    if (qtySelector && qtyDropdown) {
      qtySelector.addEventListener("click", function (e) {
        // Only open dropdown on mobile
        if (!isMobile()) return;

        // Don't open if clicking nested buttons (relevant for hybrid states)
        if (e.target.closest(".qty-btn")) return;

        e.stopPropagation();
        qtyDropdown.classList.add("open");
        updateSelectedOption();
      });

      // Close dropdown when clicking outside
      qtyDropdown.addEventListener("click", function (e) {
        if (e.target === qtyDropdown) {
          qtyDropdown.classList.remove("open");
        }
      });

      // Handle quantity option selection
      qtyOptions.forEach(function (option) {
        option.addEventListener("click", function () {
          const selectedQty = parseInt(this.getAttribute("data-qty"));
          qtyInput.value = selectedQty;
          // Sync with native input if it exists
          const mainForm = document.querySelector("#add-to-cart-or-refresh");
          const nativeQtyInput = mainForm
            ? mainForm.querySelector('input[name="qty"]')
            : null;
          if (nativeQtyInput) {
            nativeQtyInput.value = selectedQty;
            nativeQtyInput.dispatchEvent(
              new Event("change", { bubbles: true }),
            );
          }

          qtyDropdown.classList.remove("open");
          updateSelectedOption();
        });
      });

      // Update selected option visual state
      function updateSelectedOption() {
        const currentQty = parseInt(qtyInput.value);
        qtyOptions.forEach(function (option) {
          const optionQty = parseInt(option.getAttribute("data-qty"));
          if (optionQty === currentQty) {
            option.classList.add("selected");
          } else {
            option.classList.remove("selected");
          }
        });
      }
    }

    /**
     * Listen for PrestaShop events to manage loading state
     */
    if (typeof prestashop !== "undefined" && prestashop.on) {
      prestashop.on("updateCart", function () {
        stickyBtn.classList.remove("loading");
      });
      prestashop.on("handleError", function () {
        stickyBtn.classList.remove("loading");
      });

      // Update sticky bar when product combination changes
      prestashop.on("updatedProduct", function (event) {
        if (!event || !event.product_minimal_quantity) return;

        // Update price
        const currentPrice = document.querySelector(".sticky-current-price");
        const mobilePrice = document.querySelector(".sticky-mobile-price");
        if (currentPrice && event.product_prices.price) {
          currentPrice.textContent = event.product_prices.price;
        }
        if (mobilePrice && event.product_prices.price) {
          mobilePrice.textContent = event.product_prices.price;
        }

        // Update regular price
        const regularPrice = document.querySelector(".sticky-regular-price");
        if (regularPrice) {
          if (event.product_prices.regular_price) {
            regularPrice.textContent = event.product_prices.regular_price;
            regularPrice.style.display = "block";
          } else {
            regularPrice.style.display = "none";
          }
        }

        // Update image if switched
        const img = stickyBar.querySelector(".sticky-product-image img");
        if (img && event.product_cover) {
          img.src = event.product_cover.bySize.small_default.url;
        }

        // Update variations swatch (color or image)
        updateVariationSwatch(event);
      });
    }

    /**
     * Add product to cart by triggering the native theme button if possible,
     * otherwise fallback to AJAX.
     */
    function addToCart(event) {
      event.preventDefault();

      if (stickyBtn.classList.contains("loading")) {
        return;
      }

      const qty = qtyInput ? parseInt(qtyInput.value) : 1;

      // 1. Try to find the native theme "Add to Cart" button and quantity input
      const mainForm = document.querySelector("#add-to-cart-or-refresh");
      const nativeQtyInput = mainForm
        ? mainForm.querySelector('input[name="qty"]')
        : null;
      const nativeBtn = mainForm
        ? mainForm.querySelector('[data-button-action="add-to-cart"]')
        : null;

      if (nativeBtn) {
        stickyBtn.classList.add("loading");

        // Sync quantity to native input if exists
        if (nativeQtyInput) {
          nativeQtyInput.value = qty;
          // Trigger change event just in case
          const changeEvt = new Event("change", { bubbles: true });
          nativeQtyInput.dispatchEvent(changeEvt);
        }

        // Trigger click on the native button
        if (typeof jQuery !== "undefined") {
          jQuery(nativeBtn).trigger("click");
        } else {
          const clickEvt = new MouseEvent("click", {
            bubbles: true,
            cancelable: true,
            view: window,
          });
          nativeBtn.dispatchEvent(clickEvt);
        }

        // Safety timeout
        setTimeout(() => stickyBtn.classList.remove("loading"), 5000);
        return;
      }

      // 2. Fallback AJAX logic
      const productId = parseInt(stickyBtn.getAttribute("data-product-id"));
      const token = stickyBtn.getAttribute("data-token");
      let productAttributeId = 0;

      stickyBtn.classList.add("loading");

      const data = {
        id_product: productId,
        qty: qty,
        action: "update",
        add: 1,
        ajax: true,
        token:
          token ||
          (typeof prestashop !== "undefined" ? prestashop.static_token : ""),
      };

      if (mainForm) {
        const attrInput = mainForm.querySelector(
          'input[name="id_product_attribute"]',
        );
        if (attrInput) productAttributeId = parseInt(attrInput.value);
        data.id_product_attribute = productAttributeId;
      }

      const addToCartUrl = stickyBtn.getAttribute("data-add-url");

      if (typeof jQuery !== "undefined") {
        jQuery
          .post(addToCartUrl, data, null, "json")
          .done(function (resp) {
            stickyBtn.classList.remove("loading");
            if (resp.hasError) {
              alert(resp.errors.join("\n"));
              return;
            }
            showSuccessMessage();
            if (typeof prestashop !== "undefined" && prestashop.emit) {
              prestashop.emit("updateCart", {
                reason: {
                  idProduct: productId,
                  idProductAttribute: productAttributeId,
                  linkAction: "add-to-cart",
                  cart: resp.cart,
                },
                resp: resp,
              });
            }
          })
          .fail(function () {
            stickyBtn.classList.remove("loading");
            alert("An error occurred. Please try again.");
          });
      } else {
        const formData = new FormData();
        for (const key in data) formData.append(key, data[key]);
        fetch(addToCartUrl, {
          method: "POST",
          body: formData,
          headers: { "X-Requested-With": "XMLHttpRequest" },
        })
          .then((res) => res.json())
          .then((resp) => {
            stickyBtn.classList.remove("loading");
            showSuccessMessage();
            if (typeof prestashop !== "undefined" && prestashop.emit) {
              prestashop.emit("updateCart", {
                reason: {
                  idProduct: productId,
                  idProductAttribute: productAttributeId,
                  linkAction: "add-to-cart",
                  cart: resp.cart,
                },
                resp: resp,
              });
            }
          })
          .catch(() => stickyBtn.classList.remove("loading"));
      }
    }

    /**
     * Show success message
     */
    function showSuccessMessage() {
      if (!successMessage) {
        return;
      }

      successMessage.style.display = "flex";

      setTimeout(function () {
        successMessage.style.display = "none";
      }, SUCCESS_MESSAGE_DURATION);
    }

    /**
     * Update variation swatch when product combination changes
     */
    function updateVariationSwatch(event) {
      // Find the variation container
      const variationContainer = stickyBar.querySelector(
        ".sticky-name-variation",
      );
      if (!variationContainer) return;

      // Remove existing swatch elements
      const existingSeparator =
        variationContainer.querySelector(".sticky-separator");
      const existingSwatch = variationContainer.querySelector(".sticky-swatch");
      if (existingSeparator) existingSeparator.remove();
      if (existingSwatch) existingSwatch.remove();

      // Check if event has attribute groups
      if (!event.product_details || !event.product_details.attributes) {
        return;
      }

      // Look for color attribute in the attributes array
      let colorValue = "";
      let textureUrl = "";

      // Iterate through attributes to find color group
      for (let i = 0; i < event.product_details.attributes.length; i++) {
        const attr = event.product_details.attributes[i];

        // Check if this is a color attribute
        if (
          attr.group_type === "color" ||
          attr.name.toLowerCase().includes("color") ||
          attr.name.toLowerCase().includes("couleur")
        ) {
          // Texture/image takes precedence
          if (attr.texture) {
            textureUrl = attr.texture;
            break;
          } else if (attr.html_color_code) {
            colorValue = attr.html_color_code;
            break;
          }
        }
      }

      // If we found a color or texture, add the swatch
      if (colorValue || textureUrl) {
        // Add separator
        const separator = document.createElement("span");
        separator.className = "sticky-separator";
        separator.textContent = "|";
        variationContainer.appendChild(separator);

        // Add swatch
        if (textureUrl) {
          // Image swatch
          const swatch = document.createElement("span");
          swatch.className = "sticky-swatch sticky-image-swatch";
          const img = document.createElement("img");
          img.src = textureUrl;
          img.alt = "variation";
          swatch.appendChild(img);
          variationContainer.appendChild(swatch);
        } else if (colorValue) {
          // Color swatch
          const swatch = document.createElement("span");
          swatch.className = "sticky-swatch sticky-color-swatch";
          swatch.style.backgroundColor = colorValue;
          variationContainer.appendChild(swatch);
        }
      }
    }

    /**
     * Throttle function to limit scroll event firing
     */
    function throttle(func, limit) {
      let inThrottle;
      return function () {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
          func.apply(context, args);
          inThrottle = true;
          setTimeout(function () {
            inThrottle = false;
          }, limit);
        }
      };
    }

    // Event listeners
    window.addEventListener("scroll", throttle(handleScroll, 100));
    stickyBtn.addEventListener("click", addToCart);

    // Initial check
    handleScroll();
  });
})();
