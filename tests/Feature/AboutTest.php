<?php

namespace Tests\Feature;

use Tests\TestCase;

final class AboutTest extends TestCase
{
    /** @test */
    public function users_can_view_the_about_page()
    {
        $this->get('/about')
            ->assertViewIs('about');
    }
}
