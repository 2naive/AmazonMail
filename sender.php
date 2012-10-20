<?php

    require_once("config.php");

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
    Lib::print_array( $SES->listVerifiedEmailAddresses() );

    # Sending 1 email per user
    foreach ($users as $id=>$user)
    {
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
        
        $send_emails[]=($SES->sendEmail($m));
        unset($m);
    }
    
echo "# Sended emails\r\n";
Lib::print_array($send_emails);

?>