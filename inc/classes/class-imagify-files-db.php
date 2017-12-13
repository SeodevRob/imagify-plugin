<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/**
 * DB class that handles files in "custom folders".
 *
 * @since  1.7
 * @author Grégory Viguier
 */
class Imagify_Files_DB extends Imagify_Abstract_DB {

	/**
	 * Class version.
	 *
	 * @var string
	 */
	const VERSION = '1.0';

	/**
	 * The single instance of the class.
	 *
	 * @since  1.7
	 * @access protected
	 *
	 * @var object
	 */
	protected static $_instance;

	/**
	 * The suffix used in the name of the database table (so, without the wpdb prefix).
	 *
	 * @var    string
	 * @since  1.7
	 * @access protected
	 */
	protected $table = 'imagify_files';

	/**
	 * The version of our database table.
	 *
	 * @var    int
	 * @since  1.7
	 * @access protected
	 */
	protected $table_version = 10;

	/**
	 * Tell if the table is the same for each site of a Multisite.
	 *
	 * @var    bool
	 * @since  1.7
	 * @access protected
	 */
	protected $table_is_global = true;

	/**
	 * The name of the primary column.
	 *
	 * @var    string
	 * @since  1.7
	 * @access protected
	 */
	protected $primary_key = 'file_id';

	/**
	 * Get the main Instance.
	 *
	 * @since  1.7
	 * @access public
	 * @author Grégory Viguier
	 *
	 * @return object Main instance.
	 */
	public static function get_instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Whitelist of columns.
	 *
	 * @since  1.7
	 * @access public
	 * @author Grégory Viguier
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'file_id'            => '%d',
			'folder_id'          => '%d',
			'path'               => '%s',
			'width'              => '%d',
			'height'             => '%d',
			'original_size'      => '%d',
			'original_hash'      => '%s',
			'optimized_size'     => '%d',
			'optimized_hash'     => '%s',
			'percent'            => '%d',
			'optimization_level' => '%d',
			'status'             => '%s',
			'error'              => '%s',
			'modified'           => '%d',
		);
	}

	/**
	 * Default column values.
	 *
	 * @since  1.7
	 * @access public
	 * @author Grégory Viguier
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'file_id'            => 0,
			'folder_id'          => 0,
			'path'               => '',
			'width'              => 0,
			'height'             => 0,
			'original_size'      => 0,
			'original_hash'      => '',
			'optimized_size'     => null,
			'optimized_hash'     => null,
			'percent'            => null,
			'optimization_level' => null,
			'status'             => null,
			'error'              => null,
			'modified'           => null,
		);
	}

	/**
	 * Get the query to create the table fields.
	 *
	 * @since  1.7
	 * @access protected
	 * @author Grégory Viguier
	 *
	 * @return string
	 */
	protected function get_table_schema() {
		return "
			file_id bigint(20) unsigned NOT NULL auto_increment,
			folder_id bigint(20) unsigned NOT NULL default 0,
			path varchar(191) NOT NULL default '',
			width int(5) NOT NULL default 0,
			height int(5) NOT NULL default 0,
			original_size int(10) NOT NULL default 0,
			original_hash varchar(32) NOT NULL default '',
			optimized_size int(10) default NULL,
			optimized_hash varchar(32) default NULL,
			percent int(2) unsigned default NULL,
			optimization_level int(1) default NULL,
			status varchar(20) default NULL,
			error varchar(100) default NULL,
			modified int(1) default NULL,
			PRIMARY KEY (file_id),
			UNIQUE KEY path (path),
			KEY folder_id (folder_id),
			KEY optimization_level (optimization_level),
			KEY status (status),
			KEY modified (modified)";
	}
}
