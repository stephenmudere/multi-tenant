<?php

/*
 * This file is part of the hyn/multi-tenant package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://laravel-tenancy.com
 * @see https://github.com/hyn/multi-tenant
 */

namespace Hyn\Tenancy\Tests\Traits;

trait InteractsWithBuilds
{
    protected $buildWebserver = 'none';
    protected $buildPhpVersion;
    protected $buildDb;
    protected $buildLaravelVersion;

    public function identifyBuild()
    {
        $name = env('CIRCLE_JOB');

        if ($name && preg_match(
            '/^php\-(?<php_version>[0-9\.]+)\-(?<webserver>[a-z]+)$/',
            $name,
            $m
        )) {
            $this->buildWebserver = $m['webserver'];
            $this->buildPhpVersion = $m['php_version'];
            $this->buildDb = env('DB_HOST');
        }
    }
}
