<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter-HMVC
 *
 * @package    CodeIgniter-HMVC
 * @author     Martin <martin@click-ap.com>
 * @copyright  2023 Click-AP {@link https://www.click-ap.com }
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @version    GIT: $Id$
 * @since      Version 0.0.1
 */

class FrontendController extends MY_Controller
{
    //
    public $CI;

    /**
     * Cache settings
     * @var array
     */
    private $cache_settings;

    /**
     * An array of variables to be passed through to the view, layout, ....
     */
    protected $data = array();

    /**
     * @var array of moodle_url List of custom theme sheets, these are strongly discouraged!
     * Useful mostly only for CSS submitted by teachers that is not part of the theme.
     */
    protected $cssurls = array();

    /**
     * @var array of moodle_url Theme sheets, initialised only from core_renderer
     */
    protected $cssthemeurls = array();

    protected $M_cfg;

    protected $jsinitcode = array();

    /**
     * @var array Extra modules
     */
    protected $extramodules = array();

    /**
     * @var bool Flag indicated head stuff already printed
     */
    protected $headdone = false;

    protected $yui3loader;

    protected $YUI_config;

    /**
     * @var array List of JS variables to be initialised
     */
    protected $jsinitvariables = array('head'=>array(), 'footer'=>array());

    /**
     * @var array Included JS scripts
     */
    protected $jsincludes = array('head'=>array(), 'footer'=>array());

    /**
     * [__construct description]
     *
     * @method __construct
     */
    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();

        // CI Profiler for debugging
        //$this->output->enable_profiler(true);

        // This function returns the main CodeIgniter object.
        // Normally, to call any of the available CodeIgniter object or pre defined library classes then you need to declare.
        $this->CI =& get_instance();

//debugBreak();
        // Cache settings
        $this->CI->load->config('smarty_acl', TRUE);
        $this->cache_settings = $this->CI->config->item('cache_settings', 'smarty_acl');
        //if ($this->cache_settings['status'] === TRUE) {
            $this->CI->load->driver('cache', array('adapter' => $this->cache_settings['driver'], 'backup' => $this->cache_settings['driver_fallback']));
        //}
        $cache = $this->CI->cache->get('foo');

        $sep = '/'; // $sep = empty($CFG->yuislasharguments) ? '?' : '/';

        $this->yui3loader = new stdClass();
//debugBreak();
        $this->load->library('YUI_config');
        $this->YUI_config = new YUI_config();
        // $this->load->config('yui'), $this->config->item('
        // Set up some loader options.
        $this->yui3loader->local_base = base_url('/lib/yuilib/') . $CFG->yui3version . '/';
        $this->yui3loader->local_comboBase = base_url('/theme/yui_combo.php') . $sep;
        $this->yui3loader->base = $this->yui3loader->local_base;
        $this->yui3loader->comboBase = $this->yui3loader->local_comboBase;
        // Enable combo loader? This significantly helps with caching and performance!
        $this->yui3loader->combine = TRUE; //!empty($CFG->yuicomboloading);
        $jsrev = $this->get_jsrev();
        // Set up JS YUI loader helper object.
        $this->YUI_config->base         = $this->yui3loader->base;
        $this->YUI_config->comboBase    = $this->yui3loader->comboBase;
        $this->YUI_config->combine      = $this->yui3loader->combine;
        //$configname = "@yui1ConfigFn@";
//debugBreak();
        $configname = $this->YUI_config->set_config_source('lib/yui/config/yui2.js');
        $yui2version = '2.9.0';
        $this->YUI_config->add_group('yui2', array(
            // Loader configuration for our 2in3, for now ignores $CFG->useexternalyui.
            'base' => base_url('/lib/yuilib/2in3/') . $yui2version . '/build/',
            'comboBase' => base_url('/theme/yui_combo.php') . $sep,
            'combine' => $this->yui3loader->combine,
            'ext' => false,
            'root' => '2in3/' . $yui2version .'/build/',
            'patterns' => array(
                'yui2-' => array(
                    'group' => 'yui2',
                    'configFn' => $configname,
                )
            )
        ));
//debugBreak();
        $configname = $this->YUI_config->set_config_source('lib/yui/config/moodle.js');
        $this->YUI_config->add_group('moodle', array(
            'name' => 'moodle',
            'base' => base_url('/theme/yui_combo.php') . $sep . 'm/' . $jsrev . '/',
            'combine' => $this->yui3loader->combine,
            'comboBase' => base_url('/theme/yui_combo.php') . $sep,
            'ext' => false,
            'root' => 'm/'.$jsrev.'/', // Add the rev to the root path so that we can control caching.
            'patterns' => array(
                'moodle-' => array(
                    'group' => 'moodle',
                    'configFn' => $configname,
                )
            )
        ));

        $this->YUI_config->add_group('gallery', array(
            'name' => 'gallery',
            'base' => base_url('/lib/yuilib/gallery/'),
            'combine' => $this->yui3loader->combine,
            'comboBase' => base_url('/theme/yui_combo.php') . $sep,
            'ext' => false,
            'root' => 'gallery/' . $jsrev . '/',
            'patterns' => array(
                'gallery-' => array(
                    'group' => 'gallery',
                )
            )
        ));
debugBreak();
        // $CFG->debugdeveloper
        $this->YUI_config->filter = 'RAW';
        $this->YUI_config->groups['moodle']['filter'] = 'DEBUG';
        // We use the yui3loader->filter setting when writing the YUI3 seed scripts into the header.
        $this->yui3loader->filter = $this->YUI_config->filter;
        $this->YUI_config->debug = true;
        
        // Add the moodle group's module data.
        $this->YUI_config->add_moodle_metadata(); // 取所有外掛的 meta

        // Every page should include definition of following modules.
        //$this->js_module($this->find_module('core_filepicker'));
        // end of outputrequirementslib::__construct()

//debugBreak();
        $this->load->library('theme');

        // Example data
        // Site name
        $this->data['sitename'] = 'CodeIgniter-HMVC';

        // Example data
        // Browser tab
        $this->data['site_title'] = ucfirst('Frontend');
    }

    /**
     * Return the safe config values that get set for javascript in "M.cfg".
     *
     * @since 2.9
     * @return array List of safe config values that are available to javascript.
     */
    function get_config_for_javascript() {
        global $CFG;

        if (empty($this->M_cfg)) {
            // JavaScript should always work with $CFG->httpswwwroot rather than $CFG->wwwroot.
            // Otherwise, in some situations, users will get warnings about insecure content
            // on secure pages from their web browser.

            $this->M_cfg = array(
                'wwwroot'             => $CFG->httpswwwroot, // Yes, really. See above.
                'sesskey'             => $this->sesskey(),
                'themerev'            => -1,
                'slasharguments'      => 1,
                'theme'               => 'default',
                'jsrev'               => -1,
                'admin'               => 'admin'
            );
            //if ($CFG->debugdeveloper) {
                $this->M_cfg['developerdebug'] = true;
            //}
        }
        return $this->M_cfg;
    }

    function yui_module($modules, $function, array $arguments = null, $galleryversion = null, $ondomready = false) {
        if (!is_array($modules)) {
            $modules = array($modules);
        }

        if ($galleryversion != null) {
            debugging('The galleryversion parameter to yui_module has been deprecated since Moodle 2.3.');
        }

        $jscode = 'Y.use('.join(',', array_map('json_encode', convert_to_array($modules))).',function() {'.js_writer::function_call($function, $arguments).'});';
        if ($ondomready) {
            $jscode = "Y.on('domready', function() { $jscode });";
        }
        $this->jsinitcode[] = $jscode;
    }

    function init_requirements_data() {
        // Init the js config.
        $this->get_config_for_javascript();
        $this->yui_module('moodle-core-blocks', 'M.core_blocks.init_dragdrop', [], null, true);
    }

    /**
     * Determine the correct JS Revision to use for this load.
     *
     * @return int the jsrev to use.
     */
    protected function get_jsrev() {
        global $CFG;

        if (empty($CFG->cachejs)) {
            $jsrev = -1;
        } else if (empty($CFG->jsrev)) {
            $jsrev = 1;
        } else {
            $jsrev = $CFG->jsrev;
        }

        return $jsrev;
    }


    protected function get_yui3lib_headcode() {
        global $CFG;

        $code = '';

        $jsrev = $this->get_jsrev();
debugBreak();
        $yuiformat = '-min';
        if ($this->yui3loader->filter === 'RAW') {
            $yuiformat = '';
        }

        $format = '-min';
        if ($this->YUI_config->groups['moodle']['filter'] === 'DEBUG') {
            $format = '-debug';
        }

        $rollupversion = $CFG->yui3version;
        if (!empty($CFG->yuipatchlevel)) {
            $rollupversion .= '_' . $CFG->yuipatchlevel;
        }

        $baserollups = array(
            'rollup/' . $rollupversion . "/yui-moodlesimple{$yuiformat}.js",
            'rollup/' . $jsrev . "/mcore{$format}.js",
        );

        if ($this->yui3loader->combine) {
            if (!empty($page->theme->yuicssmodules)) {
                $modules = array();
                foreach ($page->theme->yuicssmodules as $module) {
                    $modules[] = "$CFG->yui3version/$module/$module-min.css";
                }
                $code .= '<link rel="stylesheet" type="text/css" href="'.$this->yui3loader->comboBase.implode('&amp;', $modules).'" />';
            }
            $code .= '<link rel="stylesheet" type="text/css" href="'.$this->yui3loader->local_comboBase.'rollup/'.$CFG->yui3version.'/yui-moodlesimple' . $yuiformat . '.css" />';
            $code .= '<script type="text/javascript" src="'.$this->yui3loader->local_comboBase
                    . implode('&amp;', $baserollups) . '"></script>';

        } else {
            if (!empty($page->theme->yuicssmodules)) {
                foreach ($page->theme->yuicssmodules as $module) {
                    $code .= '<link rel="stylesheet" type="text/css" href="'.$this->yui3loader->base.$module.'/'.$module.'-min.css" />';
                }
            }
            $code .= '<link rel="stylesheet" type="text/css" href="'.$this->yui3loader->local_comboBase.'rollup/'.$CFG->yui3version.'/yui-moodlesimple' . $yuiformat . '.css" />';
            foreach ($baserollups as $rollup) {
                $code .= '<script type="text/javascript" src="'.$this->yui3loader->local_comboBase.$rollup.'"></script>';
            }
        }

        if ($this->yui3loader->filter === 'RAW') {
            $code = str_replace('-min.css', '.css', $code);
        } else if ($this->yui3loader->filter === 'DEBUG') {
            $code = str_replace('-min.css', '.css', $code);
        }
        return $code;
    }

    /**
     * Return jQuery related markup for page start.
     * @return string
     */
    protected function get_jquery_headcode() {
        if (empty($this->jqueryplugins['jquery'])) {
            // If nobody requested jQuery then do not bother to load anything.
            // This may be useful for themes that want to override 'ui-css' only if requested by something else.
            return '';
        }

        $included = array();
        $urls = array();

        foreach ($this->jqueryplugins as $name => $unused) {
            if (isset($included[$name])) {
                continue;
            }
            if (array_key_exists($name, $this->jquerypluginoverrides)) {
                // The following loop tries to resolve the replacements,
                // use max 100 iterations to prevent infinite loop resulting
                // in blank page.
                $cyclic = true;
                $oldname = $name;
                for ($i=0; $i<100; $i++) {
                    $name = $this->jquerypluginoverrides[$name];
                    if (!array_key_exists($name, $this->jquerypluginoverrides)) {
                        $cyclic = false;
                        break;
                    }
                }
                if ($cyclic) {
                    // We can not do much with cyclic references here, let's use the old plugin.
                    $name = $oldname;
                    debugging("Cyclic overrides detected for jQuery plugin '$name'");

                } else if (empty($name)) {
                    // Developer requested removal of the plugin.
                    continue;

                } else if (!isset($this->jqueryplugins[$name])) {
                    debugging("Unknown jQuery override plugin '$name' detected");
                    $name = $oldname;

                } else if (isset($included[$name])) {
                    // The plugin was already included, easy.
                    continue;
                }
            }

            $plugin = $this->jqueryplugins[$name];
            $urls = array_merge($urls, $plugin->urls);
            $included[$name] = true;
        }

        $output = '';
        $attributes = array('rel' => 'stylesheet', 'type' => 'text/css');
        foreach ($urls as $url) {
            if (preg_match('/\.js$/', $url)) {
                $output .= html_writer::script('', $url);
            } else if (preg_match('/\.css$/', $url)) {
                $attributes['href'] = $url;
                $output .= html_writer::empty_tag('link', $attributes) . "\n";
            }
        }

        return $output;
    }

    /**
     * Returns html tags needed for inclusion of theme CSS.
     *
     * @return string
     */
    protected function get_css_code() {
        // First of all the theme CSS, then any custom CSS
        // Please note custom CSS is strongly discouraged,
        // because it can not be overridden by themes!
        // It is suitable only for things like mod/data which accepts CSS from teachers.
        $attributes = array('rel'=>'stylesheet', 'type'=>'text/css');

        // This line of code may look funny but it is currently required in order
        // to avoid MASSIVE display issues in Internet Explorer.
        // As of IE8 + YUI3.1.1 the reference stylesheet (firstthemesheet) gets
        // ignored whenever another resource is added until such time as a redraw
        // is forced, usually by moving the mouse over the affected element.
        $code = html_writer::tag('script', '/** Required in order to fix style inclusion problems in IE with YUI **/', array('id'=>'firstthemesheet', 'type'=>'text/css'));

        $urls = $this->cssthemeurls + $this->cssurls;
debugBreak();
        foreach ($urls as $url) {
            $attributes['href'] = $url;
            $code .= html_writer::empty_tag('link', $attributes) . "\n";
            // This id is needed in first sheet only so that theme may override YUI sheets loaded on the fly.
            unset($attributes['id']);
        }

        return $code;
    }

    public function js_init_call($function, array $extraarguments = null, $ondomready = false, array $module = null) {
        $jscode = js_writer::function_call_with_Y($function, $extraarguments);
        if (!$module) {
            // Detect module automatically.
            if (preg_match('/M\.([a-z0-9]+_[^\.]+)/', $function, $matches)) {
                $module = $this->find_module($matches[1]);
            }
        }

        $this->js_init_code($jscode, $ondomready, $module);
    }

    /**
     * Find out if JS module present and return details.
     *
     * @param string $component name of component in frankenstyle, ex: core_group, mod_forum
     * @return array description of module or null if not found
     */
    protected function find_module($component) {
        global $CFG, $PAGE;

        $module = null;

        if (strpos($component, 'core_') === 0) {
            // Must be some core stuff - list here is not complete, this is just the stuff used from multiple places
            // so that we do nto have to repeat the definition of these modules over and over again.
            switch($component) {
                case 'core_filepicker':
                    $module = array('name'     => 'core_filepicker',
                                    'fullpath' => '/repository/filepicker.js',
                                    'requires' => array('base', 'node', 'node-event-simulate', 'json', 'async-queue', 'io-base', 'io-upload-iframe', 'io-form', 'yui2-treeview', 'panel', 'cookie', 'datatable', 'datatable-sort', 'resize-plugin', 'dd-plugin', 'escape', 'moodle-core_filepicker'),
                                    'strings'  => array(array('lastmodified', 'moodle'), array('name', 'moodle'), array('type', 'repository'), array('size', 'repository'),
                                                        array('invalidjson', 'repository'), array('error', 'moodle'), array('info', 'moodle'),
                                                        array('nofilesattached', 'repository'), array('filepicker', 'repository'), array('logout', 'repository'),
                                                        array('nofilesavailable', 'repository'), array('norepositoriesavailable', 'repository'),
                                                        array('fileexistsdialogheader', 'repository'), array('fileexistsdialog_editor', 'repository'),
                                                        array('fileexistsdialog_filemanager', 'repository'), array('renameto', 'repository'),
                                                        array('referencesexist', 'repository'), array('select', 'repository')
                                                    ));
                    break;
                case 'core_comment':
                    $module = array('name'     => 'core_comment',
                                    'fullpath' => '/comment/comment.js',
                                    'requires' => array('base', 'io-base', 'node', 'json', 'yui2-animation', 'overlay'),
                                    'strings' => array(array('confirmdeletecomments', 'admin'), array('yes', 'moodle'), array('no', 'moodle'))
                                );
                    break;
                case 'core_role':
                    $module = array('name'     => 'core_role',
                                    'fullpath' => '/admin/roles/module.js',
                                    'requires' => array('node', 'cookie'));
                    break;
                case 'core_completion':
                    $module = array('name'     => 'core_completion',
                                    'fullpath' => '/course/completion.js');
                    break;
                case 'core_message':
                    $module = array('name'     => 'core_message',
                                    'requires' => array('base', 'node', 'event', 'node-event-simulate'),
                                    'fullpath' => '/message/module.js');
                    break;
                case 'core_group':
                    $module = array('name'     => 'core_group',
                                    'fullpath' => '/group/module.js',
                                    'requires' => array('node', 'overlay', 'event-mouseenter'));
                    break;
                case 'core_question_engine':
                    $module = array('name'     => 'core_question_engine',
                                    'fullpath' => '/question/qengine.js',
                                    'requires' => array('node', 'event'));
                    break;
                case 'core_rating':
                    $module = array('name'     => 'core_rating',
                                    'fullpath' => '/rating/module.js',
                                    'requires' => array('node', 'event', 'overlay', 'io-base', 'json'));
                    break;
                case 'core_dndupload':
                    $module = array('name'     => 'core_dndupload',
                                    'fullpath' => '/lib/form/dndupload.js',
                                    'requires' => array('node', 'event', 'json', 'core_filepicker'),
                                    'strings'  => array(array('uploadformlimit', 'moodle'), array('droptoupload', 'moodle'), array('maxfilesreached', 'moodle'),
                                                        array('dndenabled_inbox', 'moodle'), array('fileexists', 'moodle'), array('maxbytesforfile', 'moodle'),
                                                        array('maxareabytesreached', 'moodle'), array('serverconnection', 'error'),
                                                    ));
                    break;
            }

        } else {
            if ($dir = core_component::get_component_directory($component)) {
                if (file_exists("$dir/module.js")) {
                    if (strpos($dir, $CFG->dirroot.'/') === 0) {
                        $dir = substr($dir, strlen($CFG->dirroot));
                        $module = array('name'=>$component, 'fullpath'=>"$dir/module.js", 'requires' => array());
                    }
                }
            }
        }

        return $module;
    }

    /**
     * Add short static javascript code fragment to page footer.
     * This is intended primarily for loading of js modules and initialising page layout.
     * Ideally the JS code fragment should be stored in plugin renderer so that themes
     * may override it.
     *
     * @param string $jscode
     * @param bool $ondomready wait for dom ready (helps with some IE problems when modifying DOM)
     * @param array $module JS module specification array
     */
    public function js_init_code($jscode, $ondomready = false, array $module = null) {
        $jscode = trim($jscode, " ;\n"). ';';

        $uniqid = html_writer::random_id();
        $startjs = " M.util.js_pending('" . $uniqid . "');";
        $endjs = " M.util.js_complete('" . $uniqid . "');";

        if ($module) {
            $this->js_module($module);
            $modulename = $module['name'];
            $jscode = "$startjs Y.use('$modulename', function(Y) { $jscode $endjs });";
        }

        if ($ondomready) {
            $jscode = "$startjs Y.on('domready', function() { $jscode $endjs });";
        }

        $this->jsinitcode[] = $jscode;
    }

    /**
     * Append YUI3 module to default YUI3 JS loader.
     * The structure of module array is described at {@link http://developer.yahoo.com/yui/3/yui/}
     *
     * @param string|array $module name of module (details are autodetected), or full module specification as array
     * @return void
     */
    public function js_module($module) {
        global $CFG;

        if (empty($module)) {
            throw new coding_exception('Missing YUI3 module name or full description.');
        }

        if (is_string($module)) {
            $module = $this->find_module($module);
        }

        if (empty($module) or empty($module['name']) or empty($module['fullpath'])) {
            throw new coding_exception('Missing YUI3 module details.');
        }

        $module['fullpath'] = $this->js_fix_url($module['fullpath'])->out(false);
        // Add all needed strings.
        if (!empty($module['strings'])) {
            foreach ($module['strings'] as $string) {
                $identifier = $string[0];
                $component = isset($string[1]) ? $string[1] : 'moodle';
                $a = isset($string[2]) ? $string[2] : null;
                $this->string_for_js($identifier, $component, $a);
            }
        }
        unset($module['strings']);

        // Process module requirements and attempt to load each. This allows
        // moodle modules to require each other.
        if (!empty($module['requires'])){
            foreach ($module['requires'] as $requirement) {
                $rmodule = $this->find_module($requirement);
                if (is_array($rmodule)) {
                    $this->js_module($rmodule);
                }
            }
        }

        if ($this->headdone) {
            $this->extramodules[$module['name']] = $module;
        } else {
            $this->YUI_config->add_module_config($module['name'], $module);
        }
    }
    
}
