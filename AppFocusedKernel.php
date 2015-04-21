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
    /**
     * The path to the directory containing the application code.
     *
     * This defaults to src/ in the root of the application
     */
    protected $appPath;

    /**
     * The namespace prefix for the application code.
     *
     * This defaults to an empty string, meaning no prefix.
     */
    protected $appNamespace = '';

    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);

        if (!$this->appPath) {
            $this->appPath = realpath($this->getRootDir().'/../src');
        } else {
            $this->appPath = str_replace('%kernel.root_dir%', $this->getRootDir(), $this->appPath);
        }
    }

    protected function initializeBundles()
    {
        parent::initializeBundles();

        $this->bundles['App'] = $bundle = new VirtualBundle('App', $this->appPath, $this->appNamespace);
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
