<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ==================================================================================================== */

/**
 * Main Class
 *
 * This class is a controller who implements all libraries of the CodeIgniter_Auth to authentication
 * Esta clase es una controlador que implementa todas las librerias de CodeIgniter_Auth para la autenticacion
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Contollers
 * @category	Controllers
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Main extends CI_Controller {

	/**
	 * Constructor
	 *
	 * Setting parent to not rewrite the extends of CI_Controller
	 * Configura parent para no sobreescribir la herencia de CI_Controller
	 *
	 * @access public
	 */
	function __construct()
	{
		/**
		 * Avoids rewrite the extens of CI_Controller
		 * Evita sobreescribir la herencia de CI_Controller
		 */
		parent::__construct();
		
		/** 
		 * Array of necessary libraries
		 * Arreglo de librerias necesarias
		 */
		$libraries = array('login_required', 'permission_required', 'group_required', 'pass_test_required');
		/**
		 * Loading libraries
		 * Cargando las librerias 
		 */
		$this->load->library($libraries);
	}

	/** 
	 * This site to default site
	 * Sito para el sitio por defecto
	 */
	function index()
	{
		$data['content'] = 'main.html';
		$data['js_files'] = array(
			base_url().'/grocery_crud/themes/datatables/js/jquery-1.6.2.min.js',
			base_url().'/grocery_crud/themes/datatables/js/cookies.js',
			base_url().'/grocery_crud/themes/datatables/js/flexigrid.min.js',
			base_url().'/grocery_crud/themes/datatables/js/jquery.form.js',
			base_url().'/grocery_crud/themes/datatables/js/jquery.numeric.js'
		);
		$data['css_files'] = array(
			'grocery_crud/themes/flexigrid/css/flexigrid.css'
		);
		$this->load->view('base.html', $data);
	}

	/** 
	 * This site to implement permission_required(library)
	 * Sito para implementar la libreria permission_required
	 */
	function site_add_user()
	{
		/**
		 * Implements has_permission of permission_required to restriction access view if no have permission
		 * Implementa has_permission de permission_required para restringir el acceso a la vista si no tiene permiso
		 */
		$this->permission_required->has_permission('add_user');
		/**
		 * Implements has_permissions of permission_required to restriction access view if no have permission
		 * Implementa has_permissions de permission_required para restringir el acceso a la vista si no tiene permisos
		 */
		//$this->permission_required->has_permissions(array('add_user', 'change_user'));
		echo 'Hello world, site is site add user';
	}

	/** 
	 * This site to implement group_required(library)
	 * Sito para el sitio por defecto
	 */
	function site_group_admin()
	{
		/**
		 * Implements has_group of group_required to restriction access view if no have group
		 * Implementa has_group de group_required para restringir el acceso a la vista si no tiene grupo
		 */
		$this->group_required->has_group('ADMIN');
		/**
		 * Implements has_groups of group_required to restriction access view if no have groups
		 * Implementa has_groups de group_required para restringir el acceso a la vista si no tiene grupos
		 */
		//$this->group_required->has_groups(array('ADMIN', 'asda'));
		echo 'Hello world, site is site group admin';
	}

	/** 
	 * This site to implement pass_test_required(library)
	 * Sito para el sitio por defecto
	 */
	function site_pass_test()
	{
		/**
		 * Implements test of pass_test_required to restrict access view if no pass test
		 * Implementa test de pass_test_required para restringir el acceso a la vista si no pasa el test
		 */
		$this->pass_test_required->test($this->_test());
		echo 'Hello world, site is site pass test';
	}

	private function _test()
	{
		return TRUE;
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
