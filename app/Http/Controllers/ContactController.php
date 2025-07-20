<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;
use App\Mail\ContactConfirmationMail;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.contact');
    }

    /**
     * Handle the contact form submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
            'privacy' => 'required|accepted',
            'g-recaptcha-response' => 'required',
        ], [
            'first_name.required' => 'Der Vorname ist erforderlich.',
            'first_name.max' => 'Der Vorname darf nicht länger als 255 Zeichen sein.',
            'last_name.required' => 'Der Nachname ist erforderlich.',
            'last_name.max' => 'Der Nachname darf nicht länger als 255 Zeichen sein.',
            'email.required' => 'Die E-Mail-Adresse ist erforderlich.',
            'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'email.max' => 'Die E-Mail-Adresse darf nicht länger als 255 Zeichen sein.',
            'company.max' => 'Der Firmenname darf nicht länger als 255 Zeichen sein.',
            'phone.max' => 'Die Telefonnummer darf nicht länger als 255 Zeichen sein.',
            'service.max' => 'Der Service darf nicht länger als 255 Zeichen sein.',
            'message.required' => 'Die Nachricht ist erforderlich.',
            'message.max' => 'Die Nachricht darf nicht länger als 2000 Zeichen sein.',
            'privacy.required' => 'Du musst der Datenschutzerklärung zustimmen.',
            'privacy.accepted' => 'Du musst der Datenschutzerklärung zustimmen.',
            'g-recaptcha-response.required' => 'Bitte bestätige, dass du kein Roboter bist.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contact')
                ->withErrors($validator)
                ->withInput();
        }

        // Verify reCAPTCHA
        if (!$this->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return back()
                ->withErrors(['g-recaptcha-response' => 'Die reCAPTCHA-Verifizierung ist fehlgeschlagen. Bitte versuche es erneut.'])
                ->withInput();
        }

        try {
            // Create contact record
            $contact = Contact::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'company' => $request->company,
                'phone' => $request->phone,
                'service' => $request->service,
                'message' => $request->message,
                'privacy_accepted' => true,
                'status' => 'new',
            ]);

            // Send email notification
            $this->sendNotificationEmail($contact);

            return redirect()->route('contact')
                ->with('success', 'Vielen Dank für deine Nachricht! Wir werden uns zeitnah bei dir melden.');

        } catch (\Exception $e) {
            return redirect()->route('contact')
                ->with('error', 'Es gab ein Problem beim Senden deiner Nachricht. Bitte versuche es später erneut.')
                ->withInput();
        }
    }

        /**
     * Send notification email to admin.
     *
     * @param \App\Models\Contact $contact
     * @return void
     */
    private function sendNotificationEmail(Contact $contact)
    {
        try {
            // Send notification email to admin
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($contact));

            // Send confirmation email to user
            $this->sendConfirmationEmail($contact);

        } catch (\Exception $e) {
            // Log the error but don't fail the entire process
            Log::error('Failed to send contact form emails: ' . $e->getMessage());
        }
    }

    /**
     * Send confirmation email to user.
     *
     * @param \App\Models\Contact $contact
     * @return void
     */
    private function sendConfirmationEmail(Contact $contact)
    {
        try {
            Mail::to($contact->email)->send(new ContactConfirmationMail($contact));
        } catch (\Exception $e) {
            Log::error('Failed to send contact confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Verify reCAPTCHA response.
     *
     * @param string $recaptchaResponse
     * @return bool
     */
    private function verifyRecaptcha($recaptchaResponse)
    {
        if (empty($recaptchaResponse)) {
            return false;
        }

        try {
            $response = Http::asForm()->post(config('recaptcha.verify_url'), [
                'secret' => config('recaptcha.secret_key'),
                'response' => $recaptchaResponse,
                'remoteip' => request()->ip(),
            ]);

            $data = $response->json();

            return isset($data['success']) && $data['success'] === true;
        } catch (\Exception $e) {
            // Log error if needed
            // Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
            return false;
        }
    }
}
