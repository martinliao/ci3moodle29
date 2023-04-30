<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shop_user_model extends MY_Model
{
    public $table = 'BS_shop_user';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getFormDefault($user=array())
    {
        $data = array_merge(array(
            'user_group_id' => 0,
            'member_id' => 0,
            'name' => '',
            'username' => '',
            'pssword' => '',
            'passconf' => '',
            'email' => '',
            'telephone' => '',
            'enable' => 0,
        ), $user);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            'user_group_id' => array(
                'field' => 'user_group_id',
                'label' => '群組',
                'rules' => 'required',
            ),
            'member_id' => array(
                'field' => 'member_id',
                'label' => '選擇會員',
                'rules' => 'required',
            ),
            'name' => array(
                'field' => 'name',
                'label' => '名稱',
                // 'rules' => 'trim|required|min_length[4]|max_length[128]',
            ),
            'username' => array(
                'field' => 'username',
                'label' => '帳號',
                // 'rules' => 'trim|required|min_length[4]|max_length[20]|is_unique[BS_user.username]',
            ),
            'password' => array(
                'field' => 'password',
                'label' => '密碼',
                // 'rules' => 'required|min_length[4]|max_length[20]',
            ),
            'passconf' => array(
                'field' => 'passconf',
                'label' => '確認密碼',
                // 'rules' => 'required|matches[password]',
            ),
            'email' => array(
                'field' => 'email',
                'label' => 'E-Mail',
                // 'rules' => 'trim|required|valid_email|is_unique[BS_user.email]',
            ),
            'telephone' => array(
                'field' => 'telephone',
                'label' => 'Telephone',
                // 'rules' => 'trim|integer',
            ),
            'enable' => array(
                'field' => 'enable',
                'label' => '啟用',
                'rules' => 'required|in_list[0,1]',
            ),
        );

        return $config;
    }

    public function getList($attrs=array())
    {
        $groups = $this->user_group_model->getAll();
        $params = array(
            'select' => 'id, user_group_id, name, username, email, telephone, enable, date_added',
            'order_by' => 'date_added',
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
                    array('field' => 'username', 'value'=>$attrs['q'], 'position'=>'both'),
                    array('field' => 'email', 'value'=>$attrs['q'], 'position'=>'both'),
                    array('field' => 'telephone', 'value'=>$attrs['q'], 'position'=>'both'),
                ),
            );
            // unset
        }

        $data = $this->getData($params);

        foreach ($data as & $user) {
            foreach ($groups as $group) {
                if ($user['user_group_id'] == $group['id']) {
                    $user['group'] = $group['name'];
                    break;
                }
            }
        }


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
        $users = $this->getList();
        foreach ($users as $user) {
            $data[$user['id']] = $user['name'];
        }

        return $data;
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
        return $this->insert($fields, 'date_added');
    }

    public function _update($pk, $fields=array()) {

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

