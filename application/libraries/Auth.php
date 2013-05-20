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
 * Auth Class
 *
 * This class enable functions to manage authentication
 * these functions are authenticate, login and logout.
 *
 * Esta clase habilita funciones para manejar la autenticacion
 * Estas funciones son authenticate, login y logout.
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Libraries
 * @category	Libraries
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Auth {


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
	 * authenticate
	 *
	 * This is a function to return array with data for session
	 * Es una función que retorna un array con los datos para la sesion
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param   array
	 * @return	array
	 */
	function authenticate($user, $password, $others_parameters_for_session = array())
	{
		/**
		 * Setting array with data (username and password)
		 * Defininiendo un array con los datos (username y password)
		 */
		$data = array("username"=> $user, "password"=> sha1($password));

		/**
		 * Setting array with fields for session
		 * Defininiendo un array con los campos para la sesion
		 */
		$fields_for_session = array("username", "is_active", "is_staff", "is_superuser");
		/**
		 * Selecting fields for include session
		 * Seleccionando los campos para incluir en la sesion
		 */
		$this->CI->db->select(implode(", ", $fields_for_session));
		/**
		 * Getting data of the table auth
		 * Tomando los datos de la tabla de autenticacion
		 */
		$query = $this->CI->db->get_where("auth_user", $data, 1, 0);

		/**
		 * Initialize $user_data
		 * Inicializando $user_data
		 */
		$user_data = array();

		/**
		 * Asking number rows
		 * Preguntando el nro de filas
		 */
		if($query->num_rows() === 1)
		{
			/**
			 * Getting data and sending to $user_data
			 * Tomando los datos y enviandolos a $user_data
			 */
			foreach ($query->result_array() as $data) {
				/**
				 * Setting $user_data with $data
				 * Definiendo $user_data con los valores de $data
				 */
				$user_data = $data;
			}

			/**
			 * Merge $user_data with $others_parameters_for_session, to pass others parameters to session
			 * Combina $user_data con $otheres_parameters_for_session, para pasar otros parametros a la session
			 */
			$user_data = array_merge($user_data, $others_parameters_for_session);

			/**
			 * Setting $user_data["is_authenticated"] = TRUE
			 * Definiendo $user_data["is_authenticated"] = TRUE
			 */
			$user_data["is_authenticated"] = TRUE;
		}
		else
		{
			$user_data["is_authenticated"] = FALSE;
		}

		/**
		 * Return array ($user_data)
		 * Retornando array ($user_data)
		 */
		return $user_data;
	}


	/**
	 * login
	 *
	 * This is a function to create session with request data  and redirect
	 * Esta es una funcion para crear una session con los datos de la peticion y redirigir
	 *
	 * @access	public
	 * @param	array
	 * @param   string
	 */
	function login($request, $redirect_to = "admin_site")
	{
		/**
		 * Update last login
		 * Actualizar la ultima entrada
		 */
		$this->update_last_login($request['username']);
		/**
		 * Set userdata for session with $request and create
		 * Establece o define userdata para la session y la crea
		 */
		$this->CI->session->set_userdata($request);
		/**
		 * Redirect to $redirect_to (default = "admin_site")
		 * redirecciona a $redirect_to (por defecto = "admin_site")
		 */
		redirect($redirect_to, "refresh");
	}


	/**
	 * Update last login
	 * Actualizar la ultima entrada
	 */
	private function update_last_login($username)
	{
		$this->CI->db->where("username", $username);
		$this->CI->db->limit(1);
		$this->CI->db->update("auth_user", array("last_login" => date("Y-m-d h:i:s")));
	}


	/**
	 * logout
	 *
	 * This is a function to destroy session and redirect
	 * Esta es una funcion para destruir la sesion y redirigir
	 *
	 * @access	public
	 * @param	string
	 */
	function logout($redirect_to = "admin_site/login")
	{
		/**
		 * Destroying the session
		 * Destruyendo la sesion
		 */
		$this->CI->session->sess_destroy();
		/**
		 * Redirect to $redirect_to (default = "admin_site/login")
		 * redirecciona a $redirect_to (por defecto = "admin_site/login")
		 */
		redirect($redirect_to, "refresh");
	}

}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
