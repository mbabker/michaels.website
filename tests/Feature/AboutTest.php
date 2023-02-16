<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class AboutTest extends TestCase
{
    #[Test]
    public function users_can_view_the_about_page(): void
    {
        $this->get('/about')
            ->assertViewIs('about');
    }
}
