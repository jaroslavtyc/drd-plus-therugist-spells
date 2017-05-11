<?php
namespace DrdPlus\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Speed\Speed;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Theurgist\Spells\SpellParameters\Partials\IntegerCastingParameter;

/**
 * @method SpellSpeed getWithAddition($additionValue)
 */
class SpellSpeed extends IntegerCastingParameter
{
    /**
     * @param SpeedTable $speedTable
     * @return Speed
     */
    public function getSpeed(SpeedTable $speedTable): Speed
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return (new SpeedBonus($this->getValue(), $speedTable))->getSpeed();
    }
}