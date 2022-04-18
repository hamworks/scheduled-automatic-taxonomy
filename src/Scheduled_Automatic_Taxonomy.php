<?php

namespace HAMWORKS\WP\Scheduled_Automatic_Taxonomy;

use HAMWORKS\WP\Taxonomy\Builder;

/**
 * Class Init
 */
class Scheduled_Automatic_Taxonomy {


	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		$this->create_taxonomy();
		$this->register_automatic_terms();
	}

	private function create_taxonomy() {
		$taxonomy_slug       = add_filter( 'scheduled_automatic_taxonomy_taxonomy_slug', 'schedule_tag' );
		$taxonomy_name       = add_filter( 'scheduled_automatic_taxonomy_taxonomy_name', esc_html__( 'スケジュールタグ', 'scheduled-automatic-taxonomy' ) );
		$taxonomy_post_types = add_filter( 'scheduled_automatic_taxonomy_taxonomy_post_types', array( 'post' ) );
		$builder             = new Builder( $taxonomy_slug, $taxonomy_name, $taxonomy_post_types );
		$builder->set_options(
			array(
				'public'      => true,
				'description' => 'taxonomy for schedule',
				'has_archive' => true,
			)
		);
		$builder->create();
	}

	public function register_automatic_terms() {
		$terms = array(
			array(
				'meta_key' => 'open',
				'term'     => esc_html__( 'Opened', 'scheduled-automatic-taxonomy' ),
				'slug'     => 'opened',
			),
			array(
				'meta_key' => 'close',
				'term'     => esc_html__( 'Closed', 'scheduled-automatic-taxonomy' ),
				'slug'     => 'closed',
			),
		);

		$terms = add_filter( 'scheduled_automatic_taxonomy_automatic_terms', $terms );

		foreach ( $terms as $term ) {
			new Automatic_Term( $term['meta_key'], $term['term'], $term['slug'] );
		}
	}


}
