<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
    public $table = 'BS_user';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getFormDefault($user=array())
    {
        $data = array_merge(array(
            // 'user_group_id' => 0,
            'member_id' => 0,
            'name' => '',
            'username' => '',
            'pssword' => '',
            'passconf' => '',
            'idno' =>'',
            'email' => '',
            'telephone' => '',
            'enable' => 0,
        ), $user);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            // 'user_group_id' => array(
            //     'field' => 'user_group_id',
            //     'label' => '群組',
            //     'rules' => 'required',
            // ),
            'name' => array(
                'field' => 'name',
                'label' => '名稱',
                'rules' => 'trim|required|min_length[2]|max_length[128]',
            ),
            'username' => array(
                'field' => 'username',
                'label' => '帳號',
                'rules' => 'trim|required|min_length[4]|max_length[20]|is_unique[BS_user.username]',
            ),
            'password' => array(
                'field' => 'password',
                'label' => '密碼',
                'rules' => 'required|min_length[4]|max_length[20]',
            ),
            'passconf' => array(
                'field' => 'passconf',
                'label' => '確認密碼',
                'rules' => 'required|matches[password]',
            ),
            'idno' => array(
                'field' => 'idno',
                'label' => '身分證字號',
                'rules' => 'required|is_unique[BS_user.idno]',
            ),
            'email' => array(
                'field' => 'email',
                'label' => 'E-Mail',
                'rules' => 'trim|required|valid_email',
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
            'select' => 'id, name, username, idno, email, office_email, telephone, enable, date_added',
            'order_by' => 'date_added',
        );
        if (isset($attrs['conditions'])) {
            $params['conditions'] = $attrs['conditions'];
        }
        if (isset($attrs['where_in'])) {
            $params['where_in'] = $attrs['where_in'];
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
                    array('field' => 'idno', 'value'=>$attrs['q'], 'position'=>'both'),
                    //array('field' => 'username', 'value'=>$attrs['q'], 'position'=>'both'),
                    //array('field' => 'email', 'value'=>$attrs['q'], 'position'=>'both'),
                    //array('field' => 'telephone', 'value'=>$attrs['q'], 'position'=>'both'),
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

    public function getChoices($conditions=array())
    {
        $data = array();
        $users = $this->getList();
        foreach ($users as $user) {
            $data[$user['id']] = $user['name'];
        }

        return $data;
    }

    public function getUserChoices($conditions=array())
    {
        $data = array();
        $attrs = array(
            'conditions' => array('id <' => '60'),
        );

        $users = $this->getList($attrs);
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

    public function getUserByIdno($idno)
    {
        $conditions = array(
            'idno' => $idno,
        );

        return $this->get($conditions);
    }

    public function getUserById($id)
    {
        $query = $this->db->where('id', '=', $id)->get();
        return $query->row();
    }
    /*
        取得擁有 管理者 或是 教務組管理者權限 的帳號
        EAS = Educational Affairs Section
        admin
    */
    public function getAccountRoleEASNadmin($usernames)
    {
        $query = $this->db->select("username")
                          ->from("account_role")
                          ->where_in("group_id", [1,9])
                          ->where_in("username", $usernames)                          
                          ->get();
        return $query->result();
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
        if ($fields['password'] != '' && $fields['passconf'] != '' ) {
            unset($fields['passconf']);
            $undecode_pwd = $fields['password']; //2021-06-23未加密密碼(人事登入系統)
            $fields['password'] = md5($fields['password']);
            $this->_update_account_personnel($pk,$undecode_pwd); //2021-06-23更新account_personnel表(人事登入系統)
        }
        if($fields['password'] == ''){
            unset($fields['password']);
            unset($fields['passconf']);
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
                //'password'=> 'dd201399c98bc51717f3243a2c706d64',
            );
//debugBreak();
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

    public function loginBySSO($idno)
    {
        $result = array(
            'status' => FALSE,
        );
      
        $conditions = array(
            'idno'=>$idno,
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
                $result['status'] = FALSE;
            }
        } else {
            $result['status'] = FALSE;
        }
        

        return $result;
    }

    public function _get($conditions){
        $person = $this->get($conditions);
        $person_data = array();
        if($person){
            $person_data = array(
                'id' => $person['id'],
                'name' => $person['name'],
                'birthday' => $person['birthday'],
                'job_title' => $person['job_title'],
                'bureau_id' => $person['bureau_id'],
                'gender' => $person['gender'],
            );

            if(!empty($person['job_title'])){
                $this->db->select('name');
                $this->db->from('job_title');
                $this->db->where("item_id", $person['job_title']);
                $query = $this->db->get();
                $job_title_data = $query->row_array();
                $person_data['job_title_name'] = $job_title_data['name'];
            }else{
                $person_data['job_title_name'] = '';
            }

            if(!empty($person['out_gov_name'])){
                $person_data['bureau'] = $person['out_gov_name'];
            } else if(!empty($person['bureau_id'])){
                $this->db->select('name');
                $this->db->from('bureau');
                $this->db->where("bureau_id", $person['bureau_id']);
                $query = $this->db->get();
                $bureau_data = $query->row_array();
                $person_data['bureau'] = $bureau_data['name'];
            }else{
                $person_data['bureau'] = '';
            }
            $person_data['phydisabled'] = '無';
            if(!empty($conditions['idno'])){
                $this->db->select('memo');
                $this->db->from('phydisabled');
                $this->db->where("gid", $conditions['idno']);
                $query = $this->db->get();
                $phydisabled_data = $query->row_array();
                if($phydisabled_data){
                    $person_data['phydisabled'] = $phydisabled_data['memo'];
                }

                // $this->db->select('ou_gov');
                // $this->db->from('out_gov');
                // $this->db->where("id", $conditions['idno']);
                // $query = $this->db->get();
                // $ou_gov_name = $query->row_array();
                // if($ou_gov_name && !empty($ou_gov_name['ou_gov'])){
                //     $person_data['bureau'] = $ou_gov_name['ou_gov'];
                // }

            }
        }

        return $person_data;
    }

    public function getBureauList($attrs=array())
    {
        $groups = $this->user_group_model->getAll();
        $params = array(
            'select' => 'idno, name',
            'order_by' => 'date_added',
        );
        if (isset($attrs['conditions'])) {
            $params['conditions'] = $attrs['conditions'];
        }
        if (isset($attrs['where_in'])) {
            $params['where_in'] = $attrs['where_in'];
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

    public function getBureauListCount($attrs=array())
    {
        $params = array(
            'conditions' => $attrs['conditions'],
        );

        if (isset($attrs['q'])) {
            $params['q'] = $attrs['q'];
        }
        $data = $this->getBureauList($params);
        return count($data);
    }

    public function getPersonal($where_in=array())
    {
        $params = array(
            'select' => 'idno, bureau_id',
            'order_by' => 'bureau_id',
        );
        $params['where_in'] = $where_in;
        $data = $this->getData($params);

        return $data;
    }

    public function getWorkerList($attrs=array())
    {
        $params = array(
            'select' => 'username, idno, name, bureau_id, b_name',
            'order_by' => 'username',
        );
        $params['join'] = array(
                    array(
                        'table' => '(SELECT bureau_id as b_id, name as b_name from bureau) as bureau',
                        'condition'=>'bureau.b_id = BS_user.bureau_id',
                        'join_type'=>'left',
                    ),
                );

        if (isset($attrs['conditions'])) {
            $params['conditions'] = $attrs['conditions'];
        }
        if (isset($attrs['where_in'])) {
            $params['where_in'] = $attrs['where_in'];
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
        if (isset($attrs['username'])) {
            $like_username = array(
                array('field' => 'username', 'value'=>$attrs['username'], 'position'=>'both')
            );
            $date_like = array_merge($date_like, $like_username);
        }
        if (isset($attrs['idno'])) {
            $like_idno = array(
                array('field' => 'idno', 'value'=>$attrs['idno'], 'position'=>'both')
            );
            $date_like = array_merge($date_like, $like_idno);
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

    public function getWorkerListCount($attrs=array())
    {
        $data = $this->getWorkerList($attrs);
        return count($data);
    }

    //2021-06-23更新account_personnel表(人事登入系統)
    public function _update_account_personnel($id,$pwd)
    {
        $conditions = array(
            'id'=>$id,
        );
        $user = $this->get($conditions);
        if ($user) {
            $update_pwd = array(
                'password'=>$pwd,
            );
            $this->db->where('id', $user['username']);
            $this->db->update('account_personnel', $update_pwd);
        }
    
    }


}

