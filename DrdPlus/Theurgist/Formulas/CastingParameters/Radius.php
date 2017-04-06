<?php
namespace DrdPlus\Theurgist\Formulas\CastingParameters;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Tools\ValueDescriber;

class Radius extends CastingParameter implements IntegerInterface
{
    /**
     * @var DistanceBonus
     */
    private $distance;

    /**
     * @param array $values
     * @param DistanceTable $distanceTable
     * @throws \DrdPlus\Theurgist\Formulas\CastingParameters\Exceptions\InvalidValueForRadius
     * @throws \DrdPlus\Theurgist\Formulas\CastingParameters\Exceptions\MissingValueForAdditionByRealm
     * @throws \DrdPlus\Theurgist\Formulas\CastingParameters\Exceptions\InvalidFormatOfRealmsNumber
     * @throws \DrdPlus\Theurgist\Formulas\CastingParameters\Exceptions\InvalidFormatOfAddition
     * @throws \DrdPlus\Theurgist\Formulas\CastingParameters\Exceptions\UnexpectedFormatOfAdditionByRealm
     */
    public function __construct(array $values, DistanceTable $distanceTable)
    {
        try {
            $this->distance = new DistanceBonus(ToInteger::toInteger($values[0] ?? null), $distanceTable);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidValueForRadius(
                'Expected integer for radius, got '
                . (array_key_exists(0, $values) ? ValueDescriber::describe($values[0], true) : 'nothing')
            );
        }
        parent::__construct($values, 1);
    }

    /**
     * @return DistanceBonus
     */
    public function getDistance(): DistanceBonus
    {
        return $this->distance;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->distance->getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->getValue()}/{$this->getAdditionByRealm()}";
    }
}