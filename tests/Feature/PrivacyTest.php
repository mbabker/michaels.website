<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class PrivacyTest extends TestCase
{
    #[Test]
    public function users_can_view_the_privacy_policy(): void
    {
        $this->get('/privacy')
            ->assertViewIs('privacy');
    }
}
