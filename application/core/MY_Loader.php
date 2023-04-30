<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter-HMVC
 *
 * @package    CodeIgniter-HMVC
 * @author     Martin <martin@click-ap.com>
 * @copyright  2023 Click-AP {@link https://www.click-ap.com}
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @link       <URI> (description)
 * @version    GIT: $Id$
 * @since      Version 0.0.1
 * @filesource
 *
 */

// load the MX_Loader class
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader
{
    //
    public $CI;

    /**
     * An array of variables to be passed through to the
     * view, layout,....
     */
    protected $data = array();
    
    /**
     * List of loaded views
     *
     * @return array
     */
    protected $_ci_views = array();

    /**
     * [__construct description]
     *
     * @method __construct
     */
    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();

        //
        $CI = & get_instance();
    }
    
    /**
     * List of loaded helpers
     *
     * @return array
     */
    public function get_helpers()
    {
        return $this->_ci_helpers;
    }

    /**
     * List of loaded views
     *
     * @return array
     */
    public function get_views()
    {
        return $this->_ci_views;
    }

    /**
     * List of loaded models
     *
     * @return mixed
     */
    public function get_models(){
        return $this->_ci_models;
    }

}