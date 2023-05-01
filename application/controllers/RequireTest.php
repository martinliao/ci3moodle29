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
//debugBreak();
		$standard_footer_html = $this->get_end_code();

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
			'standard_head_html' => $standard_head_html,
			'standard_footer_html' => $standard_footer_html
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

		$output .= $this->get_yui3lib_headcode();
		$output .= $this->get_jquery_headcode();
		// Now theme CSS + custom CSS in this specific order.

        $output .= $this->get_css_code();
		// Link our main JS file, all core stuff should be there.
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

	function get_end_code() {
        global $CFG;
        $output = '';
//debugBreak();
        // Set the log level for the JS logging.
        $logconfig = new stdClass();
        $logconfig->level = 'warn';
        if ($CFG->debugdeveloper) {
            $logconfig->level = 'trace';
        }
        $this->js_call_amd('core/log', 'setConfig', array($logconfig));

        // Call amd init functions.
        $output .= $this->get_amd_footercode();

        // Add other requested modules.
        $output .= $this->get_extra_modules_code();

        $this->js_init_code('M.util.js_complete("init");', true);

        // All the other linked scripts - there should be as few as possible.
        if ($this->jsincludes['footer']) {
            foreach ($this->jsincludes['footer'] as $url) {
                $output .= html_writer::script('', $url);
            }
        }

        // ToDo: Add all needed strings.
        // First add core strings required for some dialogues.
        /*$this->strings_for_js(array(
            'confirm',
            'yes',
            'no',
            'areyousure',
            'closebuttontitle',
            'unknownerror',
        ), 'moodle');
        if (!empty($this->stringsforjs)) {
            $strings = array();
            foreach ($this->stringsforjs as $component=>$v) {
                foreach($v as $indentifier => $langstring) {
                    $strings[$component][$indentifier] = $langstring->out();
                }
            }
            $output .= html_writer::script(js_writer::set_variable('M.str', $strings));
        }/** */

        // Add variables.
        if ($this->jsinitvariables['footer']) {
            $js = '';
            foreach ($this->jsinitvariables['footer'] as $data) {
                list($var, $value) = $data;
                $js .= js_writer::set_variable($var, $value, true);
            }
            $output .= html_writer::script($js);
        }

        $inyuijs = $this->get_javascript_code(false);
        $ondomreadyjs = $this->get_javascript_code(true);
        $jsinit = $this->get_javascript_init_code();
        $handlersjs = $this->get_event_handler_code();

        // There is no global Y, make sure it is available in your scope.
        $js = "YUI().use('node', function(Y) {\n{$inyuijs}{$ondomreadyjs}{$jsinit}{$handlersjs}\n});";

        $output .= html_writer::script($js);

        return $output;
    }
}