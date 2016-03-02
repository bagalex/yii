<?php

namespace frontend\libreries;

use Yii;

class Maildrill
{

    private $emailTo;
    private $template;
    private $key = 'TsR6dPlttBD6yqJkWvzUQQ';
    private $emailFrom = 'bagalex@inbox.ru';
    private $fromName = 'yii';
    private $subject;
    private $attributes;


    public function setSubject($subject)
    {
        return $this->subject = $subject;
    }

    public function setTo($email)
    {
        return $this->emailTo = $email;
    }

    public function setTemplate($template)
    {
        return $this->template = $template;
    }

    public function attributes()
    {
        return $this->attributes = [
            'key'     => $this->key,
            'message' => [
                "html"       => $this->template,
                "from_email" => $this->emailFrom,
                "from_name"  => $this->fromName,
                "subject"    => $this->subject,
                "to"         => array(array("email" => $this->emailTo)),
            ]
        ];
    }

    public function send()
    {
        $curl = curl_init('https://mandrillapp.com/api/1.0/messages/send.json');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->attributes()));

        return curl_exec($curl);
    }
}
