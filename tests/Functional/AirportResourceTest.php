<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class AirportResourceTest extends KernelTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testGetCollectionOfAirport(): void
    {
        $this->browser()
            ->get('/api/airports')
            ->dump()
        ;
    }

}
