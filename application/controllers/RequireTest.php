<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This controller contains the general home site pages.
 *
 */

//class RequireTest extends FrontendController
class RequireTest extends MoodleController
{

	public function index()
	{
		$standard_head_html = $this->get_head_code();

		// Load via MY_Controller
		$this->set_page_title('Your Custom Page Title');
		$this->set_meta_description('Your Custom Meta Description.');
		$this->load_css(array(

			'css/test.css'
		));
		$this->load_js(array(
		));

		// Do something here...
		$foo = 'bar';
		// Assign your data to an array
		$data = array(
			'foo' => $foo,
			'standard_head_html' => $standard_head_html
		);
		//$this->load->view('/general/index', $data);
		$this->theme = '_requirejs';
		$this->render_requirejs_page('general/index', $data);
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
debugBreak();
		$output .= html_writer::script('', $this->js_fix_url('/lib/javascript-static.js'));

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