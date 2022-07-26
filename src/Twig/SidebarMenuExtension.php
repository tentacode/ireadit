<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarMenuExtension extends AbstractExtension
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('is_current_menu', [$this, 'isCurrentMenu']),
        ];
    }
    
    public function isCurrentMenu(string $routeName): bool
    {
        return $routeName === $this->requestStack->getCurrentRequest()->get('_route');
    }
}
