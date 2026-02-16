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
     * Listen for PrestaShop events to manage loading state
     */
    if (typeof prestashop !== "undefined" && prestashop.on) {
      prestashop.on("updateCart", function () {
        stickyBtn.classList.remove("loading");
      });
      prestashop.on("handleError", function () {
        stickyBtn.classList.remove("loading");
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

      // 1. Try to find the native theme "Add to Cart" button
      const mainForm = document.querySelector("#add-to-cart-or-refresh");
      const nativeBtn = mainForm
        ? mainForm.querySelector('[data-button-action="add-to-cart"]')
        : null;

      if (nativeBtn) {
        stickyBtn.classList.add("loading");

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
        qty: 1,
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
