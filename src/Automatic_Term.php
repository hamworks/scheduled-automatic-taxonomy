<?php

namespace HAMWORKS\WP\Scheduled_Automatic_Taxonomy;

/**
 * Class Auto_Term
 *
 * Automatic term setting.
 *
 * @package Bacardi\Schedule
 */
class Automatic_Term {

	/**
	 * @var string
	 */
	private $meta_key;

	/**
	 * @var string
	 */
	private $term;

	/**
	 * @var string
	 */
	private $taxonomy;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * Auto_Term constructor.
	 *
	 * @param string $meta_key
	 * @param string $term
	 * @param string $slug
	 * @param string $taxonomy
	 */
	public function __construct( string $meta_key, string $term, string $slug = '', string $taxonomy = 'category' ) {
		$this->meta_key = $meta_key;
		$this->term     = $term;
		$this->taxonomy = $taxonomy;
		$this->slug     = $slug;
		if ( ! $this->slug ) {
			$this->slug = $term;
		}

		$this->create_term();
		add_action( 'save_post', [ $this, 'update_schedule' ], 100 );
		add_action( "${meta_key}_event", [ $this, 'update_term' ], 10, 1 );
	}

	/**
	 * Create term when not exist.
	 */
	private function create_term() {
		if ( term_exists( $this->slug, $this->taxonomy ) ) {
			return;
		}
		$args         = [];
		$args['slug'] = $this->slug;
		wp_insert_term( $this->term, $this->taxonomy, $args );
	}

	/**
	 * Scheduled update.
	 *
	 * @param int $post_id
	 */
	public function update_schedule( int $post_id ) {
		$meta_key   = $this->meta_key;
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		if ( $meta_value ) {
			try {
				$date_time = new \DateTime( $meta_value, new \DateTimeZone( get_option( 'timezone_string' ) ) );
			} catch ( \Exception $e ) {
				wp_die( esc_html( $e->getMessage() ) );
			}
			$time = $date_time->getTimestamp();
			if ( current_time( 'timestamp', true ) > $time ) {
				$this->update_term( $post_id );
			} else {
				wp_schedule_single_event( $time, "${meta_key}_event", [ $post_id ] );
			}
		}
	}

	/**
	 * Update Term.
	 *
	 * @param int $post_id
	 */
	public function update_term( int $post_id ) {
		add_action(
			'shutdown',
			function () use ( $post_id ) {
				$term = get_term_by( 'slug', $this->slug, $this->taxonomy );
				wp_set_post_terms( $post_id, [ $term->term_id ], $this->taxonomy, false );
			}
		);
	}
}
