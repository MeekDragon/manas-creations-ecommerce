<?php

namespace App\Services;

use Illuminate\Support\Str;

class EmailGuardrailService
{
    /**
     * A list of commonly known disposable, temporary, or burner email domains.
     */
    protected static array $disposableDomains = [
        'mailinator.com', 'tempmail.com', '10minutemail.com', 'guerrillamail.com',
        'yopmail.com', 'trashmail.com', 'sharklasers.com', 'maildrop.cc',
        'fakeinbox.com', 'getairmail.com', 'discardmail.com', 'dispostable.com',
        'temp-mail.org', 'tempmailo.com', 'burnermail.io', 'mailnesia.com',
        'mailcather.me', 'generator.email', 'tempmailaddress.com', 'crazymailing.com',
        'throwawaymail.com', 'boun.cr', 'mailinator2.com', 'smailpro.com',
        'mintemail.com', 'mytemp.email', 'temp-mail.io', 'dropmail.me',
        'mail5.club', 'tempmail.net', 'burneremail.com', 'zillamail.com',
        '10minutemail.co.za', '10minutemail.net', 'tempmail.co', 'mailtemp.net',
        'tempmailpro.app', 'getnada.com', 'disposable.com', 'fake.com',
        'dummy.com', 'invalid.com', 'example.com', 'example.net', 'example.org',
        'test.com', 'testmail.com', 'dummymail.com', 'fakemail.com', 'sample.com'
    ];

    /**
     * Generic keyboard mash or fake words list.
     */
    protected static array $blockedLocalKeywords = [
        'test', 'dummy', 'fake', 'sample', 'asdf', 'qwerty', 'zxcv', '12345',
        'admin', 'administrator', 'user', 'guest', 'hello', 'world', 'abcde'
    ];

    /**
     * Main validation function. Returns true if email passes guardrails, false/exception message if it fails.
     *
     * @param string $email
     * @param string|null $errorRef Reference to hold validation error message
     * @return bool
     */
    public function validate(string $email, ?string &$errorRef = null): bool
    {
        $email = Str::lower(trim($email));

        // 1. Basic format verification
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorRef = 'Please enter a valid email address.';
            return false;
        }

        $localPart = Str::before($email, '@');
        $domain = Str::after($email, '@');

        // 2. Check blocked local keywords (e.g. test@, fake@)
        if (in_array($localPart, self::$blockedLocalKeywords, true)) {
            $errorRef = 'This looks like a testing or placeholder email. Please enter a valid email address.';
            return false;
        }

        // 3. Check disposable email domain list
        if (in_array($domain, self::$disposableDomains, true)) {
            $errorRef = 'Temporary or disposable email domains are not allowed.';
            return false;
        }

        // 4. Keyboard mash detection (e.g. asdfasdf, qwertyuiop, zxcvzxcv)
        // Detects sequences of 3+ letters repeating, or common mash strings
        if (
            preg_match('/(asdf|sdfg|dfgh|qwer|wert|erty|zxcv|xcvb|1234)/i', $localPart) ||
            preg_match('/(.)\1{3,}/', $localPart) // 4+ repeating characters (e.g., aaaa)
        ) {
            $errorRef = 'Please enter a realistic, valid email address.';
            return false;
        }

        // 5. Active DNS checking (check if the domain actually has MX records)
        // Running checkdnsrr ensures the domain actually exists and is configured to receive emails.
        if (app()->environment() !== 'testing') {
            if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
                $errorRef = 'The email domain does not seem to exist or cannot receive mail. Please verify the spelling.';
                return false;
            }
        }

        return true;
    }
}
