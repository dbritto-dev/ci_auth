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
 * Pass_test_required Class
 *
 * This class restrict access if $function_test is false
 * Esta clase restringir el acceso si $ function_test es falsa
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Libraries
 * @category	Libraries
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Pass_test_required {
  
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
		 * Array of necessary helpers
		 * Arreglo de ayudantes necesarios
		 */
        $helpers = array("url");
        /**
		 * Loading libraries
		 * Cargando las librerias 
		 */
        $this->CI->load->helper($helpers);
	}


	/**
	 * authenticate
	 *
	 * This is a function redirect to $redirect_to if $function_test is false
	 * Esta funcionar redirecciona a $redirect_to is $funcion_test es falso
	 *
	 * @access	public
	 * @param	boolean
	 * @param	string
	 */
	function test($function_test, $redirect_to = "main")
	{
		/**
		 * Ask is $function_test is false, if is false redirect to $redirect_to
		 * Pregunta si $function_test es falso, si es falso redirecciona a $redirect_to
		 */
		if($function_test === FALSE)
		{
			/**
			 * Redirect to $redirec_to (default = "main")
			 * Redirecciona a $redirect_to (por defecto = "main")
			 */
			redirect($redirect_to, "refresh");
		}
	}

}

/* End of file Pass_test_required.php */
/* Location: ./application/libraries/Pass_test_required.php */
