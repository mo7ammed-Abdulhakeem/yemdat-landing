<?php

namespace App\Support;

/**
 * Maps free / legacy specialty values (EN + AR, misspellings, slugs, acronyms) to a single
 * canonical specialty slug from the unified taxonomy. Used by the backfill migration and safe to
 * reuse for future imports. Deterministic and idempotent (a canonical slug maps back to itself).
 */
class SpecialtyNormalizer
{
    /**
     * Resolve a canonical slug from the stored specialty and its optional free-text detail.
     */
    public static function normalize(?string $specialty, ?string $other = null): string
    {
        // The stored value (incl. existing slugs and legacy labels) classifies first…
        $bySpecialty = self::classify((string) $specialty);
        if ($bySpecialty !== null) {
            return $bySpecialty;
        }

        // …otherwise fall back to the free-text "other" detail.
        $byOther = self::classify((string) $other);
        if ($byOther !== null) {
            return $byOther;
        }

        return 'other';
    }

    /**
     * Classify a single text value to a canonical slug, or null if nothing matches.
     */
    public static function classify(string $text): ?string
    {
        $t = self::canon($text);

        if ($t === '' || $t === 'other' || $t === 'اخرى' || $t === 'أخرى') {
            return null;
        }

        // Phrase rules, ordered specific → general. First substring hit wins.
        $phraseRules = [
            'data-science'          => ['data science', 'datascience', 'علم البيانات'],
            'data-engineering'      => ['data engineering', 'هندسة البيانات'],
            'data-analytics'        => ['data analytics', 'data analysis', 'analytics', 'تحليل البيانات', 'تحليل بيانات', 'محلل بيانات'],
            'data-governance'       => ['data governance', 'governance', 'حوكمة'],
            'data-management'       => ['data management', 'data mgmt', 'ادارة البيانات'],
            'business-intelligence' => ['business intelligence', 'ذكاء الاعمال', 'ذكاء اعمال'],
            'ai-ml'                 => ['artificial intelligence', 'machine learning', 'deep learning', 'الذكاء الاصطناعي', 'ذكاء اصطناعي', 'تعلم الاله'],
            'cybersecurity'         => ['cyber', 'سيبراني', 'امن المعلومات', 'امن سيبراني'],
            'computer-science'      => ['computer science', 'computer scien', 'computer enginee', 'علوم حاسوب', 'علوم الحاسوب', 'هندسة حاسوب', 'هندسة الحاسوب'],
            'software-engineering'  => ['software', 'هندسة برمجيات', 'برمجة', 'web development', 'programming', 'developer', 'مبرمج'],
            'information-technology' => ['information technology', 'information systems', 'تقنية المعلومات', 'تقنية معلومات', 'تكنولوجيا المعلومات', 'نظم المعلومات', 'نظم معلومات'],
            'statistics-math'       => ['statistic', 'mathematic', 'احصاء', 'رياضيات'],
            'accounting-finance'    => ['accounting', 'finance', 'محاسبة', 'مالية', 'ماليه'],
            'business-management'   => ['business', 'management', 'administration', 'ادارة اعمال', 'ادارة الاعمال', 'ادارة عامة', 'إدارة', 'digital transformation', 'تحول رقمي', 'mba'],
        ];

        foreach ($phraseRules as $slug => $keywords) {
            foreach ($keywords as $kw) {
                $kw = self::canon($kw);
                if ($kw !== '' && str_contains($t, $kw)) {
                    return $slug;
                }
            }
        }

        // Short acronyms: match only as whole tokens (avoid false hits like "digital" → "it").
        $tokens = explode(' ', $t);
        $acronyms = [
            'it'  => 'information-technology',
            'mis' => 'information-technology',
            'cs'  => 'computer-science',
            'ai'  => 'ai-ml',
            'ml'  => 'ai-ml',
            'bi'  => 'business-intelligence',
        ];
        foreach ($tokens as $token) {
            if (isset($acronyms[$token])) {
                return $acronyms[$token];
            }
        }

        return null;
    }

    /**
     * Lower-case, fold Arabic alef variants, strip separators/diacritics, collapse whitespace.
     */
    private static function canon(string $text): string
    {
        $t = mb_strtolower(trim($text));
        $t = str_replace(['أ', 'إ', 'آ', 'ٱ'], 'ا', $t);
        $t = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}]/u', '', $t); // Arabic diacritics
        $t = preg_replace('/[_\-\/.,()]+/u', ' ', $t);
        $t = preg_replace('/\s+/u', ' ', $t);

        return trim($t);
    }
}
