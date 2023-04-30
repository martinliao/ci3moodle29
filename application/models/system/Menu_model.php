<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends MY_Model
{
    protected $table = 'BS_menu';
    protected $pk = 'id';
    public $choices_port = array();
    public $choices_action = array();

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

        $this->choices_port = array(
            'admin' => '管理端',
        );

        $this->chocies_action = array(
            'add' => '新增',
            'view' => '瀏覽',
            'edit' => '編輯',
            'delete' => '刪除',
            'copy' => '複製',
        );

    }

    public function getFormDefault($user=array())
    {
        $data = array_merge(array(
            'parent_id' => 0,
            'action_id' => 0,
            'port' => 'admin',
            'icon' => '',
            'name' => '',
            'link' => '',
            'enable' => 0,
            'auth' => 1,
            'sort_order' => 0,
            'actions' => '',
        ), $user);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            'parent_id' => array(
                'field' => 'parent_id',
                'label' => 'Parent ID',
                // 'rules' => 'required|integer',
            ),
            'action_id' => array(
                'field' => 'action_id',
                'label' => 'Actions',
                // 'rules' => 'required|integer',
            ),
            'port' => array(
                'field' => 'port',
                'label' => 'Port Name',
                'rules' => 'required',
            ),
            'icon' => array(
                'field' => 'icon',
                'label' => 'Icon',
                'rules' => 'trim'
            ),
            'name' => array(
                'field' => 'name',
                'label' => 'Menu Name',
                'rules' => 'trim|required|max_length[100]'
            ),
            'link' => array(
                'field' => 'link',
                'label' => 'Link',
                'rules' => 'trim|required'
            ),
            'sort_order' => array(
                'field' => 'sort_order',
                'label' => 'Order Sort',
                'rules' => 'required|integer'
            ),
            'enable' => array(
                'field' => 'enable',
                'label' => '啟用',
                'rules' => 'required|in_list[0,1]',
            ),
            'auth' => array(
                'field' => 'enable',
                'label' => '權限驗證',
                'rules' => 'required|in_list[0,1]',
            ),
            'action[]' => array(
                'field' => 'action[]',
                'label' => 'Action',
                // 'rules' => '',
                'is_array' => TRUE,
            ),
        );

        return $config;
    }

    public function getList($conditions=array())
    {
        $params = array(
            'order_by' => 'sort_order asc, link asc',
            'order_by' => 'parent_id asc, action_id asc, sort_order asc, link asc',
        );

        // main menu
        $params['conditions'] = array_merge(array(
            'parent_id' => 0,
            'action_id' => 0,
        ), $conditions);
        $m_menu = $this->getData($params);

        // sub menu
        $params['conditions'] = array_merge(array(
            'parent_id > ' => 0,
            'action_id' => 0,
        ), $conditions);
        $s_menu = $this->getData($params);

        // sort out list for menus
        $data = array();
        foreach ($m_menu as $m) {
            $data[$m['id']] = $m;
            $data[$m['id']]['display'] = $m['name'];
            if ($m['icon'] != '') {
                $data[$m['id']]['display'] = '<i class="fa '. $m['icon'] .' fa-fw"></i> '. $m['name'];
            }

            $params = array(
                'select' => 'id, parent_id, action_id, name, link, enable',
                'conditions' => array('action_id'=>$m['id']),
                'order_by' => 'name asc',
            );
            $data[$m['id']]['actions'] = $this->getData($params);

            if (count($data[$m['id']]['actions']) == 0) {
                foreach ($s_menu as $s) {
                    if ($s['parent_id'] == $m['id']) {
                        $row = $s;
                        $row['display'] = "&emsp;&nbsp;&nbsp;{$m['name']} <i class=\"fa fa-angle-right\"></i> {$s['name']}";

                        $params = array(
                            'select' => 'id, parent_id, action_id, name, link, enable',
                            'conditions' => array('action_id'=>$s['id']),
                            'order_by' => 'name asc',
                        );
                        $row['actions'] = $this->getData($params);

                        $data[$s['id']] = $row;
                    }
                }
            }

        }

        return $data;
    }

    public function getSidebarByPort($port='admin')
    {

        $data = array();
        $params = array(
            'conditions' => array('port'=>$port, 'action_id'=>0, 'enable'=>1),
            'order_by' => 'parent_id asc, action_id asc, sort_order asc, link asc',
        );

        $menus = $this->getData($params);

        foreach ($menus as $row) {
            if ($row['parent_id'] == 0) {
                $data[$row['id']] = $row;
                $data[$row['id']]['sub'] = array();
            } else {
                if (isset($data[$row['parent_id']])) {
                    $data[$row['parent_id']]['sub'][$row['id']] = $row;
                }
            }
        }

        return $data;
    }

    public function getParentChoices($conditions=array(), $icon=FALSE)
    {
        $conditions = array_merge(array(
            'parent_id' => 0,
            'action_id' => 0,
        ), $conditions);

        $params = array(
            'conditions' => $conditions,
            'order_by' => 'sort_order asc',
        );

        $menus = $this->getData($params);
        $data = array();
        foreach ($menus as $row) {
            $data[$row['id']] = $row['name'];
            if ($icon && $row['icon'] != '') {
                $data[$row['id']] = "<i class=\"fa {$row['icon']}\"></i> {$row['name']}";
            }
        }
        return $data;
    }

    public function getChoices($port='admin')
    {
        $params = array(
            'conditions' => array(
                'port' => $port,
                'enable' => 1,
                'auth' => 1,
            ),
            'order_by' => 'parent_id asc, sort_order asc, link asc',
        );
        $temp = $this->getData($params);

        // main menu
        $m_menu = array();
        foreach ($temp as $row) {
            if ($row['parent_id'] == 0 && $row['action_id'] == 0) {
                $m_menu[$row['id']] = $row;
            }
        }

        // sub menu
        $s_menu = array();
        foreach ($temp as $row) {
            if ($row['parent_id'] > 0 && $row['action_id'] == 0) {
                $s_menu[$row['id']] = $row;
            }
        }

        // sort out list for menus
        $data = array();
        foreach ($m_menu as $m) {
            $data[$m['id']] = $m['name'];
            // actions
            $params = array(
                'conditions' => array('action_id'=>$m['id']),
                'order_by' => 'link asc',
            );
            $actions = $this->getData($params);
            foreach ($actions as $a) {
                $data[$a['id']] = "{$m['name']} - {$a['name']} # {$a['link']}";
            }

            foreach ($s_menu as $s) {
                if ($s['parent_id'] == $m['id']) {
                    $data[$s['id']] = "{$m['name']} > {$s['name']} # {$s['link']}";

                    // actions
                    $params = array(
                        'conditions' => array('action_id'=>$s['id']),
                        'order_by' => 'link asc',
                    );
                    $actions = $this->getData($params);
                    foreach ($actions as $a) {
                        $data[$a['id']] = "{$m['name']} > {$s['name']} - {$a['name']} # {$a['link']}";
                    }
                }
            }
        }

        return $data;
    }


    public function getLocation()
    {
        $current_url = current_url();
        $uri_string = $this->uri->uri_string();
        $uri_array = $this->uri->segment_array();
        $uri_max_index = $this->uri->total_segments();


        while (is_numeric($uri_array[$uri_max_index])) {
            array_pop($uri_array);
            $uri_max_index--;
        }
        $uri_string = implode('/', $uri_array);

        $current = $this->get(array('link'=>$uri_string));
        if ($current['parent_id'] > 0) {
            $current['parent'] = $this->get($current['parent_id']);
        }
        if ($current['action_id'] > 0) {
            $current['function'] = $this->get($current['action_id']);
        }

        return $current;
    }

    public function _get($id=NULL)
    {
        $data = $this->get($id);

        $params = array(
            'conditions' => array('action_id'=>$id),
        );

        $actions = $this->getData($params);
        $data['actions'] = $this->getData($params);
        $data['actions_to_string'] = '';
        foreach ($data['actions'] as $row) {
            $data['actions_to_string'] .= "{$row['name']},";
        }
        $data['actions_to_string'] = strtolower($data['actions_to_string']);

        return $data;
    }

    public function _insert($fields=array())
    {
        $actions = explode(',', $fields['actions']);
        unset($fields['actions']);

        $saved_id = $this->insert($fields);
        // insert data for action
        foreach ($actions as $a) {
            if ($a != '') {
                $a = trim($a);
                $action_fields = array(
                    'port' => $fields['port'],
                    'parent_id' => $fields['parent_id'],
                    'action_id' => $saved_id,
                    'icon' => '',
                    'name' => ucfirst($a),
                    'link' => "{$fields['link']}/{$a}",
                    'enable' => $fields['enable'],
                    'auth' => $fields['auth'],
                    'sort_order' => 0,
                );
                $this->insert($action_fields);
            }
        }

        $this->_createController($fields['link'], $actions);

        return $saved_id;
    }

    public function _update($id, $fields=array())
    {
        $actions = explode(',', strtolower($fields['actions']));
        unset($fields['actions']);

        $result = $this->update($id, $fields);


        $params = array(
            'conditions' => array('action_id'=>$id),
        );
        $old_actions = $this->getData($params);
        foreach ($old_actions as $old_a) {
            $name = strtolower($old_a['name']);
            if (in_array($name, $actions)) {
                $action_fields = array(
                    'link' => "{$fields['link']}/{$old_a['name']}",
                    'enable' => $fields['enable'],
                    'auth' => $fields['auth'],
                );
                $this->update($old_a['id'], $action_fields);

                $actions = array_diff($actions, array($name));
            } else {
                $this->delete($old_a['id']);
            }
        }

        // create
        foreach ($actions as $a) {
            $a = trim($a);
            if ($a != '') {
                $action_fields = array(
                    'port' => $fields['port'],
                    'parent_id' => $fields['parent_id'],
                    'action_id' => $id,
                    'icon' => '',
                    'name' => ucfirst($a),
                    'link' => "{$fields['link']}/{$a}",
                    'enable' => $fields['enable'],
                    'auth' => $fields['auth'],
                    'sort_order' => 0,
                );
                $this->insert($action_fields);
            }
        }

        return $result;
    }

    public function _delete($id)
    {
        $item = $this->get($id);

        $this->db->where('id', $id);
        $this->db->or_where('action_id', $id);
        $result = $this->db->delete($this->table);

        return $this->_result($result);
    }

    private function _createController($link, $actions)
    {
        $filename = basename($link);
        $link = str_replace($filename, ucfirst($filename), $link);
        $path = APPPATH . 'controllers/'. $link . '.php';
        $dir = dirname($path);

        if (!file_exists($path)) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $content = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\r\n\r\n";
            $content .= "class ". ucfirst($filename) ." extends MY_Controller\r\n";
            $content .= "{\r\n";
            $content .= "    public function __construct()\r\n";
            $content .= "    {\r\n";
            $content .= "        parent::__construct();\r\n";
            $content .= "    }\r\n\r\n";
            $actions = array('index') + $actions;
            foreach ($actions as $a) {
                $content .= "    public function {$a}()\r\n";
                $content .= "    {\r\n";
                $content .= "        echo 'The page is {$filename} {$a}';\r\n";
                $content .= "    }\r\n\r\n";
            }
            $content .= "}\r\n";

            $fp = fopen($path, 'w');
            fwrite($fp, $content);
            fclose($fp);
        }
    }
}

