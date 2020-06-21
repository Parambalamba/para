<?php
/**
 * Child theme of Reboot
 * https://wpshop.ru/themes/reboot
 *
 * @package Reboot
 */

/**
 * Enqueue child styles
 *
 * НЕ УДАЛЯЙТЕ ДАННЫЙ КОД
 */
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', 100);
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'reboot-style-child', get_stylesheet_uri(), array( 'reboot-style' )  );
    wp_enqueue_style( 'noto-font', get_stylesheet_uri() . '/assets/fonts/noto.css' );
    wp_enqueue_style( 'arimo-font', get_stylesheet_uri() . '/assets/fonts/arimo.css' );
  //  wp_enqueue_style( 'swiper-style', 'https://unpkg.com/swiper/css/swiper.css' );
    //wp_enqueue_style( 'swiper-min-style', 'https://unpkg.com/swiper/css/swiper.min.css' );
	// подключаем все необходимые скрипты: jQuery, jquery-ui, datepicker
	wp_enqueue_script( 'jquery-ui-datepicker' );
	//wp_enqueue_script( 'jquery-datapicker-range', get_stylesheet_directory_uri() . '/assets/js/jquery.datepicker.extension.range.min.js' );
//	wp_enqueue_script( 'swiper', 'https://unpkg.com/swiper/js/swiper.js', array( 'jquery' ) );
//	wp_enqueue_script( 'swiper-min', 'https://unpkg.com/swiper/js/swiper.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'main-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array( 'jquery' ) );
	// подключаем нужные css стили
	wp_enqueue_style('jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null );

}

/**
 * Подключение dashicons
 */
function my_dashicons() {
    wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'my_dashicons' );

function load_font_awesome() {
  wp_enqueue_style( 'font-awesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}
add_action( 'wp_enqueue_scripts', 'load_font_awesome' );
/**
 * НИЖЕ ВЫ МОЖЕТЕ ДОБАВИТЬ ЛЮБОЙ СВОЙ КОД
 */
/**
 * Вывод двух новостей в сайдбаре на главной
 */
function get_two_news() {
	global $post;
	$result = '<div class="two-news">';
	$posts = get_posts( array(
				'category'		=> 1,
				'numberposts'	=> 2
	) );
	foreach ( $posts as $post ) {
		setup_postdata( $post );
		$title = get_the_title();
		$thumb_url = get_the_post_thumbnail_url();
		$result .= '<div class="two-news-item" style="	background: -moz-linear-gradient(bottom, rgba(18,43,81,0.9) 0%, rgba(68,101,151,0.65) 30%, rgba(255,255,255, 0) 70%), url(' . $thumb_url . ');
	background: -webkit-linear-gradient(left, rgba(18,43,81,0.9) 0%, rgba(68,101,151,0.65) 30%, rgba(255,255,255, 0) 70%), url(' . $thumb_url . ');
background: linear-gradient(to top, rgba(18,43,81,0.9) 0%, rgba(68,101,151,0.65) 30%, rgba(255,255,255, 0) 70%), url(' . $thumb_url . ');"><a href="' . get_permalink() . '" target="_blank"><p>' . $title . '</p></a></div>';
	}
	wp_reset_postdata();
	$result .= '</div>';
	return $result;
}

/**
 * скрипт выбора даты datepicker
 * version: 1
 */
function datepicker_js(){
	// инициализируем datepicker
	if( is_admin() )
		add_action('admin_footer', 'init_datepicker', 99 ); // для админки
	else
		add_action('wp_footer', 'init_datepicker', 99 ); // для админки

	function init_datepicker(){
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			'use strict';
			// настройки по умолчанию. Их можно добавить в имеющийся js файл,
			// если datepicker будет использоваться повсеместно на проекте и предполагается запускать его с разными настройками
			$.datepicker.setDefaults({
				closeText: 'Закрыть',
				prevText: '<Пред',
				nextText: 'След>',
				currentText: 'Сегодня',
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
				dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
				dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
				weekHeader: 'Нед',
				dateFormat: 'dd-mm-yy',
				firstDay: 1,
				showAnim: 'slideDown',
				isRTL: false,
				showMonthAfterYear: false,
				yearSuffix: ''
			} );

			// Инициализация
			$('input[name*="date"], .datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
			// можно подключить datepicker с доп. настройками так:
			/*
			$('input[name*="date"]').datepicker({
				dateFormat : 'yy-mm-dd',
				onSelect : function( dateText, inst ){
		// функцию для поля где указываются еще и секунды: 000-00-00 00:00:00 - оставляет секунды
		var secs = inst.lastVal.match(/^.*?\s([0-9]{2}:[0-9]{2}:[0-9]{2})$/);
		secs = secs ? secs[1] : '00:00:00'; // только чч:мм:сс, оставим часы минуты и секунды как есть, если нет то будет 00:00:00
		$(this).val( dateText +' '+ secs );
				}
			});
			*/
		});
		</script>
		<?php
	}
}

/**
 * Выводим Важные страницы на главной
 */
function get_main_pages() {
	$result = '<div class="mp-wrapper"><div class="mp-item">';
	$i = 0;
	$pages = get_posts( array(
				'order'			=> 'ASC',
				'orderby'		=> 'menu_order',
				'numberposts'	=> 21,
				'post_type'		=> 'page',
				'meta_query' => array(
							        array(
							            'key'     => 'vyvesti_na_glavnoj',
							            'value'   => '"Yes"',
							            'compare' => 'LIKE'
							        )
							    )
	) );
	if ( $pages ) {
		foreach ($pages as $page) {
			if ( ( $i == 7 ) || ( $i == 14 ) ) {
				$result .= '</div><div class="mp-item">';
			}
			$result .= '<a href="' . get_page_link( $page->ID ) . '" target="_blank">' . esc_html( $page->post_title ) . '</a>';
			$i++;
		}
		$result .= '<a href="https://www.gosuslugi.ru/" target="_blank">Единый портал государственных услуг</a></div></div>';
	}
	return $result;
}

/**
 * Создаем произвольный тип записи для Логотипов партнеров
 */
add_action('init', 'my_logos_init');
function my_logos_init(){
	register_post_type('logo_type', array(
		'labels'             => array(
			'name'               => 'Логотипы', // Основное название типа записи
			'singular_name'      => 'Логотип', // отдельное название записи типа Book
			'add_new'            => 'Добавить новый',
			'add_new_item'       => 'Добавить новый логотип',
			'edit_item'          => 'Редактировать логотип',
			'new_item'           => 'Новый логотип',
			'view_item'          => 'Посмотреть логотип',
			'search_items'       => 'Найти логотип',
			'not_found'          =>  'Логотипов не найдено',
			'not_found_in_trash' => 'В корзине логотипов не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Логотипы'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','author')
	) );
}
/**
 * Выводим карусель Партнеров
 */
function get_logos() {
	global $post;
	$result = '<div id="sw-2" class="swiper-container"><div class="swiper-wrapper">';
	$posts = get_posts( array(
				'post_type'		=> 'logo_type',
				'order'			=> 'ASC',
				'numberposts'	=> -1
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			setup_postdata( $post );
			$result .= '<div class="swiper-slide"><a href="' . get_field( 'ssylka' ) . '" target="_blank"><img src="' . get_field( 'logo' ) . '" alt="' . get_the_title() . '"></a></div>';
		}
		$result .= '</div></div>';
		$result .= '<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>';
		wp_reset_postdata();
	}
	return $result;
}

/**
	 Сельские поселения
 */
add_action('init', 'my_selpos_init');
function my_selpos_init(){
	register_post_type('sel_pos', array(
		'labels'             => array(
			'name'               => 'Сельские поселения', // Основное название типа записи
			'singular_name'      => 'Сельское поселение', // отдельное название записи типа Book
			'add_new'            => 'Добавить новое',
			'add_new_item'       => 'Добавить новое поселение',
			'edit_item'          => 'Редактировать поселение',
			'new_item'           => 'Новое поселение',
			'view_item'          => 'Посмотреть поселение',
			'search_items'       => 'Найти поселение',
			'not_found'          =>  'Поселений не найдено',
			'not_found_in_trash' => 'В корзине Поселений не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Сельские поселения'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','author')
	) );
}

/**
 * Выводим блок Сельские поселения на главной
 */
function get_selpos() {
	global $post;
	$result = '';
	$posts = get_posts( array(
				'numberposts'		=> 	-1,
				'post_type'			=>	'sel_pos',
				'order'				=>	'ASC'
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			setup_postdata( $post );
			$result .= '<div class="selpos-item"><a href="' . get_field( 'ssylka' ) . '" target="_blank"><span>' . get_the_title() . '</span></a></div>';
		}
		wp_reset_postdata();
	}
	return $result;
}

/**
 *  Добавляем страницу настроек в админ панель
 */
add_action( 'admin_menu', 'add_my_admin_menu' );
function add_my_admin_menu( ) {
    add_menu_page( 'Дополнительная страница настроек', 'Настройки темы', 'manage_options', 'site-options', 'my_api_options_page', '', 4 );
}

add_action( 'admin_init', 'my_api_settings_init' );
function my_api_settings_init( ) {
    register_setting( 'myCustom', 'my_api_settings_init' );
    add_settings_section(
    	'my_api_main_section',
    	__( 'Организация ', 'wordpress' ),
    	'my_api_main_section_callback',
    	'myCustom'
    );

    add_settings_field(
    	'my_api_org_name',
    	__( 'Название организации', 'wordpress' ),
    	'my_api_org_name_render',
    	'myCustom',
    	'my_api_main_section'
    );

    add_settings_field(
    	'my_api_org_chief',
    	__( 'Главный редактор', 'wordpress' ),
    	'my_api_org_chief_render',
    	'myCustom',
    	'my_api_main_section'
    );
    add_settings_section(
        'my_api_myCustom_section',
        __( 'Контактный телефон', 'wordpress' ),
        'my_api_settings_section_callback',
        'myCustom'
    );
    add_settings_field(
        'my_api_text_field_0',
        __( 'Телефон', 'wordpress' ),
        'my_api_text_field_0_render',
        'myCustom',
        'my_api_myCustom_section'
    );


    add_settings_section(
        'my_api_other_contacts_section',
        __( 'Другие контакты', 'wordpress' ),
        'my_other_contacts_section_callback',
        'myCustom'
    );

    add_settings_field(
        'my_api_address',
        __( 'Адрес', 'wordpress' ),
        'my_api_address_render',
        'myCustom',
        'my_api_other_contacts_section'
    );

    add_settings_field(
        'my_api_email',
        __( 'E-mail', 'wordpress' ),
        'my_api_email_render',
        'myCustom',
        'my_api_other_contacts_section'
    );

    add_settings_field(
        'my_api_registr',
        __( 'Зарегистрировано', 'wordpress' ),
        'my_api_registr_render',
        'myCustom',
        'my_api_other_contacts_section'
    );

    add_settings_field(
        'my_api_registr_number',
        __( 'Регистрационный номер', 'wordpress' ),
        'my_api_registr_number_render',
        'myCustom',
        'my_api_other_contacts_section'
    );

    add_settings_section(
        'my_api_socials_section',
        __( 'Социальные сети', 'wordpress' ),
        'my_socials_section_callback',
        'myCustom'
    );

    add_settings_field(
        'my_api_instagram',
        __( 'Instagram', 'wordpress' ),
        'my_api_instagram_render',
        'myCustom',
        'my_api_socials_section'
    );

    add_settings_field(
        'my_api_vkontakte',
        __( 'Vkontakte', 'wordpress' ),
        'my_api_vkontakte_render',
        'myCustom',
        'my_api_socials_section'
    );

    add_settings_field(
        'my_api_twitter',
        __( 'Twitter', 'wordpress' ),
        'my_api_twitter_render',
        'myCustom',
        'my_api_socials_section'
    );

    add_settings_section(
        'my_api_map_section',
        __( 'Карта', 'wordpress' ),
        'my_map_section_callback',
        'myCustom'
    );

    add_settings_field(
        'my_api_map_code',
        __( 'Код карты', 'wordpress' ),
        'my_api_map_code_render',
        'myCustom',
        'my_api_map_section'
    );

    // Регистрируем опции, чтобы они сохранялись при отправке
	// $_POST параметров и чтобы callback функции опций выводили их значение.
	register_setting( 'myCustom', 'my_api_org_name' );
	register_setting( 'myCustom', 'my_api_org_chief' );
	register_setting( 'myCustom', 'my_api_registr' );
	register_setting( 'myCustom', 'my_api_registr_number' );
	register_setting( 'myCustom', 'my_api_text_field_0' );
    register_setting( 'myCustom', 'my_api_address' );
    register_setting( 'myCustom', 'my_api_email' );
    register_setting( 'myCustom', 'my_api_instagram' );
    register_setting( 'myCustom', 'my_api_vkontakte' );
    register_setting( 'myCustom', 'my_api_twitter' );
    register_setting( 'myCustom', 'my_api_map_code' );
}

function my_api_org_name_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='text' name='my_api_org_name' value='<?php echo get_option( 'my_api_org_name' ); ?>' placeholder='Название организации' size="50">
    <?php
}

function my_api_org_chief_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='text' name='my_api_org_chief' value='<?php echo get_option( 'my_api_org_chief' ); ?>' placeholder='Главный редактор' size="50">
    <?php
}

function my_api_registr_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <textarea name='my_api_registr' rows="3" cols="50" placeholder='Зарегистрировано'><?php echo get_option( 'my_api_registr' ); ?></textarea>
    <?php
}

function my_api_registr_number_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='text' name='my_api_registr_number' value='<?php echo get_option( 'my_api_registr_number' ); ?>' placeholder='Регистрационный номер'>
    <?php
}

function my_api_text_field_0_render( ) {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='text' name='my_api_text_field_0' value='<?php echo get_option( 'my_api_text_field_0' ); ?>' placeholder='8-800-800-85-85'>
    <?php
}

function my_api_address_render( ) {
    $options = get_option( 'my_api_settings' );
    ?>
    <textarea name='my_api_address' rows="3" cols="50"><?php echo get_option( 'my_api_address' ); ?></textarea>
    <?php
}

function my_api_email_render( ) {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='email' name='my_api_email' value='<?php echo get_option( 'my_api_email' ); ?>' placeholder='info@mail.ru' size='30'>
    <?php
}

function my_api_instagram_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='url' name='my_api_instagram' value='<?php echo get_option( 'my_api_instagram' ); ?>' size='30'>
    <?php
}

function my_api_vkontakte_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='url' name='my_api_vkontakte' value='<?php echo get_option( 'my_api_vkontakte' ); ?>' size='30'>
    <?php
}

function my_api_twitter_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <input type='url' name='my_api_twitter' value='<?php echo get_option( 'my_api_twitter' ); ?>' size='30'>
    <?php
}

function my_api_map_code_render() {
    $options = get_option( 'my_api_settings' );
    ?>
    <textarea name='my_api_map_code' rows='10' cols='50'><?php echo get_option( 'my_api_map_code' ); ?></textarea>
    <?php
}
function my_api_main_section_callback() {
    echo __( 'Введите указанные данные для отображения в блоке на главной', 'wordpress' );
}

function my_api_settings_section_callback( ) {
    echo __( 'Введите телефон для отображения на сайте и ссылку href', 'wordpress' );
}

function my_other_contacts_section_callback() {
    echo __( 'Заполните необходимые поля для отображения в подвале сайта и отправки писем с форм обратной связи ', 'wordpress' );
}

function my_socials_section_callback() {
    echo __( 'Укажите ваши социальные сети', 'wordpress' );
}

function my_map_section_callback() {
    echo __( 'Вставьте код карты', 'wordpress' );
}

function my_api_options_page( ) {
    ?>
    <form action='options.php' method='post'>
        <h2><?php echo get_admin_page_title(); ?></h2>
        <?php
        settings_fields( 'myCustom' );
        do_settings_sections( 'myCustom' );
        submit_button();
        ?>
    </form>
    <?php
}

/**
	 Создаем произволньый тип записи Депутат и добавляем к ней рубрики
 */
add_action('init', 'my_deputat_init');
function my_deputat_init(){
	register_post_type('deputat', array(
		'labels'             => array(
			'name'               => 'Собрание депутатов', // Основное название типа записи
			'singular_name'      => 'Депутат', // отдельное название записи типа Book
			'add_new'            => 'Добавить нового',
			'add_new_item'       => 'Добавить нового депутата',
			'edit_item'          => 'Редактировать депутата',
			'new_item'           => 'Новый депутат ',
			'view_item'          => 'Посмотреть депутата',
			'search_items'       => 'Найти депутата',
			'not_found'          =>  'Депутатов не найдено',
			'not_found_in_trash' => 'В корзине Депутатов не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Собрание депутатов'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','author','editor', 'thumbnail'),
		'taxonomies'          => array( 'deputats' )
	) );
}

/**
 * Создаем таксономию для типа записи Депутат
 */
add_action( 'init', 'create_deputats_taxonomy' );
function create_deputats_taxonomy(){

	register_taxonomy( 'deputats_list', [ 'deputat' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Категории',
			'singular_name'     => 'Категория',
			'search_items'      => 'Найти кактегории ',
			'all_items'         => 'Все категории',
			'view_item '        => 'Посмотреть категорию',
			'parent_item'       => 'Родительская категория',
			'parent_item_colon' => 'Родительская категория:',
			'edit_item'         => 'Редактировать категорию',
			'update_item'       => 'Обновить категорию',
			'add_new_item'      => 'Добавить новую категорию',
			'new_item_name'     => 'Имя новой категории',
			'menu_name'         => 'Категории',
		],
		'description'           => '', // описание таксономии
		'public'                => true,
		// 'publicly_queryable'    => null, // равен аргументу public
		// 'show_in_nav_menus'     => true, // равен аргументу public
		// 'show_ui'               => true, // равен аргументу public
		// 'show_in_menu'          => true, // равен аргументу show_ui
		// 'show_tagcloud'         => true, // равен аргументу show_ui
		// 'show_in_quick_edit'    => null, // равен аргументу show_ui
		'hierarchical'          => true,

		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
}

function get_chief_deputat() {
	$result = '';
	$posts = get_posts( array(
			'posts_per_page'	=>	1,
			'post_type'			=>	'deputat',
			'tax_query'			=>	array(
				array(
					'taxonomy'		=> 'deputats_list',
					'field'			=> 'slug',
					'terms'			=> 'predsedatel'
				)
			)
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			$id = $post->ID;
			$title = $post->post_title;
			$result .= '<img src="' . get_the_post_thumbnail_url( $id ) . '" alt="' . $title . '"><div class="dep-desc"><a href="' . get_permalink( $id ) . '"><p class="dep-title">' . $title . '</p></a><p class="dep-dol">' . get_field( 'dolzhnost', $id ) . '</p><div class="dep-info"><div class="info-item"><p class="info-title">Адрес:</p><p class="info-val">' . get_field( 'adres', $id ) . '</p></div><div class="info-item"><p class="info-title">Телефон:</p><p class="info-val">' . get_field( 'phone', $id ) . '</p></div></div></div>';
		}
	}
	return $result;
}

function get_all_deputats( $cat ) {
	$result = '';
	$posts = get_posts( array(
			'posts_per_page'	=>	-1,
			'post_type'			=>	'deputat',
			'order'				=>	'ASC',
			'tax_query'			=>	array(
				array(
					'taxonomy'		=> 'deputats_list',
					'field'			=> 'slug',
					'terms'			=> $cat
				)
			)
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			$id = $post->ID;
			$title = $post->post_title;
			$thumb_url = get_the_post_thumbnail_url( $id, 'photo-thumb' );
			$img = '';
			if ( $thumb_url == '' )
				$img = '<div class="img-box"></div>';
			else
				$img = '<img src="' . $thumb_url . '" alt="' . $title . '">';
			$result .= '<div class="deps-item"><a href="' . get_permalink( $id ) . '">' . $img . '</a><div class="deps-item-info"><a href="' . get_permalink( $id ) . '"><p class="dep-title">' . $title . '</p></a><p class="dep-dolznost">' . get_field( 'dolzhnost', $id ) . '</p></div></div>';
		}
	}
	return $result;

}

/**
	 Создаем произволньый тип записи Документ и добавляем к ней рубрики
 */
add_action('init', 'my_document_init');
function my_document_init(){
	register_post_type('document', array(
		'labels'             => array(
			'name'               => 'Документы', // Основное название типа записи
			'singular_name'      => 'Документ', // отдельное название записи типа Book
			'add_new'            => 'Добавить новый',
			'add_new_item'       => 'Добавить новый Документ',
			'edit_item'          => 'Редактировать Документ',
			'new_item'           => 'Новый Документ ',
			'view_item'          => 'Посмотреть Документ',
			'search_items'       => 'Найти Документ',
			'not_found'          =>  'Документов не найдено',
			'not_found_in_trash' => 'В корзине Документов не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Документы'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title','author', 'slug'),
		'taxonomies'          => array( 'documents_list' )
	) );
}

/**
 * Создаем таксономию для типа записи Документ
 */
add_action( 'init', 'create_documents_list_taxonomy' );
function create_documents_list_taxonomy(){

	register_taxonomy( 'documents', [ 'document' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Категории',
			'singular_name'     => 'Категория',
			'search_items'      => 'Найти кактегории ',
			'all_items'         => 'Все категории',
			'view_item '        => 'Посмотреть категорию',
			'parent_item'       => 'Родительская категория',
			'parent_item_colon' => 'Родительская категория:',
			'edit_item'         => 'Редактировать категорию',
			'update_item'       => 'Обновить категорию',
			'add_new_item'      => 'Добавить новую категорию',
			'new_item_name'     => 'Имя новой категории',
			'menu_name'         => 'Рубрика документов',
		],
		'description'           => '', // описание таксономии
		'public'                => true,
		// 'publicly_queryable'    => null, // равен аргументу public
		// 'show_in_nav_menus'     => true, // равен аргументу public
		 'show_ui'               => true, // равен аргументу public
		// 'show_in_menu'          => true, // равен аргументу show_ui
		// 'show_tagcloud'         => true, // равен аргументу show_ui
		// 'show_in_quick_edit'    => null, // равен аргументу show_ui
		'hierarchical'          => true,

		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => true, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
}

/**
 * Функция получения иконки по типу файла
 */
function get_icon_for_file( $file_id ) {
	$icon = '';
	$file_url = wp_get_attachment_url( $file_id );
	$file_type = wp_check_filetype( $file_url )[ 'ext' ];
	if ( $file_type == 'pdf' )
		$icon = '<img src="' . get_stylesheet_directory_uri() . '/images/icons/pdf.png">';
	elseif ( ( $file_type == 'doc' ) || ( $file_type == 'docx' ) )
		$icon = '<img src="' . get_stylesheet_directory_uri() . '/images/icons/doc.png">';
	return $icon;
}

/**
 * Выводим документы на странице Собрание депутатов
 */
function get_dep_docs() {
	$result = '';
	$posts = get_posts( array(
			'posts_per_page'	=>	-1,
			'post_type'			=>	'document',
			'order'				=>	'ASC',
			'tax_query'			=>	array(
				array(
					'taxonomy'		=> 'documents',
					'field'			=> 'slug',
					'terms'			=> 'sobranie-deputatov'
				)
			)
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			$id = $post->ID;
			$file_id = get_field( 'fajl', $id );
			$result .= '<div class="doc-item"><div class="left-block">' . get_icon_for_file( $file_id ) . '<div class="doc-info"><p class="lf-title">' . $post->post_title . '</p><p class="info-date">' . get_the_date() . '</p></div></div><div class="right-block"><a href="' . wp_get_attachment_url( $file_id ) . '" target="_blank"><img src="' .get_stylesheet_directory_uri() . '/images/icons/look.png" alt="Посмотреть"></a><a href="' . wp_get_attachment_url( $file_id ) . '" download target="_blank"><img src="' .get_stylesheet_directory_uri() . '/images/icons/download.png" alt="Скачать"></a></div></div>';
		}
	}
	return $result;
}

function get_npa_docs() {
	$result = '';
	$posts = get_posts( array(
			'posts_per_page'	=>	-1,
			'post_type'			=>	'document',
			'order'				=>	'ASC',
			'tax_query'			=>	array(
				array(
					'taxonomy'		=> 'documents',
					'field'			=> 'slug',
					'terms'			=> 'sobranie-deputatov-npa'
				)
			)
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			$id = $post->ID;
			$file_id = get_field( 'fajl', $id );
			$result .= '<div class="npa-item"><div class="left-block">' . get_icon_for_file( $file_id ) . '<div class="doc-info"><p class="lf-title">' . $post->post_title . '</p><p class="info-date">' . get_the_date() . '</p></div></div><div class="right-block"><a href="' . wp_get_attachment_url( $file_id ) . '" target="_blank"><img src="' .get_stylesheet_directory_uri() . '/images/icons/look.png" alt="Посмотреть"></a><a href="' . wp_get_attachment_url( $file_id ) . '" download target="_blank"><img src="' .get_stylesheet_directory_uri() . '/images/icons/download.png" alt="Скачать"></a></div></div>';
		}
	}
	return $result;
}

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'photo-thumb', 100, 160, true ); // 300 в ширину и без ограничения в высоту
}

function get_documents_menu() {
	$result = '<div class="long-menu_container"><ul id="menu_documents" class="menu">';
	$menu_items = wp_get_nav_menu_items( 'Документы' );
	$i = 1;
	foreach ( ( array ) $menu_items as $key => $menu_item ) {
		if ( $i > 7 ) {
			$result .= '<li id="menu-item-' . $menu_item->ID . '" class="long-menu-item menu-item menu-item-type-taxonomy menu-item-object-category menu-item-' . $menu_item->ID . ' off-menu-item"><a href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';

		} else {
			$result .= '<li id="menu-item-' . $menu_item->ID . '" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-' . $menu_item->ID . '"><a href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
		}
		$i++;
	}
	$result .= '</ul>';
	if ( $i > 7 )
		$result .= '<span class="more-btn">Еще</span>';
	$result .= '</div>';
	return $result;
}

// Регистрируем и загружаем виджет
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// Creating the widget
class wpb_widget extends WP_Widget {

	function __construct() {
	parent::__construct(

	// Base ID of your widget
	'wpb_widget',

	// Widget name will appear in UI
	__('Длинное меню', 'wpb_widget_domain'),

	// Widget description
	array( 'description' => __( 'Виджет для длинного меню с раскрыванием', 'wpb_widget_domain' ), )
	);
	}

	// Creating widget front-end

	public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );

	// before and after widget arguments are defined by themes
	echo '<div class="widget widget_nav_menu">';
	//echo $args['before_widget'];
	if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];

	// This is where you run the code and display the output
	echo __( get_documents_menu(), 'wpb_widget_domain' );
	echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
	$title = $instance[ 'title' ];
	}
	else {
	$title = __( 'Заголовок', 'wpb_widget_domain' );
	}
	// Widget admin form
	?>


	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />


	<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} // Class wpb_widget ends here

if ( ! function_exists('write_log')) {

   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

function __search_by_title_only( $search, &$wp_query )
{
 global $wpdb;
$search_type = get_query_var( 'search_type' );
error_log("message = " . $search);
 if ( empty( $search ) )
 return $search; // skip processing - no search term in query
 $q = $wp_query->query_vars;
 $n = ! empty( $q['exact'] ) ? '' : '%';
if ( $search_type == 'title' ) {
	error_log("here");
	 $search =
	 $searchand = '';
	 foreach ( (array) $q['search_terms'] as $term ) {
	 $term = esc_sql( $wpdb->esc_like( $term ) );
	 $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
	 $searchand = ' AND ';
	 }
	 if ( ! empty( $search ) ) {
	 $search = " AND ({$search}) ";
	 if ( ! is_user_logged_in() )
	 $search .= " AND ($wpdb->posts.post_password = '') ";
	 }
}
return $search;
}
add_filter( 'posts_search', '__search_by_title_only', 500, 2 );


// Создадим новую функцию которая добавит условие where в запрос

function filter_where_date( $where = '' ) {
	// с 1 марта по 15 марта 2010 года
	error_log("message = " . get_query_var('choose_period'));
	error_log(" startdate = " . get_query_var( 'start_date' ));
	$d = date_create_from_format('j/m/Y', get_query_var( 'start_date' ));
	error_log("date format = " . date_format($d, 'Y-m-d'));
	$start_date = date_create_from_format('j/m/Y', get_query_var( 'start_date' ));
	$end_date = date_create_from_format('j/m/Y', get_query_var( 'end_date' ));
	if ( $end_date == $start_date )
		date_modify( $end_date, '+1 day' );
	error_log(" enddate = " . get_query_var( 'end_date' ));
	if ( ( !is_admin() ) && ( get_query_var( 'choose_period' ) == 'choose' ) ) {
		$where .= " AND post_date >= '" . date_format($start_date, 'Y-m-d') . "' AND post_date < '" . date_format($end_date, 'Y-m-d') . "' ";
		error_log("message where = " . $where);
	}
	return $where;
}
add_filter( 'posts_where', 'filter_where_date' );

function myplugin_register_query_vars( $vars ) {
 $vars[] = 'search_type';
 $vars[] = 'start_date';
 $vars[] = 'end_date';
 $vars[] = 'choose_period';
 $vars[] = 'pt_page';
 	return $vars;
}
add_filter( 'query_vars', 'myplugin_register_query_vars' );

function SearchFilter($query) {
	$pt = get_query_var('pt_page');
	error_log("pt = " . $pt);
	if( !is_admin() ) {
		if ( ( $query->is_search ) && ( $pt == 'page' ) ) {
			error_log("here we are");
			$query->set('post_type', 'page');
		}
	}
	return $query;
}
add_filter('pre_get_posts','SearchFilter');

/**
	 Создаем произволньый тип записи Депутат и добавляем к ней рубрики
 */
add_action('init', 'my_administration_init');
function my_administration_init(){
	register_post_type('chinovnik', array(
		'labels'             => array(
			'name'               => 'Структура администрации', // Основное название типа записи
			'singular_name'      => 'Чиновник', // отдельное название записи типа Book
			'add_new'            => 'Добавить нового',
			'add_new_item'       => 'Добавить нового чиновника',
			'edit_item'          => 'Редактировать чиновника',
			'new_item'           => 'Новый чиновник ',
			'view_item'          => 'Посмотреть чиновника',
			'search_items'       => 'Найти чиновника',
			'not_found'          =>  'Чиновников не найдено',
			'not_found_in_trash' => 'В корзине Чиновников не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Структура администрации'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','author','editor', 'thumbnail'),
		'taxonomies'          => array( 'chinovniks' )
	) );
}

/**
 * Создаем таксономию для типа записи Депутат
 */
add_action( 'init', 'create_chinovniks_taxonomy' );
function create_chinovniks_taxonomy(){

	register_taxonomy( 'chinovniks', [ 'chinovnik' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Категории',
			'singular_name'     => 'Категория',
			'search_items'      => 'Найти кактегории ',
			'all_items'         => 'Все категории',
			'view_item '        => 'Посмотреть категорию',
			'parent_item'       => 'Родительская категория',
			'parent_item_colon' => 'Родительская категория:',
			'edit_item'         => 'Редактировать категорию',
			'update_item'       => 'Обновить категорию',
			'add_new_item'      => 'Добавить новую категорию',
			'new_item_name'     => 'Имя новой категории',
			'menu_name'         => 'Категории чиновников',
		],
		'description'           => '', // описание таксономии
		'public'                => true,
		// 'publicly_queryable'    => null, // равен аргументу public
		// 'show_in_nav_menus'     => true, // равен аргументу public
		// 'show_ui'               => true, // равен аргументу public
		// 'show_in_menu'          => true, // равен аргументу show_ui
		// 'show_tagcloud'         => true, // равен аргументу show_ui
		// 'show_in_quick_edit'    => null, // равен аргументу show_ui
		'hierarchical'          => true,

		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
}

function get_chief_administration() {
	$result = '';
	$posts = get_posts( array(
			'posts_per_page'	=>	1,
			'post_type'			=>	'chinovnik',
			'tax_query'			=>	array(
				array(
					'taxonomy'		=> 'chinovniks',
					'field'			=> 'slug',
					'terms'			=> 'glava-mr-kizilyurtovskij-rajon'
				)
			)
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			$id = $post->ID;
			$title = $post->post_title;
			$result .= '<img src="' . get_the_post_thumbnail_url( $id ) . '" alt="' . $title . '"><div class="dep-desc"><a href="' . get_permalink( $id ) . '"><p class="dep-title">' . $title . '</p></a><p class="dep-dol">' . get_field( 'dolzhnost', $id ) . '</p></div>';
		}
	}
	return $result;
}

function get_all_chinovniks( $cat ) {
	$result = '';
	$posts = get_posts( array(
			'posts_per_page'	=>	-1,
			'post_type'			=>	'chinovnik',
			'order'				=>	'ASC',
			'tax_query'			=>	array(
				array(
					'taxonomy'		=> 'chinovniks',
					'field'			=> 'slug',
					'terms'			=> $cat
				)
			)
	) );
	if ( $posts ) {
		foreach ($posts as $post) {
			$id = $post->ID;
			$title = $post->post_title;
			$thumb_url = get_the_post_thumbnail_url( $id, 'photo-thumb' );
			$img = '';
			if ( $thumb_url == '' )
				$img = '<div class="img-box"></div>';
			else
				$img = '<img src="' . $thumb_url . '" alt="' . $title . '">';
			$result .= '<div class="deps-item"><a href="' . get_permalink( $id ) . '">' . $img . '</a><div class="deps-item-info"><a href="' . get_permalink( $id ) . '"><p class="dep-title">' . $title . '</p></a><p class="dep-dolznost">' . get_field( 'dolzhnost', $id ) . '</p></div></div>';
		}
	}
	return $result;

}

?>
