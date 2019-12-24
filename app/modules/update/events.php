<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Check if an DB update is available and show
 * alert when ACP dashboard is accessed.
 * 
 * @package     CStrike-Regnick
 * @subpackage  Update
 * @author      Alexandru G. ( www.gentle.ro )
 */
class Update_Event
{
    protected static $_ci;
    
    // ------------------------------------------------------------------------
    
    public static function run_update()
    {
        if (!$last_update = get_option('update_last_run'))
        {
            set_option('update_last_run', time());
        }
        
        if (time() > ((int)$last_update+60))
        {
            self::$_ci = & get_instance();
            self::$_ci->load->library('migration');
            self::$_ci->load->library('update/update_lib');
            self::is_update();
        }        
    }
    
    protected static function is_update()
    {
        $db_update      = self::$_ci->migration->db_update_available();
        
        store_location();
        set_option('update_last_run', time());
        
        // notify user only if an update is available.
        if ($db_update)
        {
            notify('Database update available. [ <a href="'. site_url('update/database') .'">update now</a> ]', 'info');
            return;
        }
        
        // +github
        if (self::$_ci->update_lib->release_available())
        {
            notify('A new version of CStrike-Regnick is available. Please update as soon as posible. <br> 
                    Visit <a href="http://www.gentle.ro/proiecte/cstrike-regnick/">official page</a> for more informations.', 'info');
        }
        // -github 
        
        //redirect('');
    }
}

Events::listen('acp_dashboard', 'Update_Event::run_update');
