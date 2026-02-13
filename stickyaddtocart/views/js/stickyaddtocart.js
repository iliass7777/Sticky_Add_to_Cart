/**
 * Sticky Add to Cart - JavaScript
 * PrestaShop 1.7 Module
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

    // Configuration
    const SCROLL_THRESHOLD = 300; // Show after scrolling 300px
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
     * Add product to cart via AJAX
     */
    function addToCart(event) {
      event.preventDefault();

      if (stickyBtn.classList.contains("loading")) {
        return;
      }

      const productId = stickyBtn.getAttribute("data-product-id");

      // Add loading state
      stickyBtn.classList.add("loading");

      // Prepare form data
      const formData = new FormData();
      formData.append("id_product", productId);
      formData.append("qty", 1);
      formData.append("action", "update");
      formData.append("add", 1);

      // Get the main add to cart form if it exists (for product customization)
      const mainForm = document.querySelector("#add-to-cart-or-refresh");
      if (mainForm) {
        // Get product attribute if selected
        const productAttribute = mainForm.querySelector(
          'input[name="id_product_attribute"]',
        );
        if (productAttribute && productAttribute.value) {
          formData.append("id_product_attribute", productAttribute.value);
        }

        // Get customization fields if any
        const customizationFields = mainForm.querySelectorAll(
          'input[name^="id_customization"]',
        );
        customizationFields.forEach(function (field) {
          formData.append(field.name, field.value);
        });
      }

      // PrestaShop 1.7 uses prestashop object for AJAX cart updates
      fetch(prestashop.urls.pages.cart, {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          // Remove loading state
          stickyBtn.classList.remove("loading");

          // Show success message
          showSuccessMessage();

          // Trigger PrestaShop cart update event
          prestashop.emit("updateCart", {
            reason: {
              idProduct: productId,
              idProductAttribute: formData.get("id_product_attribute") || 0,
              linkAction: "add-to-cart",
            },
          });

          // Update cart preview (if blockcart module is active)
          if (typeof prestashop !== "undefined" && prestashop.blockcart) {
            prestashop.blockcart.showModal();
          }
        })
        .catch(function (error) {
          console.error("Error adding to cart:", error);
          stickyBtn.classList.remove("loading");
          alert("An error occurred. Please try again.");
        });
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
