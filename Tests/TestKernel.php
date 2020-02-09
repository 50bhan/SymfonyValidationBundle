<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Tests;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Exception;

class TestKernel extends BaseKernel
{
    /**
     * @inheritDoc
     */
    public function registerBundles()
    {
        return [new FrameworkBundle()];
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/../Resources/config/services.yaml');
        $loader->load(__DIR__ . '/Config/config.yaml');
    }
}
