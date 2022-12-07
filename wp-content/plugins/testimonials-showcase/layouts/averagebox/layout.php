<?php


class tt_average_box {

	//make it public so it can be accessed by cmshowcase constructor
	public $layout_id = 'averagebox'; //should be same name as folder
	public $layout_name = 'Average Rating Box';
	public $settings;
	public $options;
	public $enqueue_files;
	public $shortcode_check; // js function to run for preview to work properly
	public $custom_css;
	public $custom_js;
	public $footer_content;

	function __construct($id = ''){

		$this->showcase_id = $id;

		//custom css
		//we define it here, so we empty it after first time it's used
		$advanced_section = $this->showcase_id.'_advanced_settings';

		$this->custom_css = cmshowcase_get_option( 'custom_css', $advanced_section,  '' );
		$this->custom_js = cmshowcase_get_option( 'custom_js', $advanced_section,  '' );

		//check if the advanced rich snippets options need to display. Otherwise we will unset the array options further down
		$rich_snippets_section = $this->showcase_id.'_rich_snippets';
		$adv_rich_snippets = cmshowcase_get_boolean(cmshowcase_get_option( 'adv_rich_snippets', $rich_snippets_section,  'off' ));

		$scale = cmshowcase_get_option( 'rating_scale', 'ttshowcase_basic_settings', '5' );


		//Options for the Generator
		$options = array(

			'sep01' => array(
					'type' => 'seperator'
			),

			'style_info' => array(
				'label' => __('Style','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'sep02' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'theme' => array(
					'label' => __('Theme','ttshowcase'),
					'description' => __('Aspect of Box','ttshowcase'),
					'type' => 'select',
					'default' => 'box',
					'options' => array(
						'simple_box' => __('Simple Box','ttshowcase'),
						'big_star_box' => __('Big Stars Box','ttshowcase'),
						'none' => __('None','ttshowcase')
						)
			),

			'sep03' => array(
					'type' => 'seperator'
			),

			'visual_info' => array(
				'label' => __('What do display','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'sep04' => array(
					'label' => '',
					'type' => 'seperator'
			),


			'stars' => array(
				'label' => __('Show Stars','ttshowcase'),
				'description' => __('Display Star Icons','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'on',
				'value' => 'on'
				),

			'half_stars' => array(
				'label' => __('Use Half Stars','ttshowcase'),
				'description' => __('Display Half Stars','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'on',
				'value' => 'on'
				),

			'stars_label' => array(
				'label' => __('Stars Label','ttshowcase'),
				'description' => __('Text to display before the Star Icons','ttshowcase'),
				'type' => 'text',
				'default' => 'Rating of ',
				'size' => 'medium'
				),

			'average' => array(
				'label' => __('Show Average','ttshowcase'),
				'description' => __('Display average value of ratings','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'on',
				'value' => 'on'
				),

			'average_label' => array(
				'label' => __('Average Label','ttshowcase'),
				'description' => __('Text to display before the Average','ttshowcase'),
				'type' => 'text',
				'default' => ' Average of ',
				'size' => 'medium'
				),

			'total' => array(
				'label' => __('Show Total Ratings','ttshowcase'),
				'description' => __('Display number of total ratings','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'on',
				'value' => 'on'
				),

			'total_label' => array(
				'label' => __('Total Label','ttshowcase'),
				'description' => __('Text to display before the Total Ratings value','ttshowcase'),
				'type' => 'text',
				'default' => ' on a total of ',
				'size' => 'medium'
				),

			'total_label_after' => array(
				'label' => __('Total Label After','ttshowcase'),
				'description' => __('Text to display after the Total Ratings value','ttshowcase'),
				'type' => 'text',
				'default' => ' Ratings',
				'size' => 'medium'
				),

			'total_label_after_singular' => array(
				'label' => __('Total Label After (singular)','ttshowcase'),
				'description' => __('Text to display after the Total Ratings value when there\'s only one rating','ttshowcase'),
				'type' => 'text',
				'default' => ' Rating',
				'size' => 'medium'
				),

			'empty' => array(
				'label' => __('Text for empty average','ttshowcase'),
				'description' => __('Text to display when there are no ratings to calculate average','ttshowcase'),
				'type' => 'text',
				'default' => 'There are no ratings',
				'size' => 'medium'
				),

			'count_empty' => array(
				'label' => __('Consider Empty Ratings','ttshowcase'),
				'description' => __('Count ratings with 0 rating (rating not submitted)','ttshowcase'),
				'type' => 'checkbox',
				'default' => '',
				'value' => 'on'
				),

			'breakdown' => array(
				'label' => __('Display Ratings Breakdown','ttshowcase'),
				'description' => __('If enabled a graphic will display with the ratings breakdown','ttshowcase'),
				'type' => 'checkbox',
				'default' => '',
				'value' => 'on'
				),

			'percentage' => array(
				'label' => __('Breakdown %','ttshowcase'),
				'description' => __('Display percentage value together with ratings breakdown','ttshowcase'),
				'type' => 'checkbox',
				'default' => '',
				'value' => 'on'
				),


			'trigger_modal' => array(
				'label' => __('Trigger Modal Window','ttshowcase'),
				'description' => __('When enabled, when users click a star it will trigger a modal window with the content set in the settings page of the plugin, in the Average Box panel.','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'off',
				'value' => 'on'
			),



			'seperator_2' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'product_info' => array(
				'label' => __('Structured Data','ttshowcase'),
				'description' => 'Fields below will override defaults. If left blank, default values will be used.',
				'type' => 'html_bold'
				),


			'seperator_3' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'richsnippets' => array(
					'label' => __('Include Structured Data','ttshowcase'),
					'description' => __('Keep the default settings or override them for this specific shortcode','ttshowcase'),
					'type' => 'select',
					'default' => '',
					'options' => array(
						'' => __('Default Settings','ttshowcase'),
						'false' => __('Do not include structured data','ttshowcase'),
						'true' => __('Include structured data','ttshowcase')
						)
			),

			'schema' => array(
				'label' => __('Schema to use','ttshowcase'),
				'description' => __('If you wish to use another type of schema, insert the URL here.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'countmeta' => array(
					'label' => __('Property to use','ttshowcase'),
					'description' => __('Which meta property to use: ratingCount when using this shortcode alone (default) or force to use the reviewCount, which needs to have a layout shortcode on the same page.','ttshowcase'),
					'type' => 'select',
					'default' => '',
					'options' => array(
						'' => __('ratingCount (Default)','ttshowcase'),
						'reviewCount' => __('reviewCount','ttshowcase'),
						)
			),



			'product' => array(
				'label' => __('Name','ttshowcase'),
				'description' => __('Name of "thing" being reviewed','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'url' => array(
				'label' => __('Website','ttshowcase'),
				'description' => __('Url to official Website','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'image_url' => array(
				'label' => __('Image URL','ttshowcase'),
				'description' => __('The URL of the product photo.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'description' => array(
				'label' => __('Product Description','ttshowcase'),
				'description' => __('Custom Product Description for Rich Snippets.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'sameas' => array(
				'label' => __('Same As URL(s)','ttshowcase'),
				'description' => __('URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website. You can use multiple URLs separated by a comma.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),
			'seperator_4' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'business_meta' => array(
				'label' => __('Business Only data','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'seperator_5' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'business_address' => array(
				'label' => __('Address','ttshowcase'),
				'description' => __('Physical address of the item.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),
			'business_telephone' => array(
				'label' => __('Telephone','ttshowcase'),
				'description' => __('Telephone number','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),

			'business_pricerange' => array(
				'label' => __('Price Range','ttshowcase'),
				'description' => __('The price range of the business, for example $$$.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),
			'business_email' => array(
				'label' => __('Email','ttshowcase'),
				'description' => __('Email address.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),
			'business_logo' => array(
				'label' => __('Logo URL','ttshowcase'),
				'description' => __('An associated logo.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),




			'seperator_6' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'product_offer' => array(
				'label' => __('Product Only - Offer Metadata','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'seperator_7' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'price' => array(
				'label' => __('Price','ttshowcase'),
				'description' => __('Number or text with the price for the product being reviewed.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),
			'price_currency' => array(
				'label' => __('Currency','ttshowcase'),
				'description' => __('The currency (in 3-letter ISO 4217 format) of the price','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),
			'price_valid' => array(
				'label' => __('Date','ttshowcase'),
				'description' => __('The date (in ISO 8601 date format) after which the price will no longer be available.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),
			'availability' => array(
					'label' => __('Availability','ttshowcase'),
					'description' => __('','ttshowcase'),
					'type' => 'select',
					'default' => '',
					'options' => array(
						'' => __('Empty (Default)','ttshowcase'),
						'inStock' => __('In Stock','ttshowcase'),
						'OutOfStock' => __('Out of Stock','ttshowcase'),
						'SoldOut'=> __('Sold Out','ttshowcase'),
						'PreOrder'=> __('Pre-order','ttshowcase'),
						'OnlineOnly'=> __('Online Only','ttshowcase'),
						'LimitedAvailability'=> __('Limited Availability','ttshowcase'),
						'InStoreOnly'=> __('In Store Only','ttshowcase'),
						'Discontinued'=> __('Discontinued','ttshowcase'),
						)
			),

		);


		if(!$adv_rich_snippets) {

				unset($options['availability']);
				unset($options['price_valid']);
				unset($options['price_currency']);
				unset($options['price']);
				unset($options['seperator_5']);
				unset($options['seperator_4']);
				unset($options['seperator_6']);
				unset($options['seperator_7']);
				unset($options['product_offer']);
				unset($options['url']);
				unset($options['image_url']);
				unset($options['description']);
				unset($options['countmeta']);
				unset($options['schema']);
				unset($options['product']);
				unset($options['description']);
				unset($options['business_meta']);
				unset($options['product']);
				unset($options['business_pricerange']);
				unset($options['business_email']);
				unset($options['business_logo']);
				unset($options['sameas']);
				unset($options['business_address']);
				unset($options['business_telephone']);

		}



		$this->options = $options;

		//Files to enqueue on the generator and when building the layout

		$enqueue = array(
			'css' => array(
				'tt-font-awesome' => array(
					'file' => '/resources/font-awesome/css/font-awesome.min.css'
					),
				'tt-global-styles' => array(
					'file' => '/resources/global.css'
					),
				'tt-averagebox-layout-style' => array(
					'file' => '/layouts/averagebox/styles.css'
					),
				),

			);

		$disable = cmshowcase_get_boolean(cmshowcase_get_option( 'disable_fontawesome', 'ttshowcase_advanced_settings', 'off' ));
		if($disable){
			unset($enqueue['css']['tt-font-awesome']);
		}

		$this->enqueue_files = $enqueue;

	}

	public function build_layout( $query = array() , $options = array(), $preview = false ) {

		/*if(!$query->have_posts()) {
			wp_reset_postdata();
			$html = isset($options['empty']) && $options['empty'] != '' ? $options['empty'] : 'Ratings empty';
			return "<!-- Empty TShowcase Container -->".$html;

		}*/

		//let's process the options to look for placeholders
		$options = ttshowcase_process_options($options);

		//enqueue necessary files
		cmshowcase_enqueue_layout_scripts($this->enqueue_files);

		//using counter to set the wrapper div
		global $tt_showcase_counter;
		$wrap = '#'.$this->showcase_id.'_'.$tt_showcase_counter;

		$custom_css = $this->custom_css;
		$custom_js = $this->custom_js;

		//If we use options
		/*

		$section = $this->showcase_id.'_'.$this->layout_id;
		$read_more_label = cmshowcase_get_option( 'read_more_label', $section, 'Continue Reading' );

		*/

		$scale = cmshowcase_get_option( 'rating_scale', 'ttshowcase_basic_settings', '5' );

		//custom options
		$opt = get_option( 'ttshowcase_averagebox_settings' );

		$rating_highest = $scale;
		$rating_total = 0;
		$review_count = 0;

		//trigger modal
		$trigger_modal = isset($options['trigger_modal']) && $options['trigger_modal'] == 'on' ? true : false;


		//count empty ratings of not
		$count_empty = isset($options['count_empty']) && $options['count_empty'] == 'on' ? true : false;

		//rating breakdown
		$numrat = intval($scale);
		$ratingbd = array();
		$x = 0;
		while($numrat >= $x) {
		    $ratingbd[$numrat] = 0;
		    $numrat--;
		}
		array_reverse ($ratingbd);
		//error_log(print_r($ratingbd));




		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {

				$query->the_post();
				$post_id = get_the_ID();



				$rating = get_post_meta( $post_id, '_aditional_info_rating', true );

				//rating break down increment
				$ratingbd[$rating] = isset($ratingbd[$rating]) ? $ratingbd[$rating] +1 : 1;

				//if ignore empty = true
				if($count_empty) {

					$rating_total = $rating_total + $rating;
					$review_count++;

				} else {

					//Consider rating only if it's bigger than 0. Meaning, not ignore.
					if($rating!=0) {
						$rating_total = $rating_total + $rating;
						$review_count++;
					}

				}

			} //end while

		} //end if query have posts




		//building the layout itself with the info gathered

		$html = '';

		//get box style
		$theme_class = isset($options['theme']) && $options['theme'] != 'none' ? 'tt_'.$options['theme'] : '';


		$rsmeta = '';
		$rsmetavalues = '';
		if($review_count!=0) {
			$average = round($rating_total/$review_count,2);
		}
		//if average is 0, no need to add structured data
		else {
			$average = 0;
			$snippet_on = 'false';
		}



		//use half stars or not
		$use_half = isset($options['half_stars']) && $options['half_stars'] == 'on' ? true : false;

		if(!$use_half) {
			$rounded = round($average);
			$half = 0;
		}

		if($use_half) {

			$rounded = intval($average);
			$decimal = $average-$rounded;
			$half = 0;
			if($decimal >= 0.25 && $decimal <= 0.74) {
			$half = 1;
			}
			if($decimal >=0.75) {
			$rounded = $rounded+1;
			}

		}

		//use empty stars or not
		$empty_stars = cmshowcase_get_boolean(cmshowcase_get_option( 'empty_stars', 'ttshowcase_basic_settings', 'off' ));
		$emptys = 0;
		if($empty_stars) {

				$maxstarts = intval($scale);

				$emptys = $maxstarts  - round($average);

				//in case we use half stars, make sure they don't add up to more than 5, due to the rounding
				if($use_half) {
					if(($emptys+$half+$rounded) > $maxstarts ) {
						$emptys = $emptys-1;
					}
				}
			}



		//rich snippet reviews needed variables
		$snippet_on = cmshowcase_get_option('shortcode_active','ttshowcase_rich_snippets','false');
		//shortcode override
		$snippet_on = isset($options['richsnippets']) && $options['richsnippets'] != '' ? $options['richsnippets'] : $snippet_on;
		$snippet_on = cmshowcase_get_boolean($snippet_on);

		if($snippet_on) {

			//schema
			$schema = cmshowcase_get_option('schema','ttshowcase_rich_snippets','http://schema.org/Product');
			//schema override
			$schema = isset($options['schema']) && $options['schema'] != '' ? cmshowcase_add_http($options['schema']) : $schema;
			$thistype = explode('/', $schema);
			$type = end($thistype);



			//we grab the product name
			$itemreviewed = cmshowcase_get_option( 'default_product', 'ttshowcase_rich_snippets', get_bloginfo() );
			$use_cat_as_prod = cmshowcase_get_option( 'categories_as_products', 'ttshowcase_rich_snippets', 'off' );
			$use_cat_as_prod = cmshowcase_get_boolean($use_cat_as_prod);

			//override
			$custom_product = isset($options['product']) ? $options['product'] : '';


			$tax_prod = false;

			if($custom_product!='') {
				$itemreviewed = $custom_product;
			}

			//if there isn't an override, then we check if the categories should be used as products

			else {

				if($use_cat_as_prod) {

					if(isset($options['taxonomy']) || isset($query->query_vars['ttshowcase_groups']) ) {

						if( isset($query->query_vars['ttshowcase_groups']) ) {
							$cat_array = explode(',',$query->query_vars['ttshowcase_groups']);
							$itemreviewed = get_taxonomy( 'taxonomy_name' );
						} else {
							$cat_array = explode(',',$options['taxonomy']);
							$term = get_term_by('slug',  $cat_array[0], 'ttshowcase_groups');
							$itemreviewed = $term->name;
						}


						$tax_prod = true;

					}

				}

			}



			//Count type
			$metaparameter = 'ratingCount';
			if(isset($options['countmeta']) && $options['countmeta'] != '' && $options['countmeta'] == 'reviewCount') {
				$metaparameter = 'reviewCount';
			}

			$properties = array(
				'@context'  => 'http://schema.org',
				'@type' 	=> $type,
				'name' => $itemreviewed,
				'aggregaterating' => array(
					'@type' => 'AggregateRating',
					$metaparameter => $review_count,
					'bestRating' => $scale,
					'ratingValue' => strval($average),
					'worstRating' => '0',
					),

			);




			//general
			$image = isset($options['image_url']) ? $options['image_url'] : (!$tax_prod ? cmshowcase_get_option( 'default_image', 'ttshowcase_rich_snippets', '' ) : '');
			$description = isset($options['description']) ? $options['description'] : (!$tax_prod ? cmshowcase_get_option( 'default_description', 'ttshowcase_rich_snippets', '' ) : '');
			$sameas = isset($options['sameas']) ? $options['sameas'] : (!$tax_prod ? cmshowcase_get_option( 'default_sameas', 'ttshowcase_rich_snippets', '' ) : '');
			$url = isset($options['url']) ? $options['url'] : (!$tax_prod ? cmshowcase_get_option( 'default_url', 'ttshowcase_rich_snippets', '' ) : '');


			if($image!=''){
				$properties['image'] = $image;
			}

			if($description!=''){
				$properties['description'] = $description;
			}

			if($sameas!=''){
				$samearray = str_replace('&#44;', ',', $sameas);
				$samearray = str_replace('#c#', ',', $samearray);
				$samearray = explode(',', $samearray);
				$samearray = str_replace('##', ':', $samearray);
				$samearray = array_map('trim', $samearray);
				$properties['sameAs'] = $samearray;
			}

			if($url!=''){
				$properties['url'] = $url;
			}

			//business
			if($type!='Product' && $type!='SoftwareApplication'){

				$tel = isset($options['business_telephone']) ? $options['business_telephone'] : (!$tax_prod ? cmshowcase_get_option( 'business_telephone', 'ttshowcase_rich_snippets', '' ) : '');
				$address = isset($options['business_address']) ? $options['business_address'] : (!$tax_prod ? cmshowcase_get_option( 'business_address', 'ttshowcase_rich_snippets', '' ) : '');
				$priceRange = isset($options['business_pricerange']) ? $options['business_pricerange'] : (!$tax_prod ? cmshowcase_get_option( 'business_pricerange', 'ttshowcase_rich_snippets', '' ) : '');
				$email = isset($options['business_email']) ? $options['business_email'] : (!$tax_prod ? cmshowcase_get_option( 'business_email', 'ttshowcase_rich_snippets', '' ) : '');
				$logo = isset($options['business_logo']) ? $options['business_logo'] : (!$tax_prod ? cmshowcase_get_option( 'business_logo', 'ttshowcase_rich_snippets', '' ) : '');

				if($tel!=''){
					$properties['telephone'] = $tel;
				}
				if($address!=''){
					$properties['address'] = $address;
				}
				if($priceRange!=''){
					$properties['priceRange'] = $priceRange;
				}

				if($email!=''){
					$properties['email'] = $email;
				}
				if($logo!=''){
					$properties['logo'] = $logo;
				}


			}

			//product
			if($type=='Product' || $type=='SoftwareApplication'){

				$price = isset($options['price']) ? $options['price'] : (!$tax_prod ? cmshowcase_get_option( 'product_price', 'ttshowcase_rich_snippets', '' ) : '');
				$currency = isset($options['price_currency']) ? $options['price_currency']  : (!$tax_prod ? cmshowcase_get_option( 'product_currency', 'ttshowcase_rich_snippets', '' ) : '');
				$date = isset($options['price_valid']) ? $options['price_valid'] : (!$tax_prod ? cmshowcase_get_option( 'product_date', 'ttshowcase_rich_snippets', '' ) : '');
				$availability = isset($options['availability']) ? $options['availability'] : (!$tax_prod ? cmshowcase_get_option( 'product_availability', 'ttshowcase_rich_snippets', '' ) : '');

				if($price!='' && $availability != '' && $currency != ''){
					$properties['offers'] = array(
						'@type' => 'Offer',
						'availability' => $availability,
						'price' => $price,
						'priceCurrency' => $currency
						);
					if($date!=''){
						$properties['offers']['priceValidUntil'] = $date;
					}
				}
			}



			$properties = str_replace('##',':',$properties);
			$properties = str_replace('#c#',',',$properties);
			$html .= '<script type="application/ld+json">'.json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'</script>';

		}

		if($trigger_modal){
			$theme_class .= ' tt_trigger_modal';

			//add necessary files
			wp_deregister_style( 'tt-colorbox-shortcode' );
			wp_register_style( 'tt-colorbox-shortcode', plugins_url( '/resources/colorbox/colorbox.css',dirname( dirname(__FILE__))) , array() , false, 'all');
			wp_enqueue_style( 'tt-colorbox-shortcode' );
			wp_deregister_script( 'tt-colorbox-script' );
			wp_register_script( 'tt-colorbox-script', plugins_url( '/resources/colorbox/jquery.colorbox.js',dirname(dirname( __FILE__))) , array('jquery') , false, false);
			wp_enqueue_script( 'tt-colorbox-script' );
			wp_deregister_script( 'tt-colorbox' );
			wp_register_script( 'tt-colorbox', plugins_url( 'colorbox.js',__FILE__) , array('jquery','tt-colorbox-script') , false, false);
			wp_enqueue_script( 'tt-colorbox' );

			$closetext = isset( $opt['modal_close'] ) && $opt['modal_close'] !== '' ? $opt['modal_close'] : __('close','ttshowcase');

			$args = array('close' => $closetext );

			wp_localize_script( 'tt-colorbox', 'ttparam', $args );


		}

		$html .= '<div class="tt_average_rating_box '.$theme_class.'" '.$rsmeta.' >';
		//end snippet wrap first block

		$html .= $rsmetavalues;

		if($review_count==0) {

			$empty_msg = isset($options['empty']) && $options['empty'] != '' ? $options['empty'] : 'Ratings empty';
			$emptycontent = apply_filters('ttshowcase_empty_average_message',$empty_msg);

			$opt = get_option( 'ttshowcase_averagebox_settings' );
			if ( $opt && isset( $opt['custom_empty'] ) && '' !== $opt['custom_empty'] ) {
				$html .= do_shortcode( $opt['custom_empty'] );
			} else {
				$html .= $emptycontent;
			}



		}

		else {

			$stars_on = isset($options['stars']) && $options['stars'] == 'on' ? true : false;
			if($stars_on) {

				$val = 5;

				$stars = '<span class="tt_rating-star" data-value="%s"><i class="fa fa-star"></i></span>';
				$halfstar = '<span class="tt_rating-star" data-value="%s"><i class="fa fa-star-half-o"></i></span>';
				$emptystar = '<span class="tt_rating-star" data-value="%s"><i class="fa fa-star-o"></i></span>';

				$rstars = '<span class="tt_rating_box_stars">';

				$i = 1;
				while ($i <= $emptys) {
					$rstars .= sprintf($emptystar,$val);
					$val--;
					$i++;
				}

				$i = 1;
				while ($i <= $half) {
					$rstars .= sprintf($halfstar,$val);
					$val--;
					$i++;
				}



				$i = 1;
				while ($i <= $rounded) {
					$rstars .= sprintf($stars,$val);
					$val--;
					$i++;
				}

				$rstars .= '</span>';

				$stars_label = isset($options['stars_label']) && $options['stars_label'] != '' ? '<span class="tt_rating_box_star_label">'.$options['stars_label'].'</span>' : '';
				$html .= '<span class="tt_star_wrap">'.$stars_label.$rstars.'</span>';
			}

			$average_on = isset($options['average']) && $options['average'] == 'on' ? true : false;
			if($average_on) {
				$average_label = isset($options['average_label']) && $options['average_label'] != '' ? $options['average_label'] : '';
				$html .= '<span class="tt_rating_box_average">'.$average_label.'<span class="tt_rating_average">'.$average.'</span></span>';

			}

			$total_on = isset($options['total']) && $options['total'] == 'on' ? true : false;

			if($total_on) {
				$total_label = isset($options['total_label']) && $options['total_label'] != '' ? $options['total_label'] : '';

				if(intval($review_count)==1) {
					$total_label_after = isset($options['total_label_after_singular']) && $options['total_label_after_singular'] != '' ? $options['total_label_after_singular'] : '';
				}
				else {
					$total_label_after = isset($options['total_label_after']) && $options['total_label_after'] != '' ? $options['total_label_after'] : '';

				}

				$html .= '<span class="tt_rating_box_total">'.$total_label.$review_count.$total_label_after.'</span>';

			}

		}

		//rating breakdown

		if(isset($options['breakdown']) && $options['breakdown'] == 'on') {

			$html .= '<div class="tts_rating_breakdown">';

			$tt_star_label_singular = cmshowcase_get_option('star_singular','ttshowcase_frontend_form','Star');
			$tt_star_label_plural = cmshowcase_get_option('star_plural','ttshowcase_frontend_form','Stars');

			$label = __($tt_star_label_plural,'ttshowcase');

			foreach ($ratingbd as $rkey => $rvalue) {
				if($rkey!='0') {

					$percentage = 0;

					if(intval($rvalue)*100 > 0) {

						$percentage = round((intval($rvalue)*100)/intval($review_count),1);

					}


					if($rkey == '1') {
						$label = __($tt_star_label_singular,'ttshowcase');
					}

					$pc = isset($options['percentage']) && $options['percentage'] == 'on' ? ' ('.$percentage.'%)' : '';


					$html .= '
					<div class="tts_rating_breakdown_line">
						<div class="tts_ratingb_value">
						'.$rkey.' '.$label.'
						</div>
						<div class="tts_ratingb_full">
							<div class="tts_ratingb_percent" style="width:'.$percentage.'%">&nbsp;</div>
						</div>
						<div class="tts_ratingb_count"><span>'.$rvalue.$pc.'</span></div>
					</div>';

				}
			}

			$html .= '</div>';

		}


		$html .= '</div>';

		if($trigger_modal){
			$modal_content = isset($opt['modal_content']) ? do_shortcode($opt['modal_content']) : '';
			$html .= '<div style="display:none;"><div class="tt_modal_content" data-value="" id="tt_modal_content">'.$modal_content.'</div></div>';
		}

		$css = '';
		$js = '';

		if($custom_css!='') {

			$css .= '<!-- Custom Styles for Testimonials Showcase -->';
			    $css .= '<style type="text/css">';
			    $css .= $custom_css;
			    $css .= '</style>';

			$this->custom_css = '';

		}

		if($custom_js!='') {

			$js .= '<!-- Custom Script for Testimonials Showcase -->';
			    $js .= '<script type="text/javascript">';
			    $js .= $custom_js;
			    $js .= '</script>';

			$this->custom_js = '';

		}

		if($preview) {

			$html = $html.$css.$js;

		}

		else {
			$this->footer_content .= $css.$js;
			add_action('wp_footer', array($this,'ttshowcase_footer_content'),100);
		}


		wp_reset_postdata();
		return $html;



	}

	function ttshowcase_footer_content() {

		echo $this->footer_content;

	}

}




?>