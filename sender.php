<?php
/**
 * AmazonMail - AWS SES PHP mailer script based on SES class
 *
 * @author Nedzelsky Alexander <to.naive@gmail.com>
 * @version 0.1
 * @link http://stupid.su/aws-ses_php_mailer/
 *
 * @todo refactor
 * @todo bugfixes
 * @todo class ? dunno yet
 * @todo AngryCurl turbo speed !!!
 * @todo show graphs
 */

    # Initializing console mode
    if (function_exists('apache_setenv'))
    {
        # Internal Server Error fix in case no apache_setenv() function exists
        @apache_setenv('no-gzip', 1);
    }
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    for ($i = 0; $i < ob_get_level(); $i++)
        ob_end_flush();
    ob_implicit_flush(1);

    require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php');

    # Loading DATA
    $users          = Lib::load_from_file(PATH_FILE_SUBSCRIBERS);
    $credentials    = Lib::load_from_file(PATH_FILE_CREDENTIALS);
    $msg_text       = Lib::file_to_string(PATH_FILE_MSG_TEXT);
    $msg_html       = Lib::file_to_string(PATH_FILE_MSG_HTML);
    
    # Importing credentials
    
    $_credentials   = array();
    foreach($credentials as $id => $param)
    {
        $_credentials[$param[0]] = $param[1];
    }
    $credentials = $_credentials;
    unset($_credentials);
    
    # Auth
    $SES            = new SimpleEmailService($credentials['AK'], $credentials['SK']);
    $SES->enableVerifyPeer(false);
    #print_r($SES->verifyEmailAddress(FROM_SHORT));
    echo "# Verified sender email addresses\r\n";
    $verified_email_arr = $SES->listVerifiedEmailAddresses();
    if(!is_array($verified_email_arr))
    {
        echo "# No emails verified or credentials wrong. Check AWS SES settings or /import/credentials.csv (Access Key Id/Secret Access Key).\r\n";
        die();
    }
    Lib::print_array($verified_email_arr);

    # Starting Timer
    $time_start = microtime(1);
    echo "<pre>";
    
    $rid = 0;
    
    # Sending 1 email per user
    foreach ($users as $id=>$user)
    {
        $rid++;
        
        # Starting inner Timer
        $time_start_inner = microtime(1);
    
        $text_msg = str_replace(MASK_USERNAME, $user[1], $msg_text);
        $html_msg = str_replace(MASK_USERNAME, $user[1], $msg_html);
        
        if(BACKBONE_ENABLED && strlen($credentials['LINK_BACKBONE']) > 0)
        {
            $html_msg .='<img src="' .
                        $credentials['LINK_BACKBONE'] .
                        '?email=' .
                        $user[0] .
                        '"/>';
        }
        
        $m = new SimpleEmailServiceMessage();
        $m->addTo($user[0]);
        $m->setFrom($credentials['FROM_FULL']);
        $m->setSubject($credentials['TITLE']);
        $m->setMessageFromString($text_msg, $html_msg);
        
        #$send_emails[]=($SES->sendEmail($m));
        $result = $SES->sendEmail($m);
        
        $time_end_inner = microtime(1);
        
        echo    $rid . "\t" .
                round($time_end_inner - $time_start_inner, 2) . "s\t" .
                $user[0] . "\t" .
                $result['RequestId'] . "\t" .
                $result['MessageId'] . "\r\n";
        unset($m);
        unset($result);
        
    }

$time_end = microtime(1);    
echo "# Sended in ".round($time_end - $time_start, 2)."s\r\n";
echo "</pre>";
#Lib::print_array($send_emails);

?>