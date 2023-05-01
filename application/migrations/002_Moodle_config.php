<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Moodle_config extends CI_Migration
{
    /**
     * Config settings
     * @var array
     */
    private $settings;

    private function get_settings()
    {
        // Load configs(Example)
        //$this->config->load('smarty_acl', TRUE);
        //Get tables array
        //$tables = $this->config->item('tables', 'smarty_acl');
        //Tables prefix
        //$this->settings['prefix'] = $tables['prefix'] ? $tables['prefix'].'_' : '';
        // Table names
        $this->settings['config'] = 'mdl_config';
    }

    public function up()
    {
        $this->get_settings();
        /**************** Start Create Tables ****************/
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array('type' => 'VARCHAR', 'constraint' => '255', 'unsigned' => TRUE, 'null' => TRUE),
            'value' => array('type' => 'TEXT', 'unsigned' => TRUE, 'null' => TRUE),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->settings['config']);
        /**************** End Create Tables ****************/
        /**************** Start Set Foreign Keys ****************/
        //Unique keys
        $this->db->query('ALTER TABLE '.$this->settings['config'].' ADD CONSTRAINT configname_unique UNIQUE (name)');
        /**************** End Set Foreign Keys ****************/
        /**************** Start Insert Data ****************/
        $this->db->insert($this->settings['config'],[
             'name' => 'licenses',
             'value' => 'unknown,allrightsreserved,public,cc,cc-nd,cc-nc-nd,cc-nc,cc-nc-sa,cc-sa'
        ]);
        $this->db->insert($this->settings['config'],[
            'name' => 'cachejs',
            'value' => 1
        ]);
        $this->db->insert($this->settings['config'],['name' => 'slasharguments','value' => 1]);
       
        /**************** End Insert Data ****************/
    }

    public function down()
    {
        //Load settings
        $this->get_settings();
        //Drop tables
        $this->dbforge->drop_table($this->settings['config']);
    }
}