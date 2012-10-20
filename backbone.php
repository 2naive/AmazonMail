<?php
    require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.php');
    
    # Generation log string
    
    $log_arr    = array();
    $log_arr[]  = date("m.d.Y H:i:s");
    $log_arr[]  = $_REQUEST['email'];

    foreach($_SERVER as $k => $v)
    {
        if(strpos($k, 'HTTP') !== false)
        {
            $log_arr[$k] = $v;
        }
    }
    
    $log_str = implode("\t", $log_arr) . "\r\n";
    unset($log_arr);
    
    # Creating log directory if needed
    
    if(!is_dir(PATH_DIR_LOG))
    {
        @mkdir(PATH_DIR_LOG);
    }
    
    # Writing log
    
    $f = fopen(PATH_DIR_LOG . DIRECTORY_SEPARATOR . date("Y.m.d") . '.txt', 'a');
    fwrite($f, $log_str );
    fclose($f);
    
    # Generating IMG for letter
    
    $img    = imagecreate(1,1);
    $color  = imagecolorallocate($img, 255, 255, 255);
    
    imagefill($img, 0, 0, $color);
    header('Content-Type: image/jpeg');
    imagejpeg($img);
?>