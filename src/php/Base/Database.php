<?php
/**
 * Database class file.
 *
 * @package LetMeHelp
 */
namespace LetMeHelp\Base;

/**
 * Class Database
 *
 * Contains functionality related to the database.
 *
 * @package LetMeHelp\Base
 */
class Database {

	/**
	 * The name of the table for links.
	 *
	 * @var string
	 */
	const LINKS_TABLE = 'letmehelp_links';

	/**
	 * The name of the table for keywords.
	 *
	 * @var string
	 */
	const KEYWORDS_TABLE = 'letmehelp_keywords';

	/**
	 * The name of the table for linking links and keywords.
	 *
	 * @var string
	 */
	const LINKS_KEYWORDS_TABLE = 'letmehelp_links_keywords';

	/**
	 * The version number of the links table.
	 *
	 * @var string
	 */
	const LINKS_TABLE_VERSION = '1.0';

	/**
	 * The version number of the keywords table.
	 *
	 * @var string
	 */
	const KEYWORDS_TABLE_VERSION = '1.0';

	/**
	 * The version number of the links-keywords table.
	 *
	 * @var string
	 */
	const LINKS_KEYWORDS_TABLE_VERSION = '1.0';

	/**
	 * Returns the name of the table.
	 *
	 * @param string $table The name of the table to get.
	 * @return string The name of the table, or an empty string if the table name is invalid.
	 */
	public static function get_table_name( $table ) {
		/**
		 * @var Object $wpdb Global database object
		 */
		global $wpdb;

		// Check if $table is set and not empty.
		if ( isset( $table ) && '' !== $table ) {
			switch ( $table ) {
				case 'links':
					return $wpdb->prefix . self::LINKS_TABLE;
				case 'keywords':
					return $wpdb->prefix . self::KEYWORDS_TABLE;
				case 'links-keywords':
					return $wpdb->prefix . self::LINKS_KEYWORDS_TABLE;
				default:
					return '';
			}
		}

		// Return an empty string if $table is not set or empty.
		return '';
	}

	/**
	 * Returns the option name for the table version.
	 *
	 * @param string $table The name of the table.
	 * @return string The option name for the table version.
	 */
	public static function get_table_version_option_name( $table ) {
		// Check if $table is set and not empty.
		if ( isset( $table ) && '' !== $table ) {
			// Determine the table name and return the corresponding option name.
			switch ( $table ) {
				case 'links':
					return self::LINKS_TABLE . '_table_version';
				case 'keywords':
					return self::KEYWORDS_TABLE . '_table_version';
				case 'links-keywords':
					return self::LINKS_KEYWORDS_TABLE . '_table_version';
				default:
					return '';
			}
		}

		// Return an empty string if $table is not set or empty.
		return '';
	}

	/**
	 * Gets the current table version option.
	 *
	 * @param string $table The name of the table.
	 * @return string|null The current table version or null if $table is empty.
	 */
	private static function get_table_version_option( $table ) {
		// Check if $table is not empty.
		if ( empty( $table ) ) {
			return null;
		}

		$option_name = self::get_table_version_option_name( $table );
		return get_option( $option_name );
	}

	/**
	 * Sets the table version option.
	 *
	 * @param string $table The name of the table.
	 * @param string $version The version to set for the table.
	 * @return void
	 */
	private static function set_table_version_option( $table, $version ) {
		// Check if $table is not empty.
		if ( empty( $table ) ) {
			return;
		}

		$option_name = self::get_table_version_option_name( $table );
		add_option( $option_name, $version );
	}

	/**
	 * Checks if the table exists.
	 *
	 * @param string $table_name The name of the table to check.
	 * @return bool Whether the table exists or not.
	 */
	private static function is_table( $table_name ) {
		/**
		 * @var Object $wpdb Global database object
		 */
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if the record exists in database.
	 *
	 * @param string $table_name The name of the table to check.
	 * @return bool Whether the table exists or not.
	 */
	public static function is_record( $table, $column, $id ) {
		/**
		 * @var Object $wpdb Global database object
		 */
		global $wpdb;

		/**
		 * Get full table name.
		 *
		 * @param string $table Name of the table.
		 * @return string Name of the table including WordPress table prefix.
		 */
		$table_name = self::get_table_name( $table );

		// Sanitize the table and column names.
		$sanitized_table_name = sanitize_key( $table_name );
		$sanitized_column     = sanitize_key( $column );

		/**
		 * Prepare the query.
		 * Since $wpdb->prepare() does not support placeholders for table and column names,
		 * we have already sanitized the variables using sanitize_key(), thus
		 * safely ignore these specific errors.
		 */
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$sql = $wpdb->prepare( "SELECT {$sanitized_column} FROM {$sanitized_table_name} WHERE {$sanitized_column} = %d", $id );

		// Execute the query, to see if record exists.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		if ( $wpdb->get_var( $sql ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Retrieve all rows from a table where a specific key matches a given value.
	 *
	 * @param string $table The name of the table to retrieve rows from.
	 * @param string $key_column_name The name of the column that holds the key to match against.
	 * @param mixed $key_value The value to match against the specified key column.
	 * @param string $value_column_name The name of the column to retrieve the values from.
	 * @return array An array of values from the specified column in the table.
	 */
	public static function get_results_by_key( $table, $key_column_name, $key_value, $value_column_name ) {
		/**
		 * @var Object $wpdb Global database object.
		 */
		global $wpdb;

		/**
		 * Get full table name.
		 *
		 * @param string $table Name of the table.
		 * @return string Name of the table including WordPress table prefix.
		 */
		$table_name = self::get_table_name( $table );

		// Sanitize column names and its values.
		$key_column_name   = sanitize_key( $key_column_name );
		$value_column_name = sanitize_key( $value_column_name );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( is_int( $key_value ) ) {
			$query = $wpdb->prepare(
				"SELECT $value_column_name FROM $table_name WHERE $key_column_name = %d",
				$key_value
			);
		} else {
			$query = $wpdb->prepare(
				"SELECT $value_column_name FROM $table_name WHERE $key_column_name = %s",
				$key_value
			);
		}
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$results = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$values  = array();

		if ( empty( $results ) ) {
			return $values;
		}

		foreach ( $results as $result ) {
			$values[] = $result[ $value_column_name ];
		}

		return $values;
	}

	/**
	 * Retrieve all rows from a table where a specific column matches a given value.
	 *
	 * @param string $table The name of the table to retrieve rows from.
	 * @param string $column_name The name of the column to match against.
	 * @param mixed $column_value The value to match against in the specified column.
	 * @return array An array of row data from the table.
	 */
	public static function get_results_by_value( $table, $column_name, $column_value ) {
		/**
		 * @var Object $wpdb Global database object.
		 */
		global $wpdb;

		/**
		 * Get full table name.
		 *
		 * @param string $table Name of the table.
		 * @return string Name of the table including WordPress table prefix.
		 */
		$table_name = self::get_table_name( $table );

		// Sanitize column name.
		$column_name = sanitize_key( $column_name );

		// Determine the correct placeholder type based on the data type of $column_value.
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( is_int( $column_value ) ) {
			// Prepare and execute the database query.
			$query = $wpdb->prepare(
				"SELECT * FROM $table_name WHERE $column_name = %d",
				$column_value
			);
		} else {
			// Prepare and execute the database query.
			$query = $wpdb->prepare(
				"SELECT * FROM $table_name WHERE $column_name = %s",
				$column_value
			);
		}
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		return $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Gets results from a table where a column matches any of the given values using WHERE IN clause.
	 *
	 * @param string $table The table to query.
	 * @param string $column_name The column to match against the given values.
	 * @param array $values An array of values to match against the column.
	 *
	 * @return array|object|null The results of the query.
	 */
	public static function get_results_where_in( $table, $column_name, $values ) {
		/**
		 * @var Object $wpdb Global database object.
		 */
		global $wpdb;

		/**
		 * Get full table name.
		 *
		 * @param string $table Name of the table.
		 * @return string Name of the table including WordPress table prefix.
		 */
		$table_name = self::get_table_name( $table );

		// Sanitize column name.
		$column_name = sanitize_key( $column_name );

		// Get the first value in the array to determine the placeholder type for the prepared statement.
		$value = reset( $values );

		// Use %d placeholder if the first value in the array is an integer, otherwise use %s.
		$placeholder_type = is_int( $value ) ? '%d' : '%s';

		// Generate a comma-separated list of placeholders for the prepared statement.
		$placeholders = implode( ',', array_fill( 0, count( $values ), $placeholder_type ) );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		/**
		 * Generate the prepared statement with the appropriate number and type of placeholders.
		 *
		 * `...$values` is the argument unpacking, to
		 * unpack the elements of the $values array and
		 * pass them as individual arguments to the function.
		 *
		 * Since the prepare() method accepts a variable number of arguments, using
		 * ...$values ensures that each element of the `$values` array is passed as a separate argument.
		 *
		 * For example, if $values is an array [1, 2, 3], then
		 * ...$values would be equivalent to passing 1, 2, 3 as separate arguments to the function.
		 */
		$query = $wpdb->prepare( "SELECT * FROM $table_name WHERE $column_name IN ($placeholders)", ...$values );
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare

		// Return the query results as an array of associative arrays.
		return $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Creates the tables if needed.
	 *
	 * @return void
	 */
	public static function maybe_create_tables() {
		/**
		 * @var Object $wpdb Global database object.
		 */
		global $wpdb;

		// Get table names.
		$links_table_name          = self::get_table_name( 'links' );
		$keywords_table_name       = self::get_table_name( 'keywords' );
		$links_keywords_table_name = self::get_table_name( 'links-keywords' );

		// Require WordPress database upgrade file.
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Check if need to create or update table for links.
		if ( ! self::is_table( $links_table_name ) || self::get_table_version_option( 'links' ) !== self::LINKS_TABLE_VERSION ) {
			// Create the links table.
			$links_table_sql = "CREATE TABLE {$links_table_name} (
				link_id bigint(20) NOT NULL AUTO_INCREMENT,
				link_url varchar(255) NOT NULL,
				link_label varchar(255) NOT NULL,
				PRIMARY KEY  (link_id)
			) DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate};";

			// Run database query to create links table.
			dbDelta( $links_table_sql );

			// Update links table version.
			self::set_table_version_option( 'links', self::LINKS_TABLE_VERSION );
		}

		// Check if need to create or update table for keywords.
		if ( ! self::is_table( $keywords_table_name ) || self::get_table_version_option( 'keywords' ) !== self::KEYWORDS_TABLE_VERSION ) {
			// Create the keywords table.
			$keywords_table_sql = "CREATE TABLE {$keywords_table_name} (
				keyword_id bigint(20) NOT NULL AUTO_INCREMENT,
				keyword_text varchar(255) NOT NULL,
				keyword_status varchar(20) DEFAULT 'active' NOT NULL,
				PRIMARY KEY  (keyword_id)
			) DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate};";

			// Run database query to create keywords table.
			dbDelta( $keywords_table_sql );

			// Update keywords table version.
			self::set_table_version_option( 'keywords', self::KEYWORDS_TABLE_VERSION );
		}

		// Check if need to create or update table for links & keywords.
		if ( ! self::is_table( $links_keywords_table_name ) || self::get_table_version_option( 'links-keywords' ) !== self::LINKS_KEYWORDS_TABLE_VERSION ) {
			// Create the links_keywords junction table.
			$links_keywords_table_sql = "CREATE TABLE {$links_keywords_table_name} (
				link_id bigint(20) NOT NULL,
				keyword_id bigint(20) NOT NULL,
				PRIMARY KEY  (link_id, keyword_id),
				FOREIGN KEY  (link_id) REFERENCES $links_table_name(link_id),
				FOREIGN KEY  (keyword_id) REFERENCES $keywords_table_name(keyword_id)
			) DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate};";

			// Run database query to create links_keywords junction table.
			dbDelta( $links_keywords_table_sql );

			// Update links_keywords junction table version.
			self::set_table_version_option( 'links-keywords', self::LINKS_KEYWORDS_TABLE_VERSION );
		}
	}

	/**
	 * Create a new item in database.
	 *
	 * @param string $table Name of the table.
	 * @param array $data Data to add.
	 * @return array|bool
	 */
	public static function insert( $table, $data ) {
		if ( ! isset( $table ) ) {
			return false;
		}

		if ( ! isset( $data ) ) {
			return false;
		}

		/**
		 * @var Object $wpdb Global database object
		 */
		global $wpdb;

		/**
		 * Get the table name.
		 *
		 * @param string $table Name of the table.
		 * @return string Name of the table including WordPress table prefix.
		 */
		$table_name = self::get_table_name( $table );

		if ( 'links' === $table ) {
			/**
			 * Insert a new link in the links table.
			 *
			 * @param string $table_name Name of the table including WordPress table prefix.
			 * @param array $data Data to add.
			 * @param array $format Format of the data.
			 * @return int|false The number of rows inserted, or false on failure.
			 */
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$created = $wpdb->insert(
				$table_name,
				array(
					'link_url'   => $data['link_url'],
					'link_label' => $data['link_label'],
				),
				array(
					'%s',
					'%s',
				)
			);
		} elseif ( 'keywords' === $table ) {
			/**
			 * Insert a new keyword in the keywords table.
			 *
			 * @param string $table_name Name of the table including WordPress table prefix.
			 * @param array $data Data to add.
			 * @param array $format Format of the data.
			 * @return int|false The number of rows inserted, or false on failure.
			 */
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$created = $wpdb->insert(
				$table_name,
				array(
					'keyword_text' => $data['keyword_text'],
				),
				array(
					'%s',
				)
			);
		} elseif ( 'links-keywords' === $table ) {
			/**
			 * Insert a new record in the links-keywords table.
			 *
			 * @param string $table_name Name of the table including WordPress table prefix.
			 * @param array $data Data to add.
			 * @param array $format Format of the data.
			 * @return int|false The number of rows inserted, or false on failure.
			 */
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$created = $wpdb->insert(
				$table_name,
				array(
					'link_id'    => $data['link_id'],
					'keyword_id' => $data['keyword_id'],
				),
				array(
					'%d',
					'%d',
				)
			);
		}

		if ( ! isset( $created ) ) {
			return false;
		}

		return array(
			'id' => $wpdb->insert_id,
		);
	}

	/**
	 * Update existing item in database.
	 *
	 * @param string $table Name of the table.
	 * @param array $data Data to update.
	 * @return array|bool
	 */
	public static function update( $table, $data ) {
		if ( ! isset( $table ) ) {
			return false;
		}

		if ( ! isset( $data ) ) {
			return false;
		}

		/**
		 * @var Object $wpdb Global database object
		 */
		global $wpdb;

		/**
		 * Get the table name.
		 *
		 * @param string $table Name of the table.
		 * @return string Name of the table including WordPress table prefix.
		 */
		$table_name = self::get_table_name( $table );

		// ID of updated database item.
		$updated_id = 0;

		if ( 'links' === $table ) {
			// Updates the link record in the database.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$updated = $wpdb->update(
				$table_name,
				array(
					'link_url'   => $data['link_url'],
					'link_label' => $data['link_label'],
				),
				array(
					'link_id' => $data['link_id'],
				),
				array(
					'%s',
					'%s',
				),
				array(
					'%d',
				)
			);

			if ( $updated ) {
				$updated_id = $data['link_id'];
			}
		} elseif ( 'keywords' === $table ) {
			// Updates the link record in the database.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$updated = $wpdb->update(
				$table_name,
				array(
					'keyword_text' => $data['keyword_text'],
				),
				array(
					'keyword_id' => $data['keyword_id'],
				),
				array(
					'%s',
				),
				array(
					'%d',
				)
			);

			if ( $updated ) {
				$updated_id = $data['keyword_id'];
			}
		}

		// Check if database item was updated.
		if ( ! $updated_id ) {
			return false;
		}

		return array(
			'id' => $updated_id,
		);
	}

	/**
	 * Delete existing item in database.
	 *
	 * @param string $table Name of the table.
	 * @param array $data Data to delete.
	 * @return bool
	 */
	public static function delete( $table, $data ) {
		if ( ! isset( $table ) ) {
			return false;
		}

		if ( ! isset( $data ) ) {
			return false;
		}

		/**
		 * @var Object $wpdb Global database object
		 */
		global $wpdb;

		/**
		 * Get the table name.
		 *
		 * @param string $table Name of the table.
		 * @return string Name of the table including WordPress table prefix.
		 */
		$table_name = self::get_table_name( $table );

		// Get format for query.
		switch ( count( $data ) ) {
			case 1:
				$format = array( '%d' );
				break;
			case 2:
				$format = array( '%d', '%d' );
				break;
			default:
				$format = array();
		}

		if ( empty( $format ) ) {
			return false;
		}

		// Delete relation.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$is_deleted = $wpdb->delete(
			$table_name,
			$data,
			$format
		);

		return $is_deleted;
	}

	/**
	 * Retrieves data from database.
	 *
	 * @param string $table The name of the table.
	 * @return array Returns an array of data.
	 */
	public static function fetch( $table ) {
		// Make sure table is provided.
		if ( ! isset( $table ) ) {
			return false;
		}

		/**
		 * @var Object $wpdb Global database object
		 */
		global $wpdb;

		// Name of the table
		$table_name = self::get_table_name( $table );

		// Retrieve all table data from the database.
		$results = $wpdb->get_results( "SELECT * FROM {$table_name}", ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		// Return the results.
		return $results;
	}

	/**
	 * Delete plugin options from database.
	 */
	public static function delete_options() {
		$option_names = array(
			self::get_table_version_option_name( 'links' ),
			self::get_table_version_option_name( 'keywords' ),
			self::get_table_version_option_name( 'links-keywords' ),
		);

		// Delete each option.
		foreach ( $option_names as $option_name ) {
			delete_option( $option_name );
		}
	}

	/**
	 * Drop plugin's tables from database.
	 */
	public static function drop_tables() {
		/**
		 * @var Object $wpdb Global database object.
		 */
		global $wpdb;

		// Availible custom table names.
		// Note, remove junction table first to avoid issues.
		$table_names = array(
			self::get_table_name( 'links-keywords' ),
			self::get_table_name( 'links' ),
			self::get_table_name( 'keywords' ),
		);

		// Disable `PreparedSQL` since there are no inputs from users.
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		foreach ( $table_names as $table_name ) {
			$drop_sql = "DROP TABLE IF EXISTS {$table_name}";
			$wpdb->query( $drop_sql );
		}
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	}
}
