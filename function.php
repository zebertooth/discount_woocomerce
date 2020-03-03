/**
 * Add Discount for all product
 */
add_action( 'woocommerce_cart_calculate_fees', 'cart_items_quantity_wine_discount', 10, 1 );
function cart_items_quantity_wine_discount($cart_object) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Set HERE your category (can be an ID, a slug or the name)
    $category = 'Discount'; // or a slug: $category = '13'; << ID

    $category_count = 0;
    $category_total = 0;
    $discount = 0;

    // Iterating through each cart item
    foreach($cart_object->get_cart() as $cart_item):

        if( has_term( $category, 'product_cat', $cart_item['product_id']) ):
            $category_count += $cart_item['quantity'];
            $category_total += $cart_item["line_total"]; // calculated total items amount (quantity x price)
        endif;

    endforeach;

    $discount_text = __( 'Quantity discount of ', 'woocommerce' );

    // ## CALCULATIONS ##
    if ( $category_count >= 1 && $category_count < 2 ) {
        $discount -= $category_total * 0.1; // Discount of 10% 
        $discount_text_output = $discount_text . '10%';
    } elseif ( $category_count >=  2 && $category_count < 3 ) {
        $discount -= $category_total * 0.2; // Discount of 20%
        $discount_text_output = $discount_text . '20%';
    } elseif ( $category_count >= 3 ) {
        $discount -= $category_total * 0.3; // Discount of 30%
        $discount_text_output = $discount_text . '30%';
    }

    // Adding the discount
    if ( $discount != 0 && $category_count >= 1 )
        $cart_object->add_fee( $discount_text_output, $discount, false );

    // Note: Last argument in add_fee() method is related to applying the tax or not to the discount (true or false)
}
