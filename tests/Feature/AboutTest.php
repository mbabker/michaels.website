<?php

namespace Tests\Feature;

use Tests\TestCase;

final class AboutTest extends TestCase
{
    public function test_users_can_view_the_about_page(): void
    {
        $this->get('/about')
            ->assertOk()
            ->assertViewIs('about');
    }
}
