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

namespace Hyn\Tenancy\Tests;

use Hyn\Tenancy\Exceptions\ModelValidationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;

class HostnameRepositoryTest extends Test
{
    /**
     * @test
     */
    public function connect_hostname_to_website()
    {
        $this->setUpHostnames(true);
        $this->setUpWebsites(true, true);

        $this->assertEquals($this->website->id, $this->hostname->website_id);
    }

    /**
     * @test
     * @expectedException \Hyn\Tenancy\Exceptions\ModelValidationException
     */
    public function assert_validation_fqdn_required()
    {
        $this->hostname->fqdn = null;

        $this->hostnames->create($this->hostname);
    }

    /**
     * @test
     */
    public function validates_website_relation()
    {
        $this->hostname->website_id = 999;

        try {
            $this->hostnames->create($this->hostname);
        } catch (ModelValidationException $e) {
            $this->assertEquals(
                Arr::get($e->validator->failed(), 'website_id.Exists.0'),
                sprintf("%s.websites", $this->connection->systemName())
            );
        }
    }

    /**
     * @test
     */
    public function validation_under_maintenance()
    {
        try {
            $this->hostname->under_maintenance_since = null;
            $this->hostnames->update($this->hostname);
            $this->assertNull($this->hostname->under_maintenance_since);
        } catch (ModelValidationException $e) {
            $this->fail("The validation should not fail, message: {$e->getMessage()}");
        }
    }

    /**
     * @test
     */
    public function validation_redirect_to()
    {
        try {
            $this->hostname->redirect_to = null;
            $this->hostnames->update($this->hostname);
            $this->assertNull($this->hostname->redirect_to);
        } catch (ModelValidationException $e) {
            $this->fail("The validation should not fail, message: {$e->getMessage()}");
        }
    }

    /**
     * @test
     */
    public function validation_website_id()
    {
        try {
            $this->hostname->website_id = null;
            $this->hostnames->update($this->hostname);
            $this->assertNull($this->hostname->website_id);
        } catch (ModelValidationException $e) {
            $this->fail("The validation should not fail, message: {$e->getMessage()}");
        }
    }

    /**
     * @test
     */
    public function hostname_update()
    {
        $this->setUpHostnames(true);

        $saved = $this->hostnames->update($this->hostname);

        $this->assertEquals($this->hostname->id, $saved->id);
    }

    /**
     * @test
     */
    public function hostname_delete()
    {
        $this->setUpHostnames(true);

        $this->hostnames->delete($this->hostname);

        $this->assertTrue($this->hostname->exists);
        $this->assertNotNull($this->hostname->deleted_at);
        $this->assertFalse($this->hostnames->query()->where('id', $this->hostname->id)->exists());

        $this->hostnames->delete($this->hostname, true);

        $this->assertFalse($this->hostname->exists);
    }


    protected function duringSetUp(Application $app)
    {
        $this->setUpWebsites();
        $this->setUpHostnames();
    }
}
