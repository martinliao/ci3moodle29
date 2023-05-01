<?php
/**
 * Initialise global $CFG variable.
 * @private to be used only from lib/setup.php
 */
function initialise_cfg() {
    global $CFG, $DB;
debugBreak();
    if (!$DB) {
        // This should not happen.
        return;
    }

    try {
        $localcfg = get_config('core');
    } catch (dml_exception $e) {
        // Most probably empty db, going to install soon.
        return;
    }

    foreach ($localcfg as $name => $value) {
        // Note that get_config() keeps forced settings
        // and normalises values to string if possible.
        $CFG->{$name} = $value;
    }
}

function print_error($errorcode, $module = 'error', $link = '', $a = null, $debuginfo = null) {
    throw new exception($errorcode, $module, $a, $debuginfo);
}