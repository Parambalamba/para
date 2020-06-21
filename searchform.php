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

?>

<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x( 'Search for:', THEME_TEXTDOMAIN ) ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_html__( 'Search…', THEME_TEXTDOMAIN ) ?>" value="<?php echo get_search_query() ?>" name="s">
    </label>
    <label>
        <span class="type-text">Искать:</span>
        <select name="post_type">
        	<option value="any">Везде</option>
        	<option value="page">По разделам</option>
        	<option value="document">По документам</option>
        	<option value="post">По новостям</option>
        </select>
    </label>
    <div class="search-docs">
    	<input type="checkbox" name="search_type" value="title">
    	<label for="search_type">По заголовкам</label>
    </div>
    <div class="search-date">
    	<input type="checkbox" id="chdate" name="choose_period" value="choose">
    	<label for="choose_period">За период:</label>
    	<div class="ch_date">
    		<label>Начало:</label>
    		<input id="cal" name="start_date" type="text" required disabled>
    		<label>Окончание:</label>
    		<input id="cal2" type="text" name="end_date" required disabled>

    	</div>
    </div>
    <input type="hidden" name="pt_page" id="ptpage">

    <button type="submit" class="search-submit">Найти</button>
</form>
