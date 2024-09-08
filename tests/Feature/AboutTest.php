<?php

namespace Tests\Feature;

use Tests\TestCase;

final class AboutTest extends TestCase
{
    public function test_the_about_page_redirects(): void
    {
        $this->get('/about')
            ->assertRedirectToRoute('homepage');
    }
}
