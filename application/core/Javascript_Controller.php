<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter-HMVC
 *
 * @package    CodeIgniter-HMVC
 * @author     Martin <martin@click-ap.com>
 * @copyright  2023 Click-AP {@link https://www.click-ap.com}
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @version    GIT: $Id$
 * @since      Version 0.0.1
 */

class JavascriptController extends MY_Controller
{
    public $CI;

    /**
     * An array of variables to be passed through to the view, layout, ....
     */
    protected $data = array();

    /**
     * [__construct description]
     *
     * @method __construct
     */
    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $this->load->helper('configonlylib');
		$this->load->helper('jslib');
		$this->load->library(['core/minify']);
        #$filterQueryString = filterQueryString($_SERVER['QUERY_STRING']);
        //$_SERVER['REQUEST_URI']
        //$filterQueryString = filterQueryString($_SERVER['REQUEST_URI']);
//debugBreak();        
		//parse_str($_SERVER['QUERY_STRING'], $_GET); 


        // CI profiler
        $this->output->enable_profiler(true);

        // This function returns the main CodeIgniter object.
        // Normally, to call any of the available CodeIgniter object or pre defined library classes then you need to declare.
        $CI =& get_instance();

        //Example data
        // Site name
        $this->data['sitename'] = 'CodeIgniter-HMVC';

        //Example data
        // Browser tab
        $this->data['site_title'] = ucfirst('Admin Dashboard');
    }

    /**
     * [render_page description]
     *
     * @method render_page
     *
     * @param  [type]      $view [description]
     * @param  [type]      $data [description]
     *
     * @return [type]            [description]
     */
    protected function render_page($view, $data)
    {
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/main_header', $this->data);
        $this->load->view('templates/main_sidebar', $this->data);
        $this->load->view($view, $this->data);
        $this->load->view('templates/footer', $this->data);
        $this->load->view('templates/control_sidebar', $this->data);
    }
}
