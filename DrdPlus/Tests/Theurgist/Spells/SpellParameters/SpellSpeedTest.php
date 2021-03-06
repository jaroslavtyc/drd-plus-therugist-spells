<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tests\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;
use DrdPlus\Theurgist\Spells\SpellParameters\SpellSpeed;

class SpellSpeedTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_speed()
    {
        $speed = new SpellSpeed(['35', '332211']);
        self::assertSame(35, $speed->getValue());
        self::assertEquals(
            (new SpeedBonus(35, $distanceTable = new SpeedTable()))->getSpeed(),
            $speed->getSpeed($distanceTable)
        );
    }
}