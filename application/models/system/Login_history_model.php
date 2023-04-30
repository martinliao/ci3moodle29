<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_history_model extends MY_Model
{
    public $table = 'login_history';
    public $pk = 'user_id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getList($attrs=array())
    {
        $params = array(
            'select' => '',
            'order_by' => 'login_time desc',
        );

        $params['join'] = array(
            array(
                'table' => "(SELECT username, name, id from BS_user ) as user",
                'condition'=>'user.id = login_history.user_id',
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

        if (isset($attrs['username'])) {
            $params['or_like'] = array(
                'many' => TRUE,
                'data' => array(
                    array('field' => 'username', 'value'=>$attrs['username'], 'position'=>'both'),
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

        if (isset($attrs['username'])) {
            $params['username'] = $attrs['username'];
        }
        $data = $this->getList($params);
        return count($data);
    }

}