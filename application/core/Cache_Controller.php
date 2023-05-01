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

class CacheController extends MY_Controller
{
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
        //$this->load->library('cache');
//debugBreak();
        // Cache settings
        /*$this->CI->load->config('smarty_acl', TRUE);
        $this->cache_settings = $this->CI->config->item('cache_settings', 'smarty_acl');
        //if ($this->cache_settings['status'] === TRUE) {
            $this->CI->load->driver('cache', array('adapter' => $this->cache_settings['driver'], 'backup' => $this->cache_settings['driver_fallback']));
        //}
        //$cache = $this->CI->cache;
        $cache = $this->cache;
        $foo = $cache->get('foo');
        if (!$foo) {
            $foo = $cache->save('foo', 1);
            $foo = 1;
        }
        $foo = $cache->increment('foo');
        var_dump($cache->cache_info());
        $foo = $cache->get('foo');
        /** */
debugBreak();
        //$cache = cache::make('core', 'config');
        $this->load->driver('cache', array('adapter' => 'redis','backup' => 'file'));
        if($this->cache->redis->is_supported()) {
            $cached = $this->cache->get('key');
            if ($cached != null){
                echo $cached;
            }
            else{
                echo 'Some Value';
                $this->cache->save('key', 'Some Value');
            }
        }
    }
    
}
