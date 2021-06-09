<?php

namespace Tests\Feature;

use Tests\TestCase;

final class HomepageTest extends TestCase
{
    /** @test */
    public function users_can_visit_the_homepage(): void
    {
        $this->get('/')
            ->assertViewIs('homepage');
    }
}
