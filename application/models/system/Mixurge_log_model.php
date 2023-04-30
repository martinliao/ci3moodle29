<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mixurge_log_model extends MY_Model
{
    public $table = 'lux_urge_logs';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getListCount($attrs=array())
    {
        $params = array(
            'conditions' => $attrs['conditions'],
        );

        if (isset($attrs['query_class_name'])) {
            $params['query_class_name'] = $attrs['query_class_name'];
        }
        $data = $this->getList($params);
        return count($data);
    }

    public function getList($attrs=array())
    {
        $params = array(
            'select' => '*',
            'order_by' => 'year,class_no,term,sendtime,content',
        );

        if (isset($attrs['query_class_name'])) {
            $params['or_like'] = array(
                'many' => TRUE,
                'data' => array(
                    array('field' => 'class_name', 'value'=>$attrs['query_class_name'], 'position'=>'both'),
                ),
            );
        }

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

        $data = $this->getData($params);

        return $data;
    }
}