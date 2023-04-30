<?php
class Layout
{
	private $CI;
	private $theme, $layout;

	//public function __construct()
	public function __construct($theme = 'common', $layout = 'layout_main')
	{
		$this->CI = &get_instance();
		if (is_array($theme)) {
            $this->layout = $theme['layout'];
			$this->theme = $theme['theme'];
        } else {
			//$this->setLayout('common/layout_main');
			$this->theme = $theme;
			$this->layout = $layout;
		}
	}

	public function setLayout($layout)
	{
		$this->layout = $layout;
		return $this;
	}

	public function setTheme($theme)
	{
		$this->theme = $theme;
	}

	public function view($view, $data = array(), $return = false)
	{
		$data['site'] = $this->CI->site;
		$data['base_url'] = base_url('/');
//debugBreak();
		//$data['_MENU'] = array();
		//if (isset($this->CI->data['_MENU']))
		//	$data['_MENU'] = $this->CI->data['_MENU'];

		$data['_CONF'] = array();
		//if (isset($this->CI->data['_SETTING']))
		//	$data['_SETTING'] = $this->CI->data['_SETTING'];

		//$data['_JSON'] = array();
		//if (isset($this->CI->data['_JSON']))
		//	$data['_JSON'] = $this->CI->data['_JSON'];
		//unset($this->CI->data);

		$data['__header'] = $this->CI->load->view("{$this->theme}/header", $data, true);
		$data['__footer'] = $this->CI->load->view("{$this->theme}/footer", $data, true);

		if (is_array($view)) {
			$data['__content'] = '';
			foreach ($view as $v) {
				$data['__content'] .= $this->CI->load->view($v, $data, true);
			}
		} elseif (!empty($view)) {
			// $data['__content'] = $this->CI->load->view($view, $data, true);
			$data['__content'] = $this->CI->load->view($view, $data, true);
		} else {
			$data['__content'] = '';
		}

		if ($return) {
			$output = $this->CI->load->view($this->theme . '/' . $this->layout, $data, true);
			return $output;
		} else {
			$this->CI->load->view($this->theme . '/' . $this->layout, $data, false);
		}
	}
}
