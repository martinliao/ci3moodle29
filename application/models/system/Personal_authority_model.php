<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal_authority_model extends MY_Model
{
    public $table = 'BS_personal_auth';
    public $pk = NULL;

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);
    }

    public function getFormDefault($user=array())
    {
        $data = array_merge(array(
            'auth' => array(),
            'user_id' => '',
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
            'user_id' => array(
                'field' => 'user_id',
                'label' => '帳號',
                'rules' => 'required',
            ),
        );

        return $config;
    }

    public function getList($attrs=array())
    {

        $params = array(
            'select' => 'user_id as id, username, name, enable',
            'order_by' => 'id',
            'group_by' => 'id',
        );
        $params['join'] = array(
                    array(
                        'table' => '(SELECT id, username, name, enable from BS_user) as user',
                        'condition'=>'user.id = BS_personal_auth.user_id',
                        'join_type'=>'left',
                    ),

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

    public function _get($id)
    {
        $params = array(
            'select' => 'user_id as id',
            'order_by' => 'id',
            'group_by' => 'id',
        );
        $params['conditions'] = array(
            'user_id' => $id,
        );
        $queryset = $this->getData($params);

        $data = array();
        foreach ($queryset as $row) {
            $data['user_id'] = $row['id'];
        }
        return $data;
    }

    public function getUserId()
    {
        $params = array(
            'select' => 'user_id as id',
            'order_by' => 'id',
            'group_by' => 'id',
        );

        $queryset = $this->getData($params);

        $data = array();
        foreach ($queryset as $row) {
            $data[] = $row['id'];
        }
        return $data;
    }

    public function getByUserID($id)
    {
        $params = array(
            'select' => 'menu_id',
            'conditions' => array('user_id'=>$id),
        );
        $queryset = $this->getData($params);

        $data = array();
        foreach ($queryset as $row) {
            $data[] = $row['menu_id'];
        }
        return $data;
    }

}


