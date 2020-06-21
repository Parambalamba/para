<?php
/**
 * ****************************************************************************
 *
 *   НЕ РЕДАКТИРУЙТЕ ЭТОТ ФАЙЛ
 *   DON'T EDIT THIS FILE
 *
 *   После обновления Вы потереяете все изменения. Используйте дочернюю тему
 *   After update you will lose all changes. Use child theme
 *
 *   https://docs.wpshop.ru/start/child-themes
 *
 * *****************************************************************************
 *
 * @package reboot
 */

global $wpshop_core;
global $wpshop_template;

$structure_single_hide  = $wpshop_core->get_option( 'structure_single_hide' );
if ( ! empty( $structure_single_hide ) ) {
    $structure_single_hide = explode( ',', $structure_single_hide );
    if ( is_array( $structure_single_hide ) ) {
        $structure_single_hide = array_map( 'trim', $structure_single_hide );
    }
} else {
    $structure_single_hide = array();
}

$thumbnail_type = get_post_meta( $post->ID, 'thumbnail_type', true );

$thumb_url = get_the_post_thumbnail_url( $post, 'full' );

$is_show_thumb         = ( ! in_array( 'thumbnail', $structure_single_hide ) && $wpshop_core->is_show_element( 'thumbnail' ) );
$is_show_breadcrumbs   = $wpshop_core->is_show_element( 'breadcrumbs' );
$is_show_title_h1      = $wpshop_core->is_show_element( 'title_h1' );
$is_show_social_top    = ( ! in_array( 'social_top', $structure_single_hide ) && $wpshop_core->is_show_element( 'social_top' ) );
$is_show_category      = ( ! in_array( 'category', $structure_single_hide ) );
$is_show_comments      = ( ! in_array( 'comments', $structure_single_hide ) && $wpshop_core->is_show_element( 'comments' ) );
$is_show_sidebar       = ( in_array( $wpshop_core->get_option( 'structure_single_sidebar' ), [ 'left', 'right' ] ) && $wpshop_core->is_show_element( 'sidebar' ) );
$is_show_related_posts = $wpshop_core->is_show_element( 'related_posts' );

get_header();
?>

    <?php while ( have_posts() ) : the_post(); ?>

        <?php if ( $thumbnail_type == 'wide' || $thumbnail_type == 'full' || $thumbnail_type == 'fullscreen' ): ?>

            <?php if ( ! empty( $thumb_url ) && $is_show_thumb ): ?>
                <div class="entry-image entry-image--<?php echo $thumbnail_type ?>"<?php if ( ! empty( $thumb_url ) ) echo ' style="background-image: url('. $thumb_url .');"' ?>>

                    <div class="entry-image__body">
                        <?php if ( $is_show_breadcrumbs ) {
                            get_template_part( 'template-parts/blocks/breadcrumbs' );
                        } ?>

                        <?php if ( $is_show_title_h1 ) { ?>
                            <?php do_action( THEME_SLUG . '_single_before_title' ) ?>
                            <h1 class="entry-title"><?php the_title() ?></h1>
                            <?php do_action( THEME_SLUG . '_single_after_title' ) ?>
                        <?php } ?>

                        <?php if ( $is_show_category ) { ?>
                            <div class="post-card__category"><?php echo $wpshop_template->get_category( [ 'micro' => false ] ) ?></div>
                        <?php } ?>

                    </div>

                </div>
            <?php endif; ?>

        <?php endif; ?>

        <div id="primary" class="content-area" itemscope itemtype="http://schema.org/Article">
            <main id="main" class="site-main article-card">

                <?php get_template_part( 'template-parts/content', 'single-deputat' );

                    //the_post_navigation();

                    // If comments are open or we have at least one comment, load up the comment template.
                ?>

            </main><!-- #main -->
        </div><!-- #primary -->

        <?php if ( $is_show_sidebar ) get_sidebar(); ?>

    <?php endwhile; ?>


<?php
get_footer();
