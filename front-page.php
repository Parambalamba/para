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

	<div id="primary" class="content-area">
		<main id="main" class="site-main front-page-main">
		<div class="cat-news"><p><?php echo get_category( 1 )->cat_name; ?></p></div>
        <div class="sidebar-home">
        	<?php echo get_two_news(  ); ?>
        	<p class="cal-news">Календарь новостей</p>
	        	<label for="from">Выберите начало периода</label>
				<div type="text" id="from" name="from"></div>

				<label for="to">Выберите окончание периода</label>
				<div type="text" id="to" name="to"></div>
				<form role="search" method="get" class="cal-form" action="<?php echo home_url( '/' ); ?>">
					<input type="hidden" name="s" value="<?php echo get_search_query() ?>">
					<input type="hidden" name="post_type" value="post">
					<input type="hidden" name="choose_period" value="choose">
					<input id="std" type="hidden" name="start_date">
					<input id="end" type="hidden" name="end_date">
					<button type="submit" class="search-period">Показать</button>
				</form>
				<span id="dayCount" style="display: none;"></span>
        </div>

            <?php

            if ( 'top' == $wpshop_core->get_option( 'structure_home_position' ) ) get_template_part( 'template-parts/content', 'home' );

            $home_constructor = $wpshop_core->get_option( 'home_constructor' );
            if ( ! empty( $home_constructor ) ) $home_constructor = json_decode( $home_constructor, true );

            if ( ! empty( $home_constructor ) && is_array( $home_constructor ) ) {
	            foreach ( $home_constructor as $section ) {
	                $section_type = ( ! empty( $section['section_type'] ) ) ? $section['section_type'] : 'posts';
		            set_query_var( 'section_options', $section );
		            get_template_part( 'template-parts/sections/' . $section_type );
                }
            } else {
            	$paged = (get_query_var('paged')) ? get_query_var('paged') : query_posts('offset=2&paged=' . $paged);
	            if ( have_posts() ) {

                    if ( is_home() && ! is_front_page() ) :
                        echo '<h1 class="page-title screen-reader-text">' . single_post_title( '', false ) . '</h1>';
                    endif;
                    ?>
					<?php
                    get_template_part( 'template-parts/post-container/' . $wpshop_core->get_option( 'structure_home_posts' ) );

                    //the_posts_pagination();

                } else {

                    get_template_part('template-parts/content', 'none');
                }

            }

            if ( 'bottom' == $wpshop_core->get_option( 'structure_home_position' ) ) get_template_part( 'template-parts/content', 'home' );

            ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div> <!-- site-content-inner -->
</div> <!--site-content -->
<div class="main-pages">
	<div class="site-content-inner">
		<?php echo get_main_pages(); ?>
	</div>
</div>
<div class="partners-logos">
	<div class="site-content-inner">

			<?php echo get_logos(); ?>

	</div>
</div>
<div class="selpos">
    <div class="site-content-inner">
        <p class="block-title">Сельские поселения Кизилюртовского района</p>
        <div class="selpos-wrapper">
            <?php echo get_selpos(); ?>
        </div>
    </div>
</div>
<?php get_template_part( 'template-parts/form', 'callback' ); ?>
<div class="address-block">
    <div class="address-item">
        <p class="adrr-title"><?php echo get_option( 'my_api_org_name' ); ?></p>
        <p class="addr-item"><span>Главный редактор: </span><?php echo get_option( 'my_api_org_chief' ); ?></p>
        <p class="addr-item"><span>Адрес: </span><?php echo get_option( 'my_api_address' ); ?></p>
        <p class="addr-item"><span>Телефон: </span><?php echo get_option( 'my_api_text_field_0' ); ?></p>
        <p class="addr-item"><span>Адрес электронной почты: </span><?php echo get_option( 'my_api_email' ); ?></p>
        <p class="addr-item"><span>Зарегистрировано: </span><?php echo get_option( 'my_api_registr' ); ?></p>
        <p class="addr-item"><span>Регистрационный номер: </span><?php echo get_option( 'my_api_registr_number' ); ?></p>
    </div>
    <div class="address-item">
        <?php echo get_option( 'my_api_map_code' ); ?>
    </div>
</div>

    <?php if ( in_array( $wpshop_core->get_option( 'structure_home_sidebar' ), [ 'left', 'right' ] ) ) get_sidebar(); ?>

<?php
get_footer();
