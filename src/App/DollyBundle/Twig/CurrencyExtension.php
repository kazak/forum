<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DollyBundle\Twig;

/**
 * Sylius currency Twig helper.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CurrencyExtension extends \Twig_Extension
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('sylius_currency', [$this, 'convertAmount']),
            new \Twig_SimpleFilter('sylius_price', [$this, 'convertAndFormatAmount']),
        ];
    }

    /**
     * Convert amount to target currency.
     *
     * @param int $amount
     *
     * @return string
     *
     * @internal param null|string $currency
     */
    public function convertAmount($amount)
    {
        return $amount.' CONVERTED';
    }

    /**
     * Convert and format amount.
     *
     * @param int $amount
     *
     * @return string
     *
     * @internal param null|string $currency
     */
    public function convertAndFormatAmount($amount)
    {
        return $amount.' CURRENCY';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_currency';
    }
}
