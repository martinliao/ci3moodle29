<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Second_category_model extends MY_Model
{
    public $table = 'second_category';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getFormDefault($info=array())
    {
        $data = array_merge(array(
                    'parent_id' => '',
                    'item_id' => '',
                    'name' => '',
                    'short_name' => '',
                    'remark' => '',
                    'enable' => ''
                ),$info);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            'parent_id' => array(
                'field' => 'parent_id',
                'label' => '系列別代碼',
                'rules' => 'required',
            ),
            'item_id' => array(
                'field' => 'item_id',
                'label' => '代碼',
                'rules' => 'trim|required|is_unique[second_category.item_id]',
            ),
            'name' => array(
                'field' => 'name',
                'label' => '次類別名稱',
                'rules' => 'trim|required',
            ),
            'short_name' => array(
                'field' => 'short_name',
                'label' => '次類別簡稱',
                'rules' => 'trim',
            ),
            'remark' => array(
                'field' => 'remark',
                'label' => '備註',
                'rules' => 'trim',
            ),
            'enable' => array(
                'field' => 'enable',
                'label' => '啟用',
                'rules' => 'required|in_list[0,1]',
            ),
        );

        return $config;
    }

    public function _insert($fields=array())
    {
        return $this->insert($fields);
    }

    public function _update($pk, $fields=array())
    {
        return parent::update($pk, $fields);
    }

    public function getListCount($attrs=array())
    {
        $params = array(
            'conditions' => $attrs['conditions'],
        );

        if (isset($attrs['q'])) {
            $params['q'] = $attrs['q'];
        }

        if (isset($attrs['item_id'])) {
            $params['item_id'] = $attrs['item_id'];
        }

        if (isset($attrs['type'])) {
            $params['type'] = $attrs['type'];
        }

        $data = $this->getList($params);
        return count($data);
    }

    public function getList($attrs=array())
    {
        $params = array(
            'select' => 'second_category.id, second_category.parent_id, second_category.item_id, second_category.name, second_category.short_name, second_category.remark, second_category.enable, series_category.name as series_name',
            'order_by' => 'second_category.create_time'
        );

        $params['join'] = array(array('table' => 'series_category',
                                'condition' => 'series_category.item_id = second_category.parent_id',
                                'join_type' => ''
                        ));

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
        // if (isset($attrs['q'])) {
        //     $params['like'] = array(
        //         'many' => TRUE,
        //         'data' => array(
        //             array('field' => 'second_category.name', 'value'=>$attrs['q'], 'position'=>'both')
        //         )
        //     );
        // }

        // if (isset($attrs['item_id'])) {
        //     $params['like'] = array(
        //         'many' => TRUE,
        //         'data' => array(
        //             array('field' => 'second_category.item_id', 'value'=>$attrs['item_id'], 'position'=>'both')
        //         )
        //     );
        // }

        $date_like = array();
        if (isset($attrs['item_id'])) {
            $like_item_id = array(
                array('field' => 'second_category.item_id', 'value'=>$attrs['item_id'], 'position'=>'both')
            );
            $date_like = array_merge($date_like, $like_item_id);
        }
        if (isset($attrs['name'])) {
            $like_name = array(
                array('field' => 'second_category.name', 'value'=>$attrs['name'], 'position'=>'both')
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

     public function getSeriesCategory()
     {
        $data = array();
        $this->db->select('item_id,name');
        $this->db->from('series_category');
        $query = $this->db->get();
        $bureau = $query->result_array();

        foreach ($bureau as $key) {
            $data[$key['item_id']] = $key['name'];
        }

        return $data;
    }

    public function getChoices($conditions=array())
    {
        $choices = array();
        $attrs = array();
        if(!empty($conditions)){
            $attrs['conditions'] = $conditions;
        }
        $data = $this->getList($attrs);
        foreach ($data as $row) {
            $choices[$row['item_id']] = $row['name'];
        }
        return $choices;
    }

}