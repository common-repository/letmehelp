<?php
/**
 * Load scripts and styles functionality
 *
 * @package LetMeHelp
 */
namespace LetMeHelp\Base;

use LetMeHelp\Base\Database;

class Api {

	const LINKS_DATABASE_SLUG          = 'links';
	const KEYWORDS_DATABASE_SLUG       = 'keywords';
	const LINKS_KEYWORDS_DATABASE_SLUG = 'links-keywords';

	/**
	 * Register class functionality.
	 *
	 * @return void
	 */
	public function register() {
		// Registers the REST API routes for the plugin.
		add_action(
			'rest_api_init',
			function () {
				/**
				 * Registers a new REST API route to retrieve links.
				 *
				 * Route: /letmehelp/v1/links/
				 * Method: GET
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route The REST API route.
				 * @param array $args Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/links/',
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_links' ),
						'permission_callback' => function() {
							return current_user_can( 'manage_options' );
						},
					)
				);

				/**
				 * Registers a new REST API route to create a link.
				 *
				 * Route: /letmehelp/v1/links/
				 * Method: POST
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route     The REST API route.
				 * @param array  $args      Array of arguments for registering the route.
				 * @return WP_Error|void    WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/links/',
					array(
						'methods'             => \WP_REST_Server::CREATABLE,
						'callback'            => array( $this, 'create_link' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'link_url'   => array(
								'validate_callback' => function( $value ) {
									return ! empty( $value );
								},
								'required'          => true,
							),
							'link_label' => array(
								'validate_callback' => function( $value ) {
									return ! empty( $value );
								},
								'required'          => true,
							),
						),
					)
				);

				/**
				 * Registers a new REST API route to delete a link by ID.
				 *
				 * Route: /letmehelp/v1/links/{id}
				 * Method: DELETE
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route     The REST API route.
				 * @param array  $args      Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/links/(?P<id>\d+)',
					array(
						'methods'             => \WP_REST_Server::DELETABLE,
						'callback'            => array( $this, 'delete_link' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'id' => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
						),
					)
				);

				/**
				 * Registers a REST API route to update a link.
				 *
				 * Route: /letmehelp/v1/links/{id}
				 * Method: POST
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route     The REST API route.
				 * @param array  $args      Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/links/(?P<id>\d+)',
					array(
						'methods'             => \WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_link' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'id'         => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
							'link_url'   => array(
								'validate_callback' => function( $value ) {
									return ! empty( $value );
								},
							),
							'link_label' => array(
								'validate_callback' => function( $value ) {
									return ! empty( $value );
								},
							),
						),
					)
				);

				/**
				 * Registers a new REST API route to search links.
				 *
				 * Route: /letmehelp/v1/search-links/
				 * Method: POST
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route     The REST API route.
				 * @param array  $args      Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/search-links/',
					array(
						'methods'             => \WP_REST_Server::CREATABLE,
						'callback'            => array( $this, 'search_links' ),
						'permission_callback' => '__return_true',
						'args'                => array(
							'keyword_text' => array(
								'validate_callback' => function( $value ) {
									return ! empty( $value );
								},
							),
						),
					)
				);

				/**
				 * Registers a new REST API route to retrieve keywords.
				 *
				 * Route: /letmehelp/v1/keywords/
				 * Method: GET
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route     The REST API route.
				 * @param array  $args      Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/keywords/',
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_keywords' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
					)
				);

				/**
				 * Registers a new REST API route to create a keyword.
				 *
				 * Route: /letmehelp/v1/keywords/
				 * Method: POST
				 *
				 * @param string $namespace           Namespace for the REST API route.
				 * @param string $route              The REST API route.
				 * @param array  $args               Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/keywords/',
					array(
						'methods'             => \WP_REST_Server::CREATABLE,
						'callback'            => array( $this, 'create_keyword' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'keyword_text' => array(
								'validate_callback' => function( $value ) {
									return ! empty( $value );
								},
								'required'          => true,
							),
						),
					)
				);

				/**
				 * Registers a REST API route to update a keyword.
				 *
				 * Route: /letmehelp/v1/keywords/{id}
				 * Method: POST
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route     The REST API route.
				 * @param array  $args      Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/keywords/(?P<id>\d+)',
					array(
						'methods'             => \WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_keyword' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'id'           => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
							'keyword_text' => array(
								'validate_callback' => function( $value ) {
									return ! empty( $value );
								},
							),
						),
					)
				);

				/**
				 * Registers a new REST API route to delete a keyword by ID.
				 *
				 * Route: /letmehelp/v1/keywords/{id}
				 * Method: DELETE
				 *
				 * @param string $namespace Namespace for the REST API route.
				 * @param string $route     The REST API route.
				 * @param array  $args      Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/keywords/(?P<id>\d+)',
					array(
						'methods'             => \WP_REST_Server::DELETABLE,
						'callback'            => array( $this, 'delete_keyword' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'id' => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
						),
					)
				);

				/**
				 * Registers a new REST API route to get links and keywords relation.
				 *
				 * Route: /letmehelp/v1/links-keywords/
				 * Method: GET
				 *
				 * @param string $namespace           Namespace for the REST API route.
				 * @param string $route              The REST API route.
				 * @param array  $args               Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/links-keywords/',
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_links_keywords_relation' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
					)
				);

				/**
				 * Registers a new REST API route to create a link-keyword relation.
				 *
				 * Route: /letmehelp/v1/links-keywords/
				 * Method: POST
				 *
				 * @param string $namespace           Namespace for the REST API route.
				 * @param string $route              The REST API route.
				 * @param array  $args               Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/links-keywords/',
					array(
						'methods'             => \WP_REST_Server::CREATABLE,
						'callback'            => array( $this, 'create_link_keyword_relation' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'link_id'    => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
							'keyword_id' => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
						),
					)
				);

				/**
				 * Registers a new REST API route to delete a link and keyword relation.
				 *
				 * Route: /letmehelp/v1/links-keywords/
				 * Method: DELETE
				 *
				 * @param string $namespace           Namespace for the REST API route.
				 * @param string $route              The REST API route.
				 * @param array  $args               Array of arguments for registering the route.
				 * @return WP_Error|void WP_Error on invalid input, void otherwise.
				 */
				register_rest_route(
					'letmehelp/v1',
					'/links-keywords/',
					array(
						'methods'             => \WP_REST_Server::DELETABLE,
						'callback'            => array( $this, 'delete_link_keyword_relation' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'link_id'    => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
							'keyword_id' => array(
								'validate_callback' => function( $value ) {
									return is_numeric( $value );
								},
								'required'          => true,
							),
						),
					)
				);
			}
		);
	}

	/**
	 * Retrieves all links from the database.
	 *
	 * @return array Returns an array of links.
	 */
	public function get_links() {
		$table = self::LINKS_DATABASE_SLUG;
		return Database::fetch( $table );
	}

	/**
	 * Create a new link in database.
	 *
	 * @param WP_REST_Request $request The REST API request object.
	 * @return WP_REST_Response The REST API response object.
	 */
	public function create_link( $request ) {
		$table = self::LINKS_DATABASE_SLUG;
		$data  = array(
			'link_url'   => sanitize_text_field( $request['link_url'] ),
			'link_label' => sanitize_text_field( $request['link_label'] ),
		);

		// Insert the new link into the database.
		$new_data = Database::insert( $table, $data );

		// If the link was successfully created, return a success response.
		if ( is_array( $new_data ) && isset( $new_data['id'] ) ) {
			return rest_ensure_response(
				array(
					'message' => esc_html__( 'Link created successfully.', 'letmehelp' ),
					'id'      => $new_data['id'],
				)
			);
		}

		// Return an error message if the link creation failed.
		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to create link.', 'letmehelp' ),
			)
		);
	}

	/**
	 * Updates an existing link in the database.
	 *
	 * @param
	 * @return
	 */
	public function update_link( $request ) {
		$table = self::LINKS_DATABASE_SLUG;
		$data  = array(
			'link_id'    => absint( sanitize_text_field( $request['link_id'] ) ),
			'link_url'   => sanitize_text_field( $request['link_url'] ),
			'link_label' => sanitize_text_field( $request['link_label'] ),
		);

		// Insert the new link into the database.
		$updated_data = Database::update( $table, $data );

		// If the link was successfully created, return a success response.
		if ( is_array( $updated_data ) && isset( $updated_data['id'] ) ) {
			return rest_ensure_response(
				array(
					'message' => esc_html__( 'Link updated successfully.', 'letmehelp' ),
					'id'      => $updated_data['id'],
				)
			);
		}

		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to updated link.', 'letmehelp' ),
			)
		);
	}

	/**
	 * Deletes a link and its related keywords from the database.
	 *
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response A response object with status code and message.
	 */
	public function delete_link( $request ) {
		$id                    = absint( sanitize_text_field( $request['id'] ) );
		$regular_table         = self::LINKS_DATABASE_SLUG;
		$junction_table        = self::LINKS_KEYWORDS_DATABASE_SLUG;
		$junction_table_column = 'link_id';

		$data = array(
			'link_id' => $id,
		);

		// Check if the keyword exists in the junction table.
		$exists_in_junction_table = Database::is_record( $junction_table, $junction_table_column, $id );

		// If the keyword exists in the junction table, delete the corresponding rows.
		if ( $exists_in_junction_table ) {
			Database::delete( $junction_table, $data );
		}

		// Delete the link from the database.
		$deleted_data = Database::delete( $regular_table, $data );

		if ( $deleted_data ) {
			return rest_ensure_response(
				array(
					'message' => esc_html__( 'Link deleted successfully.', 'letmehelp' ),
					'id'      => $id,
				)
			);
		}

		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to delete link.', 'letmehelp' ),
			)
		);
	}

	/**
	 * Searches for links associated with a given keyword.
	 *
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response The REST response object.
	 */
	public function search_links( $request ) {
		// Sanitize and retrieve the keyword text.
		$keyword_text = sanitize_text_field( $request['keyword_text'] );
		// Sanitize and retrieve request mode.
		$is_editor_mode = isset( $request['mode'] ) && 'editor' === sanitize_text_field( $request['mode'] ) ? true : false;
		// Link to final destination.
		$next_url = '';

		// Create link to final destination only for site visitors.
		if ( ! $is_editor_mode ) {
			// Sanitize and retrieve the post type ID.
			$post_id = sanitize_text_field( $request['post_id'] );

			// Build the next URL.
			$next_url = add_query_arg(
				array(
					'lmh_receptionist' => 'checked',
				),
				get_permalink( $post_id )
			);
		}

		// Initialize the not found results array.
		$not_found_results = array(
			'message' => esc_html__( 'No links found.', 'letmehelp' ),
			'result'  => array(),
			'link'    => $next_url,
		);

		$keywords_table       = self::KEYWORDS_DATABASE_SLUG;
		$keywords_text_column = 'keyword_text';

		// Get full information about keyword based on its text.
		$keywords = Database::get_results_by_value(
			$keywords_table,
			$keywords_text_column,
			$keyword_text
		);

		// If the keyword doesn't exist, return the not found results.
		if ( empty( $keywords ) ) {
			return rest_ensure_response( $not_found_results );
		}

		// Get keyword id.
		$keyword_id           = absint( $keywords[0]['keyword_id'] );
		$keywords_id_column   = 'keyword_id';
		$links_keywords_table = self::LINKS_KEYWORDS_DATABASE_SLUG;
		$links_id_column      = 'link_id';

		// Search for relation with any link based on keyword id.
		$links_ids = Database::get_results_by_key(
			$links_keywords_table,
			$keywords_id_column,
			$keyword_id,
			$links_id_column
		);

		// If no links are associated with the keyword, return the not found results.
		if ( empty( $links_ids ) ) {
			return rest_ensure_response( $not_found_results );
		}

		$links_table  = self::LINKS_DATABASE_SLUG;
		$links_result = Database::get_results_where_in(
			$links_table,
			$links_id_column,
			$links_ids
		);

		// Return a response with found links, or return $not_found_results if no links are found.
		if ( ! $links_result ) {
			return rest_ensure_response( $not_found_results );
		}

		// If links were found, return the results.
		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Links found successfully.', 'letmehelp' ),
				'result'  => $links_result,
				'link'    => $next_url,
			)
		);
	}

	/**
	 * Retrieves all keywords from the database.
	 *
	 * @return array List of keywords.
	 */
	public function get_keywords() {
		$table = self::KEYWORDS_DATABASE_SLUG;
		return Database::fetch( $table );
	}

	/**
	 * Creates a new keyword.
	 *
	 * @param WP_REST_Request $request The REST API request object.
	 * @return WP_REST_Response The REST API response object.
	 */
	public function create_keyword( $request ) {
		$table = self::KEYWORDS_DATABASE_SLUG;
		$data  = array(
			'keyword_text' => sanitize_text_field( $request['keyword_text'] ),
		);

		// Insert the new link into the database.
		$new_data = Database::insert( $table, $data );

		// If the keyword was successfully created, return a success response.
		if ( is_array( $new_data ) && isset( $new_data['id'] ) ) {
			return rest_ensure_response(
				array(
					'message' => esc_html__( 'Keyword created successfully.', 'letmehelp' ),
					'id'      => $new_data['id'],
				)
			);
		}

		// Return an error message if the keyword creation failed.
		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to create keyword.', 'letmehelp' ),
			)
		);
	}

	/**
	 * Updates an existing keyword in the database.
	 *
	 * @param
	 * @return
	 */
	public function update_keyword( $request ) {
		$table = self::KEYWORDS_DATABASE_SLUG;
		$data  = array(
			'keyword_id'   => absint( sanitize_text_field( $request['keyword_id'] ) ),
			'keyword_text' => sanitize_text_field( $request['keyword_text'] ),
		);

		// Insert the new link into the database.
		$updated_data = Database::update( $table, $data );

		// If the link was successfully created, return a success response.
		if ( is_array( $updated_data ) && isset( $updated_data['id'] ) ) {
			return rest_ensure_response(
				array(
					'message' => esc_html__( 'Keyword updated successfully.', 'letmehelp' ),
					'id'      => $updated_data['id'],
				)
			);
		}

		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to update keyword.', 'letmehelp' ),
			)
		);
	}

	/**
	 * Deletes a keyword and its associated links from the database.
	 *
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response A response object with status code and message.
	 */
	public function delete_keyword( $request ) {
		$id                    = absint( sanitize_text_field( $request['id'] ) );
		$regular_table         = self::KEYWORDS_DATABASE_SLUG;
		$junction_table        = self::LINKS_KEYWORDS_DATABASE_SLUG;
		$junction_table_column = 'keyword_id';

		$data = array(
			'keyword_id' => $id,
		);

		// Check if the keyword exists in the junction table.
		$exists_in_junction_table = Database::is_record( $junction_table, $junction_table_column, $id );

		// If the keyword exists in the junction table, delete the corresponding rows.
		if ( $exists_in_junction_table ) {
			Database::delete( $junction_table, $data );
		}

		// Delete the link from the database.
		$deleted_data = Database::delete( $regular_table, $data );

		if ( $deleted_data ) {
			return rest_ensure_response(
				array(
					'message' => esc_html__( 'Keyword deleted successfully.', 'letmehelp' ),
					'id'      => $id,
				)
			);
		}

		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to delete keyword.', 'letmehelp' ),
			)
		);
	}

	/**
	 * Retrieve all link-keyword relationships from database
	 *
	 * @return array|object|null Database query results
	 */
	public function get_links_keywords_relation() {
		$table = self::LINKS_KEYWORDS_DATABASE_SLUG;
		return Database::fetch( $table );
	}

	/**
	 * Create new link keyword relationship.
	 *
	 * @param WP_REST_Request $request The REST API request object.
	 * @return WP_REST_Response The response object.
	 */
	public function create_link_keyword_relation( $request ) {
		$table = self::LINKS_KEYWORDS_DATABASE_SLUG;
		$data  = array(
			'link_id'    => absint( sanitize_text_field( $request['link_id'] ) ),
			'keyword_id' => absint( sanitize_text_field( $request['keyword_id'] ) ),
		);

		// Insert the new link into the database.
		$new_data = Database::insert( $table, $data );

		// If the keyword was successfully created, return a success response.
		if ( is_array( $new_data ) && isset( $new_data['id'] ) ) {
			return rest_ensure_response(
				array(
					'message' => esc_html__( 'Link-keyword relationship created successfully.', 'letmehelp' ),
					'id'      => $new_data['id'],
				)
			);
		}

		// Return an error message if the keyword creation failed.
		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to create link-keyword relationship.', 'letmehelp' ),
			)
		);
	}

	/**
	 * Delete link keyword relation.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response The response object.
	 */
	public function delete_link_keyword_relation( $request ) {
		$table = self::LINKS_KEYWORDS_DATABASE_SLUG;
		$data  = array(
			'link_id'    => absint( sanitize_text_field( $request['link_id'] ) ),
			'keyword_id' => absint( sanitize_text_field( $request['keyword_id'] ) ),
		);

		// Delete the link from the database.
		$deleted_data = Database::delete( $table, $data );

		// Return appropriate response.
		if ( $deleted_data ) {
			return rest_ensure_response(
				array(
					'message'    => esc_html__( 'Link keyword relation deleted successfully.', 'letmehelp' ),
					'link_id'    => $data['link_id'],
					'keyword_id' => $data['keyword_id'],
				)
			);
		}

		return rest_ensure_response(
			array(
				'message' => esc_html__( 'Failed to delete link keyword relation.', 'letmehelp' ),
			)
		);
	}
}
