<?php
namespace App\Twig;

use App\Entity\QrcodeUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class QrExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('isQrUser', [$this, 'isOnlyQrUser'])
        ];
    }

    public function isOnlyQrUser(UserInterface $user): bool
    {
        return $user instanceof QrcodeUser;
    }
}