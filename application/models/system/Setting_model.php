<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends MY_Model
{
    protected $table = 'BS_setting';
    protected $pk = 'field';
    public $kind = array();

    public function __construct()
    {
        parent::__construct();
        $this->init($this->table, $this->pk);

        $this->kind = array(
            'web' => '網站',
            'admin' => '管理後台',
            'company' => '公司資訊',
            'seo' => 'SEO',
            'system' => '系統',
        );

    }

    public function getByField($field=NULL)
    {
        $data = $this->get(array('field'=>$field));

        return $data;
    }

    public function getChoices($conditions=array())
    {
        $data = array();
        $params = array(
            'conditions' => $conditions,
            'order_by' => 'kind asc, sort_order asc',
        );
        $settings = $this->getData($params);
        foreach ($settings as $row) {
            $data[$row['field']] = $row['value'];
        }

        return $data;
    }

    public function getList()
    {
        $params = array(
            'order_by' => 'kind asc, sort_order asc',
            'conditions' => array('show'=>1),
        );

        $setting = $this->getData($params);
        $data = array();
        foreach ($setting as $row ) {
            $data[$row['kind']][$row['field']] = $row;
        }

        return $data;

    }

    public function _update($fields) {
        foreach ($fields as $field => $value) {
            $this->update($field, array('value'=>$value));
        }
        return $this;
    }
}

