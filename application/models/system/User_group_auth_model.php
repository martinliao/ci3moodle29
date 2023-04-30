<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_group_auth_model extends MY_Model
{
    public $table = 'BS_user_group_auth';
    public $pk = NULL;

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);
    }

    public function getByGroupID($id)
    {
        $params = array(
            'select' => 'menu_id',
            'conditions' => array('user_group_id'=>$id),
        );
        $queryset = $this->getData($params);

        $data = array();
        foreach ($queryset as $row) {
            $data[] = $row['menu_id'];
        }
        return $data;
    }

}


