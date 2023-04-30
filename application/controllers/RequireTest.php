<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This controller contains the general home site pages.
 *
 */

class RequireTest extends FrontendController
{
	public function standard_head_html()
	{
		// standard_head_html:
		$output = '';
		// Get the theme javascript head and footer
		$output = $this->get_head_code();
		// List alternate versions.
		return $output;
	}

	/**
	 * Site Default Landing Page.
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
		//$output = $this->get_head_code();
debugBreak();
		$output = $this->standard_head_html();

		$tmp = $this->sesskey();
		// Load via MY_Controller
		$this->set_page_title('Your Custom Page Title');
		$this->set_meta_description('Your Custom Meta Description.');
		$this->load_css(array(
			// relative path to your page specific css eg: /css/example.css
			'css/test.css'
		));
		$this->load_js(array(
			// relative path to your page specific javascript eg: /js/example.js
			// Remember we are going to handle the JS with require.js
		));

		// Do something here...
		$foo = 'bar';

		// Assign your data to an array
		$data = array(
			'foo' => $foo
		);

		// relative path to your views file.php eg: index.php or custom/index.php
		// pass your data to the view
		$this->load->view('/general/index', $data);
	}

	/**
	 *
	 * Example of using another layout and view template for your view if needed
	 *
	 * @access public
	 * @return void
	 *
	*/
	public function highlight(){
		$this->set_page_title('Example of another page');
		$this->set_meta_description('Example of another page Meta Description.');

		// Set another layout
		$this->layout = 'highlight';

		// Do something here...
		// $foo = 'bar';

		// Assign your data to an array
		$data = array(
			//'baz' => $foo
		);

		// Load another view and pass the data
		$this->load->view('/example/index', $data);
	}

	function get_head_code() 
	{
		// From Moolde: outputrequirementslib.php
		$this->load->helper('common');
		$this->load->helper('weblib_29');
		//$this->load->library('js_writer');
		$tmp = $this->init_requirements_data();
		$output = '';
		// Set up the M namespace.
        $js = "var M = {}; M.yui = {};\n";
		$js .= "M.pageloadstarttime = new Date();\n";

		$js .= Js_writer::set_variable('M.cfg', $this->M_cfg, false);
		$js .= $this->YUI_config->get_config_functions();
        $js .= js_writer::set_variable('YUI_config', $this->YUI_config, false) . "\n";
        $js .= "M.yui.loader = {modules: {}};\n"; // Backwards compatibility only, not used any more.
        $js = $this->YUI_config->update_header_js($js);
		$output .= html_writer::script($js);
//debugBreak();
		$output .= $this->get_yui3lib_headcode();
		$output .= $this->get_jquery_headcode();
		// Now theme CSS + custom CSS in this specific order.
//debugBreak();
        $output .= $this->get_css_code();
		// Link our main JS file, all core stuff should be there.
        //$output .= html_writer::script('', $this->js_fix_url('/lib/javascript-static.js'));
//debugBreak();
        $output .= html_writer::script('', '/lib/javascript-static.js'); // ToDo: js_fix_url

		// Add variables.
        if ($this->jsinitvariables['head']) {
            $js = '';
            foreach ($this->jsinitvariables['head'] as $data) {
                list($var, $value) = $data;
                $js .= js_writer::set_variable($var, $value, true);
            }
            $output .= html_writer::script($js);
        }
		// All the other linked things from HEAD - there should be as few as possible.
        if ($this->jsincludes['head']) {
            foreach ($this->jsincludes['head'] as $url) {
                $output .= html_writer::script('', $url);
            }
        }
		// Mark head sending done, it is not possible to anything there.
        $this->headdone = true;
		return $output;
    }
}