<?php
/**
 * GC Sermons Speaker
 *
 * @version 0.1.0
 * @package GC Sermons
 */

class GCS_Speaker extends GCS_Taxonomies_Base {

	/**
	 * The identifier for this object
	 *
	 * @var string
	 */
	protected $id = 'speaker';

	/**
	 * The image meta key for this taxonomy, if applicable
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $image_meta_key = 'gc_sermon_speaker_image';

	/**
	 * Constructor
	 * Register Taxonomy. See documentation in Taxonomy_Core, and in wp-includes/taxonomy.php
	 *
	 * @since 0.1.0
	 * @param  object $sermons GCS_Sermons object.
	 * @return void
	 */
	public function __construct( $sermons ) {
		parent::__construct( $sermons, array(
			'labels' => array( __( 'Speaker', 'gc-sermons' ), __( 'Speakers', 'gc-sermons' ), 'gcs-speaker' ),
			'args'   => array( 'hierarchical' => false ),
		) );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'cmb2_admin_init', array( $this, 'fields' ) );
	}

	/**
	 * Add custom fields to the CPT
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function fields() {
		$cmb = $this->new_cmb2( array(
			'id'           => 'gc_sermon_speaker_metabox',
			'taxonomies'   => array( $this->taxonomy() ), // Tells CMB2 which taxonomies should
			'object_types' => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
			'fields'       => array(
				'gc_sermon_speaker_connected_user' => array(
					'name'  => __( 'Connected User', 'gc-sermons' ),
					'id'    => 'gc_sermon_speaker_connected_user',
					'desc'  => __( 'Type the name of the WordPress user and select from the suggested options. By associating a speaker with a WordPress user, that WordPress user account detials will be used (first/last name, avatar, bio, etc).', 'gc-sermons' ),
					'type'  => 'user_select_text',
					'options' => array(
						'minimum_user_level' => 0,
					),
				),
				$this->image_meta_key => array(
					'name' => __( 'Speaker Avatar', 'gc-sermons' ),
					'desc' => __( 'Select the speaker\'s avatar. Will only show if "Connected User" is not chosen, or if the "Connected User" does not have an avatar.', 'gc-sermons' ),
					'id'   => $this->image_meta_key,
					'type' => 'file'
				),
			),
		) );
	}

	/**
	 * Sets extra term data on the the term object, including the image and connected user object.
	 *
	 * @since  NEXT
	 *
	 * @param  WP_Term $term Term object
	 * @param  array   $args Array of arguments.
	 *
	 * @return WP_Term|false
	 */
	protected function extra_term_data( $term, $args ) {
		$term->connected_user = null;
		$term->nickname = '';

		if (
			( $connected_user = get_term_meta( $term->term_id, 'gc_sermon_speaker_connected_user', 1 ) )
			&& isset( $connected_user['id'] )
		) {
			$term = $this->augment_speaker_info( $term, $connected_user['id'], $args );
		}

		// If not connected user, do the default setting
		if ( ! $connected_user || ! isset( $term->image_url ) ) {
			$term = parent::extra_term_data( $term, $args );
		}

		return $term;
	}

	/**
	 * Takes a user ID and augments a speaker term object with user data.
	 *
	 * @since  NEXT
	 *
	 * @param  WP_Term $speaker Speaker term object.
	 * @param  int     $user_id Connected user ID.
	 * @param  array   $args    Array of arguments.
	 *
	 * @return WP_Term          Augmented term object.
	 */
	protected function augment_speaker_info( $speaker, $user_id, $args ) {
		if ( ! $user_id ) {
			return $speaker;
		}

		$user = get_userdata( $user_id );

		if ( ! $user ) {
			return $speaker;
		}

		$speaker->connected_user = $user->data;
		$speaker->user_link = get_author_posts_url( $user->ID );

		// Override speaker description with user description
		if ( $user_desc = $user->get( 'description' ) ) {
			$speaker->description = $user_desc;
		}

		// Override speaker name with user name
		if ( $first = $user->get( 'first_name' ) ) {
			$speaker->name = $first;
			if ( $last = $user->get( 'last_name' ) ) {
				$speaker->name .= ' ' . $last;
			}
		}

		// Add speaker nickname
		$speaker->nickname = $user->get( 'nickname' );

		$speaker = $this->add_image( $speaker, $args['image_size'] );

		if ( ! $speaker->image ) {
			// Add avatar
			$speaker->image = get_avatar( $user->ID, $args['image_size'], '', $speaker->name );
		}

		if ( ! $speaker->image_url ) {
			$speaker->image_url = get_avatar_url( $user->ID, array(
				'size'    => $args['image_size'],
			) );
		}

		return $speaker;
	}

}
