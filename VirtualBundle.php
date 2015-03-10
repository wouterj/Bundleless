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

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VirtualBundle extends Bundle
{
    protected $path;
    protected $namespace;

    public function __construct($name, $path, $namespace)
    {
        $this->name      = $name;
        $this->path      = $path;
        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getPath()
    {
        return $this->path;
    }
}
