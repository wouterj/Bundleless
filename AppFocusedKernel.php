<?php

/**
 * This file is part of the Bundleless package.
 *
 * Copyright (c) 2015, Wouter de Jong <wouter@wouterj.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WouterJ\Bundleless;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * A base kernel that automatically registers an App virtual bundle.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
abstract class AppFocusedKernel extends Kernel
{
    protected function getAppBundle()
    {
        return new VirtualBundle('App', realpath($this->getRootDir().'/../src'), 'App');
    }

    protected function initializeBundles()
    {
        parent::initializeBundles();

        $this->bundles['App'] = $bundle = $this->getAppBundle();
        $this->bundleMap['App'] = array($bundle);
    }
    
    protected function prepareContainer(ContainerBuilder $container)
    {
        parent::prepareContainer($container);

        $bundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $name => $fqcn) {
            switch ($name) {
                case 'DoctrineBundle':
                    $container->loadFromExtension('doctrine', array(
                        'orm' => array(
                            'mappings' => array(
                                'App' => array(
                                    'type' => 'annotation',
                                    'prefix' => $this->appNamespace ? $this->appNamespace.'\Entity' : 'Entity',
                                    'dir' => $this->appPath.'/Entity',
                                ),
                            ),
                        ),
                    ));
                    break;
            }
        }

        $container->setParameter('controller_name_converter.class', 'WouterJ\Bundleless\Controller\ControllerNameParser');
    }
}
