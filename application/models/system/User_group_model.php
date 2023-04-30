<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_group_model extends MY_Model
{
    public $table = 'BS_user_group';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->init($this->table, $this->pk);
    }

    public function getFormDefault($user=array())
    {
        $data = array_merge(array(
            'auth' => array(),
            'name' => '',
            'description' => '',
            'permission' => '',
            'enable' => 0,
        ), $user);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            'auth' => array(
                'field' => 'auth[]',
                'label' => '權限',
            ),
            'name' => array(
                'field' => 'name',
                'label' => '群組名稱',
                'rules' => 'trim|required',
                'errors' => array('required'=>'%s 請勿空白'),
            ),
            'description' => array(
                'field' => 'description',
                'label' => 'Description',
                // 'rules' => ''
            ),
            'permission' => array(
                'field' => 'premission[]',
                'label' => 'Permission',
                // 'rules' => ''
            ),
            'enable' => array(
                'field' => 'enable',
                'label' => '啟用',
                'rules' => 'required|integer',
            ),
        );

        return $config;
    }

    public function getList($attrs=array())
    {

        $params = array(
            'select' => 'id, name, description, enable',
            'order_by' => 'id',
        );
        if (isset($attrs['conditions'])) {
            $params['conditions'] = $attrs['conditions'];
        }
        if (isset($attrs['rows'])) {
            $params['rows'] = $attrs['rows'];
        }
        if (isset($attrs['offset'])) {
            $params['offset'] = $attrs['offset'];
        }
        if (isset($attrs['sort'])) {
            $params['order_by'] = $attrs['sort'];
        }
        if (isset($attrs['q'])) {
            $params['or_like'] = array(
                'many' => TRUE,
                'data' => array(
                    array('field' => 'name', 'value'=>$attrs['q'], 'position'=>'both'),
                ),
            );
            // unset
        }

        $data = $this->getData($params);

        // foreach ($data as & $group) {
        //     $conditions = array('user_group_id'=>$group['id']);
        //     $group['user_num'] = $this->user_model->getCount($conditions);
        // }


        return $data;
    }

    public function getListCount($attrs=array())
    {
        $params = array(
            'conditions' => $attrs['conditions'],
        );

        if (isset($attrs['q'])) {
            $params['q'] = $attrs['q'];
        }
        $data = $this->getList($params);
        return count($data);
    }


    public function getPermission($id)
    {
        $group = $this->get(array($this->pk=>$id));
        return explode(',', $group['permission']);
    }

    public function getChoices() {
        $choices = array();
        $groups = $this->getAll();
        foreach ($groups as $row) {
            $choices[$row['id']] = $row['name'];
        }

        return $choices;
    }

}

