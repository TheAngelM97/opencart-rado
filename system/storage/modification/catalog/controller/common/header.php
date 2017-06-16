<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('extension/extension');

		$data['analytics'] = array();

		$analytics = $this->model_extension_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get($analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
$data['hd_ctf'] = $this->load->controller('common/hd_ctf');
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');
if (!isset($this->request->get['route']) || $this->request->get['route'] == 'common/home'){
$data['ishome']=1;
}
$data['route'] = isset($this->request->get['route']) ? $this->request->get['route'] : 'common/home';

			$data['marketshop_custom_column_status'] = $this->config->get('marketshop_custom_column_status');
			$data['marketshop_video_box_status'] = $this->config->get('marketshop_video_box_status');
			$data['marketshop_facebook_block_status'] = $this->config->get('marketshop_facebook_block_status');
			$data['marketshop_twitter_block_status'] = $this->config->get('marketshop_twitter_block_status');
			$data['marketshop_skin'] = $this->config->get('marketshop_skin');
			$data['marketshop_status'] = $this->config->get('marketshop_status');
			$data['config_language_id'] = $this->config->get('config_language_id');
			$data['marketshop_search_auto_complete'] = $this->config->get('marketshop_search_auto_complete');
			$data['marketshop_title_font'] = $this->config->get('marketshop_title_font');
			$data['marketshop_body_font'] = $this->config->get('marketshop_body_font');
			$data['marketshop_top_bar_font'] = $this->config->get('marketshop_top_bar_font');
			$data['marketshop_secondary_titles_font'] = $this->config->get('marketshop_secondary_titles_font');
			$data['marketshop_footer_titles_font'] = $this->config->get('marketshop_footer_titles_font');
			$data['marketshop_header_style'] = $this->config->get('marketshop_header_style');
			$data['marketshop_main_menu_font'] = $this->config->get('marketshop_main_menu_font');
			
			$data['marketshop_background_color'] = $this->config->get('marketshop_background_color');
			$data['config_ssl'] = $this->config->get('config_ssl');
			$data['config_url'] = $this->config->get('config_url');
			$data['marketshop_custom_image'] = $this->config->get('marketshop_custom_image');
			$data['marketshop_custom_image_position'] = $this->config->get('marketshop_custom_image_position');
			$data['marketshop_custom_image_repeat'] = $this->config->get('marketshop_custom_image_repeat');
			$data['marketshop_custom_image_attachment'] = $this->config->get('marketshop_custom_image_attachment');
			$data['marketshop_pattern_overlay'] = $this->config->get('marketshop_pattern_overlay');
			$data['marketshop_general_links_color'] = $this->config->get('marketshop_general_links_color');
			$data['marketshop_body_text_color'] = $this->config->get('marketshop_body_text_color');
			$data['marketshop_general_links_hover_color'] = $this->config->get('marketshop_general_links_hover_color');
			$data['marketshop_heading_color'] = $this->config->get('marketshop_heading_color');
			$data['marketshop_secondary_heading_color'] = $this->config->get('marketshop_heading_color');
			$data['marketshop_body_text_color'] = $this->config->get('marketshop_secondary_heading_color');
			$data['marketshop_secondary_heading_border_color'] = $this->config->get('marketshop_secondary_heading_border_color');
			$data['marketshop_secondary_heading_border_hl_color'] = $this->config->get('marketshop_secondary_heading_border_hl_color');
			$data['marketshop_layout_border_radius'] = $this->config->get('marketshop_layout_border_radius');
			$data['marketshop_layout_top_margin'] = $this->config->get('marketshop_layout_top_margin');
			$data['marketshop_header_margin'] = $this->config->get('marketshop_header_margin');
			$data['marketshop_logo_margin'] = $this->config->get('marketshop_logo_margin');
			$data['marketshop_top_bar_bg_color'] = $this->config->get('marketshop_top_bar_bg_color');
			$data['marketshop_top_bar_link_color'] = $this->config->get('marketshop_top_bar_link_color');
			$data['marketshop_top_bar_link_separator_color'] = $this->config->get('marketshop_top_bar_link_separator_color');
			$data['marketshop_top_bar_link_hover_color'] = $this->config->get('marketshop_top_bar_link_hover_color');
			$data['marketshop_top_bar_tele_email_color'] = $this->config->get('marketshop_top_bar_tele_email_color');
			$data['marketshop_top_bar_link_color'] = $this->config->get('marketshop_top_bar_link_color');
			$data['marketshop_top_bar_link_separator_style'] = $this->config->get('marketshop_top_bar_link_separator_style');
			$data['marketshop_top_bar_sub_link_color'] = $this->config->get('marketshop_top_bar_sub_link_color');
			$data['marketshop_top_bar_sub_link_hover_color'] = $this->config->get('marketshop_top_bar_sub_link_hover_color');
			$data['marketshop_sub_menu_top_border_color'] = $this->config->get('marketshop_sub_menu_top_border_color');
			$data['marketshop_header_bg_color'] = $this->config->get('marketshop_header_bg_color');
			$data['marketshop_mini_cart_icon_color'] = $this->config->get('marketshop_mini_cart_icon_color');
			$data['marketshop_mini_cart_icon_color'] = $this->config->get('marketshop_mini_cart_icon_color');
			$data['marketshop_mini_cart_link_color'] = $this->config->get('marketshop_mini_cart_link_color');
			$data['marketshop_mini_cart_active_link_color'] = $this->config->get('marketshop_mini_cart_active_link_color');
			$data['marketshop_search_bar_background_color'] = $this->config->get('marketshop_search_bar_background_color');
			$data['marketshop_search_bar_border_color'] = $this->config->get('marketshop_search_bar_border_color');
			$data['marketshop_search_bar_text_color'] = $this->config->get('marketshop_search_bar_text_color');
			$data['marketshop_search_bar_border_hover_color'] = $this->config->get('marketshop_search_bar_border_hover_color');
			$data['marketshop_search_bar_icon_color'] = $this->config->get('marketshop_search_bar_icon_color');
			$data['marketshop_search_bar_icon_bg_color'] = $this->config->get('marketshop_search_bar_icon_bg_color');
			$data['marketshop_main_menu_align'] = $this->config->get('marketshop_main_menu_align');
			$data['marketshop_menu_bg_color'] = $this->config->get('marketshop_menu_bg_color');
			$data['marketshop_menu_bg_color_status'] = $this->config->get('marketshop_menu_bg_color_status');
			$data['marketshop_home_page_link_icon_color'] = $this->config->get('marketshop_home_page_link_icon_color');
			$data['marketshop_home_page_link_icon_color_hover'] = $this->config->get('marketshop_home_page_link_icon_color_hover');
			$data['marketshop_myaccount_section_background_color'] = $this->config->get('marketshop_myaccount_section_background_color');
			$data['marketshop_myaccount_section_background_color_hover'] = $this->config->get('marketshop_myaccount_section_background_color_hover');
			$data['marketshop_myaccount_section_link_color'] = $this->config->get('marketshop_myaccount_section_link_color');
			$data['marketshop_myaccount_section_link_color_hover'] = $this->config->get('marketshop_myaccount_section_link_color_hover');
			$data['marketshop_custom_link_right_background_color'] = $this->config->get('marketshop_custom_link_right_background_color');
			$data['marketshop_custom_link_right_background_color_hover'] = $this->config->get('marketshop_custom_link_right_background_color_hover');
			$data['marketshop_custom_link_right_link_color'] = $this->config->get('marketshop_custom_link_right_link_color');
			$data['marketshop_custom_link_right_link_color_hover'] = $this->config->get('marketshop_custom_link_right_link_color_hover');
			$data['marketshop_menu_link_color'] = $this->config->get('marketshop_menu_link_color');
			$data['marketshop_menu_link_hover_color'] = $this->config->get('marketshop_menu_link_hover_color');
			$data['marketshop_menu_link_hover_bg_color'] = $this->config->get('marketshop_menu_link_hover_bg_color');
			$data['marketshop_menu_link_separator_size'] = $this->config->get('marketshop_menu_link_separator_size');
			$data['marketshop_menu_link_separator_style'] = $this->config->get('marketshop_menu_link_separator_style');
			$data['marketshop_menu_link_separator_color'] = $this->config->get('marketshop_menu_link_separator_color');
			$data['marketshop_menu_link_separator_status'] = $this->config->get('marketshop_menu_link_separator_status');
			$data['marketshop_menu_link_border_top_size'] = $this->config->get('marketshop_menu_link_border_top_size');
			$data['marketshop_menu_link_border_top_style'] = $this->config->get('marketshop_menu_link_border_top_style');
			$data['marketshop_menu_link_border_top_color'] = $this->config->get('marketshop_menu_link_border_top_color');
			$data['marketshop_menu_link_border_top_status'] = $this->config->get('marketshop_menu_link_border_top_status');
			$data['marketshop_menu_link_border_bottom_size'] = $this->config->get('marketshop_menu_link_border_bottom_size');
			$data['marketshop_menu_link_border_bottom_style'] = $this->config->get('marketshop_menu_link_border_bottom_style');
			$data['marketshop_menu_link_border_bottom_color'] = $this->config->get('marketshop_menu_link_border_bottom_color');
			$data['marketshop_menu_link_border_bottom_status'] = $this->config->get('marketshop_menu_link_border_bottom_status');
			$data['marketshop_sub_menu_background_color'] = $this->config->get('marketshop_sub_menu_background_color');
			$data['marketshop_sub_menu_heading_text_color'] = $this->config->get('marketshop_sub_menu_heading_text_color');
			$data['marketshop_sub_menu_heading_text_separator_color'] = $this->config->get('marketshop_sub_menu_heading_text_separator_color');
			$data['marketshop_sub_menu_heading_text_separator_style'] = $this->config->get('marketshop_sub_menu_heading_text_separator_style');
			$data['marketshop_sub_menu_link_color'] = $this->config->get('marketshop_sub_menu_link_color');
			$data['marketshop_sub_menu_link_hover_color'] = $this->config->get('marketshop_sub_menu_link_hover_color');
			$data['marketshop_sub_menu_link_separator_color'] = $this->config->get('marketshop_sub_menu_link_separator_color');
			$data['marketshop_sub_menu_link_separator_style'] = $this->config->get('marketshop_sub_menu_link_separator_style');
			$data['marketshop_home_page_link_background_color'] = $this->config->get('marketshop_home_page_link_background_color');
			$data['marketshop_home_page_link_background_color_hover'] = $this->config->get('marketshop_home_page_link_background_color_hover');
			$data['marketshop_home_page_link_link_color'] = $this->config->get('marketshop_home_page_link_link_color');
			$data['marketshop_home_page_link_link_color_hover'] = $this->config->get('marketshop_home_page_link_link_color_hover');
			$data['marketshop_categories_section_background_color'] = $this->config->get('marketshop_categories_section_background_color');
			$data['marketshop_categories_section_background_color_hover'] = $this->config->get('marketshop_categories_section_background_color_hover');
			$data['marketshop_categories_section_link_color'] = $this->config->get('marketshop_categories_section_link_color');
			$data['marketshop_categories_section_link_color_hover'] = $this->config->get('marketshop_categories_section_link_color_hover');
			$data['marketshop_brands_section_background_color'] = $this->config->get('marketshop_brands_section_background_color');
			$data['marketshop_brands_section_background_color_hover'] = $this->config->get('marketshop_brands_section_background_color_hover');
			$data['marketshop_brands_section_link_color'] = $this->config->get('marketshop_brands_section_link_color');
			$data['marketshop_brands_section_link_color_hover'] = $this->config->get('marketshop_brands_section_link_color_hover');
			$data['marketshop_custom_link_section_background_color'] = $this->config->get('marketshop_custom_link_section_background_color');
			$data['marketshop_custom_link_section_background_color_hover'] = $this->config->get('marketshop_custom_link_section_background_color_hover');
			$data['marketshop_custom_link_section_link_color'] = $this->config->get('marketshop_custom_link_section_link_color');
			$data['marketshop_custom_link_section_link_color_hover'] = $this->config->get('marketshop_custom_link_section_link_color_hover');
			$data['marketshop_custom_block_section_background_color'] = $this->config->get('marketshop_custom_block_section_background_color');
			$data['marketshop_custom_block_section_background_color_hover'] = $this->config->get('marketshop_custom_block_section_background_color_hover');
			$data['marketshop_custom_block_section_link_color'] = $this->config->get('marketshop_custom_block_section_link_color');
			$data['marketshop_custom_block_section_link_color_hover'] = $this->config->get('marketshop_custom_block_section_link_color_hover');
			$data['marketshop_information_section_background_color'] = $this->config->get('marketshop_information_section_background_color');
			$data['marketshop_information_section_background_color_hover'] = $this->config->get('marketshop_information_section_background_color_hover');
			$data['marketshop_information_section_link_color'] = $this->config->get('marketshop_information_section_link_color');
			$data['marketshop_information_section_link_color_hover'] = $this->config->get('marketshop_information_section_link_color_hover');
			$data['marketshop_contact_section_background_color'] = $this->config->get('marketshop_contact_section_background_color');
			$data['marketshop_contact_section_background_color_hover'] = $this->config->get('marketshop_contact_section_background_color_hover');
			$data['marketshop_contact_section_link_color'] = $this->config->get('marketshop_contact_section_link_color');
			$data['marketshop_contact_section_link_color_hover'] = $this->config->get('marketshop_contact_section_link_color_hover');
			$data['marketshop_custom_block_bg_color'] = $this->config->get('marketshop_custom_block_bg_color');
			$data['marketshop_video_block_bg_color'] = $this->config->get('marketshop_video_block_bg_color');
			$data['marketshop_footer_bg_color'] = $this->config->get('marketshop_footer_bg_color');
			$data['marketshop_footer_titles_color'] = $this->config->get('marketshop_footer_titles_color');
			$data['marketshop_footer_text_color'] = $this->config->get('marketshop_footer_text_color');
			$data['marketshop_footer_link_color'] = $this->config->get('marketshop_footer_link_color');
			$data['marketshop_footer_link_hover_color'] = $this->config->get('marketshop_footer_link_hover_color');
			$data['marketshop_contact_icon_color'] = $this->config->get('marketshop_contact_icon_color');
			$data['marketshop_contact_icon_bg_color'] = $this->config->get('marketshop_contact_icon_bg_color');
			$data['marketshop_footer_second_bg_color'] = $this->config->get('marketshop_footer_second_bg_color');
			$data['marketshop_footer_second_text_color'] = $this->config->get('marketshop_footer_second_text_color');
			$data['marketshop_footer_second_link_color'] = $this->config->get('marketshop_footer_second_link_color');
			$data['marketshop_footer_second_link_hover_color'] = $this->config->get('marketshop_footer_second_link_hover_color');
			$data['marketshop_footer_second_separator_color'] = $this->config->get('marketshop_footer_second_separator_color');
			$data['marketshop_footer_second_separator_style'] = $this->config->get('marketshop_footer_second_separator_style');
			$data['marketshop_footer_second_fst_separator_status'] = $this->config->get('marketshop_footer_second_fst_separator_status');
			$data['marketshop_footer_second_2nd_separator_color'] = $this->config->get('marketshop_footer_second_2nd_separator_color');
			$data['marketshop_footer_second_2nd_separator_style'] = $this->config->get('marketshop_footer_second_2nd_separator_style');
			$data['marketshop_footer_second_2nd_separator_status'] = $this->config->get('marketshop_footer_second_2nd_separator_status');
			$data['marketshop_footer_second_2nd_separator_status'] = $this->config->get('marketshop_footer_second_2nd_separator_status');
			$data['marketshop_footer_backtotop_bg_color'] = $this->config->get('marketshop_footer_backtotop_bg_color');
			$data['marketshop_product_box_border_hover_color'] = $this->config->get('marketshop_product_box_border_hover_color');
			$data['marketshop_product_name_color'] = $this->config->get('marketshop_product_name_color');
			$data['marketshop_product_name_hover_color'] = $this->config->get('marketshop_product_name_hover_color');
			$data['marketshop_price_color'] = $this->config->get('marketshop_price_color');
			$data['marketshop_tax_price_color'] = $this->config->get('marketshop_tax_price_color');
			$data['marketshop_old_price_color'] = $this->config->get('marketshop_old_price_color');
			$data['marketshop_new_price_color'] = $this->config->get('marketshop_new_price_color');
			$data['marketshop_saving_percentage_bg_color'] = $this->config->get('marketshop_saving_percentage_bg_color');
			$data['marketshop_saving_percentage_text_color'] = $this->config->get('marketshop_saving_percentage_text_color');
			$data['marketshop_wishlist_compare_bg_color'] = $this->config->get('marketshop_wishlist_compare_bg_color');
			$data['marketshop_wishlist_compare_text_color'] = $this->config->get('marketshop_wishlist_compare_text_color');
			$data['marketshop_wishlist_compare_hover_bg_color'] = $this->config->get('marketshop_wishlist_compare_hover_bg_color');
			$data['marketshop_wishlist_compare_hover_text_color'] = $this->config->get('marketshop_wishlist_compare_hover_text_color');
			$data['marketshop_button_bg_color'] = $this->config->get('marketshop_button_bg_color');
			$data['marketshop_button_bg_hover_color'] = $this->config->get('marketshop_button_bg_hover_color');
			$data['marketshop_button_text_color'] = $this->config->get('marketshop_button_text_color');
			$data['marketshop_button_text_hover_color'] = $this->config->get('marketshop_button_text_hover_color');
			$data['marketshop_default_button_bg_color'] = $this->config->get('marketshop_default_button_bg_color');
			$data['marketshop_default_button_bg_hover_color'] = $this->config->get('marketshop_default_button_bg_hover_color');
			$data['marketshop_default_button_text_color'] = $this->config->get('marketshop_default_button_text_color');
			$data['marketshop_default_button_text_hover_color'] = $this->config->get('marketshop_default_button_text_hover_color');
			$data['marketshop_cart_button_bg_color'] = $this->config->get('marketshop_cart_button_bg_color');
			$data['marketshop_cart_button_bg_hover_color'] = $this->config->get('marketshop_cart_button_bg_hover_color');
			$data['marketshop_cart_button_text_color'] = $this->config->get('marketshop_cart_button_text_color');
			$data['marketshop_cart_button_text_hover_color'] = $this->config->get('marketshop_cart_button_text_hover_color');
			$data['marketshop_price_bg_color'] = $this->config->get('marketshop_price_bg_color');
			$data['marketshop_price_text_color'] = $this->config->get('marketshop_price_text_color');
			$data['marketshop_old_price_product_page_color'] = $this->config->get('marketshop_old_price_product_page_color');
			$data['marketshop_excl_button_bg_color'] = $this->config->get('marketshop_excl_button_bg_color');
			$data['marketshop_excl_button_bg_hover_color'] = $this->config->get('marketshop_excl_button_bg_hover_color');
			$data['marketshop_excl_button_text_color'] = $this->config->get('marketshop_excl_button_text_color');
			$data['marketshop_excl_button_text_hover_color'] = $this->config->get('marketshop_excl_button_text_hover_color');
			$data['marketshop_feature_box1_border_color'] = $this->config->get('marketshop_feature_box1_border_color');
			$data['marketshop_feature_box1_bg_color'] = $this->config->get('marketshop_feature_box1_bg_color');
			$data['marketshop_feature_box1_title_color'] = $this->config->get('marketshop_feature_box1_title_color');
			$data['marketshop_feature_box1_subtitle_color'] = $this->config->get('marketshop_feature_box1_subtitle_color');
			$data['marketshop_feature_box2_border_color'] = $this->config->get('marketshop_feature_box2_border_color');
			$data['marketshop_feature_box2_bg_color'] = $this->config->get('marketshop_feature_box2_bg_color');
			$data['marketshop_feature_box2_title_color'] = $this->config->get('marketshop_feature_box2_title_color');
			$data['marketshop_feature_box2_subtitle_color'] = $this->config->get('marketshop_feature_box2_subtitle_color');
			$data['marketshop_feature_box3_border_color'] = $this->config->get('marketshop_feature_box3_border_color');
			$data['marketshop_feature_box3_bg_color'] = $this->config->get('marketshop_feature_box3_bg_color');
			$data['marketshop_feature_box3_title_color'] = $this->config->get('marketshop_feature_box3_title_color');
			$data['marketshop_feature_box3_subtitle_color'] = $this->config->get('marketshop_feature_box3_subtitle_color');
			$data['marketshop_feature_box4_border_color'] = $this->config->get('marketshop_feature_box4_border_color');
			$data['marketshop_feature_box4_bg_color'] = $this->config->get('marketshop_feature_box4_bg_color');
			$data['marketshop_feature_box4_title_color'] = $this->config->get('marketshop_feature_box4_title_color');
			$data['marketshop_feature_box4_subtitle_color'] = $this->config->get('marketshop_feature_box4_subtitle_color');
			$data['marketshop_feature_box_title_font_size'] = $this->config->get('marketshop_feature_box_title_font_size');
			$data['marketshop_feature_box_title_font_weight'] = $this->config->get('marketshop_feature_box_title_font_weight');
			$data['marketshop_feature_box_title_font_transform'] = $this->config->get('marketshop_feature_box_title_font_transform');
			$data['marketshop_feature_box_subtitle_font_size'] = $this->config->get('marketshop_feature_box_subtitle_font_size');
			$data['marketshop_feature_box_subtitle_font_weight'] = $this->config->get('marketshop_feature_box_subtitle_font_weight');
			$data['marketshop_feature_box_subtitle_font_transform'] = $this->config->get('marketshop_feature_box_subtitle_font_transform');
			$data['marketshop_title_font_size'] = $this->config->get('marketshop_title_font_size');
			$data['marketshop_title_font_weight'] = $this->config->get('marketshop_title_font_weight');
			$data['marketshop_title_font_uppercase'] = $this->config->get('marketshop_title_font_uppercase');
			$data['marketshop_main_menu_font_size'] = $this->config->get('marketshop_main_menu_font_size');
			$data['marketshop_main_menu_font_weight'] = $this->config->get('marketshop_main_menu_font_weight');
			$data['marketshop_main_menu_font_uppercase'] = $this->config->get('marketshop_main_menu_font_uppercase');
			$data['marketshop_top_bar_font_size'] = $this->config->get('marketshop_top_bar_font_size');
			$data['marketshop_top_bar_font_weight'] = $this->config->get('marketshop_top_bar_font_weight');
			$data['marketshop_top_bar_font_uppercase'] = $this->config->get('marketshop_top_bar_font_uppercase');
			$data['marketshop_secondary_titles_font_size'] = $this->config->get('marketshop_secondary_titles_font_size');
			$data['marketshop_secondary_titles_font_weight'] = $this->config->get('marketshop_secondary_titles_font_weight');
			$data['marketshop_secondary_titles_font_weight'] = $this->config->get('marketshop_secondary_titles_font_weight');
			$data['marketshop_secondary_titles_font_uppercase'] = $this->config->get('marketshop_secondary_titles_font_uppercase');
			$data['marketshop_footer_titles_font_size'] = $this->config->get('marketshop_footer_titles_font_size');
			$data['marketshop_footer_titles_font_weight'] = $this->config->get('marketshop_footer_titles_font_weight');
			$data['marketshop_footer_titles_font_uppercase'] = $this->config->get('marketshop_footer_titles_font_uppercase');
			$data['marketshop_custom_css'] = $this->config->get('marketshop_custom_css');
			
			$data['marketshop_layout_style'] = $this->config->get('marketshop_layout_style');
			$data['marketshop_custom_link1_top'] = $this->config->get('marketshop_custom_link1_top');
			$data['marketshop_custom_link1_top_title'] = $this->config->get('marketshop_custom_link1_top_title');
			$data['marketshop_custom_link1_top_url'] = $this->config->get('marketshop_custom_link1_top_url');
			$data['marketshop_target_link1_top'] = $this->config->get('marketshop_target_link1_top');
			$data['marketshop_custom_link2_top'] = $this->config->get('marketshop_custom_link2_top');
			$data['marketshop_custom_link2_top_title'] = $this->config->get('marketshop_custom_link2_top_title');
			$data['marketshop_custom_link2_top_url'] = $this->config->get('marketshop_custom_link2_top_url');
			$data['marketshop_target_link2_top'] = $this->config->get('marketshop_target_link2_top');
			$data['marketshop_custom_link3_top'] = $this->config->get('marketshop_custom_link3_top');
			$data['marketshop_custom_link3_top_title'] = $this->config->get('marketshop_custom_link3_top_title');
			$data['marketshop_custom_link3_top_url'] = $this->config->get('marketshop_custom_link3_top_url');
			$data['marketshop_target_link3_top'] = $this->config->get('marketshop_target_link3_top');
			$data['marketshop_custom_block_top_status'] = $this->config->get('marketshop_custom_block_top_status');
			$data['marketshop_custom_block_top_title'] = $this->config->get('marketshop_custom_block_top_title');
			$data['marketshop_custom_block_top_content'] = $this->config->get('marketshop_custom_block_top_content');
			$data['marketshop_custom_block2_top_status'] = $this->config->get('marketshop_custom_block2_top_status');
			$data['marketshop_custom_block2_top_title'] = $this->config->get('marketshop_custom_block2_top_title');
			$data['marketshop_custom_block2_top_content'] = $this->config->get('marketshop_custom_block2_top_content');
			$data['marketshop_wishlist_top_link'] = $this->config->get('marketshop_wishlist_top_link');
			$data['marketshop_checkout_top_link'] = $this->config->get('marketshop_checkout_top_link');
			$data['marketshop_top_bar_contact_status'] = $this->config->get('marketshop_top_bar_contact_status');
			$data['marketshop_top_bar_contact'] = $this->config->get('marketshop_top_bar_contact');
			$data['marketshop_top_bar_email_status'] = $this->config->get('marketshop_top_bar_email_status');
			$data['marketshop_top_bar_email'] = $this->config->get('marketshop_top_bar_email');
			
			$data['marketshop_main_menu_style'] = $this->config->get('marketshop_main_menu_style');
			$data['marketshop_mobile_menu_title'] = $this->config->get('marketshop_mobile_menu_title');
			
			$data['marketshop_home_page_link_icon'] = $this->config->get('marketshop_home_page_link_icon');
			$data['marketshop_home_page_link'] = $this->config->get('marketshop_home_page_link');
			$data['marketshop_top_menu'] = $this->config->get('marketshop_top_menu');
			$data['marketshop_menu_categories_title'] = $this->config->get('marketshop_menu_categories_title');
			$data['marketshop_menu_brands'] = $this->config->get('marketshop_menu_brands');
			$data['marketshop_menu_brands_title'] = $this->config->get('marketshop_menu_brands_title');
			$data['marketshop_brands_display_style'] = $this->config->get('marketshop_brands_display_style');
			
			$data['marketshop_custom_link1'] = $this->config->get('marketshop_custom_link1');
			$data['marketshop_custom_link1_title'] = $this->config->get('marketshop_custom_link1_title');
			$data['marketshop_custom_link1_url'] = $this->config->get('marketshop_custom_link1_url');
			$data['marketshop_target_link1'] = $this->config->get('marketshop_target_link1');
			$data['marketshop_custom_link2'] = $this->config->get('marketshop_custom_link2');
			$data['marketshop_custom_link2_title'] = $this->config->get('marketshop_custom_link2_title');
			$data['marketshop_custom_link2_url'] = $this->config->get('marketshop_custom_link2_url');
			$data['marketshop_target_link2'] = $this->config->get('marketshop_target_link2');
			$data['marketshop_custom_link3'] = $this->config->get('marketshop_custom_link3');
			$data['marketshop_custom_link3_title'] = $this->config->get('marketshop_custom_link3_title');
			$data['marketshop_custom_link3_url'] = $this->config->get('marketshop_custom_link3_url');
			$data['marketshop_target_link3'] = $this->config->get('marketshop_target_link3');
			$data['marketshop_custom_link4'] = $this->config->get('marketshop_custom_link4');
			$data['marketshop_custom_link4_title'] = $this->config->get('marketshop_custom_link4_title');
			$data['marketshop_custom_link4_url'] = $this->config->get('marketshop_custom_link4_url');
			$data['marketshop_target_link4'] = $this->config->get('marketshop_target_link4');
			$data['marketshop_custom_link5'] = $this->config->get('marketshop_custom_link5');
			$data['marketshop_custom_link5_title'] = $this->config->get('marketshop_custom_link5_title');
			$data['marketshop_custom_link5_url'] = $this->config->get('marketshop_custom_link5_url');
			$data['marketshop_target_link5'] = $this->config->get('marketshop_target_link5');
			
			$data['marketshop_custom_block_status'] = $this->config->get('marketshop_custom_block_status');
			$data['marketshop_custom_block_title'] = $this->config->get('marketshop_custom_block_title');
			$data['marketshop_custom_block_content'] = $this->config->get('marketshop_custom_block_content');
			$data['marketshop_custom_block2_status'] = $this->config->get('marketshop_custom_block2_status');
			$data['marketshop_custom_block2_title'] = $this->config->get('marketshop_custom_block2_title');
			$data['marketshop_custom_block2_content'] = $this->config->get('marketshop_custom_block2_content');
			$data['marketshop_custom_block3_status'] = $this->config->get('marketshop_custom_block3_status');
			$data['marketshop_custom_block3_title'] = $this->config->get('marketshop_custom_block3_title');
			$data['marketshop_custom_block3_content'] = $this->config->get('marketshop_custom_block3_content');
			
			$data['marketshop_my_account'] = $this->config->get('marketshop_my_account');
			$data['marketshop_custom_link_right'] = $this->config->get('marketshop_custom_link_right');
			$data['marketshop_information_page'] = $this->config->get('marketshop_information_page');
			$data['marketshop_contact_us'] = $this->config->get('marketshop_contact_us');
			
			$data['marketshop_custom_link_right'] = $this->config->get('marketshop_custom_link_right');
			$data['marketshop_custom_link_right_title'] = $this->config->get('marketshop_custom_link_right_title');
			$data['marketshop_custom_link_right_url'] = $this->config->get('marketshop_custom_link_right_url');
			$data['marketshop_custom_link_right_target'] = $this->config->get('marketshop_custom_link_right_target');
			
			$data['marketshop_feature_box_per_row'] = $this->config->get('marketshop_feature_box_per_row');
			$data['marketshop_feature_box_homepage_only'] = $this->config->get('marketshop_feature_box_homepage_only');
			$data['marketshop_feature_box_show_header_footer'] = $this->config->get('marketshop_feature_box_show_header_footer');
			$data['marketshop_feature_box_per_row'] = $this->config->get('marketshop_feature_box_per_row');
			$data['marketshop_feature_box1_title'] = $this->config->get('marketshop_feature_box1_title');
			$data['marketshop_feature_box1_subtitle'] = $this->config->get('marketshop_feature_box1_subtitle');
			$data['marketshop_feature_box2_title'] = $this->config->get('marketshop_feature_box2_title');
			$data['marketshop_feature_box2_subtitle'] = $this->config->get('marketshop_feature_box2_subtitle');
			$data['marketshop_feature_box3_title'] = $this->config->get('marketshop_feature_box3_title');
			$data['marketshop_feature_box3_subtitle'] = $this->config->get('marketshop_feature_box3_subtitle');
			$data['marketshop_feature_box4_title'] = $this->config->get('marketshop_feature_box4_title');
			$data['marketshop_feature_box4_subtitle'] = $this->config->get('marketshop_feature_box4_subtitle');
			$data['marketshop_feature_box1_status'] = $this->config->get('marketshop_feature_box1_status');
			$data['marketshop_feature_box2_status'] = $this->config->get('marketshop_feature_box2_status');
			$data['marketshop_feature_box3_status'] = $this->config->get('marketshop_feature_box3_status');
			$data['marketshop_feature_box4_status'] = $this->config->get('marketshop_feature_box4_status');
		

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
$data['text_information'] = $this->language->get('text_information');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
$this->load->model('catalog/information');
		$data['informations'] = array();
		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
    	}

		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
$this->load->model('catalog/manufacturer');
		$this->load->model('tool/image');
		$results = $this->model_catalog_manufacturer->getManufacturers();
		foreach ($results as $result) {	
			if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}			
			$data['manufacturers'][] = array(
				'name' => $result['name'],
				'image' => $this->model_tool_image->resize($image, 60, 60),
				'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
			);
		}

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

$children_level2 = $this->model_catalog_category->getCategories($child['category_id']);
					$children_data_level2 = array();
					foreach ($children_level2 as $child_level2) {
							$data_level2 = array(
									'filter_category_id'  => $child_level2['category_id'],
									'filter_sub_category' => true
							);
							$product_total_level2 = '';
							//if ($this->config->get('config_product_count')) {
							//		$product_total_level2 = ' (' . $this->model_catalog_product->getTotalProducts($data_level2) . ')';
							//}

							$children_data_level2[] = array(
									'name'  =>  $child_level2['name'] . $product_total_level2,
									'href'  => $this->url->link('product/category', 'path=' . $category['category_id']. '_' . $child['category_id'] . '_' . $child_level2['category_id']),
									'id' => $category['category_id']. '_' . $child['category_id']. '_' . $child_level2['category_id']
							);
					}
					$children_data[] = array(
						'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
							'id' => $category['category_id']. '_' . $child['category_id'],
							'children_level2' => $children_data_level2,
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id']),
					'image'    => $category['image']
				);
			}
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} elseif (isset($this->request->get['information_id'])) {
				$class = '-' . $this->request->get['information_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		$custom_styles = array('custom-header.css');

		if (isset($this->request->get['route'])) {
			$route = explode('&', $this->request->get['route']);
			$route = $route[0];
			if ($route == 'common/home') {
				$custom_styles[] = 'custom-index.css';
			}
			if ($route == 'product/product') {
				$custom_styles[] = 'custom-product.css';
			}
		}
		else {
			$custom_styles[] = 'custom-index.css';
		}

		$data['custom_styles'] = $custom_styles;

		return $this->load->view('common/header', $data);
	}
}
