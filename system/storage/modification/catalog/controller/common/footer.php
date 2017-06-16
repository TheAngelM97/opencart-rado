<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['scripts'] = $this->document->getScripts('footer');

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

		$this->load->model('catalog/information');
if (!isset($this->request->get['route']) || $this->request->get['route'] == 'common/home'){
$data['ishome']=1;
}

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/account', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['marketshop_status'] = $this->config->get('marketshop_status');
		$data['config_language_id'] = $this->config->get('config_language_id');
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
			
		$data['marketshop_affiliate_status'] = $this->config->get('marketshop_affiliate_status');
		$data['marketshop_about_contact_details'] = $this->config->get('marketshop_about_contact_details');
		$data['marketshop_address'] = $this->config->get('marketshop_address');
		$data['marketshop_contact'] = $this->config->get('marketshop_contact');
		$data['marketshop_mobile'] = $this->config->get('marketshop_mobile');
		$data['marketshop_email'] = $this->config->get('marketshop_email');
		
		$data['marketshop_contact_status'] = $this->config->get('marketshop_contact_status');
		$data['marketshop_address_status'] = $this->config->get('marketshop_address_status');
		$data['marketshop_mobile_status'] = $this->config->get('marketshop_mobile_status');
		$data['marketshop_email_status'] = $this->config->get('marketshop_email_status');
		$data['marketshop_about_details'] = $this->config->get('marketshop_about_details');
		
		$data['marketshop_custom_payment_image_status'] = $this->config->get('marketshop_custom_payment_image_status');
		$data['config_ssl'] = $this->config->get('config_ssl');
		$data['marketshop_custom_payment_image'] = $this->config->get('marketshop_custom_payment_image');
		$data['marketshop_custom_payment_image_url'] = $this->config->get('marketshop_custom_payment_image_url');
		$data['marketshop_paypal'] = $this->config->get('marketshop_paypal');
		$data['marketshop_paypal_url'] = $this->config->get('marketshop_paypal_url');
		$data['marketshop_american'] = $this->config->get('marketshop_american');
		$data['marketshop_american_url'] = $this->config->get('marketshop_american_url');
		$data['marketshop_2checkout'] = $this->config->get('marketshop_2checkout');
		$data['marketshop_2checkout_url'] = $this->config->get('marketshop_2checkout_url');
		$data['marketshop_maestro'] = $this->config->get('marketshop_maestro');
		$data['marketshop_maestro_url'] = $this->config->get('marketshop_maestro_url');
		$data['marketshop_discover'] = $this->config->get('marketshop_discover');
		$data['marketshop_discover_url'] = $this->config->get('marketshop_discover_url');
		$data['marketshop_mastercard'] = $this->config->get('marketshop_mastercard');
		$data['marketshop_mastercard_url'] = $this->config->get('marketshop_mastercard_url');
		$data['marketshop_visa'] = $this->config->get('marketshop_visa');
		$data['marketshop_visa_url'] = $this->config->get('marketshop_visa_url');
		$data['marketshop_sagepay'] = $this->config->get('marketshop_sagepay');
		$data['marketshop_sagepay_url'] = $this->config->get('marketshop_sagepay_url');
		$data['marketshop_moneybookers'] = $this->config->get('marketshop_moneybookers');
		$data['marketshop_moneybookers_url'] = $this->config->get('marketshop_moneybookers_url');
		$data['marketshop_cirrus'] = $this->config->get('marketshop_cirrus');
		$data['marketshop_cirrus_url'] = $this->config->get('marketshop_cirrus_url');
		$data['marketshop_delta'] = $this->config->get('marketshop_delta');
		$data['marketshop_delta_url'] = $this->config->get('marketshop_delta_url');
		$data['marketshop_direct'] = $this->config->get('marketshop_direct');
		$data['marketshop_direct_url'] = $this->config->get('marketshop_direct_url');
		$data['marketshop_google'] = $this->config->get('marketshop_google');
		$data['marketshop_google_url'] = $this->config->get('marketshop_google_url');
		$data['marketshop_solo'] = $this->config->get('marketshop_solo');
		$data['marketshop_solo_url'] = $this->config->get('marketshop_solo_url');
		$data['marketshop_switch'] = $this->config->get('marketshop_switch');
		$data['marketshop_switch_url'] = $this->config->get('marketshop_switch_url');
		$data['marketshop_western_union'] = $this->config->get('marketshop_western_union');
		$data['marketshop_western_union_url'] = $this->config->get('marketshop_western_union_url');
		$data['marketshop_ebay'] = $this->config->get('marketshop_ebay');
		
		$data['marketshop_powered'] = $this->config->get('marketshop_powered');
		
		$data['marketshop_facebook_id'] = $this->config->get('marketshop_facebook_id');
		$data['marketshop_twitter_username'] = $this->config->get('marketshop_twitter_username');
		$data['marketshop_gplus_id'] = $this->config->get('marketshop_gplus_id');
		$data['marketshop_pint_id'] = $this->config->get('marketshop_pint_id');
		$data['marketshop_rss'] = $this->config->get('marketshop_rss');
		$data['marketshop_blogger'] = $this->config->get('marketshop_blogger');
		$data['marketshop_myspace'] = $this->config->get('marketshop_myspace');
		$data['marketshop_linkedin'] = $this->config->get('marketshop_linkedin');
		$data['marketshop_evernote'] = $this->config->get('marketshop_evernote');
		$data['marketshop_dopplr'] = $this->config->get('marketshop_dopplr');
		$data['marketshop_ember'] = $this->config->get('marketshop_ember');
		$data['marketshop_flickr'] = $this->config->get('marketshop_flickr');
		$data['marketshop_picasa_web'] = $this->config->get('marketshop_picasa_web');
		$data['marketshop_youtube'] = $this->config->get('marketshop_youtube');
		$data['marketshop_technorati'] = $this->config->get('marketshop_technorati');
		$data['marketshop_grooveshark'] = $this->config->get('marketshop_grooveshark');
		$data['marketshop_vimeo'] = $this->config->get('marketshop_vimeo');
		$data['marketshop_sharethis'] = $this->config->get('marketshop_sharethis');
		$data['marketshop_yahoobuzz'] = $this->config->get('marketshop_yahoobuzz');
		$data['marketshop_viddler'] = $this->config->get('marketshop_viddler');
		$data['marketshop_skype'] = $this->config->get('marketshop_skype');
		$data['marketshop_google_googletalk'] = $this->config->get('marketshop_google_googletalk');
		$data['marketshop_digg'] = $this->config->get('marketshop_digg');
		$data['marketshop_reddit'] = $this->config->get('marketshop_reddit');
		$data['marketshop_delicious'] = $this->config->get('marketshop_delicious');
		$data['marketshop_stumbleupon'] = $this->config->get('marketshop_stumbleupon');
		$data['marketshop_friendfeed'] = $this->config->get('marketshop_friendfeed');
		$data['marketshop_tumblr'] = $this->config->get('marketshop_tumblr');
		$data['marketshop_yelp'] = $this->config->get('marketshop_yelp');
		$data['marketshop_posterous'] = $this->config->get('marketshop_posterous');
		$data['marketshop_bebo'] = $this->config->get('marketshop_bebo');
		$data['marketshop_virb'] = $this->config->get('marketshop_virb');
		$data['marketshop_last_fm'] = $this->config->get('marketshop_last_fm');
		$data['marketshop_pandora'] = $this->config->get('marketshop_pandora');
		$data['marketshop_mixx'] = $this->config->get('marketshop_mixx');
		$data['marketshop_newsvine'] = $this->config->get('marketshop_newsvine');
		$data['marketshop_openid'] = $this->config->get('marketshop_openid');
		$data['marketshop_readernaut'] = $this->config->get('marketshop_readernaut');
		$data['marketshop_xing_me'] = $this->config->get('marketshop_xing_me');
		$data['marketshop_instagram'] = $this->config->get('marketshop_instagram');
		$data['marketshop_spotify'] = $this->config->get('marketshop_spotify');
		$data['marketshop_forrst'] = $this->config->get('marketshop_forrst');
		$data['marketshop_viadeo'] = $this->config->get('marketshop_viadeo');
		$data['marketshop_vk_com'] = $this->config->get('marketshop_vk_com');
		
		$data['marketshop_custom_column_footer_status'] = $this->config->get('marketshop_custom_column_footer_status');
		$data['marketshop_custom_column_footer'] = $this->config->get('marketshop_custom_column_footer');
		$data['marketshop_back_to_top'] = $this->config->get('marketshop_back_to_top');
		$data['marketshop_facebook_block_status'] = $this->config->get('marketshop_facebook_block_status');
		$data['marketshop_facebook_box_align'] = $this->config->get('marketshop_facebook_box_align');
		$data['marketshop_facebook_block_sort_order'] = $this->config->get('marketshop_facebook_block_sort_order');
		$data['marketshop_facebook_id_box'] = $this->config->get('marketshop_facebook_id_box');
		$data['marketshop_twitter_block_status'] = $this->config->get('marketshop_twitter_block_status');
		$data['marketshop_twitter_box_align'] = $this->config->get('marketshop_twitter_box_align');
		$data['marketshop_twitter_block_sort_order'] = $this->config->get('marketshop_twitter_block_sort_order');
		$data['twitter_username'] = $this->config->get('twitter_username');
		$data['marketshop_widget_id'] = $this->config->get('marketshop_widget_id');
		$data['marketshop_video_box_status'] = $this->config->get('marketshop_video_box_status');
		$data['marketshop_video_box_align'] = $this->config->get('marketshop_video_box_align');
		$data['marketshop_video_box_sort_order'] = $this->config->get('marketshop_video_box_sort_order');
		$data['marketshop_video_box_content'] = $this->config->get('marketshop_video_box_content');
		$data['marketshop_custom_column_status'] = $this->config->get('marketshop_custom_column_status');
		$data['marketshop_custom_column'] = $this->config->get('marketshop_custom_column');
		$data['marketshop_custom_side_block_align'] = $this->config->get('marketshop_custom_side_block_align');
		$data['marketshop_custom_side_block_sort_order'] = $this->config->get('marketshop_custom_side_block_sort_order');
		

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		return $this->load->view('common/footer', $data);
	}
}
