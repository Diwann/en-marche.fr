<?php

namespace Tests\App\Entity\Timeline;

use App\Entity\Timeline\Measure;
use App\Entity\Timeline\MeasureTranslation;
use App\Entity\Timeline\Theme;
use PHPUnit\Framework\TestCase;

class MeasureTest extends TestCase
{
    public function testTitles()
    {
        $measure = new Measure();

        // No translation
        $this->assertEmpty($measure->getTitles());

        // French only
        $measure->addTranslation($this->createMeasureTranslation('fr', 'Titre'));

        $this->assertEquals(['fr' => 'Titre', 'en' => 'Titre'], $measure->getTitles());

        // French + English
        $measure->addTranslation($this->createMeasureTranslation('en', 'Title'));

        $this->assertEquals(['fr' => 'Titre', 'en' => 'Title'], $measure->getTitles());
    }

    public function testGetThemesToIndex()
    {
        $measure = new Measure();

        $this->assertEmpty($measure->getThemesToIndex());

        $measure->addTheme($theme1 = $this->createTheme());
        $measure->addTheme($theme2 = $this->createTheme());

        $this->assertEquals([$theme1, $theme2], $measure->getThemesToIndex()->toArray());

        $measure->removeTheme($theme1);
        $measure->addTheme($theme3 = $this->createTheme());

        $this->assertEquals([$theme1, $theme2, $theme3], $measure->getThemesToIndex()->toArray());
    }

    private function createMeasureTranslation(string $locale, string $title): MeasureTranslation
    {
        $translation = $this->createMock(MeasureTranslation::class);

        $translation
            ->expects($this->any())
            ->method('getLocale')
            ->willReturn($locale)
        ;

        $translation
            ->expects($this->any())
            ->method('getTitle')
            ->willReturn($title)
        ;

        return $translation;
    }

    private function createTheme(): Theme
    {
        return $this->createMock(Theme::class);
    }
}
