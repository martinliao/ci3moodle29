<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_blacklist_model extends MY_Model
{
    protected $table = 'BS_user_blacklist';
    protected $pk = 'name';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);
    }

    public function cancelAccount($username)
    {
        return $this->delete(array('account'=>$username));
    }

    public function cancelIP($ip)
    {
        return $this->delete(array('ip'=>$ip));
    }

    public function getLockTimeByIP($ip)
    {
        $params = array(
            'conditions' => array('ip'=>$ip, 'unlock_time >'=>date('Y-m-d H:i:s')),
            'order_by' => 'unlock_time desc',
        );

        $locks = $this->getData($params);
        $lock_time = FALSE;
        if ($locks) {
            $lock = array_shift($locks);
            $lock_time = $lock['unlock_time'];
        }

        return $lock_time;
    }

    public function getLockTimeByAccount($account)
    {
        $params = array(
            'conditions' => array('account'=>$account, 'unlock_time >'=>date('Y-m-d H:i:s')),
            'order_by' => 'unlock_time desc',
        );

        $locks = $this->getData($params);
        $lock_time = FALSE;
        if (count($locks) > 0) {
            $values = array_values($locks);
            $lock = array_shift($values);
            $lock_time = $lock['unlock_time'];
        }

        return $lock_time;
    }

    public function insertAccount($account, $unlock_time)
    {
        $params = array(
            'conditions' => array('account'=>$account),
        );
        $data = $this->getData($params);
        if ($data) {
            $conditions = array('account' => $account);
            $fields = array('unlock_time' => $unlock_time);
            $rs = $this->update($conditions, $fields);
        } else {
            $rs = $this->insert(array('account'=>$account, 'ip'=>'', 'unlock_time'=>$unlock_time));
        }

        return $rs;
    }

    public function insertIP($ip, $unlock_time)
    {
        $params = array(
            'conditions' => array('ip'=>$ip),
        );
        $data = $this->getData($params);
        if ($data) {
            $conditions = array('ip' => $ip);
            $fields = array('unlock_time' => $unlock_time);
            $rs = $this->update($conditions, $fields);
        } else {
            $rs = $this->insert(array('ip'=>$ip, 'account'=>'', 'unlock_time'=>$unlock_time));
        }

        return $rs;
    }
}

