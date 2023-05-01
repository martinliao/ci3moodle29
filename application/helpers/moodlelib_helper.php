<?php
/**
 * Set a key in global configuration
 *
 * Set a key/value pair in both this session's {@link $CFG} global variable
 * and in the 'config' database table for future sessions.
 *
 * Can also be used to update keys for plugin-scoped configs in config_plugin table.
 * In that case it doesn't affect $CFG.
 *
 * A NULL value will delete the entry.
 *
 * NOTE: this function is called from lib/db/upgrade.php
 *
 * @param string $name the key to set
 * @param string $value the value to set (without magic quotes)
 * @param string $plugin (optional) the plugin scope, default null
 * @return bool true or exception
 */
function set_config($name, $value, $plugin=null) {
    global $CFG, $DB;
debugBreak();
    if (empty($plugin)) {
        if (!array_key_exists($name, $CFG->config_php_settings)) {
            // So it's defined for this invocation at least.
            if (is_null($value)) {
                unset($CFG->$name);
            } else {
                // Settings from db are always strings.
                $CFG->$name = (string)$value;
            }
        }

        if ($DB->get_field('config', 'name', array('name' => $name))) {
            if ($value === null) {
                $DB->delete_records('config', array('name' => $name));
            } else {
                $DB->set_field('config', 'value', $value, array('name' => $name));
            }
        } else {
            if ($value !== null) {
                $config = new stdClass();
                $config->name  = $name;
                $config->value = $value;
                $DB->insert_record('config', $config, false);
            }
        }
        //if ($name === 'siteidentifier') {
        //    cache_helper::update_site_identifier($value);
        //}
        cache_helper::invalidate_by_definition('core', 'config', array(), 'core');
    } else {
        // Plugin scope.
        if ($id = $DB->get_field('config_plugins', 'id', array('name' => $name, 'plugin' => $plugin))) {
            if ($value===null) {
                $DB->delete_records('config_plugins', array('name' => $name, 'plugin' => $plugin));
            } else {
                $DB->set_field('config_plugins', 'value', $value, array('id' => $id));
            }
        } else {
            if ($value !== null) {
                $config = new stdClass();
                $config->plugin = $plugin;
                $config->name   = $name;
                $config->value  = $value;
                $DB->insert_record('config_plugins', $config, false);
            }
        }
        cache_helper::invalidate_by_definition('core', 'config', array(), $plugin);
    }

    return true;
}