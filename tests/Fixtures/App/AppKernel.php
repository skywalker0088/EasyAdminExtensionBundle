<?php

/*
 * This file is part of the EasyAdminBundle.
 *
 * (c) Javier Eguiluz <javier.eguiluz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * The kernel used in the application of most functional tests.
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle(),
            new AlterPHP\EasyAdminExtensionBundle\EasyAdminExtensionBundle(),
            new AlterPHP\EasyAdminExtensionBundle\Tests\Fixtures\AppTestBundle\AppTestBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', array(
                'assets' => null,
            ));
        });

        if ($this->requiresLogoutOnUserChange()) {
            $loader->load(function (ContainerBuilder $container) {
                $container->loadFromExtension('security', array(
                    'firewalls' => array(
                        'secured_area' => array(
                            'logout_on_user_change' => true,
                        ),
                    ),
                ));
            });
        }
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__.'/../../../build/cache/'.$this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return __DIR__.'/../../../build/kernel_logs/'.$this->getEnvironment();
    }

    protected function requiresLogoutOnUserChange()
    {
        return Kernel::VERSION_ID >= 30400 && Kernel::VERSION_ID < 40100;
    }
}
