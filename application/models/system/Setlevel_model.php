<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setlevel_model extends MY_Model
{
    protected $table = 'BS_setlevel';
    protected $pk = 'id';
    protected $locale = array('name');

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getFormDefault($data=array())
    {
        $fields = array(
            'name' => '',
            'sort_order' => 0,
            'link' => '',
        );


        return array_merge($fields, $data);
    }

    public function getVerifyConfig()
    {
        $config = array(
            'sort_order' => array(
                'field' => 'sort_order',
                'label' => '排序',
                'rules' => 'required|integer',
            ),
            'name' => array(
                'field' => 'name',
                'label' => '名稱',
                'rules' => 'required',
            ),
            'link' => array(
                'field' => 'link',
                'label' => 'Link',
                'rules' => 'required',
            ),
        );

        return $config;
    }

    public function getList($attrs=array())
    {

        $params = array(
            'select' => '',
            'order_by' => 'sort_order asc',
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

    public function getChoices()
    {
        $choices = array();
        $attrs['conditions'] = array();
        $data = $this->getList($attrs);
        foreach ($data as $row) {
            $choices[$row['id']] = $row['name'];
        }
        return $choices;
    }


}

