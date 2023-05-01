<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter-HMVC
 *
 * @package    CodeIgniter-HMVC
 * @author     Martin <martin@click-ap.com>
 * @copyright  2023 Click-AP {@link https://www.click-ap.com}
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @version    GIT: $Id$
 * @since      Version 0.0.1
 */

class RequireJS extends MY_Controller
{

    /**
     * An array of variables to be passed through to the view, layout, ....
     */
    protected $data = array();

    /**
     * [__construct description]
     *
     * @method __construct
     */
    public function __construct()
    {
        global $CFG;
        define('MOODLE_INTERNAL', true);
//debugBreak();
        $CFG->yui2version = '2.9.0';
        $CFG->yui3version = '3.17.2';
        $CFG->dirroot = FCPATH;
        $CFG->libdir = $CFG->dirroot . '/lib';
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $this->load->helper('configonlylib');
        $this->load->helper('jslib');
        $this->load->library(['core/minify']);
        $path = $this->CI->config->item('cache_path');
        $CFG->localcachedir = ($path === '') ? APPPATH.'cache' : $path;
        $CFG->directorypermissions = 02777;
        $CFG->filepermissions      = ($CFG->directorypermissions & 0666);
    }

    public function get($id = null, $path, $scriptfile) 
    {
        global $CFG;
        /*$slashargument = min_get_slash_argument();
        if (!$slashargument) {
            // The above call to min_get_slash_argument should always work.
            die('Invalid request');
        }/** */
        $uri = current_url(true);
        $slashargument= $id.'/'.$path.'/'.$scriptfile;
        $slashargument = ltrim($slashargument, '/');
        if (substr_count($slashargument, '/') < 1) {
            header('HTTP/1.0 404 not found');
            die('Slash argument must contain both a revision and a file path');
        }
        // Split into revision and module name.
        list($rev, $file) = explode('/', $slashargument, 2);
        $rev  = min_clean_param($rev, 'INT');
        $file = '/' . min_clean_param($file, 'SAFEPATH');

        // Only load js files from the js modules folder from the components.
        $jsfiles = array();
        list($unused, $component, $module) = explode('/', $file, 3);

        // No subdirs allowed - only flat module structure please.
        if (strpos('/', $module) !== false) {
            die('Invalid module');
        }

        // Some (huge) modules are better loaded lazily (when they are used). If we are requesting
        // one of these modules, only return the one module, not the combo.
        $lazysuffix = "-lazy.js";
        $lazyload = (strpos($module, $lazysuffix) !== false);

        if ($lazyload) {
            // We are lazy loading a single file - so include the component/filename pair in the etag.
            $etag = sha1($rev . '/' . $component . '/' . $module);
        } else {
            // We loading all (non-lazy) files - so only the rev makes this request unique.
            $etag = sha1($rev);
        }


        // Use the caching only for meaningful revision numbers which prevents future cache poisoning.
        if ($rev > 0 and $rev < (time() + 60 * 60)) {
            $candidate = $CFG->localcachedir . '/requirejs/' . $etag;
//debugBreak();
            if (file_exists($candidate)) {
                if (!empty($_SERVER['HTTP_IF_NONE_MATCH']) || !empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                    // We do not actually need to verify the etag value because our files
                    // never change in cache because we increment the rev parameter.
                    js_send_unmodified(filemtime($candidate), $etag);
                }
                js_send_cached($candidate, $etag, 'requirejs.php');
                exit(0);
            } else {
                $jsfiles = array();
                if ($lazyload) {
                    $jsfiles = core_requirejs::find_one_amd_module($component, $module);
                } else {
                    // Here we respond to the request by returning ALL amd modules. This saves
                    // round trips in production.

                    $jsfiles = core_requirejs::find_all_amd_modules();
                }

                $content = '';
                foreach ($jsfiles as $modulename => $jsfile) {
                    $js = file_get_contents($jsfile) . "\n";
                    // Inject the module name into the define.
                    $replace = 'define(\'' . $modulename . '\', ';
                    $search = 'define(';
                    // Replace only the first occurrence.
                    $js = implode($replace, explode($search, $js, 2));
                    $content .= $js;
                }

                js_write_cache_file_content($candidate, $content);
                // Verify nothing failed in cache file creation.
                clearstatcache();
                if (file_exists($candidate)) {
                    js_send_cached($candidate, $etag, 'requirejs.php');
                    exit(0);
                }
            }
        }

        if ($lazyload) {
            $jsfiles = core_requirejs::find_one_amd_module($component, $module, true);
        } else {
            $jsfiles = core_requirejs::find_all_amd_modules(true);
        }

        $content = '';
        foreach ($jsfiles as $modulename => $jsfile) {
            $shortfilename = str_replace($CFG->dirroot, '', $jsfile);
            $js = "// ---- $shortfilename ----\n";
            $js .= file_get_contents($jsfile) . "\n";
            // Inject the module name into the define.
            $replace = 'define(\'' . $modulename . '\', ';
            $search = 'define(';

            if (strpos($js, $search) === false) {
                // We can't call debugging because we only have minimal config loaded.
                header('HTTP/1.0 500 error');
                die('JS file: ' . $shortfilename . ' does not contain a javascript module in AMD format. "define()" not found.');
            }

            // Replace only the first occurrence.
            $js = implode($replace, explode($search, $js, 2));
            $content .= $js;
        }
        js_send_uncached($content, $etag, 'requirejs.php');
    }

    function combo_send_cached($content, $mimetype, $etag, $lastmodified)
    {
        $lifetime = 60 * 60 * 24 * 360; // 1 year, we do not change YUI versions often, there are a few custom yui modules

        header('Content-Disposition: inline; filename="combo"');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
        header('Pragma: ');
        header('Cache-Control: public, max-age=' . $lifetime);
        header('Accept-Ranges: none');
        header('Content-Type: ' . $mimetype);
        header('Etag: "' . $etag . '"');
        if (!min_enable_zlib_compression()) {
            header('Content-Length: ' . strlen($content));
        }

        echo $content;
        die;
    }

    function combo_send_uncached($content, $mimetype)
    {
        header('Content-Disposition: inline; filename="combo"');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 2) . ' GMT');
        header('Pragma: ');
        header('Accept-Ranges: none');
        header('Content-Type: ' . $mimetype);
        if (!min_enable_zlib_compression()) {
            header('Content-Length: ' . strlen($content));
        }

        echo $content;
        die;
    }

    function combo_not_found($message = '')
    {
        header('HTTP/1.0 404 not found');
        if ($message) {
            echo $message;
        } else {
            echo 'Combo resource not found, sorry.';
        }
        die;
    }

    function combo_params()
    {
debugBreak();
        if (isset($_SERVER['QUERY_STRING']) and strpos($_SERVER['QUERY_STRING'], 'file=/') === 0) {
            // url rewriting
            $slashargument = substr($_SERVER['QUERY_STRING'], 6);
            return array($slashargument, true);
        } else if (isset($_SERVER['REQUEST_URI']) and strpos($_SERVER['REQUEST_URI'], '?') !== false) {
            $parts = explode('?', $_SERVER['REQUEST_URI'], 2);
            return array($parts[1], false);
        } else if (isset($_SERVER['QUERY_STRING']) and strpos($_SERVER['QUERY_STRING'], '?') !== false) {
            // note: buggy or misconfigured IIS does return the query string in REQUEST_URI
            return array($_SERVER['QUERY_STRING'], false);
        } else if ($slashargument = min_get_slash_argument(false)) {
            $slashargument = ltrim($slashargument, '/');
            return array($slashargument, true);
        } else {
            // unsupported server, sorry!
            $this->combo_not_found('Unsupported server - query string can not be determined, try disabling YUI combo loading in admin settings.');
        }
    }
}
