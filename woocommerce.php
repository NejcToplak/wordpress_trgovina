<?php
/**
 * woocommerce.php — osnovna WooCommerce predloga za child temo
 *
 * Ta datoteka zagotovi, da WooCommerce vsebina
 * pravilno deluje znotraj naše child teme.
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php woocommerce_content(); ?>
    </main>
</div>

<?php
get_sidebar();
get_footer();
