<?php

namespace Tests\Feature;

use Tests\TestCase;

final class PrivacyTest extends TestCase
{
    /** @test */
    public function users_can_view_the_privacy_policy(): void
    {
        $this->get('/privacy')
            ->assertViewIs('privacy');
    }
}
