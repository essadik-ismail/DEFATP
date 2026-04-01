<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationPagesTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_health_endpoint_is_accessible(): void
    {
        $response = $this->get('/health');

        $response->assertOk();
    }

    public function test_captcha_refresh_only_returns_the_question(): void
    {
        $response = $this->get('/captcha/refresh');

        $response->assertOk()
            ->assertJsonStructure(['question'])
            ->assertJsonMissingPath('answer');
    }
}
