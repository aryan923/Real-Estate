<?php

if (!function_exists('format_price')) {
    /**
     * Format price with standard thousands separator and base currency symbol.
     *
     * @param mixed $amount
     * @return string
     */
    function format_price($amount): string
    {
        if ($amount === null || $amount === '') {
            return 'Contact for Price';
        }

        $cleanAmount = preg_replace('/[^\d.]/', '', $amount);

        if (!is_numeric($cleanAmount) || $cleanAmount === '') {
            return $amount;
        }

        static $baseCurrency = null;
        if ($baseCurrency === null) {
            try {
                $baseCurrency = \App\Models\Currency::where('is_default', true)->first();
            } catch (\Exception $e) {
                // Fallback in case table doesn't exist yet
            }

            if (!$baseCurrency) {
                $baseCurrency = (object)[
                    'symbol' => '$',
                    'code' => 'USD',
                ];
            }
        }

        return $baseCurrency->symbol . number_format((float)$cleanAmount, 0, '.', ',');
    }
}

if (!function_exists('format_price_text')) {
    /**
     * Format a price info text block, adding the currency symbol to the first numeric word.
     *
     * @param string|null $text
     * @return string
     */
    function format_price_text($text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $tokens = preg_split('/\s+/', trim($text), 2);
        if (count($tokens) > 0) {
            $first = $tokens[0];
            $cleanFirst = preg_replace('/[^\d.]/', '', $first);
            if (is_numeric($cleanFirst) && $cleanFirst !== '') {
                $formattedFirst = format_price($cleanFirst);
                return count($tokens) > 1 ? $formattedFirst . ' ' . $tokens[1] : $formattedFirst;
            }
        }

        return $text;
    }
}
