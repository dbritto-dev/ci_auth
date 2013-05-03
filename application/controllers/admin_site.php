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
 * admin_site Class
 *
 * This class is a controller to authentication
 * Esta clase es una controlador para la autenticacion
 *
 * @package		CodeIgniter_Auth
 * @subpackage	Contollers
 * @category	Controllers
 * @author		@danilobrinu
 * @link		https://bitbucket.org/danilobrinu/codeigniter_auth/wiki/Home
 */
class Admin_site extends CI_Controller {

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
	}

	function index()
	{
		$this->load->library('login_required');
		$data['content'] = 'auth/index.html';
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

	function users()
	{
		$this->load->library('login_required');
		$this->grocery_crud->set_table('auth_user');
		$this->grocery_crud->required_fields('username', 'password', 'email');
		$this->grocery_crud->change_field_type('password', 'password');
		$this->grocery_crud->edit_fields('username', 'first_name', 'last_name', 'password', 'is_staff', 'is_active', 'is_superuser');
		$this->grocery_crud->callback_before_insert(array($this, 'encrypt_password'));
		$this->grocery_crud->callback_before_update(array($this, 'encrypt_password'));
		$data = $this->grocery_crud->render();
		$data->content = 'auth/crud.html';
		$this->load->view('base.html', $data);
	}

	private function encrypt_password($request) {
		$request['password'] = sha1($request['password']);
		return $request;
	}

	function groups()
	{
		$this->load->library('login_required');
		$this->grocery_crud->set_table('auth_group');
		$data = $this->grocery_crud->render();
		$data->content = 'auth/crud.html';
		$this->load->view('base.html', $data);
	}

	function permissions()
	{
		$this->load->library('login_required');
		$this->grocery_crud->set_table('auth_permission');
		$data = $this->grocery_crud->render();
		$data->content = 'auth/crud.html';
		$this->load->view('base.html', $data);
	}

	function users_groups()
	{
		$this->load->library('login_required');
		$this->grocery_crud->set_table('auth_user_groups');
		$this->grocery_crud->set_relation('user_id','auth_user','username');
		$this->grocery_crud->set_relation('group_id','auth_group','name');
		$data = $this->grocery_crud->render();
		$data->content = 'auth/crud.html';
		$this->load->view('base.html', $data);
	}

	function groups_permissions()
	{
		$this->load->library('login_required');
		$this->grocery_crud->set_table('auth_group_permissions');
		$this->grocery_crud->set_relation('group_id','auth_group','name');
		$this->grocery_crud->set_relation('permission_id','auth_permission','name');
		$data = $this->grocery_crud->render();
		$data->content = 'auth/crud.html';
		$this->load->view('base.html', $data);
	}

	function users_permissions()
	{
		$this->load->library('login_required');
		$this->grocery_crud->set_table('auth_user_user_permissions');
		$this->grocery_crud->set_relation('user_id','auth_user','username');
		$this->grocery_crud->set_relation('permission_id','auth_permission','name');
		$data = $this->grocery_crud->render();
		$data->content = 'auth/crud.html';
		$this->load->view('base.html', $data);
	}

	/**
	 * login
	 *
	 * This is a function to login
	 * Es una funcion para el inicio de sesion
	 *
	 * @access	public
	 */
	function login()
	{
		/**
		 * Ask if REQUEST_METHOD is POST
		 * Pregunta si REQUEST_METHOD es POST
		 */
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			/**
			 * Getting data of the post
			 * Tomado los datos del post
			 */
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			/**
			 * Getting array to session
			 * Tomando el array para la sesion
			 */
			$user = $this->auth->authenticate($username, $password);

			if($user['is_authenticated'] == TRUE)
			{
				/**
				 * Setting array to login of auth
				 * Enviado el array para el inicio de sesion de la autenticacion
				 */
				if($user['is_active'] == TRUE)
				{
					$this->auth->login($user);
				}
				else
				{
					redirect('admin_site/login', 'refresh');
				}
			}
			else
			{
				redirect('admin_site/login', 'refresh');
			}
		}
		else
		{
			/**
			 * Setting data to config view
			 * Definiendo los datos de la configuracion de la vista
			 */
			$data['content'] = 'auth/login.html';
			/**
			 * Loading view with config to view
			 * Cargando la vista con la configuracion para la vista
			 */
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
	}

	/**
	 * logout
	 *
	 * This is a function to logout
	 * Es una funcion para el cierre de sesion
	 *
	 * @access	public
	 */
	function logout()
	{
		/**
		 * Logout
		 * Cerrando sesion
		 */
		$this->auth->logout();
	}

}

/* End of file auth_users.php */
/* Location: ./application/controllers/auth_users.php */
