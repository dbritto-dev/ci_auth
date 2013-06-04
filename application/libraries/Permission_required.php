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
 * Permision_required Class
 *
 * This class restricts access to authenticated users if not have permission
 * Esta clase restringe el acceso a los usuarios autenticados si no tienen permiso
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Libraries
 * @category	Libraries
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Permission_required {

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
	 * has_permission
	 *
	 * This is a function restricts to authenticated users if no have permission
	 * Es una función que restringe a los usuarios autenticados que no tiene permiso
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	array
	 */
	function has_permission($permission, $redirect_to = "main")
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
		 * Initialize $user_permissions
		 * Inicializando $user_permissions
		 */
		$user_permissions = array();
		/**
		 * Selecting fields of the table auth_user_user_permissions
		 * Seleccionando los campos de la tabla auth_user_user_permissions
		 * Getting data of the table auth_user_user_permissions
		 * Tomando los datos de la tabla de auth_user_user_permissions
		 */
		$query_user_permissions = $this->CI->db->select("permission_id")->from("auth_user_user_permissions")->where(array("user_id" => $user_id))->get()->result_array();
		foreach ($query_user_permissions as $data) {
			/**
			 * Setting $user_permissions with $data
			 * Definiendo $user_permissions con los valores de $data
			 */
			$user_permissions[] = $data["permission_id"];
		}
		/**
		 * Setting $permission with $data
		 * Definiendo $permission con los valores de $data
		 */
		$permission = $this->CI->db->select("id")->from("auth_permission")->where(array("codename" => $permission))->limit(1,0);
	    $permission->ar_where = array("BINARY ".$permission->ar_where[0]); // hack to convert case sensitive query => add BINARY before fields
		$permission = $permission->get()->result_array();
		if(!empty($permission))
		{
			$permission = $permission[0]["id"];
		}
		else
		{
			$permission = NULL;
		}
		/**
		 * Ask if $permission are not in $user_permissions
		 * Pregunta si $permission no esta dentro de $user_permissions
		 */
		if(in_array($permission, $user_permissions) === FALSE)
		{
			/**
			 * Redirect to $redirect_to (default = "main")
			 * Redirecciona a $redirect_to (por defecto = "main")
			 */
			redirect($redirect_to, "refresh");
		}
	}

	/**
	 * has_permissions
	 *
	 * This is a function restricts access to authenticated users if no have permissions
	 * Es una función que restringe el acceso a usuarios autenticados que no tienes permisos
	 *
	 * @access	public
	 * @param	array
	 * @param	string
	 * @return	array
	 */
	function has_permissions($permissions = array(), $redirect_to = "main")
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
		 * Initialize $user_permissions
		 * Inicializando $user_permissions
		 */
		$user_permissions = array();
		/**
		 * Selecting fields of the table auth_user_user_permissions
		 * Seleccionando los campos de la tabla auth_user_user_permissions
		 */
		$this->CI->db->select("permission_id");
		/**
		 * Getting data of the auth_user_user_permissions
		 * Tomando los datos de la auth_user_user_permissions
		 */
		foreach ($this->CI->db->get_where("auth_user_user_permissions", array("user_id" => $user_id))->result() as $data) {
			/**
			 * Setting $user_permissions with $data
			 * Definiendo $user_permissions con los valores de $data
			 */
			$user_permissions[] = $data->permission_id;
		}
		/**
		 * Initialize $valid
		 * Inicializando $valid
		 */
		$valid = TRUE;
		foreach ($permissions as $permission) {
			/**
			 * Setting $permission with $data
			 * Definiendo $permission con los valores de $data
			 */
			$permission = $this->CI->db->select("id")->from("auth_permission")->where(array("codename" => $permission))->limit(1,0);
		    $permission->ar_where = array("BINARY ".$permission->ar_where[0]); // hack to convert case sensitive query => add BINARY before fields
			$permission = $permission->get()->result_array();
			if(!empty($permission))
			{
				$permission = $permission[0]["id"];
			}
			else
			{
				$permission = NULL;
			}
			/**
			 * Ask if $permission are not in $user_permissions
			 * Pregunta si $permission no esta dentro de $user_permissions
			 */
			if(in_array($permission, $user_permissions) === FALSE)
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

/* End of file Permission_required.php */
/* Location: ./application/libraries/Permission_required.php */
