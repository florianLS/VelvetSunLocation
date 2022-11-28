$(document).ready(function () {

    //Modify quantity product for adding to the Cart in product page
    if ($(".container.product-page").length) {
        $minus = $(".cart-product .addon-minus");
        $plus = $(".cart-product .addon-plus");
        $inputCart = $(".cart-product #quantity-product");
        $minus.click(function () {
            var val = parseInt($inputCart.val()) - 1;
            $inputCart.val(val > 0 ? val : 1);
            return false
        });
        $plus.click(function () {
            var val = parseInt($inputCart.val()) + 1;
            $inputCart.val(val);
            return false
        });
    }
    //Add a product on the Cart
    $("#add-to-cart").click(function (e) {
        e.preventDefault();
        $productId = $(".cart-product #product-id").val();
        $urlCart = $("#add-to-cart").data("path");
        $quantityProduct = $("#quantity-product").val();

        $.ajax({
            type: 'POST',
            url: $urlCart,
            data: { quantity: $quantityProduct },
            success: function (data) {
                console.log(data);
            },
        });
    })

});