<?php


namespace App\Service;


use Swift_Mailer;
use Swift_Message;

class SendEmail
{
    private Swift_Mailer $mailer;

    /**
     * SendEmail constructor.
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param  $destination
     * @param $subject
     * @param $message
     */
    public function send($destination,$subject,$message)
    {
        $message = (new Swift_Message($subject))
            ->setFrom('send@example.com')
            ->setTo( $destination)
            ->setBody($message);

        $this->mailer ->send($message);
    }
}