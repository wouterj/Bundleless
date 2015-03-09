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

use WouterJ\Bundleless\Bundle\Virtual as VirtualBundle;
use Symfony\Component\HttpKernel\Kernel;

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
}
