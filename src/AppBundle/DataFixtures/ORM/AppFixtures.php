<?php

namespace AppBundle\DataFixtures\ORM\AppFixtures;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;

class AppFixtures extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return  array(
            __DIR__ . '/users.yml',
            __DIR__ . '/products.yml',
            __DIR__ . '/itemLists.yml',
            __DIR__ . '/items.yml',

        );
    }
}
