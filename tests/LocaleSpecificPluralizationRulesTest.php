<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class LocaleSpecificPluralizationRulesTest extends TestCase
{
    public function testArabicPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            'ولا صوت',
            'صوت واحد',
            'صوتان',
            '%{smart_count} أصوات',
            '%{smart_count} صوت',
            '%{smart_count} صوت'
        ];

        $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => 'ar']);

        $this->assertSame($polyglot->t('n_votes', 0), 'ولا صوت');
        $this->assertSame($polyglot->t('n_votes', 1), 'صوت واحد');
        $this->assertSame($polyglot->t('n_votes', 2), 'صوتان');
        $this->assertSame($polyglot->t('n_votes', 3), '3 أصوات');
        $this->assertSame($polyglot->t('n_votes', 11), '11 صوت');
        $this->assertSame($polyglot->t('n_votes', 102), '102 صوت');
    }

    public function testRussianPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            '%{smart_count} машина',
            '%{smart_count} машины',
            '%{smart_count} машин'
        ];

        foreach (['ru', 'ru-RU'] as $locale) {
            $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => $locale]);

            $this->assertSame($polyglot->t('n_votes', 1), '1 машина');
            $this->assertSame($polyglot->t('n_votes', 11), '11 машин');
            $this->assertSame($polyglot->t('n_votes', 101), '101 машина');
            $this->assertSame($polyglot->t('n_votes', 112), '112 машин');
            $this->assertSame($polyglot->t('n_votes', 932), '932 машины');
            $this->assertSame($polyglot->t('n_votes', 324), '324 машины');
            $this->assertSame($polyglot->t('n_votes', 12), '12 машин');
            $this->assertSame($polyglot->t('n_votes', 13), '13 машин');
            $this->assertSame($polyglot->t('n_votes', 14), '14 машин');
            $this->assertSame($polyglot->t('n_votes', 15), '15 машин');
        }
    }

    public function testCroatianGuestPluralization()
    {
        // English would be: "1 guest" / "%{smart_count} guests"
        $translations = [
            '%{smart_count} gost',
            '%{smart_count} gosta',
            '%{smart_count} gostiju'
        ];

        $polyglot = new Polyglot(['phrases' => ['n_guests' => implode(' |||| ', $translations)], 'locale' => 'hr-HR']);

        $this->assertSame($polyglot->t('n_guests', 1), '1 gost');
        $this->assertSame($polyglot->t('n_guests', 11), '11 gostiju');
        $this->assertSame($polyglot->t('n_guests', 21), '21 gost');

        $this->assertSame($polyglot->t('n_guests', 2), '2 gosta');
        $this->assertSame($polyglot->t('n_guests', 3), '3 gosta');
        $this->assertSame($polyglot->t('n_guests', 4), '4 gosta');

        $this->assertSame($polyglot->t('n_guests', 12), '12 gostiju');
        $this->assertSame($polyglot->t('n_guests', 13), '13 gostiju');
        $this->assertSame($polyglot->t('n_guests', 14), '14 gostiju');
        $this->assertSame($polyglot->t('n_guests', 112), '112 gostiju');
        $this->assertSame($polyglot->t('n_guests', 113), '113 gostiju');
        $this->assertSame($polyglot->t('n_guests', 114), '114 gostiju');
    }

    public function testCroatianVotePluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            '%{smart_count} glas',
            '%{smart_count} glasa',
            '%{smart_count} glasova'
        ];

        $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => 'hr-HR']);

        foreach ([1, 21, 31, 101] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' glas');
        }

        foreach ([2, 3, 4, 22, 23, 24, 32, 33, 34] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' glasa');
        }

        foreach ([0, 5, 6, 11, 12, 13, 14, 15, 16, 17, 25, 26, 35, 36, 112, 113, 114] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' glasova');
        }

        $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => 'hr']);

        foreach ([1, 21, 31, 101] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' glas');
        }

        foreach ([2, 3, 4, 22, 23, 24, 32, 33, 34] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' glasa');
        }

        foreach ([0, 5, 6, 11, 12, 13, 14, 15, 16, 17, 25, 26, 35, 36, 112, 113, 114] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' glasova');
        }
    }

    public function testSerbianLatinAndCyrillicPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            '%{smart_count} miš',
            '%{smart_count} miša',
            '%{smart_count} miševa'
        ];

        foreach (['srl-RS', 'sr-RS'] as $locale) {
            $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => $locale]);

            $this->assertSame($polyglot->t('n_votes', 1), '1 miš');
            $this->assertSame($polyglot->t('n_votes', 11), '11 miševa');
            $this->assertSame($polyglot->t('n_votes', 101), '101 miš');
            $this->assertSame($polyglot->t('n_votes', 932), '932 miša');
            $this->assertSame($polyglot->t('n_votes', 324), '324 miša');
            $this->assertSame($polyglot->t('n_votes', 12), '12 miševa');
            $this->assertSame($polyglot->t('n_votes', 13), '13 miševa');
            $this->assertSame($polyglot->t('n_votes', 14), '14 miševa');
            $this->assertSame($polyglot->t('n_votes', 15), '15 miševa');
            $this->assertSame($polyglot->t('n_votes', 0), '0 miševa');
        }
    }

    public function testBosnianLatinAndCyrillicPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            '%{smart_count} članak',
            '%{smart_count} članka',
            '%{smart_count} članaka'
        ];

        foreach (['bs-Latn-BA', 'bs-Cyrl-BA'] as $locale) {
            $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => $locale]);

            $this->assertSame($polyglot->t('n_votes', 1), '1 članak');
            $this->assertSame($polyglot->t('n_votes', 11), '11 članaka');
            $this->assertSame($polyglot->t('n_votes', 101), '101 članak');
            $this->assertSame($polyglot->t('n_votes', 932), '932 članka');
            $this->assertSame($polyglot->t('n_votes', 324), '324 članka');
            $this->assertSame($polyglot->t('n_votes', 12), '12 članaka');
            $this->assertSame($polyglot->t('n_votes', 13), '13 članaka');
            $this->assertSame($polyglot->t('n_votes', 14), '14 članaka');
            $this->assertSame($polyglot->t('n_votes', 15), '15 članaka');
            $this->assertSame($polyglot->t('n_votes', 112), '112 članaka');
            $this->assertSame($polyglot->t('n_votes', 113), '113 članaka');
            $this->assertSame($polyglot->t('n_votes', 114), '114 članaka');
            $this->assertSame($polyglot->t('n_votes', 115), '115 članaka');
            $this->assertSame($polyglot->t('n_votes', 0), '0 članaka');
        }
    }

    public function testCzechPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            '%{smart_count} komentář',
            '%{smart_count} komentáře',
            '%{smart_count} komentářů'
        ];

        $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => 'cs-CZ']);

        $this->assertSame($polyglot->t('n_votes', 1), '1 komentář');
        $this->assertSame($polyglot->t('n_votes', 2), '2 komentáře');
        $this->assertSame($polyglot->t('n_votes', 3), '3 komentáře');
        $this->assertSame($polyglot->t('n_votes', 4), '4 komentáře');
        $this->assertSame($polyglot->t('n_votes', 0), '0 komentářů');
        $this->assertSame($polyglot->t('n_votes', 11), '11 komentářů');
        $this->assertSame($polyglot->t('n_votes', 12), '12 komentářů');
        $this->assertSame($polyglot->t('n_votes', 16), '16 komentářů');
    }

    public function testSlovenianPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            '%{smart_count} komentar',
            '%{smart_count} komentarja',
            '%{smart_count} komentarji',
            '%{smart_count} komentarjev'
        ];

        $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => 'sl-SL']);

        foreach ([1, 12301, 101, 1001, 201, 301] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' komentar');
        }

        foreach ([2, 102, 202, 302] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' komentarja');
        }

        foreach ([0, 11, 12, 13, 14, 52, 53] as $count) {
            $this->assertSame($polyglot->t('n_votes', $count), $count . ' komentarjev');
        }
    }

    public function testTurkishPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            'Sepetinizde %{smart_count} X var. Bunu almak istiyor musunuz?',
            'Sepetinizde %{smart_count} X var. Bunları almak istiyor musunuz?'
        ];

        $polyglot = new Polyglot(['phrases' => ['n_x_cart' => implode(' |||| ', $translations)], 'locale' => 'tr']);


        $this->assertSame($polyglot->t('n_x_cart', 1), 'Sepetinizde 1 X var. Bunu almak istiyor musunuz?');
        $this->assertSame($polyglot->t('n_x_cart', 2), 'Sepetinizde 2 X var. Bunları almak istiyor musunuz?');
    }

    public function testLithuanianPluralization()
    {
        // English would be: "1 vote" / "%{smart_count} votes"
        $translations = [
            '%{smart_count} balsas',
            '%{smart_count} balsai',
            '%{smart_count} balsų'
        ];

        $polyglot = new Polyglot(['phrases' => ['n_votes' => implode(' |||| ', $translations)], 'locale' => 'lt']);

        $this->assertSame($polyglot->t('n_votes', 0), '0 balsų');
        $this->assertSame($polyglot->t('n_votes', 1), '1 balsas');
        $this->assertSame($polyglot->t('n_votes', 2), '2 balsai');
        $this->assertSame($polyglot->t('n_votes', 9), '9 balsai');
        $this->assertSame($polyglot->t('n_votes', 10), '10 balsų');
        $this->assertSame($polyglot->t('n_votes', 11), '11 balsų');
        $this->assertSame($polyglot->t('n_votes', 12), '12 balsų');
        $this->assertSame($polyglot->t('n_votes', 90), '90 balsų');
        $this->assertSame($polyglot->t('n_votes', 91), '91 balsas');
        $this->assertSame($polyglot->t('n_votes', 92), '92 balsai');
        $this->assertSame($polyglot->t('n_votes', 102), '102 balsai');
    }
}