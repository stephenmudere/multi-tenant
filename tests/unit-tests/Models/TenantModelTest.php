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

namespace Hyn\Tenancy\Tests\Models;

use Hyn\Tenancy\Tests\Extend\TenantExtend;
use Hyn\Tenancy\Tests\Test;

class TenantModelTest extends Test
{
    /**
     * @test
     */
    public function uses_correct_connection()
    {
        $model = new TenantExtend();

        $this->assertEquals($model->getConnectionName(), $this->connection->tenantName());

        $this->setUpHostnames(true);
        $this->setUpWebsites(true, true);
        $this->activateTenant();

        $this->assertEquals($model->getConnection(), $this->connection->get());
    }
}
