<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Moodle_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * setuplib.php, line 788.
     */
    public function initialise_cfg($plugin, $name='core')
    {
        global $CFG;
        $query = $this->db->query("SELECT * FROM mdl_config");
        $localcfg  = $query->result_array();
        foreach ($localcfg as $row) {
            $CFG->{$row['name']} = $row['value'];
        }
        return $localcfg;
    }

    public function set_config($name, $value, $plugin=null) {
        global $CFG;
        if (empty($plugin)) {
            // So it's defined for this invocation at least.
            if (is_null($value)) {
                unset($CFG->$name);
            } else {
                // Settings from db are always strings.
                $CFG->$name = (string)$value;
            }
//debugBreak();
            //$query = $this->db->get_where('mdl_config', array('name' => $name));
            //$rs = $query->row();
            //if ($rs->value) {
                $this->db->replace('mdl_config', array('name' => $name, 'value' => $value));
            //}
        }
    }
    
}
