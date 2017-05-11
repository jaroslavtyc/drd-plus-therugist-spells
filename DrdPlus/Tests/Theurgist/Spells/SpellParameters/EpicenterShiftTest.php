<?php
namespace DrdPlus\Tests\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tests\Theurgist\Spells\SpellParameters\Partials\IntegerCastingParameterTest;
use DrdPlus\Theurgist\Spells\SpellParameters\EpicenterShift;

class EpicenterShiftTest extends IntegerCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_its_distance()
    {
        $shift = new EpicenterShift(['35', '332211']);
        self::assertSame(35, $shift->getValue());
        self::assertEquals(
            (new DistanceBonus(35, $distanceTable = new DistanceTable()))->getDistance(),
            $shift->getDistance($distanceTable)
        );
    }
}