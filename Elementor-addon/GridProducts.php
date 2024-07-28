<?php
namespace MN\RTL\Elementor;

use MN\RTL\Utils;

class GridProducts extends \Elementor\Widget_Base {
	public function get_name() {
		return 'mn_el_grid_products';
	}

	public function get_title() {
		return esc_html__( 'Show Products in Grid view', 'mn_rtl' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return ['mn-rtl', 'basic'];
	}

	public function get_keywords() {
		return ['product', 'products', 'archive', 'محصول', 'محصولات', 'آرشیو'];
	}

	protected function register_controls() {
		// Content Tab
		$this->text_control();
		$this->product_control();

		// Style Tab
		// $this->style_title_control();
		$this->style_title_control();
		$this->style_desc_control();
	}

	private function text_control() {
		$this->start_controls_section(
			'text_section',
			[
				'label' => esc_html__( 'Title & Description', 'mn_rtl' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Section title
		$this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Title', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Trending Product', 'mn_rtl' ),
				'placeholder' => esc_html__( 'Section Title', 'mn_rtl' ),
			]
		);

		// Section title tag
		$this->add_control(
			'widget_title_tag',
			[
				'label' => esc_html__( 'Title Tag', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1'	=> 'H1',
					'h2'	=> 'H2',
					'h3'	=> 'H3',
					'h4'	=> 'H4',
					'h5'	=> 'H5',
					'h6'	=> 'H6',
				],
				'default'	=> 'h2'
			]
		);

		// Section description
		$this->add_control(
			'widget_desc',
			[
				'label' => esc_html__( 'Description', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form', 'mn_rtl' ),
			]
		);

		$this->end_controls_section();
	}

	private function product_control() {
		$this->start_controls_section(
			'products_section',
			[
				'label' => esc_html__( 'Products Settings', 'mn_rtl' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Get products category
		$cats = get_categories( [
			'taxonomy'		=> 'product_cat',
			'hide_empty'	=> false,
			'orderby'		=> 'name',
			'order'			=> 'ASC'
		] );
		$cats = wp_list_pluck( $cats, 'name', 'slug' );
		$cats = array_merge( [
			'0'	=> __( 'All Categories', 'mn_rtl' ) // 0 means all cats
		], $cats );

		// Select Category section
		$this->add_control(
			'category_control',
			[
				'label' => __( 'Product Categories', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $cats,
			]
		);

		// Select Products ordering
		$ordering_options = [
			'menu_order' => __( 'Default sorting', 'woocommerce' ),
			'popularity' => __( 'Sort by popularity', 'woocommerce' ),
			'rating'     => __( 'Sort by average rating', 'woocommerce' ),
			'date'       => __( 'Sort by latest', 'woocommerce' ),
			'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
			'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
		];
		if( !wc_review_ratings_enabled() ) {
			unset( $ordering_options['rating'] );
		}
		$this->add_control(
			'ordering_control',
			[
				'label' => __( 'Order', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => $ordering_options,
			]
		);

		// Offset
		$this->add_control(
			'products_offset',
			[
				'label' => esc_html__( 'Offset', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min'		=> 0,
				'default'	=> 0,
			]
		);

		// Limit
		$this->add_control(
			'products_limit',
			[
				'label' => esc_html__( 'Limit', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min'		=> 1,
				'default'	=> 8,
			]
		);

		// Text for no products found
		$this->add_control(
			'no_products_text',
			[
				'label' => esc_html__( 'No Products Found Text', 'mn_rtl' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'No Products Found!', 'mn_rtl' ),
			]
		);

		$this->end_controls_section();
	}

	private function style_title_control() {
		$selector = "{{WRAPPER}} .mn_section_title";
		$this->start_controls_section(
			'style_title_section',
			[
				'label'	=> esc_html__( 'Title style', 'mn_rtl' ),
				'tab'	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Padding
		$this->add_responsive_control(
			'title_padding',
			[
				'label'			=> esc_html__( 'Padding', 'mn_rtl' ),
				'size_units'	=> [ 'px', '%', 'em', 'rem', 'custom' ],
				'type'			=> \Elementor\Controls_Manager::DIMENSIONS,
				'selectors'		=> [
					$selector	=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'title_margin',
			[
				'label'			=> esc_html__( 'Margin', 'mn_rtl' ),
				'size_units'	=> [ 'px', '%', 'em', 'rem', 'custom' ],
				'type'			=> \Elementor\Controls_Manager::DIMENSIONS,
				'selectors'		=> [
					$selector	=> 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'title_typography'
			]
		);

		// Color
		$this->add_control(
			'title_color',
			[
				'label'			=> esc_html__( 'Color', 'mn_rtl' ),
				'type'			=> \Elementor\Controls_Manager::COLOR,
				'selectors'		=> [
					$selector	=> 'color: {{VALUE}};',
					"{$selector}::before"	=> 'background-color: {{VALUE}}'
				],
			]
		);

		// Background
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'title_background'
			]
		);

		// Border
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'title_border'
			]
		);

		// Border radius
		$this->add_responsive_control(
			'title_border_radius',
			[
				'label'			=> esc_html__( 'Border Radius', 'mn_rtl' ),
				'size_units'	=> [ 'px', '%', 'em', 'rem', 'custom' ],
				'type'			=> \Elementor\Controls_Manager::DIMENSIONS,
				'selectors'		=> [
					$selector	=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'title_box_shadow'
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'title_text_shadow'
			]
		);

		$this->end_controls_section();
	}

	private function style_desc_control() {
		$selector = "{{WRAPPER}} .mn_section_desc";
		$this->start_controls_section(
			'style_desc_section',
			[
				'label'	=> esc_html__( 'Title style', 'mn_rtl' ),
				'tab'	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Padding
		$this->add_responsive_control(
			'desc_padding',
			[
				'label'			=> esc_html__( 'Padding', 'mn_rtl' ),
				'size_units'	=> [ 'px', '%', 'em', 'rem', 'custom' ],
				'type'			=> \Elementor\Controls_Manager::DIMENSIONS,
				'selectors'		=> [
					$selector	=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'desc_margin',
			[
				'label'			=> esc_html__( 'Margin', 'mn_rtl' ),
				'size_units'	=> [ 'px', '%', 'em', 'rem', 'custom' ],
				'type'			=> \Elementor\Controls_Manager::DIMENSIONS,
				'selectors'		=> [
					$selector	=> 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'desc_typography'
			]
		);

		// Color
		$this->add_control(
			'desc_color',
			[
				'label'			=> esc_html__( 'Color', 'mn_rtl' ),
				'type'			=> \Elementor\Controls_Manager::COLOR,
				'selectors'		=> [
					$selector	=> 'color: {{VALUE}};',
					"{$selector}::before"	=> 'background-color: {{VALUE}}'
				],
			]
		);

		// Background
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'desc_background'
			]
		);

		// Border
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'desc_border'
			]
		);

		// Border radius
		$this->add_responsive_control(
			'desc_border_radius',
			[
				'label'			=> esc_html__( 'Border Radius', 'mn_rtl' ),
				'size_units'	=> [ 'px', '%', 'em', 'rem', 'custom' ],
				'type'			=> \Elementor\Controls_Manager::DIMENSIONS,
				'selectors'		=> [
					$selector	=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'desc_box_shadow'
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'selector'	=> $selector,
				'name'		=> 'desc_text_shadow'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$title = Utils::convert_chars( $settings['widget_title'] );
		$title_tag = Utils::convert_chars( $settings['widget_title_tag'] );
		$desc = wp_kses_post( $settings['widget_desc'] );
		$cats = !empty( $settings['category_control'] ) ? $settings['category_control'] : [];
		$orderby = Utils::convert_chars( $settings['ordering_control'] );
		$no_products_text = wp_kses_post( $settings['no_products_text'] );
		$offset = Utils::convert_chars( $settings['products_offset'], true, 'absint' );
		$limit = Utils::convert_chars( $settings['products_limit'], true, 'absint' );

		foreach( $cats as &$cat ) {
			if( $cat == 0 ) { // Means all cats
				$cats = [];
				break;
			}
			$cat = Utils::convert_chars( $cat );
		}

		if( !in_array( $title_tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] ) ) $title_tag = 'h4';

		// Get products
		$args = [
			'limit' 	=> $limit,
			'offset'	=> $offset,
			'orderby'	=> $orderby == 'price-desc' ? 'price' : $orderby,
			'order'		=> $orderby == 'price' ? 'ASC' : 'DESC',
		];

		if( !empty( $cats ) ) {
			$args['category'] = $cats;
		}
		// print_r( $args ); die;
		$products = wc_get_products( $args );

		?>
		<!-- Start Trending Product Area -->
		<section class="trending-product section">
			<?php if( empty( $products ) ) { ?>
				<div class="text-center h3"><?php echo wpautop( $no_products_text ) ?></div>
			<?php } else { ?>
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="section-title">
								<<?php echo $title_tag ?> class="mn_section_title"><?php echo $title ?></<?php echo $title_tag ?>>
								<div class="mn_section_desc"><?php echo wpautop( $desc ) ?></div>
							</div>
						</div>
					</div>
					<div class="row">
						<!-- Start Single Product -->
						<?php foreach( $products as $product ) { ?>
							<div class="col-lg-3 col-md-6 col-12">
								<!-- get data -->
								<?php
								$pro_cat = $product->get_category_ids();
								$cat_name = !empty( $pro_cat ) ? get_term( $pro_cat[0] )->name : "";

								if( wc_review_ratings_enabled() ) {
									$average_rating	= $product->get_average_rating();
								}
								?>
								<div class="single-product">
									<div class="product-image">
										<?php echo $product->get_image() ?>
										<div class="button">
											<a href="<?php echo $product->add_to_cart_url() ?>" class="btn"><i class="lni lni-cart"></i> <?php echo $product->add_to_cart_text() ?></a>
										</div>
									</div>
									<div class="product-info">
										<span class="category"><?php echo $cat_name ?></span>
										<h4 class="title">
											<a href="<?php echo get_permalink( $product->get_id() ); ?>"><?php echo $product->get_name() ?></a>
										</h4>
										<?php if( wc_review_ratings_enabled() ) { ?>
											<ul class="review">
												<?php for ($i=0; $i <= 5; $i++) { ?>
													<li><i class="lni lni-star<?php $average_rating <= 1 ? '-filled' : ''?>"></i></li>
												<?php } ?>
												<li><span><?php echo $average_rating ?> Review(s)</span></li>
											</ul>
										<?php } ?>
										<div class="price">
											<span><?php echo $product->get_price_html() ?></span>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						<!-- End Single Product -->
					</div>
				</div>
			<?php } ?>
		</section>
		<!-- End Trending Product Area -->
		<?php
	}
	
}