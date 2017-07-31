<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="format-detection" content="telephone=no" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/theme/marketshop/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/theme/marketshop/js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<?php if(($marketshop_custom_column_status == 1) || ($marketshop_video_box_status == 1) || ($marketshop_facebook_block_status == 1) || ($marketshop_twitter_block_status == 1)){ ?>
<script src="catalog/view/theme/marketshop/js/jquery.easing-1.3.min.js" type="text/javascript" ></script>
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/stylesheet.min.css" />
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/responsive.min.css" />
<?php if ($direction == 'rtl') { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/js/bootstrap/css/bootstrap-rtl.min.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/stylesheet-rtl.min.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/responsive-rtl.min.css" />
<?php } ?>
<?php if($marketshop_skin == 2) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/stylesheet-skin2.min.css" />
<?php } else if($marketshop_skin == 3) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/stylesheet-skin3.min.css" />
<?php } else if($marketshop_skin == 4) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/stylesheet-skin4.min.css" />
<?php } ?>

<!-- Custom CSS -->
<!-- <link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/custom-header.css" /> -->
<?php 
foreach ($custom_styles as $custom) {
	?>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/marketshop/stylesheet/<?= $custom ?>" />
	<?php
}
?>

<script type="text/javascript" src="catalog/view/theme/marketshop/js/common.min.js"></script>
<script type="text/javascript" src="catalog/view/theme/marketshop/js/custom.min.js"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php if($marketshop_search_auto_complete == 1) { ?>
<?php if ($route != 'affiliate/tracking') { ?>
<script src="catalog/view/theme/marketshop/js/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {	
	$("#filter_name").autocomplete("getdata.php?lan=<?php echo $lang; ?>", {
		width: 260,
		resultsClass: "ac_results col-lg-4<?php if($marketshop_header_style == 2) { ?> style2<?php } ?><?php if($marketshop_header_style == 3) { ?> style3<?php } ?>",
		matchContains: true
	});
});
</script>
<?php } ?>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
<?php if($marketshop_status == 1) {
if($marketshop_title_font !='' || $marketshop_body_font !='' || $marketshop_top_bar_font !='' || $marketshop_secondary_titles_font !='' || $marketshop_footer_titles_font !='' || $marketshop_main_menu_font != '' ) {		
		$regfonts = array('', 'Arial', 'Verdana', 'Helvetica', 'Lucida Grande', 'Trebuchet MS', 'Times New Roman', 'Tahoma', 'Georgia' );
		if (in_array($marketshop_title_font, $regfonts)==false) { ?>
<link href='//fonts.googleapis.com/css?family=<?php echo $marketshop_title_font ?>' rel='stylesheet' type='text/css'>
<?php }
        if (in_array($marketshop_body_font, $regfonts)==false) { ?>
<link href='//fonts.googleapis.com/css?family=<?php echo $marketshop_body_font ?>' rel='stylesheet' type='text/css'>
<?php }
        if (in_array($marketshop_main_menu_font, $regfonts)==false) { ?>
<link href='//fonts.googleapis.com/css?family=<?php echo $marketshop_main_menu_font ?>' rel='stylesheet' type='text/css'>
<?php }
        if (in_array($marketshop_top_bar_font, $regfonts)==false) { ?>
<link href='//fonts.googleapis.com/css?family=<?php echo $marketshop_top_bar_font ?>' rel='stylesheet' type='text/css'>
<?php  }
        if (in_array($marketshop_secondary_titles_font, $regfonts)==false) { ?>
<link href='//fonts.googleapis.com/css?family=<?php echo $marketshop_secondary_titles_font ?>' rel='stylesheet' type='text/css'>
<?php }
        if (in_array($marketshop_footer_titles_font, $regfonts)==false) { ?>
<link href='//fonts.googleapis.com/css?family=<?php echo $marketshop_footer_titles_font ?>' rel='stylesheet' type='text/css'>
<?php } 
	} 
?>
<?php } ?>
<style type="text/css">
body { <?php if($marketshop_background_color !='') {
?> background-color: <?php echo $marketshop_background_color;
?>;
 <?php
}
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
 $path_image = $config_ssl . 'image/';
}
else {  $path_image = $config_url . 'image/';
}
if($marketshop_custom_image !='') {
?> background-image: url("<?php echo $path_image . $marketshop_custom_image ?>");
?>;
background-position:<?php echo $marketshop_custom_image_position; ?>;
background-repeat:<?php echo $marketshop_custom_image_repeat;
?>;
background-attachment:<?php echo $marketshop_custom_image_attachment;
?>;
<?php
}
else if($marketshop_pattern_overlay!='none') {
?> background-image: url("catalog/view/theme/marketshop/image/patterns/<?php echo $marketshop_pattern_overlay; ?>.png");
<?php
}
else { ?> background-image: none;
 <?php
}
?>
}
<?php if($marketshop_general_links_color!='') {
?> .pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
background-color: <?php echo $marketshop_general_links_color;
?>;
border-color: <?php echo $marketshop_general_links_color;
?>;
}
<?php
}
if($marketshop_body_text_color!='') {
?> body {
color: <?php echo $marketshop_body_text_color;
?>;
}
<?php
}
if($marketshop_general_links_color!='') {
?> a, a b, .articleHeader span a, .pagination > li > a {
color: <?php echo $marketshop_general_links_color;
?>;
}
 <?php
}
if($marketshop_general_links_color!='') {
?> .owl-carousel.slideshowhome .owl-controls .owl-buttons .owl-prev:hover, .owl-carousel.slideshowhome .owl-controls .owl-buttons .owl-next:hover, .nivo-directionNav .nivo-nextNav:hover, .nivo-directionNav .nivo-prevNav:hover {
background-color: <?php echo $marketshop_general_links_color;
?>;
}
 <?php
}
if($marketshop_general_links_hover_color!='') {
?> a:hover, a b:hover, .category .tabs li a:hover, .sitemap li a:hover, .pagination > li > a:hover, .breadcrumb a:hover, .login-content .right a:hover, .box-category a:hover, .list-item a:hover, #blogArticle .articleHeader h1 a:hover, #blogCatArticles .articleHeader h3 a:hover, .tags-update .tags a:hover, .articleHeader span a:hover {
color: <?php echo $marketshop_general_links_hover_color;
?>;
}
<?php
}
if($marketshop_heading_color!='') {
?> #container h1 {
color: <?php echo $marketshop_heading_color;
?>;
}
<?php
}
if($marketshop_secondary_heading_color!='') {
?> #container h2, #container h3, .product-tab .htabs a, .product-tab .tabs li a {
color: <?php echo $marketshop_secondary_heading_color;
?>;
}
<?php
}
if($marketshop_secondary_heading_border_color!='') {
?> #container h2, #container h3, .product-tab .htabs a, .product-tab .tabs li.active a, .product-tab .tabs, .category .tabs li.active a {
border-color: <?php echo $marketshop_secondary_heading_border_color;
?>;
}
<?php
}
if($marketshop_header_margin!='') {
?> #header .header-row {
padding: <?php echo $marketshop_header_margin;
?>px 0px;
}
/*===== TOP BAR =====*/
<?php
}
if($marketshop_top_bar_bg_color!='') {
?> #header .htop, .left-top {
background-color:<?php echo $marketshop_top_bar_bg_color;
?>;
}
<?php
}
if($marketshop_top_bar_link_color!='') {
?> #header .links > ul > li.mobile, #header .links > ul > li > a, #form-language span, #form-currency span, #header #top-links > ul > li > a, .drop-icon {
color:<?php echo $marketshop_top_bar_link_color;
?>;
}
<?php
}
if($marketshop_top_bar_link_color!='') {
?> #header .links > ul > li.wrap_custom_block a b {
border-top-color:<?php echo $marketshop_top_bar_link_color;
?>;
}
<?php
}
if(($marketshop_top_bar_link_separator_style!='') && ($marketshop_top_bar_link_separator_color!='')) {
?> #header .links > ul > li, #header #top-links > ul > li {
border-left:1px <?php echo $marketshop_top_bar_link_separator_style ?> <?php echo $marketshop_top_bar_link_separator_color ?>
}
<?php
}
if(($marketshop_top_bar_link_separator_style!='') && ($marketshop_top_bar_link_separator_color!='')) {
?> #header .links, #form-language, #form-currency, #header #top-links {
border-right:1px <?php echo $marketshop_top_bar_link_separator_style ?> <?php echo $marketshop_top_bar_link_separator_color ?>
}
<?php
}
if($marketshop_top_bar_sub_link_color !='') {
?> #top .dropdown-menu li a, #form-currency ul li .currency-select, #form-language ul li .language-select {
color:<?php echo $marketshop_top_bar_sub_link_color ;
?>;
}
<?php
}
if($marketshop_top_bar_sub_link_hover_color !='') {
?> #top .dropdown-menu li a:hover, #form-currency ul li .currency-select:hover, #form-language ul li .language-select:hover {
color:<?php echo $marketshop_top_bar_sub_link_hover_color ;
?>;
}
<?php
}
if($marketshop_header_bg_color!='') {
?> #header .header-row {
background-color:<?php echo $marketshop_header_bg_color;
?>;
}
<?php
}
if($marketshop_mini_cart_icon_color!='') {
?> #header #cart .heading h4 {
background-color:<?php echo $marketshop_mini_cart_icon_color;
?>;
}
<?php
}
if($marketshop_mini_cart_icon_color!='') {
?> #header #cart .heading h4:after, #header #cart .heading h4:before, #header #cart .dropdown-menu {
border-color:<?php echo $marketshop_mini_cart_icon_color;
?>;
}
<?php
}
if($marketshop_mini_cart_icon_color!='') {
?> #header #cart.open .heading span:after {
border-color:transparent transparent <?php echo $marketshop_mini_cart_icon_color;
?>;
}
<?php
}
if($marketshop_mini_cart_link_color!='') {
?> #header #cart .heading {
color:<?php echo $marketshop_mini_cart_link_color;
?>;
}
<?php
}
if($marketshop_mini_cart_active_link_color!='') {
?> #header #cart.open .heading {
color:<?php echo $marketshop_mini_cart_active_link_color;
?>;
}
 <?php
}
if($marketshop_search_bar_background_color!='') {
?> #header #search input {
background-color:<?php echo $marketshop_search_bar_background_color;
?>;
}
<?php
}
if($marketshop_search_bar_border_color!='') {
?> #header #search input {
border-color:<?php echo $marketshop_search_bar_border_color;
?>;
}
<?php
}
if($marketshop_search_bar_text_color!='') {
?> #header #search input {
color:<?php echo $marketshop_search_bar_text_color;
?>;
}
<?php
}
if($marketshop_search_bar_border_hover_color!='') {
?> #header #search input:focus, #header #search input:hover {
border-color:<?php echo $marketshop_search_bar_border_hover_color;
?>;
}
<?php
}
if($marketshop_search_bar_icon_color!='') {
?> #header .button-search {
color:<?php echo $marketshop_search_bar_icon_color;
?>;
}


/*===== MAIN MENU =====*/
<?php
}
if(($marketshop_main_menu_align!='') && ($marketshop_main_menu_align ==2)) {
?> #menu .navbar-nav {
text-align: center;
}
#menu .nav > li {
display: inline-block;
float: none;
}
<?php
}
if(($marketshop_menu_bg_color!='') && ($marketshop_menu_bg_color_status ==1)) {
?> #menu {
background-color: <?php echo $marketshop_menu_bg_color;
?>;
}
<?php
}
if($marketshop_menu_link_color!='') {
?> #menu .nav > li > a, #menu .navbar-header > span, #menu .nav > li > span.submore:after {
color: <?php echo $marketshop_menu_link_color;
?>;
}
<?php
}
if($marketshop_menu_link_color!='') {
?> #menu .navbar-header > span > b:before {
border-color: transparent transparent <?php echo $marketshop_menu_link_color;
?>;
}
<?php
}
if($marketshop_menu_link_color!='') {
?> #menu .navbar-header > span > b:after {
border-color: <?php echo $marketshop_menu_link_color;
?> transparent transparent;
}
 <?php
}
if($marketshop_menu_link_hover_color!='') {
?> #menu .nav > li:hover > a {
color: <?php echo $marketshop_menu_link_hover_color;
?>;
}
<?php
}
if($marketshop_menu_link_hover_bg_color!='') {
?> #menu .nav > li:hover > a {
background-color: <?php echo $marketshop_menu_link_hover_bg_color;
?>;
}
 <?php
}
if(($marketshop_menu_link_separator_size!='') && ($marketshop_menu_link_separator_style!='') && ($marketshop_menu_link_separator_color!='') && ($marketshop_menu_link_separator_status ==1)) {
?> #menu .nav > li + li {
border-left:<?php echo $marketshop_menu_link_separator_size ?>px <?php echo $marketshop_menu_link_separator_style ?> <?php echo $marketshop_menu_link_separator_color ?>
}
 <?php
}
if(($marketshop_menu_link_border_top_size !='') && ($marketshop_menu_link_border_top_style !='') && ($marketshop_menu_link_border_top_color !='') && ($marketshop_menu_link_border_top_status ==1)) {
?> #menu {
border-top:<?php echo $marketshop_menu_link_border_top_size ?>px <?php echo $marketshop_menu_link_border_top_style ?> <?php echo $marketshop_menu_link_border_top_color ?>
}
<?php
}
if(($marketshop_menu_link_border_top_status == 2)) {
?> #menu {
border-top:none;
}
<?php
}
if(($marketshop_menu_link_border_bottom_size !='') && ($marketshop_menu_link_border_bottom_style !='') && ($marketshop_menu_link_border_bottom_color !='') && ($marketshop_menu_link_border_bottom_status == 1)) {
?> #menu {
border-bottom:<?php echo $marketshop_menu_link_border_bottom_size ?>px <?php echo $marketshop_menu_link_border_bottom_style ?> <?php echo $marketshop_menu_link_border_bottom_color ?>
}
<?php
}
if(($marketshop_menu_link_border_bottom_status == 2)) {
?> #menu {
border-bottom:none;
}
<?php
}
if($marketshop_sub_menu_background_color !='') {
?> #menu .nav > li.categories_hor > div, #menu .nav > li.categories > div, #menu .nav > li.categories_hor > div .submenu, #menu .nav > li.categories > div > .column > div, #menu .nav > li > .dropdown-menu, #menu .submenu {
background-color:<?php echo $marketshop_sub_menu_background_color;
?>;
}
<?php
}
if($marketshop_sub_menu_top_border_color!='') {
?> #menu .nav > li.categories_hor > div, #menu .nav > li.categories > div, #menu .nav > li.categories_hor > div .submenu, #menu .nav > li.categories > div > .column > div, #menu .nav > li > .dropdown-menu, #menu .submenu {
border-color:<?php echo $marketshop_sub_menu_top_border_color;
?>;
}
<?php
}
if($marketshop_sub_menu_top_border_color!='') {
?> #menu .nav > li.sub:hover > a:after {
border-bottom-color:<?php echo $marketshop_sub_menu_top_border_color;
?>;
}
<?php
}
if($marketshop_sub_menu_heading_text_color!='') {
?> #menu .nav > li.categories_hor > div > .column > a {
color:<?php echo $marketshop_sub_menu_heading_text_color;
?>;
}
<?php
}
if(($marketshop_sub_menu_heading_text_separator_color!='') && ($marketshop_sub_menu_heading_text_separator_style!='')) {
?> #menu .nav > li.categories_hor > div > .column > a {
border-bottom:1px <?php echo $marketshop_sub_menu_heading_text_separator_style ?> <?php echo $marketshop_sub_menu_heading_text_separator_color ?>
}
<?php
}
if($marketshop_sub_menu_link_color !='') {
?> #menu .nav > li.categories > div > .column > a, #menu .nav > li div > ul > li > a, #menu .nav > li.menu_brands > div > div a, #menu .custom_block, #menu .custom_block a {
color: <?php echo $marketshop_sub_menu_link_color;
?>;
}
<?php
}
if($marketshop_sub_menu_link_hover_color!='') {
?> #menu .nav > li.categories > div > .column:hover > a, #menu .nav > li div > ul > li:hover > a, #menu .nav > li.categories_hor > div > .column:hover > a, #menu .nav > li.menu_brands > div > div a:hover, #menu .custom_block a:hover {
color: <?php echo $marketshop_sub_menu_link_hover_color;
?>;
}
<?php
}
if(($marketshop_sub_menu_link_separator_color!='') && ($marketshop_sub_menu_link_separator_style!='')) {
?> #menu .nav > li.categories > div > .column + .column, #menu .nav > li > div > ul li + li, #menu .nav > li.categories > div > .column:hover > div ul li + li, #menu .nav > li.categories_hor > div .submenu ul li + li a {
border-top:1px <?php echo $marketshop_sub_menu_link_separator_style ?> <?php echo $marketshop_sub_menu_link_separator_color ?>
}
 <?php
}
if($marketshop_home_page_link_background_color !='') {
?> #menu .nav > li > a.home_link {
background-color: <?php echo $marketshop_home_page_link_background_color ;
?>;
}
<?php
}
if($marketshop_home_page_link_background_color_hover !='') {
?> #menu .nav > li > a.home_link:hover {
background-color: <?php echo $marketshop_home_page_link_background_color_hover;
?>;
}
<?php
}
if($marketshop_home_page_link_link_color!='') {
?> #menu .nav > li > a.home_link {
color: <?php echo $marketshop_home_page_link_link_color;
?>;
}
<?php
}
if($marketshop_home_page_link_link_color_hover!='') {
?> #menu .nav > li > a.home_link:hover {
color: <?php echo $marketshop_home_page_link_link_color_hover;
?>;
}
<?php
}
if($marketshop_home_page_link_icon_color!='') {
?> #menu .nav > li > a.home_link span {
background-color: <?php echo $marketshop_home_page_link_icon_color;
?>;
}
<?php
}
if($marketshop_home_page_link_icon_color!='') {
?> #menu .nav > li > a.home_link span:before {
border-bottom-color: <?php echo $marketshop_home_page_link_icon_color;
?>;
}
<?php
}
if($marketshop_home_page_link_icon_color!='') {
?> #menu .nav > li > a.home_link span:after {
border-color: <?php echo $marketshop_home_page_link_icon_color;
?>;
}
<?php
}
if($marketshop_home_page_link_icon_color_hover!='') {
?> #menu .nav > li > a.home_link:hover span {
background-color: <?php echo $marketshop_home_page_link_icon_color_hover;
?>;
}
<?php
}
if($marketshop_home_page_link_icon_color_hover!='') {
?> #menu .nav > li > a.home_link:hover span:before {
border-bottom-color: <?php echo $marketshop_home_page_link_icon_color_hover;
?>;
}
<?php
}
if($marketshop_home_page_link_icon_color_hover!='') {
?> #menu .nav > li > a.home_link:hover span:after {
border-color: <?php echo $marketshop_home_page_link_icon_color_hover;
?>;
}
 <?php
}
if($marketshop_categories_section_background_color!='') {
?> #menu .nav > li.categories_defu > a, #menu .nav > li.categories > a, #menu .nav > li.categories_hor > a {
background-color: <?php echo $marketshop_categories_section_background_color;
?>;
}
<?php
}
if($marketshop_categories_section_background_color_hover!='') {
?> #menu .nav > li.categories:hover > a {
background-color: <?php echo $marketshop_categories_section_background_color_hover;
?>;
}
<?php
}
if($marketshop_categories_section_link_color!='') {
?> #menu .nav > li.categories > a {
color: <?php echo $marketshop_categories_section_link_color;
?>;
}
<?php
}
if($marketshop_categories_section_link_color_hover!='') {
?> #menu .nav > li.categories:hover > a {
color: <?php echo $marketshop_categories_section_link_color_hover;
?>;
}
 <?php
}
if($marketshop_brands_section_background_color!='') {
?> #menu .nav > li.menu_brands > a {
background-color: <?php echo $marketshop_brands_section_background_color;
?>;
}
<?php
}
if($marketshop_brands_section_background_color_hover!='') {
?> #menu .nav > li.menu_brands:hover > a {
background-color: <?php echo $marketshop_brands_section_background_color_hover;
?>;
}
<?php
}
if($marketshop_brands_section_link_color!='') {
?> #menu .nav > li.menu_brands > a {
color: <?php echo $marketshop_brands_section_link_color;
?>;
}
<?php
}
if($marketshop_brands_section_link_color_hover!='') {
?> #menu .nav > li.menu_brands:hover > a {
color: <?php echo $marketshop_brands_section_link_color_hover;
?>;
}
 <?php
}
if($marketshop_custom_link_section_background_color!='') {
?> #menu .nav > li.custom-link > a {
background-color: <?php echo $marketshop_custom_link_section_background_color;
?>;
}
<?php
}
if($marketshop_custom_link_section_background_color_hover!='') {
?> #menu .nav > li.custom-link:hover > a {
background-color: <?php echo $marketshop_custom_link_section_background_color_hover;
?>;
}
<?php
}
if($marketshop_custom_link_section_link_color!='') {
?> #menu .nav > li.custom-link > a {
color: <?php echo $marketshop_custom_link_section_link_color;
?>;
}
<?php
}
if($marketshop_custom_link_section_link_color_hover!='') {
?> #menu .nav > li.custom-link:hover > a {
color: <?php echo $marketshop_custom_link_section_link_color_hover;
?>;
}
 <?php
}
if($marketshop_custom_block_section_background_color!='') {
?> #menu .nav > li.wrap_custom_block > a {
background-color: <?php echo $marketshop_custom_block_section_background_color;
?>;
}
<?php
}
if($marketshop_custom_block_section_background_color_hover!='') {
?> #menu .nav > li.wrap_custom_block:hover > a {
background-color: <?php echo $marketshop_custom_block_section_background_color_hover;
?>;
}
<?php
}
if($marketshop_custom_block_section_link_color!='') {
?> #menu .nav > li.wrap_custom_block > a {
color: <?php echo $marketshop_custom_block_section_link_color;
?>;
}
<?php
}
if($marketshop_custom_block_section_link_color_hover!='') {
?> #menu .nav > li.wrap_custom_block:hover > a {
color: <?php echo $marketshop_custom_block_section_link_color_hover;
?>;
}
 <?php
}
if($marketshop_myaccount_section_background_color!='') {
?> #menu .nav > li.my-account-link > a {
background-color: <?php echo $marketshop_myaccount_section_background_color;
?>;
}
<?php
}
if($marketshop_myaccount_section_background_color_hover!='') {
?> #menu .nav > li.my-account-link:hover > a {
background-color: <?php echo $marketshop_myaccount_section_background_color_hover;
?>;
}
<?php
}
if($marketshop_myaccount_section_link_color!='') {
?> #menu .nav > li.my-account-link > a {
color: <?php echo $marketshop_myaccount_section_link_color;
?>;
}
<?php
}
if($marketshop_myaccount_section_link_color_hover!='') {
?> #menu .nav > li.my-account-link:hover > a {
color: <?php echo $marketshop_myaccount_section_link_color_hover;
?>;
}
 <?php
}
if($marketshop_information_section_background_color!='') {
?> #menu .nav > li.information-link > a {
background-color: <?php echo $marketshop_information_section_background_color;
?>;
}
<?php
}
if($marketshop_information_section_background_color_hover!='') {
?> #menu .nav > li.information-link:hover > a {
background-color: <?php echo $marketshop_information_section_background_color_hover;
?>;
}
<?php
}
if($marketshop_information_section_link_color!='') {
?> #menu .nav > li.information-link > a {
color: <?php echo $marketshop_information_section_link_color;
?>;
}
<?php
}
if($marketshop_information_section_link_color_hover!='') {
?> #menu .nav > li.information-link:hover > a {
color: <?php echo $marketshop_information_section_link_color_hover;
?>;
}
 <?php
}
if($marketshop_contact_section_background_color!='') {
?> #menu .nav > li.contact-link > a {
background-color: <?php echo $marketshop_contact_section_background_color;
?>;
}
<?php
}
if($marketshop_contact_section_background_color_hover!='') {
?> #menu .nav > li.contact-link:hover > a {
background-color: <?php echo $marketshop_contact_section_background_color_hover;
?>;
}
<?php
}
if($marketshop_contact_section_link_color!='') {
?> #menu .nav > li.contact-link > a {
color: <?php echo $marketshop_contact_section_link_color;
?>;
}
<?php
}
if($marketshop_contact_section_link_color_hover!='') {
?> #menu .nav > li.contact-link:hover > a {
color: <?php echo $marketshop_contact_section_link_color_hover;
?>;
}
 <?php
}
if($marketshop_custom_link_right_background_color!='') {
?> #menu .nav > li.custom-link-right > a {
background-color: <?php echo $marketshop_custom_link_right_background_color;
?>;
}
<?php
}
if($marketshop_custom_link_right_background_color_hover!='') {
?> #menu .nav > li.custom-link-right:hover > a {
background-color: <?php echo $marketshop_custom_link_right_background_color_hover;
?>;
}
<?php
}
if($marketshop_custom_link_right_link_color!='') {
?> #menu .nav > li.custom-link-right > a {
color: <?php echo $marketshop_custom_link_right_link_color;
?>;
}
<?php
}
if($marketshop_custom_link_right_link_color_hover!='') {
?> #menu .nav > li.custom-link-right:hover > a {
color: <?php echo $marketshop_custom_link_right_link_color_hover;
?>;
}
 <?php
}
if($marketshop_custom_block_bg_color!='') {
?> .custom_side_block_icon {
background-color: <?php echo $marketshop_custom_block_bg_color;
?>;
}
<?php
}
if($marketshop_custom_block_bg_color!='') {
?> #custom_side_block {
border-color: <?php echo $marketshop_custom_block_bg_color;
?>;
}



/*===== FOOTER =====*/
<?php
}
if($marketshop_footer_bg_color!='') {
?> #footer .fpart-first {
background-color: <?php echo $marketshop_footer_bg_color;
?>;
}
<?php
}
if($marketshop_footer_titles_color!='') {
?> #footer h5 {
color: <?php echo $marketshop_footer_titles_color;
?>;
}
<?php
}
if($marketshop_footer_text_color!='') {
?> #footer .fpart-first {
color: <?php echo $marketshop_footer_text_color;
?>;
}
<?php
}
if($marketshop_footer_link_color!='') {
?> #footer .fpart-first a {
color: <?php echo $marketshop_footer_link_color;
?>;
}
<?php
}
if($marketshop_footer_link_hover_color!='') {
?> #footer .fpart-first a:hover {
color: <?php echo $marketshop_footer_link_hover_color;
?>;
}
<?php
}
if($marketshop_contact_icon_color!='') {
?> #footer .contact > ul > li > .fa {
color: <?php echo $marketshop_contact_icon_color;
?>;
}
<?php
}
if($marketshop_footer_second_bg_color!='') {
?> #footer .fpart-second {
background-color: <?php echo $marketshop_footer_second_bg_color;
?>;
}
<?php
}
if($marketshop_footer_second_text_color!='') {
?> #footer .fpart-second {
color: <?php echo $marketshop_footer_second_text_color;
?>;
}
 <?php
}
if($marketshop_footer_second_link_color!='') {
?> #footer .fpart-second a {
color: <?php echo $marketshop_footer_second_link_color;
?>;
}
<?php
}
if($marketshop_footer_second_link_hover_color!='') {
?> #footer .fpart-second a:hover {
color: <?php echo $marketshop_footer_second_link_hover_color;
?>;
}
<?php
}
if(($marketshop_footer_second_separator_color!='') && ($marketshop_footer_second_separator_style!='')) {
?>#footer #powered {
border-bottom:<?php echo $marketshop_footer_second_separator_size ?>px <?php echo $marketshop_footer_second_separator_style ?> <?php echo $marketshop_footer_second_separator_color ?>
}
<?php
}
if($marketshop_footer_backtotop_bg_color!='') {
?> #back-top a:hover {
background-color: <?php echo $marketshop_footer_backtotop_bg_color;
?>;
}

/*===== PRICE =====*/
<?php
}
if($marketshop_price_color!='') {
?> .product-thumb .price, .product-info .price {
color: <?php echo $marketshop_price_color;
?>;
}
<?php
}
if($marketshop_old_price_color!='') {
?> .product-thumb .price-old, .product-info .price-old {
color: <?php echo $marketshop_old_price_color;
?>;
}
<?php
}
if($marketshop_new_price_color!='') {
?> .product-thumb .price-new, .product-info .price-new {
color: <?php echo $marketshop_new_price_color;
?>;
}
<?php
}
if($marketshop_tax_price_color!='') {
?> .product-info .price-tax, .product-thumb .price .price-tax {
color: <?php echo $marketshop_tax_price_color;
?>;
}
<?php
}
if($marketshop_saving_percentage_bg_color!='') {
?> .saving {
background-color: <?php echo $marketshop_saving_percentage_bg_color;
?>;
}
<?php
}
if($marketshop_saving_percentage_text_color!='') {
?> .saving {
color: <?php echo $marketshop_saving_percentage_text_color;
?>;
}

/*===== BUTTON =====*/
<?php
}
if($marketshop_button_bg_color!='') {
?> .btn-primary {
background-color: <?php echo $marketshop_button_bg_color;
?>;
}
<?php
}
if($marketshop_button_bg_hover_color!='') {
?> .btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] {
background-color: <?php echo $marketshop_button_bg_hover_color;
?>;
}
<?php
}
if($marketshop_button_text_color!='') {
?> .btn-primary {
color: <?php echo $marketshop_button_text_color;
?>;
}
<?php
}
if($marketshop_button_text_hover_color!='') {
?> .btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] {
color: <?php echo $marketshop_button_text_hover_color;
?>;
}

/*===== DEFAULT BUTTON =====*/
<?php
}
if($marketshop_default_button_bg_color!='') {
?> .btn-default {
background-color: <?php echo $marketshop_default_button_bg_color;
?>;
}
<?php
}
if($marketshop_default_button_bg_hover_color!='') {
?> .btn-default:hover {
background-color: <?php echo $marketshop_default_button_bg_hover_color;
?>;
}
<?php
}
if($marketshop_default_button_text_color!='') {
?> .btn-default {
color: <?php echo $marketshop_default_button_text_color;
?>;
}
<?php
}
if($marketshop_default_button_text_hover_color!='') {
?> .btn-default:hover {
color: <?php echo $marketshop_default_button_text_hover_color;
?>;
}

/*===== AddToCart BUTTONS =====*/
<?php
}
if($marketshop_cart_button_bg_color!='') {
?> .product-thumb .button-group > button {
background-color: <?php echo $marketshop_cart_button_bg_color;
?>;
}
<?php
}
if($marketshop_cart_button_bg_hover_color!='') {
?> .product-thumb .button-group > button:hover {
background-color: <?php echo $marketshop_cart_button_bg_hover_color;
?>;
}
<?php
}
if($marketshop_cart_button_text_color!='') {
?> .product-thumb .button-group > button {
color: <?php echo $marketshop_cart_button_text_color;
?>;
}
<?php
}
if($marketshop_cart_button_text_hover_color!='') {
?> .product-thumb .button-group > button:hover {
color: <?php echo $marketshop_cart_button_text_hover_color;
?>;
}

/*===== Exclusive BUTTONS =====*/
<?php
}
if($marketshop_excl_button_bg_color!='') {
?> .product-info .cart #button-cart {
background-color: <?php echo $marketshop_excl_button_bg_color;
?>;
}
<?php
}
if($marketshop_excl_button_bg_hover_color!='') {
?> .product-info .cart #button-cart:hover {
background-color: <?php echo $marketshop_excl_button_bg_hover_color;
?>;
}
 <?php
}
if($marketshop_excl_button_text_color!='') {
?> .product-info .cart #button-cart {
color: <?php echo $marketshop_excl_button_text_color;
?>;
}
<?php
}
if($marketshop_excl_button_text_hover_color!='') {
?> .product-info .cart #button-cart:hover {
color: <?php echo $marketshop_excl_button_text_hover_color;
?>;
}
 <?php
}
if($marketshop_feature_box1_bg_color!='') {
?> .custom-feature-box .feature-box.fbox_1 {
background: <?php echo $marketshop_feature_box1_bg_color;
?>;
}
<?php
}
if($marketshop_feature_box1_title_color!='') {
?> .custom-feature-box .feature-box.fbox_1 .title {
color: <?php echo $marketshop_feature_box1_title_color;
?>;
}
<?php
}
if($marketshop_feature_box1_subtitle_color!='') {
?> .custom-feature-box .feature-box.fbox_1 p {
color: <?php echo $marketshop_feature_box1_subtitle_color;
?>;
}
 <?php
}
if($marketshop_feature_box2_bg_color!='') {
?> .custom-feature-box .feature-box.fbox_2 {
background: <?php echo $marketshop_feature_box2_bg_color;
?>;
}
<?php
}
if($marketshop_feature_box2_title_color!='') {
?> .custom-feature-box .feature-box.fbox_2 .title {
color: <?php echo $marketshop_feature_box2_title_color;
?>;
}
<?php
}
if($marketshop_feature_box2_subtitle_color!='') {
?> .custom-feature-box .feature-box.fbox_2 p {
color: <?php echo $marketshop_feature_box2_subtitle_color;
?>;
}
 <?php
}
if($marketshop_feature_box3_bg_color!='') {
?> .custom-feature-box .feature-box.fbox_3 {
background: <?php echo $marketshop_feature_box3_bg_color;
?>;
}
<?php
}
if($marketshop_feature_box3_title_color!='') {
?> .custom-feature-box .feature-box.fbox_3 .title {
color: <?php echo $marketshop_feature_box3_title_color;
?>;
}
<?php
}
if($marketshop_feature_box3_subtitle_color!='') {
?> .custom-feature-box .feature-box.fbox_3 p {
color: <?php echo $marketshop_feature_box3_subtitle_color;
?>;
}
 <?php
}
if($marketshop_feature_box4_bg_color!='') {
?> .custom-feature-box .feature-box.fbox_4 {
background: <?php echo $marketshop_feature_box4_bg_color;
?>;
}
<?php
}
if($marketshop_feature_box4_title_color!='') {
?> .custom-feature-box .feature-box.fbox_4 .title {
color: <?php echo $marketshop_feature_box4_title_color;
?>;
}
<?php
}
if($marketshop_feature_box4_subtitle_color!='') {
?> .custom-feature-box .feature-box.fbox_4 p {
color: <?php echo $marketshop_feature_box4_subtitle_color;
?>;
}
<?php
}
if($marketshop_feature_box_title_font_size!='') {
?> .custom-feature-box .feature-box .title {
font-size:<?php echo $marketshop_feature_box_title_font_size;
?>;
}
<?php
}
if($marketshop_feature_box_title_font_weight!='') {
?> .custom-feature-box .feature-box .title {
font-weight:<?php echo $marketshop_feature_box_title_font_weight;
?>;
}
<?php
}
if($marketshop_feature_box_title_font_transform!='') {
?> .custom-feature-box .feature-box .title {
text-transform:<?php echo $marketshop_feature_box_title_font_transform;
?>;
}
 <?php
}
if($marketshop_feature_box_subtitle_font_size!='') {
?> .custom-feature-box .feature-box p {
font-size:<?php echo $marketshop_feature_box_subtitle_font_size;
?>;
}
<?php
}
if($marketshop_feature_box_subtitle_font_weight!='') {
?> .custom-feature-box .feature-box p {
font-weight:<?php echo $marketshop_feature_box_subtitle_font_weight;
?>;
}
<?php
}
if($marketshop_feature_box_subtitle_font_transform!='') {
?> .custom-feature-box .feature-box p {
text-transform:<?php echo $marketshop_feature_box_subtitle_font_transform;
?>;
}


/*===== FONTS =====*/
<?php
}
if ($marketshop_body_font!= '' ) {
 $fontpre = $marketshop_body_font;
 $font = str_replace("+", " ", $fontpre);
?>  body {
font-family:<?php echo $font ?>;
}
<?php
}
 if($marketshop_title_font!='') {
 $fontpre = $marketshop_title_font;
 $font = str_replace("+", " ", $fontpre);
?>  #container h1 {
font-family:<?php echo $font ?>;
}
<?php
}
if($marketshop_title_font_size!='') {
?> #container h1 {
font-size:<?php echo $marketshop_title_font_size;
?>;
}
<?php
}
if($marketshop_title_font_weight!='') {
?> #container h1 {
font-weight:<?php echo $marketshop_title_font_weight;
?>;
}
<?php
}
if($marketshop_title_font_uppercase!='') {
?> #container h1 {
text-transform:<?php echo $marketshop_title_font_uppercase;
?>;
}
<?php
}
 if($marketshop_main_menu_font!='') {
 $fontpre = $marketshop_main_menu_font;
 $font = str_replace("+", " ", $fontpre);
?>  #menu {
font-family:<?php echo $font ?>;
}
<?php
}
if($marketshop_main_menu_font_size!='') {
?> #menu .nav > li > a {
font-size:<?php echo $marketshop_main_menu_font_size;
?>;
}
<?php
}
if($marketshop_main_menu_font_weight!='') {
?> #menu .nav > li > a {
font-weight:<?php echo $marketshop_main_menu_font_weight;
?>;
}
<?php
}
if($marketshop_main_menu_font_uppercase!='') {
?> #menu .nav > li > a {
text-transform:<?php echo $marketshop_main_menu_font_uppercase;
?>;
}
<?php
}
 if($marketshop_top_bar_font!='') {
 $fontpre = $marketshop_top_bar_font;
 $font = str_replace("+", " ", $fontpre);
?>  #header .htop {
font-family:<?php echo $font ?>;
}
<?php
}
if($marketshop_top_bar_font_size!='') {
?> #header .links > ul > li.mobile, #header #top-links > ul > li > a, #header .links ul li a, #form-language span, #form-currency span, #header .links .wrap_custom_block > a {
font-size:<?php echo $marketshop_top_bar_font_size;
?>;
}
<?php
}
if($marketshop_top_bar_font_weight!='') {
?> #header .links > ul > li.mobile, #header #top-links > ul > li > a, #header .links ul li a, #form-language span, #form-currency span, #header .links .wrap_custom_block > a {
font-weight:<?php echo $marketshop_top_bar_font_weight;
?>;
}
<?php
}
if($marketshop_top_bar_font_uppercase!='') {
?> #header .links > ul > li.mobile, #header #top-links > ul > li > a, #header .links ul li a, #form-language span, #form-currency span, #header .links .wrap_custom_block > a {
text-transform:<?php echo $marketshop_top_bar_font_uppercase;
?>;
}
<?php
}
 if($marketshop_secondary_titles_font!='') {
 $fontpre = $marketshop_secondary_titles_font;
 $font = str_replace("+", " ", $fontpre);
 ?>#container h2, #container h3, .product-tab .htabs a, .product-tab .tabs li a {
font-family:<?php echo $font ?>;
}
<?php
}
if($marketshop_secondary_titles_font_size!='') {
?> #container h2, #container h3, .product-tab .htabs a, .product-tab .tabs li a {
font-size:<?php echo $marketshop_secondary_titles_font_size;
?>;
}
<?php
}
if($marketshop_secondary_titles_font_weight!='') {
?> #container h2, #container h3, .product-tab .htabs a, .product-tab .tabs li a {
font-weight:<?php echo $marketshop_secondary_titles_font_weight;
?>;
}
<?php
}
if($marketshop_secondary_titles_font_uppercase!='') {
?> #container h2, #container h3, .product-tab .htabs a, .product-tab .tabs li a {
text-transform:<?php echo $marketshop_secondary_titles_font_uppercase;
?>;
}
<?php
}
 if($marketshop_footer_titles_font!='') {
 $fontpre = $marketshop_footer_titles_font;
 $font = str_replace("+", " ", $fontpre);
?>  #footer h5 {
font-family:<?php echo $font ?>;
}
 <?php
}
if($marketshop_footer_titles_font_size!='') {
?> #footer h5 {
font-size:<?php echo $marketshop_footer_titles_font_size;
?>;
}
<?php
}
if($marketshop_footer_titles_font_weight!='') {
?> #footer h5 {
font-weight:<?php echo $marketshop_footer_titles_font_weight;
?>;
}
<?php
}
if($marketshop_footer_titles_font_uppercase!='') {
?> #footer h5 {
text-transform:<?php echo $marketshop_footer_titles_font_uppercase;
?>;
}
<?php
}
 if ($marketshop_custom_css!= '') {
 echo htmlspecialchars_decode($marketshop_custom_css);
}
?> @media screen and (max-width:800px) {
<?php if($marketshop_top_bar_bg_color!='') {
?> .left-top {
background-color:<?php echo $marketshop_top_bar_bg_color;
?>;
}
<?php
}
if(($marketshop_main_menu_align!='') && ($direction == 'ltr') && ($marketshop_main_menu_align ==2)) {
?> #menu .navbar-nav {
text-align: left;
}
#menu .nav > li {
display:block;
}
<?php
}
if(($marketshop_main_menu_align!='') && ($direction == 'rtl') && ($marketshop_main_menu_align ==2)) {
?> #menu .navbar-nav {
text-align: right;
}
#menu .nav > li {
display:block;
}
<?php
}
if($marketshop_menu_link_color!='') {
?> #menu > span {
color: <?php echo $marketshop_menu_link_color;
?>;
}
<?php
}
if($marketshop_menu_link_color!='') {
?> #menu > span:before {
border-bottom-color: <?php echo $marketshop_menu_link_color;
?>;
}
<?php
}
if($marketshop_menu_link_color!='') {
?> #menu > span:after {
border-top-color: <?php echo $marketshop_menu_link_color;
?>;
}
<?php
}
if(($marketshop_menu_link_border_bottom_size!='') && ($marketshop_menu_link_border_bottom_style!='') && ($marketshop_menu_link_border_bottom_color!='') && ($marketshop_menu_link_border_bottom_status ==1)) {
?> #menu .nav {
border-top:<?php echo $marketshop_menu_link_border_bottom_size ?>px <?php echo $marketshop_menu_link_border_bottom_style ?> <?php echo $marketshop_menu_link_border_bottom_color ?>
}
<?php
}
?>
}
</style>
</head>
<body class="<?php echo $class; ?> test">
<?php if($marketshop_layout_style == 1) { ?>
<section class="wrapper-wide">
<?php } else { ?>
<section class="wrapper-box">
<?php } ?>
<?php if($marketshop_header_style == 1) { ?>
<div id="header" >
<?php } ?>
<?php if($marketshop_header_style == 2) { ?>
<div id="header" class="style2">
<?php } ?>
<?php if($marketshop_header_style == 3) { ?>
<div id="header" class="style3">
  <?php } ?>
  <nav class="htop" id="top" style="display: none;">
    <div class="<?php if($marketshop_layout_style == 1) { ?>container<?php } else { ?>container-fluid<?php } ?>">
      <div class="row"> <span class="drop-icon visible-sm visible-xs"><i class="fa fa-align-justify"></i></span>
        <div class="pull-left flip left-top">
          <?php $lang = (int)$config_language_id;?>
          <div class="links">
            <ul>
              <?php if($marketshop_top_bar_contact_status == 1) {
 	if((isset($marketshop_top_bar_contact[$lang]) && $marketshop_top_bar_contact[$lang] != '')) { ?>
              <li class="mobile"><i class="fa fa-phone"></i><?php echo $marketshop_top_bar_contact[$lang]; ?></li>
              <?php } ?>
              <?php } ?>
              <?php if($marketshop_top_bar_email_status == 1) {
 	if((isset($marketshop_top_bar_email[$lang]) && $marketshop_top_bar_email[$lang] != '')) { ?>
              <li class="email"><a href="mailto:<?php echo $marketshop_top_bar_email[$lang]; ?>"><i class="fa fa-envelope"></i><?php echo $marketshop_top_bar_email[$lang]; ?></a></li>
              <?php } ?>
              <?php } ?>
              <?php if($marketshop_custom_link1_top == 1) {
 	if((isset($marketshop_custom_link1_top_title[$lang]) && $marketshop_custom_link1_top_title[$lang] != '')) { ?>
              <li><a href="<?php echo $marketshop_custom_link1_top_url; ?>" target="<?php echo $marketshop_target_link1_top; ?>"> <?php echo $marketshop_custom_link1_top_title[$lang]; ?></a></li>
              <?php } ?>
              <?php } ?>
              <?php if($marketshop_custom_link2_top == 1) {
 	if((isset($marketshop_custom_link2_top_title[$lang]) && $marketshop_custom_link2_top_title[$lang] != '')) { ?>
              <li><a href="<?php echo $marketshop_custom_link2_top_url; ?>" target="<?php echo $marketshop_target_link2_top; ?>"> <?php echo $marketshop_custom_link2_top_title[$lang]; ?></a></li>
              <?php } ?>
              <?php } ?>
              <?php if($marketshop_custom_link3_top == 1) {
 	if((isset($marketshop_custom_link3_top_title[$lang]) && $marketshop_custom_link3_top_title[$lang] != '')) { ?>
              <li><a href="<?php echo $marketshop_custom_link3_top_url; ?>" target="<?php echo $marketshop_target_link3_top; ?>"> <?php echo $marketshop_custom_link3_top_title[$lang]; ?></a></li>
              <?php } ?>
              <?php } ?>
              <?php if($marketshop_custom_block_top_status == 1) {
    if(isset($marketshop_custom_block_top_title[$lang]) && $marketshop_custom_block_top_title[$lang]!= '') { ?>
              <li class="wrap_custom_block hidden-sm hidden-xs"><a><?php echo $marketshop_custom_block_top_title[$lang]; ?><b></b></a>
                <div class="custom_block">
                  <ul>
                  <?php if(isset($marketshop_custom_block_top_content[$lang]['description']) && $marketshop_custom_block_top_content[$lang]['description']!=""){?>
                      <li><?php echo html_entity_decode($marketshop_custom_block_top_content[$lang]['description'], ENT_QUOTES, 'UTF-8'); ?></li>
                      <?php } ?>
                  </ul>
                </div>
              </li>
              <?php } ?>
              <?php } ?>
              <?php if($marketshop_custom_block2_top_status == 1) {
    if(isset($marketshop_custom_block2_top_title[$lang]) && $marketshop_custom_block2_top_title[$lang]!= '') { ?>
              <li class="wrap_custom_block hidden-sm hidden-xs"><a><?php echo $marketshop_custom_block2_top_title[$lang]; ?><b></b></a>
                <div class="custom_block">
                  <ul>
                  <?php if(isset($marketshop_custom_block2_top_content[$lang]['description']) && $marketshop_custom_block2_top_content[$lang]['description']!=""){?>
                      <li><?php echo html_entity_decode($marketshop_custom_block2_top_content[$lang]['description'], ENT_QUOTES, 'UTF-8'); ?></li>
                      <?php } ?>
                  </ul>
                </div>
              </li>
              <?php } ?>
              <?php } ?>
              <?php if($marketshop_wishlist_top_link == 1) { ?>
              <li><a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a></li>
              <?php } ?>
              <?php if($marketshop_checkout_top_link == 1) { ?>
              <li><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <?php echo $language; ?> <?php echo $currency; ?> </div>
        <div id="top-links" class="nav pull-right flip">
          <ul>
            <?php if ($logged) { ?>
            <li id="my_account" class="dropdown"><a href="<?php echo $account; ?>"><?php echo $text_account; ?> <i class="fa fa-caret-down"></i></a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
                <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
              </ul>
            </li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <header class="header-row">
    <div class="container">
      <?php if($marketshop_header_style == 1) { ?>
      <div class="table-container">
        <div class="col-table-cell col-lg-3 col-md-3 col-sm-12 col-xs-12 inner">
          <?php if ($logo) { ?>
          <div id="logo"><a href="<?php echo $home; ?>"><img class="img-responsive" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" style="width: 130px;" /></a></div>
          <?php } ?>
        </div>
        <div class="col-table-cell col-lg-3 col-md-3 col-sm-6 col-xs-12 inner"> <?php echo $search; ?> </div>
        <div class="col-table-cell col-lg-3 col-md-3 col-sm-6 col-xs-12"> <?php echo $cart; ?> </div>
      </div>
      <?php } ?>
      <?php if($marketshop_header_style == 2) { ?>
      <div class="table-container">
        <div class="col-table-cell col-lg-3 col-md-3 col-sm-12 col-xs-12 inner"> <?php echo $cart; ?> </div>
        <div class="col-table-cell col-lg-6 col-md-6 col-sm-12 col-xs-12">
          <?php if ($logo) { ?>
          <div id="logo"><a href="<?php echo $home; ?>"><img class="img-responsive" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
          <?php } ?>
        </div>
        <div class="col-table-cell col-lg-3 col-md-3 col-sm-12 col-xs-12 inner"> <?php echo $search; ?> </div>
      </div>
      <?php } ?>
      <?php if($marketshop_header_style == 3) { ?>
      <div class="table-container">
        <div class="col-table-cell col-lg-4 col-md-4 col-sm-12 col-xs-12 inner">
          <?php if ($logo) { ?>
          <div id="logo"><a href="<?php echo $home; ?>"><img class="img-responsive" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
          <?php } ?>
        </div>
        <div class="col-table-cell col-lg-5 col-md-5 col-md-push-0 col-sm-6 col-sm-push-6 col-xs-12"> <?php echo $search; ?> </div>
        <div class="col-table-cell col-lg-3 col-md-3 col-md-pull-0 col-sm-6 col-sm-pull-6 col-xs-12 inner"> <?php echo $cart; ?> </div>
      </div>
      <?php } ?>
	    <div class="text-center auth-holder">
	  	<?php 
	      	if ($logged) { ?>
	      		<!-- <li id="my_account" class="dropdown"><a href="<?php echo $account; ?>"><?php echo $text_account; ?> <i class="fa fa-caret-down"></i></a>
	              <ul class="dropdown-menu dropdown-menu-right">
	                <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
	                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
	                <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
	                <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
	              </ul>
	            </li> -->
	            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
    <?php  	}
	      	else { ?>
				<a href="<?= $login ?>"><?= $text_login ?></a>
				<a href="<?= $register ?>"><?= $text_register ?></a>
	<?php  	}
	      ?>
	  	</div>
    </div>
  </header>
  <?php if($marketshop_main_menu_style == 2) { ?>
  <div id="menu-line">
	  <div class="container">
	    <?php } ?>
	    <nav id="menu" class="navbar">
	      <div class="navbar-header"> <span class="visible-xs visible-sm">
	        <?php if($marketshop_status == 1) {
	    if(isset($marketshop_mobile_menu_title[$lang]) && $marketshop_mobile_menu_title[$lang]!= '') { ?>
	        <?php echo $marketshop_mobile_menu_title[$lang]; ?>
	        <?php } ?>
	        <?php } ?>
	        <b></b></span>
	      </div>
	      <?php if($marketshop_main_menu_style == 1) { ?>
	      <div class="container">
	        <?php } ?>
	        <div class="collapse navbar-collapse navbar-ex1-collapse">
	          <ul class="nav navbar-nav">
	            <?php if ($categories) {?>
	            <?php if($marketshop_top_menu == 1) {?>
	            <?php foreach ($categories as $category) { ?>
	            <li class="categories_defu dropdown"><a class="dropdown-toggle" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	              <?php if ($category['children']) { ?>
	              <div class="dropdown-menu">
	                <?php for ($i = 0; $i < count($category['children']);) { ?>
	                <ul>
	                  <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
	                  <?php for (; $i < $j; $i++) { ?>
	                  <?php if (isset($category['children'][$i])) { ?>
	                  <li>
	                    <?php
					if(count($category['children'][$i]['children_level2'])>0){
					?>
	                    <a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?>
	                    <?php
							echo "<span>&rsaquo;</span></a>";
					}
					else
					{
					?>
	                    <a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a>
	                    <?php
					}
					?>
	                    <?php if ($category['children'][$i]['children_level2']) { ?>
	                    <div class="submenu">
	                      <ul>
	                        <?php for ($wi = 0; $wi < count($category['children'][$i]['children_level2']); $wi++) { ?>
	                        <li> <a href="<?php echo $category['children'][$i]['children_level2'][$wi]['href']; ?>"  ><?php echo $category['children'][$i]['children_level2'][$wi]['name']; ?> </a> </li>
	                        <?php } ?>
	                      </ul>
	                    </div>
	                    <?php } ?>
	                  </li>
	                  <?php } ?>
	                  <?php } ?>
	                </ul>
	                <?php } ?>
	              </div>
	              <?php } ?>
	            </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_top_menu == 2) {?>
	            <li class="categories dropdown">
	              <?php if($marketshop_status == 1) {
	 	if((isset($marketshop_menu_categories_title[$lang]) && $marketshop_menu_categories_title[$lang] != '')) { ?>
	              <a class="active"><?php echo $marketshop_menu_categories_title[$lang]; ?></a>
	              <?php } ?>
	              <?php } ?>
	              <div class="dropdown-menu">
	                <?php foreach ($categories as $category) { ?>
	                <div class="column">
	                	<?php 
							if ($category['image']) {
								$image = 'image/' . $category['image'];
							}
							else {
								$image = 'catalog/view/theme/marketshop/images-custom/cat-clock.png';
							}
						?>
	                  <?php
					if(count($category['children'])>0){
					?>
	                  <a href="<?php echo $category['href']; ?>"><img src="<?= $image ?>"><?php echo $category['name']; ?></span>
	                  <?php
							echo "<span>&rsaquo;</span></a>";
					}
					else
					{
					?>
	                  <a href="<?php echo $category['href']; ?>"><img src="<?= $image ?>"><?php echo $category['name']; ?></a>
	                  <?php
					}
					?>
	                  <?php if ($category['children']) { ?>
	                  <div>
	                    <?php for ($i = 0; $i < count($category['children']);) { ?>
	                    <ul>
	                      <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
	                      <?php for (; $i < $j; $i++) { ?>
	                      <?php if (isset($category['children'][$i])) { ?>
	                      <li>
	                        <?php
					if(count($category['children'][$i]['children_level2'])>0){
					?>
	                        <a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?>
	                        <?php
							echo "<span>&rsaquo;</span></a>";
					}
					else
					{
					?>
	                        <a href="<?php echo $category['children'][$i]['href']; ?>" ><?php echo $category['children'][$i]['name']; ?></a>
	                        <?php
					}
					?>
	                        <?php if ($category['children'][$i]['children_level2']) { ?>
	                        <div class="submenu">
	                          <ul>
	                            <?php for ($wi = 0; $wi < count($category['children'][$i]['children_level2']); $wi++) { ?>
	                            <li> <a href="<?php echo $category['children'][$i]['children_level2'][$wi]['href']; ?>"  ><?php echo $category['children'][$i]['children_level2'][$wi]['name']; ?> </a> </li>
	                            <?php } ?>
	                          </ul>
	                        </div>
	                        <?php } ?>
	                      </li>
	                      <?php } ?>
	                      <?php } ?>
	                    </ul>
	                    <?php } ?>
	                  </div>
	                  <?php } ?>
	                </div>
	                <?php } ?>
	              </div>
	            </li>
	            <?php } ?>
	            <?php if($marketshop_top_menu == 3) {?>
	            <li class="categories_hor dropdown">
	              <?php if($marketshop_status == 1) {
	 	if((isset($marketshop_menu_categories_title[$lang]) && $marketshop_menu_categories_title[$lang] != '')) { ?>
	              <a><?php echo $marketshop_menu_categories_title[$lang]; ?></a>
	              <?php } ?>
	              <?php } ?>
	              <div class="dropdown-menu">
	                <?php foreach ($categories as $category) { ?>
	                <div class="column col-lg-2 col-md-3"> <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	                  <?php if ($category['children']) { ?>
	                  <div>
	                    <?php for ($i = 0; $i < count($category['children']);) { ?>
	                    <ul>
	                      <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
	                      <?php for (; $i < $j; $i++) { ?>
	                      <?php if (isset($category['children'][$i])) { ?>
	                      <li>
	                        <?php
					if(count($category['children'][$i]['children_level2'])>0){
					?>
	                        <a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?>
	                        <?php
							echo "<span>&rsaquo;</span></a>";
					}
					else
					{
					?>
	                        <a href="<?php echo $category['children'][$i]['href']; ?>" ><?php echo $category['children'][$i]['name']; ?></a>
	                        <?php
					}
					?>
	                        <?php if ($category['children'][$i]['children_level2']) { ?>
	                        <div class="submenu">
	                          <ul>
	                            <?php for ($wi = 0; $wi < count($category['children'][$i]['children_level2']); $wi++) { ?>
	                            <li> <a href="<?php echo $category['children'][$i]['children_level2'][$wi]['href']; ?>"  ><?php echo $category['children'][$i]['children_level2'][$wi]['name']; ?> </a> </li>
	                            <?php } ?>
	                          </ul>
	                        </div>
	                        <?php } ?>
	                      </li>
	                      <?php } ?>
	                      <?php } ?>
	                    </ul>
	                    <?php } ?>
	                  </div>
	                  <?php } ?>
	                </div>
	                <?php } ?>
	              </div>
	            </li>
	            <?php } ?>
	            <?php if($marketshop_top_menu == 4) {?>
	            <?php foreach ($categories as $category) { ?>
	            <li class="categories_hor dropdown"> <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	              <?php if ($category['children']) { ?>
	              <div class="dropdown-menu">
	                <?php for ($i = 0; $i < count($category['children']);) { ?>
	                <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
	                <?php for (; $i < $j; $i++) { ?>
	                <?php if (isset($category['children'][$i])) { ?>
	                <div class="column col-lg-2 col-md-3"> <a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a>
	                  <?php if ($category['children'][$i]['children_level2']) { ?>
	                  <div>
	                    <ul>
	                      <?php for ($wi = 0; $wi < count($category['children'][$i]['children_level2']); $wi++) { ?>
	                      <li> <a href="<?php echo $category['children'][$i]['children_level2'][$wi]['href']; ?>"  ><?php echo $category['children'][$i]['children_level2'][$wi]['name']; ?> </a> </li>
	                      <?php } ?>
	                    </ul>
	                  </div>
	                  <?php } ?>
	                </div>
	                <?php } ?>
	                <?php } ?>
	                <?php } ?>
	              </div>
	              <?php } ?>
	            </li>
	            <?php } ?>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_menu_brands == 1) { ?>
	            <li class="menu_brands <?php if($marketshop_brands_display_style == 1) { ?>name<?php } ?> dropdown">
	              <?php if($marketshop_status == 1) {
	 	if((isset($marketshop_menu_brands_title[$lang]) && $marketshop_menu_brands_title[$lang] != '')) { ?>
	              <a href="<?php echo $manufacturer; ?>"><?php echo $marketshop_menu_brands_title[$lang]; ?></a>
	              <?php } ?>
	              <?php } ?>
	              <?php if($marketshop_brands_display_style == 1) { ?>
	              <div class="dropdown-menu">
	                <?php if ($manufacturers) { ?>
	                <ul>
	                  <?php $counter = 0; foreach ($manufacturers as $manufacturer) { ?>
	                  <li><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a></li>
	                  <?php $counter++; } ?>
	                </ul>
	                <?php } ?>
	              </div>
	              <?php } ?>
	              <?php if($marketshop_brands_display_style == 2) { ?>
	              <div class="dropdown-menu">
	                <?php if ($manufacturers) { ?>
	                <?php $counter = 0; foreach ($manufacturers as $manufacturer) { ?>
	                <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6"> <a href="<?php echo $manufacturer['href']; ?>"><img src="<?php echo $manufacturer['image']; ?>" title="<?php echo $manufacturer['name']; ?>" alt="<?php echo $manufacturer['name']; ?>" /></a> </div>
	                <?php $counter++; } ?>
	                <?php } ?>
	              </div>
	              <?php } ?>
	              <?php if($marketshop_brands_display_style == 3) { ?>
	              <div class="dropdown-menu">
	                <?php if ($manufacturers) { ?>
	                <?php $counter = 0; foreach ($manufacturers as $manufacturer) { ?>
	                <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6"> <a href="<?php echo $manufacturer['href']; ?>"><img src="<?php echo $manufacturer['image']; ?>" title="<?php echo $manufacturer['name']; ?>" alt="<?php echo $manufacturer['name']; ?>" /></a> <a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a> </div>
	                <?php $counter++; } ?>
	                <?php } ?>
	              </div>
	            </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_link1 == 1) {
	 	if((isset($marketshop_custom_link1_title[$lang]) && $marketshop_custom_link1_title[$lang] != '')) { ?>
	            <li class="custom-link"><a href="<?php echo $marketshop_custom_link1_url; ?>" target="<?php echo $marketshop_target_link1; ?>"> <?php echo $marketshop_custom_link1_title[$lang]; ?></a> </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_link2== 1) {
	 	if((isset($marketshop_custom_link2_title[$lang]) && $marketshop_custom_link2_title[$lang] != '')) { ?>
	            <li class="custom-link"><a href="<?php echo $marketshop_custom_link2_url; ?>" target="<?php echo $marketshop_target_link2; ?>"> <?php echo $marketshop_custom_link2_title[$lang]; ?></a> </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_link3== 1) {
	 	if((isset($marketshop_custom_link3_title[$lang]) && $marketshop_custom_link3_title[$lang] != '')) { ?>
	            <li class="custom-link"><a href="<?php echo $marketshop_custom_link3_url; ?>" target="<?php echo $marketshop_target_link3; ?>"> <?php echo $marketshop_custom_link3_title[$lang]; ?></a> </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_link4== 1) {
	 	if((isset($marketshop_custom_link4_title[$lang]) && $marketshop_custom_link4_title[$lang] != '')) { ?>
	            <li class="custom-link"><a href="<?php echo $marketshop_custom_link4_url; ?>" target="<?php echo $marketshop_target_link4; ?>"> <?php echo $marketshop_custom_link4_title[$lang]; ?></a> </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_link5 == 1) {
	 	if((isset($marketshop_custom_link5_title[$lang]) && $marketshop_custom_link5_title[$lang] != '')) { ?>
	            <li class="custom-link"><a href="<?php echo $marketshop_custom_link5_url; ?>" target="<?php echo $marketshop_target_link5; ?>"> <?php echo $marketshop_custom_link5_title[$lang]; ?></a> </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_block_status == 1) {
	    if(isset($marketshop_custom_block_title[$lang]) && $marketshop_custom_block_title[$lang]!= '') { ?>
	            <li class="dropdown wrap_custom_block hidden-sm hidden-xs"><a><?php echo $marketshop_custom_block_title[$lang]; ?></a>
	              <div class="dropdown-menu custom_block">
	                <ul>
	                <?php if(isset($marketshop_custom_block_content[$lang]['description']) && $marketshop_custom_block_content[$lang]['description']!=""){?>
	                 <li><?php echo html_entity_decode($marketshop_custom_block_content[$lang]['description'], ENT_QUOTES, 'UTF-8'); ?></li>
	                <?php } ?>                      
	                </ul>
	              </div>
	            </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_block2_status == 1) {
	    if(isset($marketshop_custom_block2_title[$lang]) && $marketshop_custom_block2_title[$lang]!= '') { ?>
	            <li class="dropdown wrap_custom_block hidden-sm hidden-xs"><a><?php echo $marketshop_custom_block2_title[$lang]; ?></a>
	              <div class="dropdown-menu custom_block">
	                <ul>
	                <?php if(isset($marketshop_custom_block2_content[$lang]['description']) && $marketshop_custom_block2_content[$lang]['description']!=""){?>
	                  <li><?php echo html_entity_decode($marketshop_custom_block2_content[$lang]['description'], ENT_QUOTES, 'UTF-8'); ?></li>
	                <?php } ?>
	                </ul>
	              </div>
	            </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_custom_block3_status == 1) {
	    if(isset($marketshop_custom_block3_title[$lang]) && $marketshop_custom_block3_title[$lang]!= '') { ?>
	            <li class="dropdown wrap_custom_block hidden-sm hidden-xs"><a><?php echo $marketshop_custom_block3_title[$lang]; ?></a>
	              <div class="dropdown-menu custom_block">
	                <ul>
	                <?php if(isset($marketshop_custom_block3_content[$lang]['description']) && $marketshop_custom_block3_content[$lang]['description']!=""){?>
	                  <li><?php echo html_entity_decode($marketshop_custom_block3_content[$lang]['description'], ENT_QUOTES, 'UTF-8'); ?></li>
	                <?php } ?>
	                </ul>
	              </div>
	            </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_my_account == 1) { ?>
	            <li class="dropdown my-account-link"><a><?php echo $text_account; ?></a>
	              <div class="dropdown-menu">
	                <ul>
	                  <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
	                  <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
	                  <li><a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a></li>
	                  <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
	                  <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
	                  <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
	                </ul>
	              </div>
	            </li>
	            <?php } ?>
	            <?php if($marketshop_information_page == 1) { 
	   if ($informations) { ?>
	            <li class="dropdown information-link"><a><?php echo $text_information; ?></a>
	              <div class="dropdown-menu">
	                <ul>
	                  <?php foreach ($informations as $information) { ?>
	                  <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
	                  <?php } ?>
	                </ul>
	              </div>
	            </li>
	            <?php } ?>
	            <?php } ?>
	            <?php if($marketshop_contact_us == 1) { ?>
	            <li class="contact-link"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
	            <?php } ?>
	            <?php if($marketshop_custom_link_right == 1) {
	 	if((isset($marketshop_custom_link_right_title[$lang]) && $marketshop_custom_link_right_title[$lang] != '')) { ?>
	            <li class="custom-link-right"><a href="<?php echo $marketshop_custom_link_right_url; ?>" target="<?php echo $marketshop_custom_link_right_target; ?>"> <?php echo $marketshop_custom_link_right_title[$lang]; ?></a> </li>
	            <?php } ?>
	            <?php } ?>
	          </ul>
	        </div>
	        <?php if($marketshop_main_menu_style == 1) { ?>
	      </div>
	      <?php } ?>
	    </nav>
	    <?php if($marketshop_main_menu_style == 2) { ?>
	  </div>
  </div>
  <?php } ?>
</div>
<?php echo $hd_ctf; ?>
<div id="container">
<?php if($marketshop_feature_box_per_row == 'pr1') { ?>
<?php $class_fbox = 'col-xs-12'; ?>
<?php } elseif($marketshop_feature_box_per_row == 'pr2') { ?>
<?php $class_fbox = 'col-sm-6 col-xs-12'; ?>
<?php } elseif($marketshop_feature_box_per_row == 'pr3') { ?>
<?php $class_fbox = 'col-sm-4 col-xs-12'; ?>
<?php } elseif($marketshop_feature_box_per_row == 'pr4') { ?>
<?php $class_fbox = 'col-md-3 col-sm-6 col-xs-12'; ?>
<?php } ?>
<?php if(($marketshop_feature_box_homepage_only == 1) && (isset($ishome)) || ($marketshop_feature_box_homepage_only == 2)) { ?>
<?php if($marketshop_feature_box_show_header_footer == 1) {

if((isset($marketshop_feature_box1_title[$lang]) && $marketshop_feature_box1_title[$lang] != '') || (isset($marketshop_feature_box1_subtitle[$lang]) && $marketshop_feature_box1_subtitle[$lang] != '') || (isset($marketshop_feature_box2_title[$lang]) && $marketshop_feature_box2_title[$lang] != '') || (isset($marketshop_feature_box2_subtitle[$lang]) && $marketshop_feature_box2_subtitle[$lang] != '') || (isset($marketshop_feature_box3_title[$lang]) && $marketshop_feature_box3_title[$lang] != '') || (isset($marketshop_feature_box3_subtitle[$lang]) && $marketshop_feature_box3_subtitle[$lang] != '') || (isset($marketshop_feature_box4_title[$lang]) && $marketshop_feature_box4_title[$lang] != '') || (isset($marketshop_feature_box4_subtitle[$lang]) && $marketshop_feature_box4_subtitle[$lang] != '')) { ?>
<div class="container">
  <div class="custom-feature-box row">
    <?php if($marketshop_feature_box1_status == 1) { if((isset($marketshop_feature_box1_title[$lang]) && $marketshop_feature_box1_title[$lang] != '') || (isset($marketshop_feature_box1_subtitle[$lang]) && $marketshop_feature_box1_subtitle[$lang] != '')) { ?>
    <div class="<?php echo $class_fbox; ?>">
      <div class="feature-box fbox_1">
        <div class="title"><?php echo $marketshop_feature_box1_title[$lang]; ?></div>
        <p><?php echo $marketshop_feature_box1_subtitle[$lang]; ?></p>
      </div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php if($marketshop_feature_box2_status == 1) { if((isset($marketshop_feature_box2_title[$lang]) && $marketshop_feature_box2_title[$lang] != '') || (isset($marketshop_feature_box2_subtitle[$lang]) && $marketshop_feature_box2_subtitle[$lang] != '')) { ?>
    <div class="<?php echo $class_fbox; ?>">
      <div class="feature-box fbox_2">
        <div class="title"><?php echo $marketshop_feature_box2_title[$lang]; ?></div>
        <p><?php echo $marketshop_feature_box2_subtitle[$lang]; ?></p>
      </div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php if($marketshop_feature_box3_status == 1) { if((isset($marketshop_feature_box3_title[$lang]) && $marketshop_feature_box3_title[$lang] != '') || (isset($marketshop_feature_box3_subtitle[$lang]) && $marketshop_feature_box3_subtitle[$lang] != '')) { ?>
    <div class="<?php echo $class_fbox; ?>">
      <div class="feature-box fbox_3">
        <div class="title"><?php echo $marketshop_feature_box3_title[$lang]; ?></div>
        <p><?php echo $marketshop_feature_box3_subtitle[$lang]; ?></p>
      </div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php if($marketshop_feature_box4_status == 1) { if((isset($marketshop_feature_box4_title[$lang]) && $marketshop_feature_box4_title[$lang] != '') || (isset($marketshop_feature_box4_subtitle[$lang]) && $marketshop_feature_box4_subtitle[$lang] != '')) { ?>
    <div class="<?php echo $class_fbox; ?>">
      <div class="feature-box fbox_4">
        <div class="title"><?php echo $marketshop_feature_box4_title[$lang]; ?></div>
        <p><?php echo $marketshop_feature_box4_subtitle[$lang]; ?></p>
      </div>
    </div>
    <?php } ?>
    <?php } ?>
  </div>
</div>
<?php } ?>
<?php } ?>
<?php } ?>