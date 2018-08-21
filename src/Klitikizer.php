<?php

namespace Cosmix\Klitikizer;

class Klitikizer
{
    /**
     * @var array the accented greek vowels.
     */
    const ACCENTED_GREEK_VOWELS = ['ά', 'έ', 'ί', 'ή', 'ό', 'ώ', 'ύ', 'Ά', 'Έ', 'Ί', 'Ή', 'Ό', 'Ώ', 'Ύ'];

    /**
     * @var array The greek diphthongs.
     */
    const GREEK_DIPHTHONGS = [
        'Οι', 'Οί', 'Όι', 'Ου', 'Ού', 'Αι', 'Αί', 'Άι', 'Αύ', 'Αυ', 'Άυ',
        'Ει', 'Εί', 'Έι', 'Εύ', 'Ευ', 'Έυ', 'Υι', 'Υί', 'Ύι', 'Α', 'Ε', 'Ι', 'Ο',
        'Υ', 'Η', 'Ω', 'Ά', 'Έ', 'Ί', 'Ό', 'Ύ', 'Ή', 'Ώ', 'ου', 'ού', 'όυ', 'ιώ',
        'έι', 'ει', 'εί', 'αί', 'αι', 'άι', 'αύ', 'αυ', 'άυ', 'οί', 'οι', 'όι',
        'υι', 'υί', 'ύι', 'ευ', 'εύ', 'έυ', 'ι', 'α', 'ο', 'ω', 'ά', 'ό', 'ώ',
        'ή', 'η', 'ί', 'ε', 'έ', 'υ', 'ύ',
    ];

    /**
     * The PSpell link
     *
     * @var int
     */
    private $speller;

    public function __construct()
    {
        // Initialize the PSpell.
        $this->speller = pspell_new('el');
    }

    /**
     * Return the klitiki (vocative) case of the provided first, or last (family) Greek name.
     *
     * @param string $name the name for which the vocative case is requested
     * @param boolean $is_first_name a boolean flag indicating that the provided name is a first name.
     * @return string the voacative case of the provided name or the original name if it is not possible to convert it.
     */
    public function getKlitikiForName(string $name, bool $is_first_name): string
    {
        $accent_offset = $this->getAccentOffsetFromEnd($name);

        if (in_array(mb_substr($name, -2), ['ος', 'ός'])) {
            if ($accent_offset < 1) { // unaccented
                // try to add accent.
                $n_name = $this->suggestionFor(mb_strtolower($name));
                if ($n_name === mb_strtolower(($name))) { // no accent was found.
                    $syllable_count = $this->countSyllables($name);

                    if ($syllable_count == 2) {
                        return preg_replace("/(.)ς$/u", "\\1", $name);
                    } else {
                        return preg_replace("/(.)(ο|ό)ς/u", '\\1ε', $name);
                    }
                } else {
                    return $this->getKlitikiForName(mb_convert_case($n_name, MB_CASE_TITLE), $is_first_name);
                }
            } else {
                switch ($accent_offset) {
                    case 1:
                        return preg_replace("/ός/u", 'έ', $name);
                        break;
                    case 2:
                        $syllable_count = $this->countSyllables($name) == 2;

                        if ($is_first_name) {
                            if ($syllable_count == 2) {
                                return preg_replace("/ος/u", 'ο', $name);
                            } else {
                                return preg_replace("/ος/u", 'ε', $name);
                            }
                        } else {
                            $matched = false;

                            foreach (['ούκος', 'άτος', 'άκος', 'ίτσος'] as $suffix) {
                                if (mb_substr($name, -count($suffix) == $suffix)) {
                                    $matched = true;
                                    break;
                                }
                            }

                            if ($syllable_count == 2 || ($syllable_count > 2 && $matched)) {
                                return preg_replace("/ος/u", 'ο', $name);
                            } else {
                                return preg_replace("/ος/u", 'ε', $name);
                            }
                        }
                        break;
                    default:
                        return preg_replace("/ος/u", 'ε', $name);
                }
            }
        } elseif (in_array(mb_substr($name, -2), ['ης', 'ής', 'ας', 'άς', 'ις'])) {
            return preg_replace("/(.)ς$/u", '\1', $name);
        }

        return $name;
    }

    /**
     * Return number of syllables from the end where there's an accent.
     *
     * @param string $word the word to match
     * @return integer the offset from the end where an accent was found
     */
    private function getAccentOffsetFromEnd(string $word): int
    {
        $offset_from_end = 1;

        preg_match_all('/' . implode('|', self::GREEK_DIPHTHONGS) . '/', $word, $matches);

        $matches = array_reverse($matches[0]);

        foreach ($matches as $match) {

            $chars_ra = preg_split('/(?<!^)(?!$)/u', $match);

            foreach ($chars_ra as $a_char) {
                if (in_array($a_char, self::ACCENTED_GREEK_VOWELS)) {
                    return $offset_from_end;
                }
            }
            ++$offset_from_end;
        }

        return 0;
    }

    /**
     * Count the number of syllables in the provided word
     *
     * @param string $word the provided word
     * @return integer the number of syllables in the provided word
     */
    public function countSyllables(string $word): int
    {
        preg_match_all('(' . implode('|', self::GREEK_DIPHTHONGS) . ')', $word, $matches);
        return count($matches[0]);
    }

    /**
     * Remove accents and other diacritics from the provided word.
     *
     * @param string $word the provided word
     * @return string the word with the accents (or other diacritics) removed
     */
    private function removeAccents(string $word): string
    {
        return preg_replace('/\p{M}/u', '', \Normalizer::normalize($word, \Normalizer::FORM_D));
    }

    /**
     * Get a suggestion from {A,P}Spell for the provided word.
     *
     * @param string $word the provided word
     * @return string the suggestion (if any) from PSpell
     */
    public function suggestionFor(string $word): string
    {
        $suggestions = pspell_suggest($this->speller, $word);
        if ($suggestions &&
            mb_strtolower($this->removeAccents($suggestions[0]))
            === mb_strtolower($this->removeAccents($word))
        ) {
            return mb_strtolower($suggestions[0]);
        }

        return $word;
    }
}
