<?php
if (!function_exists('filterQueryString')) {
    function filterQueryString($queryString)
    {
        parse_str($queryString, $array);
        $filterQueryString = "";
        foreach ($array as $key => $value) {
            if ($filterQueryString == "") {
                $filterQueryString = htmlspecialchars(urlencode($key), ENT_HTML5 | ENT_QUOTES) . '=' . htmlspecialchars(urlencode($value), ENT_HTML5 | ENT_QUOTES);
            } else {
                $filterQueryString .= '&' . htmlspecialchars(urlencode($key), ENT_HTML5 | ENT_QUOTES) . '=' . htmlspecialchars(urlencode($value), ENT_HTML5 | ENT_QUOTES);
            }
        }
        return $filterQueryString;
    }
}

if (!function_exists('random_string')) {
    function random_string($length=15) {
        $randombytes = random_bytes_emulate($length);
        $pool  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pool .= 'abcdefghijklmnopqrstuvwxyz';
        $pool .= '0123456789';
        $poollen = strlen($pool);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $rand = ord($randombytes[$i]);
            $string .= substr($pool, ($rand%($poollen)), 1);
        }
        return $string;
    }   
}

if (!function_exists('random_bytes_emulate')) {
    function random_bytes_emulate($length) {
        global $CFG;
        if ($length <= 0) {
            debugging('Invalid random bytes length', DEBUG_DEVELOPER);
            return '';
        }
        if (function_exists('random_bytes')) {
            // Use PHP 7 goodness.
            $hash = @random_bytes($length);
            if ($hash !== false) {
                return $hash;
            }
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            // For PHP 5.3 and later with openssl extension.
            $hash = openssl_random_pseudo_bytes($length);
            if ($hash !== false) {
                return $hash;
            }
        }

        // Bad luck, there is no reliable random generator, let's just hash some unique stuff that is hard to guess.
        $hash = sha1(serialize($CFG) . serialize($_SERVER) . microtime(true) . uniqid('', true), true);
        // NOTE: the last param in sha1() is true, this means we are getting 20 bytes, not 40 chars as usual.
        if ($length <= 20) {
            return substr($hash, 0, $length);
        }
        return $hash . random_bytes_emulate($length - 20);
    }
}

if (!function_exists('convert_to_array')) {
    function convert_to_array($var) {
        $result = array();

        // Loop over elements/properties.
        foreach ($var as $key => $value) {
            // Recursively convert objects.
            if (is_object($value) || is_array($value)) {
                $result[$key] = convert_to_array($value);
            } else {
                // Simple values are untouched.
                $result[$key] = $value;
            }
        }
        return $result;
    }
}