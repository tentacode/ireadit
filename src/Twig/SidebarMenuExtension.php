<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Webmozart\Assert\Assert;

class SidebarMenuExtension extends AbstractExtension
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function getFunctions()
    {
        return [new TwigFunction('is_current_menu', [$this, 'isCurrentMenu'])];
    }

    public function isCurrentMenu(string $routeName): bool
    {
        Assert::notNull($this->requestStack->getCurrentRequest());

        return $routeName === $this->requestStack->getCurrentRequest()
            ->get('_route');
    }
}
