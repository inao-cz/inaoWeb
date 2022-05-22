<?php

namespace App\Util;

error_reporting(E_ALL);

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailUtil
{
    public const MAILER_FROM = "inao@inao.xn--6frz82g";

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $template, string $subject, string $to, string $link = null): bool
    {
        $completeTemplate = "mail/" . $template . ".html.twig";

        $message = (new TemplatedEmail())
            ->to($to)
            ->from(self::MAILER_FROM)
            ->subject($subject)
            ->htmlTemplate($completeTemplate)
            ->context([
                'link' => $link
            ]);
        try {
            $this->mailer->send($message);
            return true;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }
}