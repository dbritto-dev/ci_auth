<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ==================================================================================================== */

/**
 * Login_required Class
 *
 * This class restricts access to unauthenticated users
 * Esta clase restringe el acceso a los usuarios no autenticados
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Libraries
 * @category	Libraries
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Login_required {

	/**
	 * Constructor
	 *
	 * Load instance CodeIgniter (get_instance) for load libraries, helpers and others necessary files.
	 * Carga la instancia de CodeIgniter (get_instance) para cargar librerias, ayudantes y otros archivos necesarios.
	 *
	 * @access public
	 * @param  string
	 */
	function __construct($config = array("login_url" => "admin_site/login"))
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
		 * Asking if is an unauthenticated user
		 * Preguntar si es un usuario no autenticado
		 */
		if($this->is_authenticated() === FALSE)
		{
			/**
			 * Redirect to $redirect_to (default = "auth_users/login") the unauthenticated user
			 * redirecciona a $redirect_to (por defecto = "auth_users/login") al usuario no autenticado
			 */
			redirect($config['login_url'], "refresh");
		}
	}

	function is_authenticated()
	{
		$is_authenticated = $this->CI->session->userdata("is_authenticated");

		/**
		 * Asking if $is_authenticated is not isset or empty
		 * Preguntando si $is_authenticated no esta definicido o vacio
		 */
		if(empty($is_authenticated))
		{
			/**
			 * If TRUE return FALSE
			 * Si es verdad retorna FALSE
			 */
			return FALSE;
		}
		else
		{
			/**
			 * If FALSE return $is_authenticated
			 * Si es verdad retorna $is_authenticated
			 */
			return $is_authenticated;
		}
	}

}

/* End of file Login_required.php */
/* Location: ./application/libraries/Login_required.php */
