<?php

/**
 *  如果要在 controller 切換, 只要在 load-view 前, 加上: $this->theme->set_layout('default'); 就可以切換 layout/theme;
 */
class Theme
{

    function __construct($layout = 'highlight', $theme = 'AdminLTE')
    {
//debugBreak();
        if (is_array($layout)) {
            $this->layout = $layout['layout'];
            $this->theme = $layout['theme'];
            #$this->theme = $this->config->item('theme');
        } else {
            $this->layout = $layout;
            $this->theme = $theme;
        }
        $this->obj = &get_instance();
        $this->data = array();
    }

    function set_data($key, $value)
    {
        $this->data[$key] = $value;
    }

    function view($view, $data = array())
    {
        $data = array_merge($this->data, $data);
        $data['content'] = $this->obj->load->view($this->theme . '/' . $view, $data, TRUE);
        $this->obj->load->view($this->theme . '/' . $this->layout, $data);
    }

    function set_layout($layout)
    {
        $this->layout = $layout;
    }

    function get_theme()
    {
        return $this->theme;
    }
}
