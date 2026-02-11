<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;

class FifaEmojiTest extends TestCase
{
    private array $fifaToEmoji;

    protected function setUp(): void
    {
        $this->fifaToEmoji = [
            'MEX' => '🇲🇽',
            'FRA' => '🇫🇷',
            'BRA' => '🇧🇷',
            'USA' => '🇺🇸',
            'ENG' => '🏴',
            'GER' => '🇩🇪',
        ];
    }

    public function testMappingFifaToEmoji(): void
    {
        // Codes valides
        $this->assertEquals('🇫🇷', $this->fifaToEmoji['FRA']);
        $this->assertEquals('🏴', $this->fifaToEmoji['ENG']);

        // Code inexistant -> fallback
        $this->assertArrayNotHasKey('XYZ', $this->fifaToEmoji);
    }
}
