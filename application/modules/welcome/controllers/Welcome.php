<?php defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends MY_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	/**
	 * [__construct description]
	 *
	 * @method __construct
	 */
	public function __construct()
	{
		// Load the constructer from MY_Controller
		parent::__construct();
		$this->load->library('smarty_acl');
		$this->load->helper('url');
		$this->load->library('form_validation');
	}

	/**
	 * [index description]
	 *
	 * @method index
	 *
	 * @return [type] [description]
	 */
	public function index()
	{
		//
		$this->load->view('welcome_message');
	}

	public function importdatabase()
	{
		$this->load->library('migration');
		if ($this->migration->latest() === FALSE) {
			echo $this->migration->error_string();
		}
		$this->session->set_flashdata('success_msg', 'Database migrated successfully!');
		return redirect('/');
	}

	protected function logged_in()
	{
		if (!$this->smarty_acl->logged_in(FALSE)) {
			return redirect('login');
		}
	}

	public function account()
	{
		$this->logged_in();
		$this->load->view('account');
	}

	public function unauthorized()
	{
		echo 'UNAUTHORIZED ACCESS';
	}
}
