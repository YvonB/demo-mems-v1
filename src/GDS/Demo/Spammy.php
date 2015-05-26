<?php
/**
 * Crude anti-spam tool
 *
 * @author Tom Walder
 */
namespace GDS\Demo;
class Spammy
{

    /**
     * Does the string look spammy?
     *
     * Ultra basic. Probably need to pull in some useful library or API for this...
     *
     * @param $str_test
     * @return bool
     */
    public static function looksSpammy($str_test)
    {
        $arr_spam_words = ['http://', 'https://', 'viagra'];
        $str_regex = '#' . implode('|', $arr_spam_words) . '#i';
        if(preg_match($str_regex, $str_test)) {
            return TRUE;
        }
        return FALSE;
    }
}