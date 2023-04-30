<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_role_model extends MY_Model
{
    public $table = 'account_role';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function getFormDefault($user=array())
    {
        $data = array_merge(array(
            'username' => '',
            'group_id' => '',
            'b_name' => '',
            'idno' => '',
            'name' => '',
        ), $user);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            'username' => array(
                'field' => 'username',
                'label' => '帳號',
                'rules' => 'trim|required',
                'errors' => array('required'=>'請填寫帳號'),
            ),
            'group_id' => array(
                'field' => 'group_id',
                'label' => '腳色',
                'rules' => 'trim|required',
                'errors' => array('required'=>'請選擇角色'),
            ),

        );

        return $config;
    }

    public function getList($attrs=array())
    {

        $params = array(
            'select' => '',
            'order_by' => 'group_id, username',
        );
        $params['join'] = array(
                    array(
                        'table' => '(SELECT name, idno, username as u_name, bureau_id from BS_user) as BS_user',
                        'condition'=>'BS_user.u_name = account_role.username',
                        'join_type'=>'left',
                    ),
                    array(
                        'table' => '(SELECT bureau_id as b_id, name as b_name from bureau) as bureau',
                        'condition'=>'bureau.b_id = BS_user.bureau_id',
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

        $date_like = array();
        if (isset($attrs['b_name'])) {
            $like_b_name = array(
                array('field' => 'b_name', 'value'=>$attrs['b_name'], 'position'=>'both')
            );
            $date_like = array_merge($date_like, $like_b_name);
        }
        if (isset($attrs['name'])) {
            $like_name = array(
                array('field' => 'name', 'value'=>$attrs['name'], 'position'=>'both')
            );
            $date_like = array_merge($date_like, $like_name);
        }
        if (isset($date_like)) {
            $params['like'] = array(
                'many' => TRUE,
                'data' => $date_like,
            );
        }

        $data = $this->getData($params);

        return $data;
    }

    public function getListCount($attrs=array())
    {

        $data = $this->getList($attrs);
        return count($data);
    }

    public function getByUsername($username)
    {
        $params = array(
            'select' => 'group_id',
            'conditions' => array('username'=>$username),
        );
        $queryset = $this->getData($params);

        $data = array();
        foreach ($queryset as $row) {
            $data[] = $row['group_id'];
        }
        // $data[] = '5';
        return $data;
    }

    public function _get($id)
    {

        $data = $this->get($id);
        $conditions = array(
            'username' => $data['username'],
        );
        $user_data = $this->user_model->get($conditions);

        if(!empty($user_data['bureau_id'])){
            $this->db->select('name');
            $this->db->from('bureau');
            $this->db->where("bureau_id", $user_data['bureau_id']);
            $query = $this->db->get();
            $bureau_data = $query->row_array();
            $data['b_name'] = $bureau_data['name'];
        }else{
            $data['b_name'] = '';
        }

        $form = array(
            'username' => $data['username'],
            'group_id' => $data['group_id'],
            'b_name' => $data['b_name'],
            'idno' => $user_data['idno'],
            'name' => $user_data['name'],
        );

        return $form;
    }

}