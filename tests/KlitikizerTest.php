<?php

namespace Cosmix\Klitikizer\Tests;

use Cosmix\Klitikizer\Klitikizer;
use PHPUnit\Framework\TestCase;

class KlitikizerTest extends TestCase
{

    /**
     * @covers Cosmix\Klitikizer\Klitikizer
     */
    public function testKlitikizerLatinFirstName()
    {
        $klit = new Klitikizer();
        $res = $klit->getKlitikiForName('Paul', true);
        $this->assertSame('Paul', $res);
    }

    /**
     * @covers Cosmix\Klitikizer\Klitikizer
     */
    public function testKlitikizerLatinLastName()
    {
        $klit = new Klitikizer();
        $res = $klit->getKlitikiForName('Johnson', true);
        $this->assertSame('Johnson', $res);
    }

    /**
     * @covers Cosmix\Klitikizer\Klitikizer
     */
    public function testEmptyName()
    {
        $klit = new Klitikizer();
        $res = $klit->getKlitikiForName('', false);
        $this->assertSame('', $res);

        $res = $klit->getKlitikiForName('', true);
        $this->assertSame('', $res);
    }

    public function syllableProvider()
    {
        return [
            ['Δημοσθένης', 4],
            ['Θανάσης', 3],
            ['Σωτήρης', 3],
            ['Βάσω', 2],
            ['Ελένη', 3],
            ['Φίλιππος', 3],
            ['αυτοκίνητο', 5],
        ];
    }

    /**
     * @dataProvider syllableProvider
     * @covers Cosmix\Klitikizer\Klitikizer
     */
    public function testSyllableCount($word, $syl_cnt)
    {
        $klit = new Klitikizer();
        $this->assertSame($syl_cnt, $klit->countSyllables($word));
    }

    /**
     * @covers Cosmix\Klitikizer\Klitikizer
     */
    public function testNonDictionaryWord()
    {
        $klit = new Klitikizer();
        $res = $klit->getKlitikiForName('Σφλούρχθος', true);
        $this->assertSame('Σφλούρχθο', $res);

        // no accent, unknown word.
        $res = $klit->getKlitikiForName('Σφλουρχθος', true);
        $this->assertSame('Σφλουρχθο', $res);

        // no accent, shorter unknown word.
        $res = $klit->getKlitikiForName('Σφλος', true);
        $this->assertSame('Σφλε', $res);
    }

    /**
     * @covers Cosmix\Klitikizer\Klitikizer
     */
    public function testKlitikizerGreekFirstNames()
    {
        $klit = new Klitikizer();
        $res = $klit->getKlitikiForName('Παύλος', true);
        $this->assertSame('Παύλο', $res);

        // no accent
        $res = $klit->getKlitikiForName('Παυλος', true);
        $this->assertEquals(true, in_array($res, ['Παυλο', 'Παύλο'])); // depends on pspell.

        $res = $klit->getKlitikiForName('Εδουάρδος', true);
        $this->assertSame('Εδουάρδε', $res);

        $res = $klit->getKlitikiForName('Χαρίλαος', true);
        $this->assertSame('Χαρίλαε', $res);

        $res = $klit->getKlitikiForName('Σωτήριος', true);
        $this->assertSame('Σωτήριε', $res);

        $res = $klit->getKlitikiForName('Σωτήρης', true);
        $this->assertSame('Σωτήρη', $res);

        $res = $klit->getKlitikiForName('Σώτος', true);
        $this->assertSame('Σώτο', $res);

        $res = $klit->getKlitikiForName('Θανάσης', true);
        $this->assertSame('Θανάση', $res);

        $res = $klit->getKlitikiForName('Αθανάσιος', true);
        $this->assertSame('Αθανάσιε', $res);

        $res = $klit->getKlitikiForName('Λουκιανός', true);
        $this->assertSame('Λουκιανέ', $res);

        $res = $klit->getKlitikiForName('Λουκάς', true);
        $this->assertSame('Λουκά', $res);

        $res = $klit->getKlitikiForName('Μηνάς', true);
        $this->assertSame('Μηνά', $res);

        $res = $klit->getKlitikiForName('Γεώργιος', true);
        $this->assertSame('Γεώργιε', $res);

        $res = $klit->getKlitikiForName('Αρίων', true);
        $this->assertSame('Αρίων', $res);

        $res = $klit->getKlitikiForName('Γιώργος', true);
        $this->assertSame('Γιώργο', $res);

        $res = $klit->getKlitikiForName('Βαρδής', true);
        $this->assertSame('Βαρδή', $res);

        $res = $klit->getKlitikiForName('Ιωάννης', true);
        $this->assertSame('Ιωάννη', $res);

        $res = $klit->getKlitikiForName('Λουκάς', true);
        $this->assertSame('Λουκά', $res);

        $res = $klit->getKlitikiForName('Κώστας', true);
        $this->assertSame('Κώστα', $res);

        $res = $klit->getKlitikiForName('Δημήτρης', true);
        $this->assertSame('Δημήτρη', $res);

        $res = $klit->getKlitikiForName('Ελένη', true);
        $this->assertSame('Ελένη', $res);

        $res = $klit->getKlitikiForName('Μαρία', true);
        $this->assertSame('Μαρία', $res);

        $res = $klit->getKlitikiForName('Βάσω', true);
        $this->assertSame('Βάσω', $res);

        $res = $klit->getKlitikiForName('Βοιός', true);
        $this->assertSame('Βοιέ', $res);

        $res = $klit->getKlitikiForName('Θεμιστοκλής', true);
        $this->assertSame('Θεμιστοκλή', $res);

        $res = $klit->getKlitikiForName('Βασιλάκης', true);
        $this->assertSame('Βασιλάκη', $res);
    }

    /**
     * @covers Cosmix\Klitikizer\Klitikizer
     */
    public function testKlitikizerGreekLastNames()
    {
        $klit = new Klitikizer();
        $res = $klit->getKlitikiForName('Δημητρόπουλος', false);
        $this->assertSame('Δημητρόπουλε', $res);

        $res = $klit->getKlitikiForName('Παπαδόπουλος', false);
        $this->assertSame('Παπαδόπουλε', $res);

        $res = $klit->getKlitikiForName('Σημίτης', false);
        $this->assertSame('Σημίτη', $res);

        $res = $klit->getKlitikiForName('Μητσοτάκης', false);
        $this->assertSame('Μητσοτάκη', $res);

        $res = $klit->getKlitikiForName('Τσίπρας', false);
        $this->assertSame('Τσίπρα', $res);

        $res = $klit->getKlitikiForName('Παπανδρέου', false);
        $this->assertSame('Παπανδρέου', $res);

        $res = $klit->getKlitikiForName('Φλωράκης', false);
        $this->assertSame('Φλωράκη', $res);

        $res = $klit->getKlitikiForName('Κύρκος', false);
        $this->assertSame('Κύρκο', $res);

        $res = $klit->getKlitikiForName('Φλώρος', false);
        $this->assertSame('Φλώρο', $res);

        $res = $klit->getKlitikiForName('Σπυρούλος', false);
        $this->assertSame('Σπυρούλε', $res);

        $res = $klit->getKlitikiForName('Ανδριανοπούλου', false);
        $this->assertSame('Ανδριανοπούλου', $res);
    }
}
