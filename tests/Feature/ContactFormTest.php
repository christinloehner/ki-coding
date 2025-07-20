<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Mock successful reCAPTCHA verification.
     */
    private function mockRecaptchaSuccess(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'challenge_ts' => now()->toISOString(),
                'hostname' => 'localhost',
            ], 200)
        ]);
    }

    /**
     * Mock failed reCAPTCHA verification.
     */
    private function mockRecaptchaFailure(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response'],
            ], 200)
        ]);
    }

    /**
     * Test that contact page loads successfully.
     */
    public function test_contact_page_loads(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertViewIs('pages.contact');
    }

    /**
     * Test contact form submission with valid data.
     */
    public function test_contact_form_submission_with_valid_data(): void
    {
        Mail::fake();
        $this->mockRecaptchaSuccess();

        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'message' => 'This is a test message.',
            'privacy' => '1',
            'g-recaptcha-response' => 'valid-recaptcha-token',
        ];

        $response = $this->post('/contact', $data);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');
    }

    /**
     * Test contact form validation fails with invalid data.
     */
    public function test_contact_form_validation_fails(): void
    {
        $response = $this->post('/contact', []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'message', 'privacy', 'g-recaptcha-response']);
    }

    /**
     * Test contact form email validation.
     */
    public function test_contact_form_email_validation(): void
    {
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'invalid-email',
            'message' => 'This is a test message.',
            'privacy' => '1',
            'g-recaptcha-response' => 'valid-recaptcha-token',
        ];

        $response = $this->post('/contact', $data);
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test contact form reCAPTCHA validation fails.
     */
    public function test_contact_form_recaptcha_validation_fails(): void
    {
        Mail::fake();
        $this->mockRecaptchaFailure();

        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'message' => 'This is a test message.',
            'privacy' => '1',
            'g-recaptcha-response' => 'invalid-recaptcha-token',
        ];

        $response = $this->post('/contact', $data);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['g-recaptcha-response']);
    }

    /**
     * Test contact form without reCAPTCHA response.
     */
    public function test_contact_form_without_recaptcha(): void
    {
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'message' => 'This is a test message.',
            'privacy' => '1',
        ];

        $response = $this->post('/contact', $data);
        $response->assertSessionHasErrors(['g-recaptcha-response']);
    }
}
