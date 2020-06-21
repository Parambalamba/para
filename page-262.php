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

                        <?php if ( $is_show_social_top ) { ?>
                            <?php get_template_part( 'template-parts/blocks/social', 'buttons' ) ?>
                        <?php } ?>
                    </div>

                </div>
            <?php endif; ?>

        <?php endif; ?>

        <div id="primary" class="content-area" itemscope itemtype="http://schema.org/Article">
            <main id="main" class="site-main">
                <div class="entry-content deputats-wrapper" itemprop="articleBody">
                    <?php
                    do_action( THEME_SLUG . '_page_before_the_content' ); ?>
                    <div class="chief-dep chief-adm">
                        <?php echo get_chief_administration(); ?>
                    </div>
                    <?php $term = get_term( 54, 'chinovniks' ); ?>
                    <div class="chin-list">
                        <?php echo get_all_chinovniks( $term->slug ); ?>
                    </div>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php if ( $is_show_sidebar ) get_sidebar(); ?>

    <?php endwhile; ?>

    <?php if ( $is_show_related_posts ) { ?>
        <?php do_action( THEME_SLUG . '_single_before_related' ); ?>
        <?php get_template_part( 'template-parts/related', 'posts' ); ?>
        <?php do_action( THEME_SLUG . '_single_after_related' ); ?>
    <?php } ?>

<?php
get_footer();
