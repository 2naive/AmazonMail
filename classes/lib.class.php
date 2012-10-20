<?php

/**
* Lib - Helper static functions class
*
* @author Nedzelsky Alexander <to.naive@gmail.com>
* @link http://stupid.su
* @version 0.0.1
*
* @var array   $debug_info         -   debug information
*/

class Lib {

    public static $debug_info       =   array();
    public static $debug_log        =   false;
    public static $console_mode     =   false;

    /**
    * Loading info from external files to 2d array
    *
    * @access private
    * @param string $filename
    * @param string $delim_column
    * @param string $delim_line
    * 
    * @return array
    */
    public static function load_from_file($filename, $delim_column = ';', $delim_line = "\n")
        {
            $data = self::file_to_string($filename);
            if($data === FALSE)
            {
                self::add_debug_msg("# Error while loading file: $filename");
                return array();
            }
            
            $array = @explode($delim_line, $data);
            
            if(is_array($array) && count($array)>0)
            {
                foreach($array as $k => $v)
                {
                    $v = trim($v);
                    if(strlen( $v ) > 0)
                    {
                        if(strlen($delim_column) > 0)
                        {
                            $array[$k] = explode($delim_column, $v);
                            foreach($array[$k] as $key => $value)
                            {
                                $array[$k][$key] = trim($value);
                            }
                        }
                        else
                            $array[$k] = $v;
                    }
                }
                return $array;
            }
            else
            {
                self::add_debug_msg("# Empty data array in file: $filename");
                return array();
            }
        }
    
    /**
    * Loading info from external files to string
    *
    * @access public
    * @param string $filename
    * 
    * @return string or FALSE
    */
    public static function file_to_string($filename)
    {
        $fp = @fopen($filename, 'r');
            
        if(!$fp)
        {
            self::add_debug_msg("# Failed to open file: $filename");
            return FALSE;
        }
        
        $data = @fread($fp, @filesize($filename) );
        @fclose($fp);
        
        if(strlen($data)<1)
        {
            self::add_debug_msg("# Empty file: $filename");
            return FALSE;
        }
        
        return $data;
    }
    
    /**
    * Printing debug information method
    *
    * @access public
    * @return void
    */
    public static function print_debug()
    {
        self::print_array(self::$debug_info);
    }
    
    /**
    * Printing array information method
    *
    * @access public
    * @return void
    */
    public static function print_array($array)
    {
        if (count($array, COUNT_RECURSIVE) == count($array))
        {
            $str = implode("\r\n", $array);
        }else
        {
            # we could write here a method for printing N-dimension arrays, but I need just 2d
            # headers are just for 2nd dim
            # $dim = count($array);
            
            $_array     = array();
            $_headers   = array();
            
            foreach($array as $key => $value)
            {
                if(is_array($value))
                {
                    $_array[] = implode("\t", $value);
                }
                else
                {
                    $_array[] = $value;
                }
                
                if(count($_headers) < 1)
                {
                    foreach($value as $key2 => $value2)
                    {
                        $_headers[] = $key2;
                    }
                }    
            }
            $headers = implode("\t", $_headers) . "\r\n";
            $str = implode("\r\n", $_array);
        }

        echo "<pre>";
        echo htmlspecialchars($headers);
        echo htmlspecialchars($str);
        echo "</pre>";
    }
  
    /**
    * Logging method
    *
    * @access public
    * @var string $msg message
    * @return void
    */
    public static function add_debug_msg($msg)
    {
        if(self::$debug_log)
        {
            self::$debug_info[] = $msg;
        }
        
        if(self::$console_mode)
        {
            echo htmlspecialchars($msg)."\r\n";
        }
    }
}