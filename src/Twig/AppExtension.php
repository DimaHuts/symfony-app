<?php

namespace App\Twig;

use Symfony\Component\Intl\Intl;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $localeCodes;
    private $locales;

    public function __construct($locales)
    {
        $this->localeCodes = explode('|', $locales);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('locales', [$this, 'getLocales']),
        ];
    }

    public function getLocales(): array
    {
        if (null !== $this->locales) {
            return $this->locales;
        }
        $this->locales = [];
        foreach ($this->localeCodes as $localeCode) {
            $this->locales[] = ['code' => $localeCode, 'name' => Intl::getLocaleBundle()->getLocaleName($localeCode, $localeCode)];
        }
        return $this->locales;
    }
}