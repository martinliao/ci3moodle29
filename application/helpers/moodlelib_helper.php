<?php
/**
 * Used by {@link optional_param()} and {@link required_param()} to
 * clean the variables and/or cast to specific types, based on
 * an options field.
 * <code>
 * $course->format = clean_param($course->format, PARAM_ALPHA);
 * $selectedgradeitem = clean_param($selectedgradeitem, PARAM_INT);
 * </code>
 *
 * @param mixed $param the variable we are cleaning
 * @param string $type expected format of param after cleaning.
 * @return mixed
 * @throws coding_exception
 */
function clean_param($param, $type) {
    global $CFG;

    if (is_array($param)) {
        throw new coding_exception('clean_param() can not process arrays, please use clean_param_array() instead.');
    } else if (is_object($param)) {
        if (method_exists($param, '__toString')) {
            $param = $param->__toString();
        } else {
            throw new coding_exception('clean_param() can not process objects, please use clean_param_array() instead.');
        }
    }

    switch ($type) {
        case PARAM_RAW:
            // No cleaning at all.
            $param = fix_utf8($param);
            return $param;

        case PARAM_RAW_TRIMMED:
            // No cleaning, but strip leading and trailing whitespace.
            $param = fix_utf8($param);
            return trim($param);

        case PARAM_CLEAN:
            // General HTML cleaning, try to use more specific type if possible this is deprecated!
            // Please use more specific type instead.
            if (is_numeric($param)) {
                return $param;
            }
            $param = fix_utf8($param);
            // Sweep for scripts, etc.
            return clean_text($param);

        case PARAM_CLEANHTML:
            // Clean html fragment.
            $param = fix_utf8($param);
            // Sweep for scripts, etc.
            $param = clean_text($param, FORMAT_HTML);
            return trim($param);

        case PARAM_INT:
            // Convert to integer.
            return (int)$param;

        case PARAM_FLOAT:
            // Convert to float.
            return (float)$param;

        case PARAM_ALPHA:
            // Remove everything not `a-z`.
            return preg_replace('/[^a-zA-Z]/i', '', $param);

        case PARAM_ALPHAEXT:
            // Remove everything not `a-zA-Z_-` (originally allowed "/" too).
            return preg_replace('/[^a-zA-Z_-]/i', '', $param);

        case PARAM_ALPHANUM:
            // Remove everything not `a-zA-Z0-9`.
            return preg_replace('/[^A-Za-z0-9]/i', '', $param);

        case PARAM_ALPHANUMEXT:
            // Remove everything not `a-zA-Z0-9_-`.
            return preg_replace('/[^A-Za-z0-9_-]/i', '', $param);

        case PARAM_SEQUENCE:
            // Remove everything not `0-9,`.
            return preg_replace('/[^0-9,]/i', '', $param);

        case PARAM_BOOL:
            // Convert to 1 or 0.
            $tempstr = strtolower($param);
            if ($tempstr === 'on' or $tempstr === 'yes' or $tempstr === 'true') {
                $param = 1;
            } else if ($tempstr === 'off' or $tempstr === 'no'  or $tempstr === 'false') {
                $param = 0;
            } else {
                $param = empty($param) ? 0 : 1;
            }
            return $param;

        case PARAM_NOTAGS:
            // Strip all tags.
            $param = fix_utf8($param);
            return strip_tags($param);

        case PARAM_TEXT:
            // Leave only tags needed for multilang.
            $param = fix_utf8($param);
            // If the multilang syntax is not correct we strip all tags because it would break xhtml strict which is required
            // for accessibility standards please note this cleaning does not strip unbalanced '>' for BC compatibility reasons.
            do {
                if (strpos($param, '</lang>') !== false) {
                    // Old and future mutilang syntax.
                    $param = strip_tags($param, '<lang>');
                    if (!preg_match_all('/<.*>/suU', $param, $matches)) {
                        break;
                    }
                    $open = false;
                    foreach ($matches[0] as $match) {
                        if ($match === '</lang>') {
                            if ($open) {
                                $open = false;
                                continue;
                            } else {
                                break 2;
                            }
                        }
                        if (!preg_match('/^<lang lang="[a-zA-Z0-9_-]+"\s*>$/u', $match)) {
                            break 2;
                        } else {
                            $open = true;
                        }
                    }
                    if ($open) {
                        break;
                    }
                    return $param;

                } else if (strpos($param, '</span>') !== false) {
                    // Current problematic multilang syntax.
                    $param = strip_tags($param, '<span>');
                    if (!preg_match_all('/<.*>/suU', $param, $matches)) {
                        break;
                    }
                    $open = false;
                    foreach ($matches[0] as $match) {
                        if ($match === '</span>') {
                            if ($open) {
                                $open = false;
                                continue;
                            } else {
                                break 2;
                            }
                        }
                        if (!preg_match('/^<span(\s+lang="[a-zA-Z0-9_-]+"|\s+class="multilang"){2}\s*>$/u', $match)) {
                            break 2;
                        } else {
                            $open = true;
                        }
                    }
                    if ($open) {
                        break;
                    }
                    return $param;
                }
            } while (false);
            // Easy, just strip all tags, if we ever want to fix orphaned '&' we have to do that in format_string().
            return strip_tags($param);

        case PARAM_COMPONENT:
            // We do not want any guessing here, either the name is correct or not
            // please note only normalised component names are accepted.
            if (!preg_match('/^[a-z]+(_[a-z][a-z0-9_]*)?[a-z0-9]+$/', $param)) {
                return '';
            }
            if (strpos($param, '__') !== false) {
                return '';
            }
            if (strpos($param, 'mod_') === 0) {
                // Module names must not contain underscores because we need to differentiate them from invalid plugin types.
                if (substr_count($param, '_') != 1) {
                    return '';
                }
            }
            return $param;

        case PARAM_PLUGIN:
        case PARAM_AREA:
            // We do not want any guessing here, either the name is correct or not.
            if (!is_valid_plugin_name($param)) {
                return '';
            }
            return $param;

        case PARAM_SAFEDIR:
            // Remove everything not a-zA-Z0-9_- .
            return preg_replace('/[^a-zA-Z0-9_-]/i', '', $param);

        case PARAM_SAFEPATH:
            // Remove everything not a-zA-Z0-9/_- .
            return preg_replace('/[^a-zA-Z0-9\/_-]/i', '', $param);

        case PARAM_FILE:
            // Strip all suspicious characters from filename.
            $param = fix_utf8($param);
            $param = preg_replace('~[[:cntrl:]]|[&<>"`\|\':\\\\/]~u', '', $param);
            if ($param === '.' || $param === '..') {
                $param = '';
            }
            return $param;

        case PARAM_PATH:
            // Strip all suspicious characters from file path.
            $param = fix_utf8($param);
            $param = str_replace('\\', '/', $param);

            // Explode the path and clean each element using the PARAM_FILE rules.
            $breadcrumb = explode('/', $param);
            foreach ($breadcrumb as $key => $crumb) {
                if ($crumb === '.' && $key === 0) {
                    // Special condition to allow for relative current path such as ./currentdirfile.txt.
                } else {
                    $crumb = clean_param($crumb, PARAM_FILE);
                }
                $breadcrumb[$key] = $crumb;
            }
            $param = implode('/', $breadcrumb);

            // Remove multiple current path (./././) and multiple slashes (///).
            $param = preg_replace('~//+~', '/', $param);
            $param = preg_replace('~/(\./)+~', '/', $param);
            return $param;

        case PARAM_HOST:
            // Allow FQDN or IPv4 dotted quad.
            $param = preg_replace('/[^\.\d\w-]/', '', $param );
            // Match ipv4 dotted quad.
            if (preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/', $param, $match)) {
                // Confirm values are ok.
                if ( $match[0] > 255
                     || $match[1] > 255
                     || $match[3] > 255
                     || $match[4] > 255 ) {
                    // Hmmm, what kind of dotted quad is this?
                    $param = '';
                }
            } else if ( preg_match('/^[\w\d\.-]+$/', $param) // Dots, hyphens, numbers.
                       && !preg_match('/^[\.-]/',  $param) // No leading dots/hyphens.
                       && !preg_match('/[\.-]$/',  $param) // No trailing dots/hyphens.
                       ) {
                // All is ok - $param is respected.
            } else {
                // All is not ok...
                $param='';
            }
            return $param;

        case PARAM_URL:          // Allow safe ftp, http, mailto urls.
            $param = fix_utf8($param);
            include_once($CFG->dirroot . '/lib/validateurlsyntax.php');
            if (!empty($param) && validateUrlSyntax($param, 's?H?S?F?E?u-P-a?I?p?f?q?r?')) {
                // All is ok, param is respected.
            } else {
                // Not really ok.
                $param ='';
            }
            return $param;

        case PARAM_LOCALURL:
            // Allow http absolute, root relative and relative URLs within wwwroot.
            $param = clean_param($param, PARAM_URL);
            if (!empty($param)) {

                // Simulate the HTTPS version of the site.
                $httpswwwroot = str_replace('http://', 'https://', $CFG->wwwroot);

                if ($param === $CFG->wwwroot) {
                    // Exact match;
                } else if (!empty($CFG->loginhttps) && $param === $httpswwwroot) {
                    // Exact match;
                } else if (preg_match(':^/:', $param)) {
                    // Root-relative, ok!
                } else if (preg_match('/^' . preg_quote($CFG->wwwroot . '/', '/') . '/i', $param)) {
                    // Absolute, and matches our wwwroot.
                } else if (!empty($CFG->loginhttps) && preg_match('/^' . preg_quote($httpswwwroot . '/', '/') . '/i', $param)) {
                    // Absolute, and matches our httpswwwroot.
                } else {
                    // Relative - let's make sure there are no tricks.
                    if (validateUrlSyntax('/' . $param, 's-u-P-a-p-f+q?r?')) {
                        // Looks ok.
                    } else {
                        $param = '';
                    }
                }
            }
            return $param;

        case PARAM_PEM:
            $param = trim($param);
            // PEM formatted strings may contain letters/numbers and the symbols:
            //   forward slash: /
            //   plus sign:     +
            //   equal sign:    =
            //   , surrounded by BEGIN and END CERTIFICATE prefix and suffixes.
            if (preg_match('/^-----BEGIN CERTIFICATE-----([\s\w\/\+=]+)-----END CERTIFICATE-----$/', trim($param), $matches)) {
                list($wholething, $body) = $matches;
                unset($wholething, $matches);
                $b64 = clean_param($body, PARAM_BASE64);
                if (!empty($b64)) {
                    return "-----BEGIN CERTIFICATE-----\n$b64\n-----END CERTIFICATE-----\n";
                } else {
                    return '';
                }
            }
            return '';

        case PARAM_BASE64:
            if (!empty($param)) {
                // PEM formatted strings may contain letters/numbers and the symbols
                //   forward slash: /
                //   plus sign:     +
                //   equal sign:    =.
                if (0 >= preg_match('/^([\s\w\/\+=]+)$/', trim($param))) {
                    return '';
                }
                $lines = preg_split('/[\s]+/', $param, -1, PREG_SPLIT_NO_EMPTY);
                // Each line of base64 encoded data must be 64 characters in length, except for the last line which may be less
                // than (or equal to) 64 characters long.
                for ($i=0, $j=count($lines); $i < $j; $i++) {
                    if ($i + 1 == $j) {
                        if (64 < strlen($lines[$i])) {
                            return '';
                        }
                        continue;
                    }

                    if (64 != strlen($lines[$i])) {
                        return '';
                    }
                }
                return implode("\n", $lines);
            } else {
                return '';
            }

        case PARAM_TAG:
            $param = fix_utf8($param);
            // Please note it is not safe to use the tag name directly anywhere,
            // it must be processed with s(), urlencode() before embedding anywhere.
            // Remove some nasties.
            $param = preg_replace('~[[:cntrl:]]|[<>`]~u', '', $param);
            // Convert many whitespace chars into one.
            $param = preg_replace('/\s+/u', ' ', $param);
            $param = core_text::substr(trim($param), 0, TAG_MAX_LENGTH);
            return $param;

        case PARAM_TAGLIST:
            $param = fix_utf8($param);
            $tags = explode(',', $param);
            $result = array();
            foreach ($tags as $tag) {
                $res = clean_param($tag, PARAM_TAG);
                if ($res !== '') {
                    $result[] = $res;
                }
            }
            if ($result) {
                return implode(',', $result);
            } else {
                return '';
            }

        case PARAM_CAPABILITY:
            if (get_capability_info($param)) {
                return $param;
            } else {
                return '';
            }

        case PARAM_PERMISSION:
            $param = (int)$param;
            if (in_array($param, array(CAP_INHERIT, CAP_ALLOW, CAP_PREVENT, CAP_PROHIBIT))) {
                return $param;
            } else {
                return CAP_INHERIT;
            }

        case PARAM_AUTH:
            $param = clean_param($param, PARAM_PLUGIN);
            if (empty($param)) {
                return '';
            } else if (exists_auth_plugin($param)) {
                return $param;
            } else {
                return '';
            }

        case PARAM_LANG:
            $param = clean_param($param, PARAM_SAFEDIR);
            if (get_string_manager()->translation_exists($param)) {
                return $param;
            } else {
                // Specified language is not installed or param malformed.
                return '';
            }

        case PARAM_THEME:
            $param = clean_param($param, PARAM_PLUGIN);
            if (empty($param)) {
                return '';
            } else if (file_exists("$CFG->dirroot/theme/$param/config.php")) {
                return $param;
            } else if (!empty($CFG->themedir) and file_exists("$CFG->themedir/$param/config.php")) {
                return $param;
            } else {
                // Specified theme is not installed.
                return '';
            }

        case PARAM_USERNAME:
            $param = fix_utf8($param);
            $param = trim($param);
            // Convert uppercase to lowercase MDL-16919.
            $param = core_text::strtolower($param);
            if (empty($CFG->extendedusernamechars)) {
                $param = str_replace(" " , "", $param);
                // Regular expression, eliminate all chars EXCEPT:
                // alphanum, dash (-), underscore (_), at sign (@) and period (.) characters.
                $param = preg_replace('/[^-\.@_a-z0-9]/', '', $param);
            }
            return $param;

        case PARAM_EMAIL:
            $param = fix_utf8($param);
            if (validate_email($param)) {
                return $param;
            } else {
                return '';
            }

        case PARAM_STRINGID:
            if (preg_match('|^[a-zA-Z][a-zA-Z0-9\.:/_-]*$|', $param)) {
                return $param;
            } else {
                return '';
            }

        case PARAM_TIMEZONE:
            // Can be int, float(with .5 or .0) or string seperated by '/' and can have '-_'.
            $param = fix_utf8($param);
            $timezonepattern = '/^(([+-]?(0?[0-9](\.[5|0])?|1[0-3](\.0)?|1[0-2]\.5))|(99)|[[:alnum:]]+(\/?[[:alpha:]_-])+)$/';
            if (preg_match($timezonepattern, $param)) {
                return $param;
            } else {
                return '';
            }

        default:
            // Doh! throw error, switched parameters in optional_param or another serious problem.
            print_error("unknownparamtype", '', '', $type);
    }
}

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

/**
 * Makes sure the data is using valid utf8, invalid characters are discarded.
 *
 * Note: this function is not intended for full objects with methods and private properties.
 *
 * @param mixed $value
 * @return mixed with proper utf-8 encoding
 */
function fix_utf8($value) {
    if (is_null($value) or $value === '') {
        return $value;

    } else if (is_string($value)) {
        if ((string)(int)$value === $value) {
            // Shortcut.
            return $value;
        }
        // No null bytes expected in our data, so let's remove it.
        $value = str_replace("\0", '', $value);

        // Note: this duplicates min_fix_utf8() intentionally.
        static $buggyiconv = null;
        if ($buggyiconv === null) {
            $buggyiconv = (!function_exists('iconv') or @iconv('UTF-8', 'UTF-8//IGNORE', '100'.chr(130).'€') !== '100€');
        }

        if ($buggyiconv) {
            if (function_exists('mb_convert_encoding')) {
                $subst = mb_substitute_character();
                mb_substitute_character('');
                $result = mb_convert_encoding($value, 'utf-8', 'utf-8');
                mb_substitute_character($subst);

            } else {
                // Warn admins on admin/index.php page.
                $result = $value;
            }

        } else {
            $result = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        }

        return $result;

    } else if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = fix_utf8($v);
        }
        return $value;

    } else if (is_object($value)) {
        // Do not modify original.
        $value = clone($value);
        foreach ($value as $k => $v) {
            $value->$k = fix_utf8($v);
        }
        return $value;

    } else {
        // This is some other type, no utf-8 here.
        return $value;
    }
}