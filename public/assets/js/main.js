$(document).ready(function () {

    //Modify quantity product for adding to the Cart in Product page
    if ($(".container.product-page").length) {
        modifyQuantityProduct();
        //Add a product on the Cart from Product page
        $("#add-to-cart").click(function (e) {
            e.preventDefault();
            addToCartAjaxFromProductPage();
        })
    }

    //Modify quantity product for adding to the Cart in Cart page
    if ($(".container.cart").length) {
        $(".cart-product .addon-minus").each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                $this = $(this);
                refreshProductFromCartPage($this, "less");
            })
        })
        $(".cart-product .addon-plus").each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                $this = $(this);
                refreshProductFromCartPage($this, "add");
            })
        })
        $("#quantity-product").on("change", function (e) {
            e.preventDefault();
            $this = $(this);
            $val = $(this).val();
            refreshProductFromCartPage($this, $val);

        })
    }

    /********** FONCTIONS ***********/

    //Add 1 quantity to a product from Cart page
    function refreshProductFromCartPage($thisPlus, $action) {
        $path = $thisPlus.closest(".cart-product").find("#add-to-cart").data("path");
        $productId = $thisPlus.closest(".cart-product").find("#product-id").val();
        $quantity = $thisPlus.closest(".cart-product").find("#quantity-product");
        //Change value input on the page (front)
        if ($action == "add") {
            var val = parseInt($quantity.val()) + 1;
        } else if ($action == "less") {
            var val = parseInt($quantity.val()) - 1;
        } else {
            var val = parseInt($action);
        }
        $quantity.val(val > 0 ? val : 1);
        //Change total product on the Cart page
        if (val > 0)
            addToCartAjaxFromCartPage($path, $productId, $action);
    }

    function addToCartAjaxFromCartPage($path, $productId, $action) {
        $setExactQuantity = 0;
        $quantity = null;
        if ($action == "add") {
            $quantity = 1;
        } else if ($action == "less") {
            $quantity = -1;
        } else {
            $setExactQuantity = $action;
        }
        $.ajax({
            type: 'POST',
            url: $path,
            data: {
                productId: $productId,
                quantity: $quantity,
                setExactQuantity: $setExactQuantity
            },
            success: function (data) {
                if ($.isNumeric(data)) {
                    refreshCart(data);
                    window.location.reload();
                }
            },
        });
    }

    //When user click on minus or plus from Product Page.
    function modifyQuantityProduct() {
        $minus = $(".cart-product .addon-minus");
        $plus = $(".cart-product .addon-plus");
        $inputCart = $(".cart-product #quantity-product");
        $minus.click(function () {
            var val = parseInt($inputCart.val()) - 1;
            $inputCart.val(val > 0 ? val : 1);
        });
        $plus.click(function () {
            var val = parseInt($inputCart.val()) + 1;
            $inputCart.val(val);
        });
    }

    //Call Ajax add a product in the Cart from product Page
    function addToCartAjaxFromProductPage() {
        $productId = $(".cart-product #product-id").val();
        $quantityProduct = $("#quantity-product").val();
        $urlCart = $("#add-to-cart").data("path");

        $.ajax({
            type: 'POST',
            url: $urlCart,
            data: { quantity: $quantityProduct },
            success: function (data) {
                if ($.isNumeric(data)) {
                    refreshCart(data);
                }
            },
        });
    }

    //Refresh Cart header after modification
    function refreshCart(quantity) {
        $("#cart-count").addClass("rotate");
        $("#cart-count").html(quantity);
    }

});