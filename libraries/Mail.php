<?php

/**
 * Mail library
 * this library can be used to send emails
 *
 * @author Vince Urag
 */
class Mail {

    public function sendMail($recipients, $from, $subject, $message) {

        $recString = "";
        $fromString = "";

        if(count($recipients) > 1) {
            $recKeys = array_keys($recipients);
            $lastRecKey = array_pop($recKeys);
            foreach($recipients as $k => $v) {
                if($k == $lastRecKey) {
                    $recString .= $v;
                } else {
                    $recString = $recString."$v, ";
                }
            }
        }

        if(count($from) == 1 && is_array($from)) {
            foreach ($from as $key => $value) {
                $fromString = "From: {$key} ". htmlspecialchars("<$value>") . "\r\n";
            }
        }

        return mail($recString, $subject, $message, $fromString);
    }
}
