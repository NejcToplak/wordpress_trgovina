<?php
/**
 * Storefront Child - functions.php
 * Računalniška trgovina TechShop
 */

// ================================================
// 1. NALOŽI STILE STARŠEVSKE TEME
// ================================================
add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_styles' );
function storefront_child_enqueue_styles() {
    wp_enqueue_style(
        'storefront-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'storefront-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'storefront-parent-style' ),
        '1.0.0'
    );
}

// ================================================
// 2. SPREMENI ŠTEVILO IZDELKOV NA STRAN (privzeto 12)
// ================================================
add_filter( 'loop_shop_per_page', function() { return 12; }, 20 );

// ================================================
// 3. DODAJ SPOROČILO O BREZPLAČNI DOSTAVI
// ================================================
add_action( 'woocommerce_before_shop_loop', 'techshop_free_shipping_notice', 5 );
function techshop_free_shipping_notice() {
    echo '<div style="background:#eff6ff; border:1px solid #0a84ff; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:0.95em;">
        🚚 <strong>Brezplačna dostava</strong> za naročila nad 50 €!
    </div>';
}

// ================================================
// 4. PRILAGODI NAPIS GUMBA "V KOŠARICO"
// ================================================
add_filter( 'woocommerce_product_single_add_to_cart_text', function() {
    return '🛒 Dodaj v košarico';
});

add_filter( 'woocommerce_product_add_to_cart_text', function() {
    return 'V košarico';
});

// ================================================
// 5. DODAJ CUSTOM FOOTER TEKST
// ================================================
add_filter( 'storefront_credit_links_output', 'techshop_custom_footer' );
function techshop_custom_footer( $output ) {
    return '<span>&copy; ' . date('Y') . ' TechShop &mdash; Vsi pravice pridržane. Šolski projekt.</span>';
}

// ================================================
// 6. PRIKAŽI ŠTEVILO IZDELKOV V KATEGORIJI
// ================================================
add_filter( 'woocommerce_subcategory_count_html', 'techshop_category_count', 10, 2 );
function techshop_category_count( $output, $category ) {
    return '<span class="count">(' . $category->count . ' ' .
        _n( 'izdelek', 'izdelkov', $category->count, 'woocommerce' ) . ')</span>';
}

// ================================================
// 7. ODSTRANI NEPOTREBNA POLJA NA BLAGAJNI
//    (za šolski projekt poenostavimo)
// ================================================
add_filter( 'woocommerce_checkout_fields', 'techshop_remove_checkout_fields' );
function techshop_remove_checkout_fields( $fields ) {
    // Obdržimo samo bistvena polja
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_address_2'] );
    return $fields;
}

// ================================================
// 8. DODAJ "NOVI IZDELEK" ZNAČKO
//    (prikaže se na izdelkih dodanih v zadnjih 30 dneh)
// ================================================
add_action( 'woocommerce_before_shop_loop_item_title', 'techshop_new_badge', 5 );
function techshop_new_badge() {
    global $product;
    $created = strtotime( $product->get_date_created() );
    $days    = ( time() - $created ) / DAY_IN_SECONDS;
    if ( $days < 30 ) {
        echo '<span style="background:#30d158; color:#fff; font-size:0.7em; font-weight:700;
              padding:2px 8px; border-radius:4px; display:inline-block; margin-bottom:6px;">NOVO</span>';
    }
}

// ================================================
// 9. DODAJ GOOGLE FONTS (IBM Plex Mono za tech videz)
// ================================================
add_action( 'wp_enqueue_scripts', 'techshop_google_fonts' );
function techshop_google_fonts() {
    wp_enqueue_style(
        'google-fonts-ibm',
        'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap',
        array(),
        null
    );
}

// Nastavi font
add_action( 'wp_head', function() {
    echo '<style>body, .site-title a { font-family: "IBM Plex Sans", sans-serif !important; }</style>';
});
