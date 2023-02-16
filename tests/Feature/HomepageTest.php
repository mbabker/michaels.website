<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class HomepageTest extends TestCase
{
    #[Test]
    public function users_can_visit_the_homepage(): void
    {
        $this->get('/')
            ->assertViewIs('homepage');
    }
}
