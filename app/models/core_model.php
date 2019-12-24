<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This file is part of the CStrike-Regnick package
 * 
 * (c) Gentle Software Solutions <www.gentle.ro>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// ------------------------------------------------------------------------

/**
 * Core model
 * 
 * Provides database access for options needed on application global level.
 * 
 * @package     CStrike-Regnick
 * @category    Models
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class core_model extends MY_Model {
    
    /**
     * Fetch specified option from DB
     * 
     * @access  public
     * @param   string      $name   Option name
     * @return  string|bool         False on error
     */
    public function get_option($name)
    {
        $query = $this->db->select('name, value')
                    ->where('name', $name)
                    ->limit(1)
                    ->get('options');
        
        $option = $query->row();
        
        return is_object($option) ? $option->value : false;
    }
    
    /**
     * Fetch specified options from DB
     * 
     * @access  public
     * @param   string      $name   Option name
     * @return  string|bool         False on error
     */
    public function get_options(array $names)
    {
        $results = array();
        $where = "name = '". implode("' OR name = '", $names)."'";
                
        $result = $this->db->get_where('options', $where)->result_array();
        
        if ( is_array($result) AND count($result)>0 )
        {
            foreach ($result as $r)
            {
                $results[$r['name']] = $r['value'];
            }
        }
        
        return $results;
    }
    
    /**
     * Add a new option or update an existing one
     * 
     * Values are stored as strings, containing a byte-stream 
     * representation of `value`.
     * 
     * @access  public
     * @param   string  $name
     * @param   string  $value
     * @see     serialize()
     * @see     update_option()
     * @see     insert_option()
     * @return  bool
     */
    public function set_option($name, $value)
    {
        if ($this->is_option($name))
        {
            return $this->update_option($name, $value);
        }
        else
        {
            return $this->insert_option($name, $value);
        }
    }
    
    /**
     * Update multiple options at the same time
     * 
     * @access  public
     * @param   array   $options
     * @return  mixed               False on error
     */
    public function set_options(array $options)
    {
        return is_null($this->db->update_batch('options', $options, 'name'));
    }
    
    /**
     * Check if specified option exists
     * 
     * @access  public
     * @param   string  $name   Option name
     * @return  bool
     */
    public function is_option($name)
    {
        $query = $this->db->select('name')
                        ->where('name', $name)
                        ->limit(1)
                        ->get('options');
                
        return ($query->num_rows() == 1) ? true : false;
    }
    
    /**
     * Delete specified option
     * 
     * @access  public
     * @param   string  $name   Option name
     * @return  bool
     */
    public function delete_option($name)
    {
        if (!$this->is_option($name))
        {
            return false;
        }
        
        $this->db->where('name', $name);
        $this->db->delete('options');
        
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    
    /**
     * Update specified option
     * 
     * @access  protected
     * @param   string      $name   Option name
     * @param   string      $value  Option value
     * @return  bool
     */
    protected function update_option($name, $value)
    {
        $data = array(
            'value' => $value,
        );
        
        $this->db->where('name', $name)->update('options', $data);
        
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * Insert specified option
     * 
     * @access  protected
     * @param   string      $name   Option name
     * @param   string      $value  Option value
     * @return  bool
     */
    protected function insert_option($name, $value)
    {
        $data = array(
            'name'  => $name,
            'value' => $value,
        );
        
        return $this->db->insert('options', $data);
    }
    
}

/* End of file */