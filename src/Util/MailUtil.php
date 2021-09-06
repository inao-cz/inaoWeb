<?php

namespace App\Util;

error_reporting(E_ALL);

use Psr\Container\ContainerInterface;
use Swift_Mailer;
use Swift_Message;

class MailUtil
{
    /** @var ContainerInterface $containerInterface */
    private $containerInterface;
    /** @var Swift_Mailer $swiftMailer */
    private $swiftMailer;
    public const MAILER_FROM = "inao@inao.xn--6frz82g";

    /**
     * MailUtil constructor.
     */
    public function __construct(Swift_Mailer $swift_Mailer, ContainerInterface $containerInterface)
    {
        $this->containerInterface = $containerInterface;
        $this->swiftMailer = $swift_Mailer;
    }

    /**
     * @param string|null $link
     */
    public function sendEmail(string $template, string $subject, string $to, string $link = null): bool
    {
        $completeTemplate = "mail/" . $template . ".html.twig";

        $message = (new Swift_Message($subject))
            ->setTo($to)
            ->setFrom(self::MAILER_FROM)
            ->setBody(
                $this->containerInterface->get('twig')->render($completeTemplate, ['link' => $link]),
                'text/html'
            );
        $fail = "";
        $result = $this->swiftMailer->send($message, $fail);
        return $result !== 0;
    }
}