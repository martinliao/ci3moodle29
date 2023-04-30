<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends MY_Model
{
    public $table = 'users';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getList($attrs=array())
    {
        $groups = $this->user_group_model->getAll();
        $params = array(
            'select' => '*',
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
        if (isset($attrs['like'])) {
            $params['like'] = $attrs['like'];
        }
        if (isset($attrs['q'])) {
            $params['or_like'] = array(
                'many' => TRUE,
                'data' => array(
                    array('field' => 'name', 'value'=>$attrs['q'], 'position'=>'both'),
                    array('field' => 'username', 'value'=>$attrs['q'], 'position'=>'both'),
                    array('field' => 'email', 'value'=>$attrs['q'], 'position'=>'both'),
                    array('field' => 'telephone', 'value'=>$attrs['q'], 'position'=>'both'),
                ),
            );
            // unset
        }

        $data = $this->getData($params);

        // foreach ($data as & $user) {
        //     foreach ($groups as $group) {
        //         if ($user['user_group_id'] == $group['id']) {
        //             $user['group'] = $group['name'];
        //             break;
        //         }
        //     }
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

    public function getChoices($conditions=array())
    {
        $data = array();
        $conditions = array(
            'companyID' => '1',
            );
        $arrt['conditions'] = $conditions;
        $users = $this->getList($arrt);
        foreach ($users as $user) {
            $data[$user['id']] = $user['realname'];
        }

        return $data;
    }

    public function getCustomer()
    {
        $data = array();
        $conditions = array(
            'companyuser_type' => '4',
            );
        $arrt['conditions'] = $conditions;
        $users = $this->getList($arrt);
        foreach ($users as $user) {
            $data[$user['id']] = $user['realname'];
        }

        return $data;
    }

    public function getCustomerGroup($id=NULL)
    {
        $customer = $this->get($id);
        $idNo = substr($customer['idNo'],0,8);
        $data = array();
        $conditions = array(
            'companyuser_type' => '4',
            );
        $arrt['conditions'] = $conditions;
        $arrt['like'] = array(
                'field' => 'idNo',
                'value' => $idNo,
                'position' => 'right',
            );
        $users = $this->getList($arrt);
        foreach ($users as $user) {
            $data[] = $user['id'];
        }

        return $data;
    }

    public function getCustomerName($member_id=NULL)
    {
        $data = $this->get($member_id);
        $company_name = '';
        if($data['companyuser_type'] == '4'){
            $company_name = $data['realname'];
        }

        return $company_name;
    }

    public function getUserByAccount($username)
    {
        $conditions = array(
            'username' => $username,
        );

        return $this->get($conditions);
    }

    public function getUserByEmail($email)
    {
        $conditions = array(
            'email' => $email,
        );

        return $this->get($conditions);
    }

    public function _insert($fields=array())
    {
        if ($fields['password'] != '' && $fields['passconf'] != '' ) {
            unset($fields['passconf']);
            $fields['password'] = md5($fields['password']);
        }

        return $this->insert($fields, 'date_added');
    }

    public function _update($pk, $fields=array()) {
        if (isset($fields['password'])) {
            unset($fields['passconf']);
            if ($fields['password'] != '') {
                $fields['password'] = md5($fields['password']);
            } else {
                unset($fields['password']);
            }
        }

        return parent::update($pk, $fields);
    }

    private function getFields($params=array()) {
        $fields = array(
            'user_group_id' => $params['user_group_id'],
            'name' => trim($params['name']),
            'username' => trim($params['username']),
            'password' => md5($params['password']),
            'email' => trim($params['email']),
            'telephone' => $params['telephone'],
            'enable' => $params['enable'],
        );

        return $fields;
    }

    public function login($username=NULL, $password=NULL)
    {
        $result = array(
            'status' => FALSE,
            'message' => '請確認帳號密碼',
        );
        if ($username && $password) {
            $conditions = array(
                'username'=>$username,
                'password'=>md5($password),
            );
            $user = $this->get($conditions);
            if ($user) {
                if ($user['enable'] == 1) {
                    $fields = array(
                        'last_login_time' => date('Y-m-d H:i:s'),
                    );
                    $this->update($user, $fields);
                    $result['status'] = TRUE;
                } else {
                    $result['message'] = '帳號已停用';
                }
            } else {
                    $result['message'] = '請確認帳號、密碼';
            }
        }

        return $result;
    }


}

