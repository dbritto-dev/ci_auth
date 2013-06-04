<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */


/* ==================================================================================================== */

/**
 * Syndb Class
 *
 * This class create tables to authentication
 * Esta clase crea tablas para la autenticacion
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Libraries
 * @category	Libraries
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Syncdb {

	/**
	 * Constructor
	 *
	 * Load instance CodeIgniter (get_instance) for load libraries, helpers and others necessary files.
	 * Carga la instancia de CodeIgniter (get_instance) para cargar librerias, ayudantes y otros archivos necesarios.
	 *
	 * @access public
	 */
	function __construct()
	{
		/**
		 * Getting instance of CodeIgniter necessary to load all files of the Core of CodeIgniter
		 * Tomando la instancia de CodeIgniter necesaria para cargar todos los archivos del nucleo de CodeIgniter
		 */
		$this->CI =& get_instance();

		/**
		 * Loading dbforge create for meta data of tables and fields
		 * Cargando dbforge para crear metadatos de tablas y campos
		 */
		$this->CI->load->dbforge();
		/**
		 * Loading database for SQL queries
		 * Cargando database para consultas SQL
		 */
		$this->CI->load->database();

		/**
		 * Loading function syncdb
		 * Cargando funcion syncdb
		 */
		$this->syncdb();
	}

	/**
	 * This function load other functions to create tables authentication
	 * Esta funcion cargar otras funciones para crear las tablas de autenticacion
	 */
	function syncdb()
	{
		/**
		 * Ask if table auth_user not exists to create
		 * Pregunta si la tabla auth_user no existe para crearla
		 */
		if($this->CI->db->table_exists("auth_user") === FALSE) { $this->auth_user(); }
		/**
		 * Ask if table auth_user not exists to create
		 * Pregunta si la tabla auth_group no existe para crearla
		 */
		if($this->CI->db->table_exists("auth_group") === FALSE) { $this->auth_group(); }
		/**
		 * Ask if table auth_user not exists to create
		 * Pregunta si la tabla auth_permission no existe para crearla
		 */
		if($this->CI->db->table_exists("auth_permission") === FALSE) { $this->auth_permission(); }
		/**
		 * Ask if table auth_user not exists to create
		 * Pregunta si la tabla auth_user_groups no existe para crearla
		 */
		if($this->CI->db->table_exists("auth_user_groups") === FALSE) { $this->auth_user_groups(); }
		/**
		 * Ask if table auth_user not exists to create
		 * Pregunta si la tabla auth_user_user_permissions no existe para crearla
		 */
		if($this->CI->db->table_exists("auth_user_user_permissions") === FALSE) { $this->auth_user_user_permissions(); }
		/**
		 * Ask if table auth_user not exists to create
		 * Pregunta si la tabla auth_group_permissions no existe para crearla
		 */
		if($this->CI->db->table_exists("auth_group_permissions") === FALSE) { $this->auth_group_permissions(); }
		/**
		 * Initialize data of superuser
		 * Inicializando datos de superusuario
		 */
		if($this->CI->db->count_all("auth_user") == 0) { $this->initialize(); }
	}

	function auth_user()
	{
		/**
		 * Setting fields and attributes for table auth_user
		 * Defininiedo los campos y sus atributos para la tabla auth_user
		 */
		$fields = array(
			"id" => array(
				"type" => "INT",
				"constraint" => "11",
				"auto_increment" => TRUE
				),
			"username" => array(
				"type" => "VARCHAR",
				"constraint" => "30"
				),
			"first_name" => array(
				"type" => "VARCHAR",
				"constraint" => "30",
				"default" => ""
				),
			"last_name" => array(
				"type" => "VARCHAR",
				"constraint" => "30",
				"default" => ""
				),
			"email" => array(
				"type" => "VARCHAR",
				"constraint" => "75",
				),
			"password" => array(
				"type" => "VARCHAR",
				"constraint" => "128",
				),
			"is_staff" => array(
				"type" => "TINYINT",
				"constraint" => "1",
				"default" => 0
				),
			"is_active" => array(
				"type" => "TINYINT",
				"constraint" => "1",
				"default" => 0
				),
			"is_superuser" => array(
				"type" => "TINYINT",
				"constraint" => "1",
				"default" => 0
				),
			"last_login" => array(
				"type" => "DATETIME"
				),
			"date_joined" => array(
				"type" => "DATETIME"
				)
		);

		/**
		 * Add fiels to table auth_user
		 * Agregando los campos a la tabla auth_user
		 */
		$this->CI->dbforge->add_field($fields);
		/**
		 * Add primary key
		 * Agregando la clave primaria
		 */
		$this->CI->dbforge->add_key("id", TRUE);
		/**
		 * Create table auth_user
		 * Creando la tabla auth_user
		 */
		$this->CI->dbforge->create_table('auth_user');

	}

	function auth_group()
	{
		/**
		 * Setting fields and attributes for table auth_group
		 * Defininiedo los campos y sus atributos para la tabla auth_group
		 */
		$fields = array(
			"id" => array(
				"type" => "INT",
				"constraint" => "11",
				"auto_increment" => TRUE
				),
			"name" => array(
				"type" => "VARCHAR",
				"constraint" => "80"
				)
		);

		/**
		 * Add fiels to table auth_group
		 * Agregando los campos a la tabla auth_group
		 */
		$this->CI->dbforge->add_field($fields);
		/**
		 * Add primary key
		 * Agregando la clave primaria
		 */
		$this->CI->dbforge->add_key("id", TRUE);
		/**
		 * Create table auth_group
		 * Creando la tabla auth_group
		 */
		$this->CI->dbforge->create_table("auth_group");

	}

	function auth_permission()
	{
		/**
		 * Setting fields and attributes for table auth_permission
		 * Defininiedo los campos y sus atributos para la tabla auth_permission
		 */
		$fields = array(
			"id" => array(
				"type" => "INT",
				"constraint" => "11",
				"auto_increment" => TRUE
				),
			"name" => array(
				"type" => "VARCHAR",
				"constraint" => "50",
				),
			"codename" => array(
				"type" => "VARCHAR",
				"constraint" => "100"
				)
		);

		/**
		 * Add fiels to table auth_permission
		 * Agregando los campos a la tabla auth_permission
		 */
		$this->CI->dbforge->add_field($fields);
		/**
		 * Add primary key
		 * Agregando la clave primaria
		 */
		$this->CI->dbforge->add_key("id", TRUE);
		/**
		 * Create table auth_permission
		 * Creando la tabla auth_permission
		 */
		$this->CI->dbforge->create_table("auth_permission");

	}

	function auth_user_groups()
	{
		/**
		 * Setting fields and attributes for table auth_permission
		 * Defininiedo los campos y sus atributos para la tabla auth_permission
		 */
		$fields = array(
			"id" => array(
				"type" => "INT",
				"constraint" => "11",
				"auto_increment" => TRUE
				),
			"user_id" => array(
				"type" => "INT",
				"constraint" => "11"
				),
			"group_id" => array(
				"type" => "INT",
				"constraint" => "11"
				)
		);

		/**
		 * Add fiels to table auth_user_groups
		 * Agregando los campos a la tabla auth_user_groups
		 */
		$this->CI->dbforge->add_field($fields);
		/**
		 * Add primary key
		 * Agregando la clave primaria
		 */
		$this->CI->dbforge->add_key("id", TRUE);
		/**
		 * Create table auth_user_groups
		 * Creando la tabla auth_user_groups
		 */
		$this->CI->dbforge->create_table("auth_user_groups");
		/**
		 * Add foreign key
		 * Agregando la clave externa
		 */
		$this->add_foreign_key("auth_user_groups", "user_id", "auth_user", "id");
		/**
		 * Add foreign key
		 * Agregando la clave externa
		 */
		$this->add_foreign_key("auth_user_groups", "group_id", "auth_group", "id");

	}

	function auth_user_user_permissions()
	{
		/**
		 * Setting fields and attributes for table auth_permission
		 * Defininiedo los campos y sus atributos para la tabla auth_permission
		 */
		$fields = array(
			"id" => array(
				"type" => "INT",
				"constraint" => "11",
				"auto_increment" => TRUE
				),
			"user_id" => array(
				"type" => "INT",
				"constraint" => "11"
				),
			"permission_id" => array(
				"type" => "INT",
				"constraint" => "11"
				)
		);

		/**
		 * Add fiels to table auth_user_groups
		 * Agregando los campos a la tabla auth_user_groups
		 */
		$this->CI->dbforge->add_field($fields);
		/**
		 * Add primary key
		 * Agregando la clave primaria
		 */
		$this->CI->dbforge->add_key("id", TRUE);
		/**
		 * Create table auth_user_user_permissions
		 * Creando la tabla auth_user_user_permissions
		 */
		$this->CI->dbforge->create_table("auth_user_user_permissions");
		/**
		 * Add foreign key
		 * Agregando la clave externa
		 */
		$this->add_foreign_key("auth_user_user_permissions", "user_id", "auth_user", "id");
		/**
		 * Add foreign key
		 * Agregando la clave externa
		 */
		$this->add_foreign_key("auth_user_user_permissions", "permission_id", "auth_permission", "id");

	}

	function auth_group_permissions()
	{
		/**
		 * Setting fields and attributes for table auth_permission
		 * Defininiedo los campos y sus atributos para la tabla auth_permission
		 */
		$fields = array(
			"id" => array(
				"type" => "INT",
				"constraint" => "11",
				"auto_increment" => TRUE
				),
			"group_id" => array(
				"type" => "INT",
				"constraint" => "11"
				),
			"permission_id" => array(
				"type" => "INT",
				"constraint" => "11"
				)
		);

		/**
		 * Add fiels to table auth_user_groups
		 * Agregando los campos a la tabla auth_user_groups
		 */
		$this->CI->dbforge->add_field($fields);
		/**
		 * Add primary key
		 * Agregando la clave primaria
		 */
		$this->CI->dbforge->add_key("id", TRUE);
		/**
		 * Create table auth_group_permissions
		 * Creando la tabla auth_group_permissions
		 */
		$this->CI->dbforge->create_table("auth_group_permissions");
		/**
		 * Add foreign key
		 * Agregando la clave externa
		 */
		$this->add_foreign_key("auth_group_permissions", "group_id", "auth_group", "id");
		/**
		 * Add foreign key
		 * Agregando la clave externa
		 */
		$this->add_foreign_key("auth_group_permissions", "permission_id", "auth_permission", "id");

	}

	/**
	 * This function alters tables for add foreign keys
	 * Esta función modifica las tablas para añadir claves externas
	 */
	function add_foreign_key($table, $field, $table_reference, $field_reference)
	{
		/**
		 * Executing sentence for alter table to add foreign keys
		 * Ejecutando sentencia para alterar tabla y agregar claves externas
		 */
		$this->CI->db->query(sprintf("ALTER TABLE %s ADD FOREIGN KEY (%s) REFERENCES %s (%s)", $table, $field, $table_reference, $field_reference));
	}

	function initialize()
	{
		// password encrypted sha1
		$superuser = array(
			"username" => "admin",
			"first_name" => "first name",
			"last_name" => "last name",
			"email" => "e-mail@email.com",
			"password" => "40bd001563085fc35165329ea1ff5c5ecbdbbeef",
			"is_staff" => 1,
			"is_active" => 1,
			"is_superuser" => 1,
			"last_login" => "0000-0-0 00:00:00",
			"date_joined" => date("Y-m-d h:i:s")
		);
		$this->CI->db->insert("auth_user", $superuser);

		$group = array("name" => "admin");
		$this->CI->db->insert("auth_group", $group);

		$permissions = [];
		$permissions[] = array("name" => "Can add permission","codename" => "add_permission");
		$permissions[] = array("name" => "Can change permission", "codename" => "change_permission");
		$permissions[] = array("name" => "Can delete permission", "codename" => "delete_permission");
		$permissions[] = array("name" => "Can add group", "codename" => "add_group");
		$permissions[] = array("name" => "Can change group", "codename" => "change_group");
		$permissions[] = array("name" => "Can delete group", "codename" => "delete_group");
		$permissions[] = array("name" => "Can add user", "codename" => "add_user");
		$permissions[] = array("name" => "Can change user", "codename" => "change_user");
		$permissions[] = array("name" => "Can delete user", "codename" => "delete_user");

		$this->CI->db->insert_batch("auth_permission", $permissions);

		$group_permissions = [];
		$group_permissions[] = array("group_id" => 1, "permission_id" => 1);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 2);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 3);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 4);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 5);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 6);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 7);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 8);
		$group_permissions[] = array("group_id" => 1, "permission_id" => 9);

		$this->CI->db->insert_batch("auth_group_permissions", $group_permissions);

		$user_group = array("user_id" => 1, "group_id" => 1);
		$this->CI->db->insert("auth_user_groups", $user_group);

		$user_permissions = [];
		$user_permissions[] = array("user_id" => 1, "permission_id" => 1);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 2);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 3);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 3);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 4);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 5);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 6);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 7);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 8);
		$user_permissions[] = array("user_id" => 1, "permission_id" => 9);

		$this->CI->db->insert_batch("auth_user_user_permissions", $user_permissions);
	}

}

/* End of file sycndb.php */
/* Location: ./application/helpers/sycndb.php */
