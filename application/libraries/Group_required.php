<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CI_auth
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */


/* ==================================================================================================== */

/**
 * Group_required Class
 *
 * This class restricts access to authenticated users if not have group
 * Esta clase restringe el acceso a los usuarios autenticados si no tienen grupo
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Libraries
 * @category	Libraries
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Group_required {

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
		 * Array of necessary libraries
		 * Arreglo de librerias necesarias
		 */
		$libraries = array("session");
		/**
		 * Loading libraries
		 * Cargando las librerias 
		 */
		$this->CI->load->library($libraries);

		/** 
		 * Array of necessary helpers
		 * Arreglo de ayudantes necesarios
		 */
		$helpers = array("url");
		/**
		 * Loading libraries
		 * Cargando las librerias 
		 */
		$this->CI->load->helper($helpers);

		/**
		 * Loading database for SQL queries
		 * Cargando database para consultas SQL
		 */
		$this->CI->load->database();
	}

	/**
	 * has_permissions
	 *
	 * This is a function restricts to authenticated users if no have group 
	 * Es una función restringe a los usuarios autenticados si no tienen grupo
	 *
	 * @access	public
	 * @param	array
	 * @param	string
	 * @return	array
	 */
	function has_group($group, $redirect_to = "main")
	{
		/**
		 * Get username to user_data of session data
		 * Obtener nombre de usuario para user_data de datos de la sesion
		 */
		$username = $this->CI->session->userdata("username");
		/**
		 * Setting $user_id with $data
		 * Definiendo $user_id con los valores de $data
		 */
		$user_id = $this->CI->db->select("id")->from("auth_user")->where(array("username" => $username))->limit(1,0)->get()->result_array()[0]["id"];
		/**
		 * Initialize $user_permissions_groups
		 * Inicializando $user_permissions_groups
		 */
		$user_permissions_groups = [];
		/**
		 * Getting data of the table auth_user_groups
		 * Tomando los datos de la tabla de auth_user_groups
		 */
		$query_user_permissions_groups = $this->CI->db->select("group_id")->from("auth_user_groups")->where(array("user_id" => $user_id))->get()->result_array();
		foreach ($query_user_permissions_groups as $data) {
			/**
			 * Setting $user_permissions_groups with $data
			 * Definiendo $user_permissions_groups con los valores de $data
			 */
			$user_permissions_groups[] = $data["group_id"];
		}
		/**
		 * Setting $group with $data
		 * Definiendo $group con los valores de $data
		 */
		$group = $this->CI->db->select("id")->from("auth_group")->where(array("name" => $group))->limit(1,0);
	    $group->ar_where = array("BINARY ".$group->ar_where[0]); // hack to convert case sensitive query => add BINARY before fields
		$group = $group->get()->result_array();
		if(!empty($group))
		{
			$group = $group[0]["id"];
		}
		else
		{
			$group = NULL;
		}
		/**
		 * Ask if $group are not in $user_permissions_groups
		 * Pregunta si $group no esta dentro de $user_permissions_groups
		 */
		if(in_array($group, $user_permissions_groups) === FALSE)
		{
			/**
			 * Redirect to $redirect_to (default = "main")
			 * Redirecciona a $redirect_to (por defecto = "main")
			 */
			redirect($redirect_to, "refresh");
		}
	}

	/**
	 * has_groups
	 *
	 * This is a function restricts to authenticate users if no have groups 
	 * Es una función restringe a los usuarios autenticados que no tiene grupos
	 *
	 * @access	public
	 * @param	array
	 * @param	string
	 * @return	array
	 */
	function has_groups($groups = array(), $redirect_to = "main")
	{
		/**
		 * Get username to user_data of session data
		 * Obtener nombre de usuario para user_data de datos de la sesion
		 */
		$username = $this->CI->session->userdata("username");
		/**
		 * Setting $user_id with $data
		 * Definiendo $user_id con los valores de $data
		 */
		$user_id = $this->CI->db->select("id")->from("auth_user")->where(array("username" => $username))->limit(1,0)->get()->result_array()[0]["id"];
		/**
		 * Initialize $user_permissions_groups
		 * Inicializando $user_permissions_groups
		 */
		$user_permissions_groups = array();
		/**
		 * Selecting fields of the table auth_user_groups
		 * Seleccionando los campos de la tabla auth_user_groups
		 */
		$this->CI->db->select("group_id");
		/**
		 * Getting data of the table auth_user_groups
		 * Tomando los datos de la tabla de auth_user_groups
		 */
		$query_user_permissions_groups = $this->CI->db->get_where("auth_user_groups", array("user_id" => $user_id))->result_array();
		foreach ($query_user_permissions_groups as $data) {
			/**
			 * Setting $user_permissions_groups with $data
			 * Definiendo $user_permissions_groups con los valores de $data
			 */
			$user_permissions_groups[] = $data["group_id"];
		}
		/**
		 * Initialize $valid
		 * Inicializando $valid
		 */
		$valid = TRUE;
		foreach ($groups as $group) {
			/**
			 * Selecting fields of the table auth_group
			 * Seleccionando los campos de la tabla auth_group
			 */
			$group = $this->CI->db->select("id")->from("auth_group")->where(array("name" => $group))->limit(1,0);
		    $group->ar_where = array("BINARY ".$group->ar_where[0]); // hack to convert case sensitive query => add BINARY before fields 
			$group = $group->get()->result_array();
			if(!empty($group))
			{
				$group = $group[0]["id"];
			}
			else
			{
				$group = NULL;
			}
			/**
			 * Ask if $group are not in $user_permissions_groups
			 * Pregunta si $group no esta dentro de $user_permissions_groups
			 */
			if(in_array($group, $user_permissions_groups) === FALSE)
			{
				/**
				 * Setting $valid with false
				 * Definiendo $valid con false
				 */
				$valid = FALSE;
			}
		}
		/**
		 * Ask if $valid is false, if false redirect to $redirect_to
		 * Pregunta si $valid es falso, si es falso redirecciona a $redirect_to
		 */
		if($valid === FALSE)
		{
			/**
			 * Redirect to $redirect_to (default = "main")
			 * Redirecciona a $redirect_to (por defecto = "main")
			 */
			redirect($redirect_to, "refresh");
		}
	}

}

/* End of file group_required.php */
/* Location: ./application/libraries/group_required.php */
