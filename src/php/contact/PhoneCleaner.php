<?php declare(strict_types=1);
namespace Netmatters\Contact;

class PhoneCleaner
{
    public function clean(string $input): string
    {
        // matches anything except: digits, +, (, ), and spaces
        // "(+44) 01235 67890" is valid and doesn't need any cleaning
        $invalid = '/[^0-9+() ]/';
        $output = preg_replace($invalid, '', $input);
        
        // matches a series of 2 or more spaces, then replace with a single space.
        $longGap = '/( {2})+/';
        return preg_replace($longGap, ' ', $output);
    }
}
