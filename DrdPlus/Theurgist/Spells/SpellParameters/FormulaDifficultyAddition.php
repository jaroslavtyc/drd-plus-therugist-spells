<?php
namespace DrdPlus\Theurgist\Spells\SpellParameters;

use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Number\NumberInterface;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class FormulaDifficultyAddition extends StrictObject implements IntegerInterface
{
    /**
     * @var int
     */
    private $realmsChangePerAdditionStep;
    /**
     * @var int
     */
    private $difficultyAdditionPerRealm;
    /**
     * @var int
     */
    private $currentAddition;

    /**
     * @param string $difficultyAdditionByRealmsNotation in format 'difficulty per realm' or 'realms=difficulty per realms'
     * @param int|null $currentAddition How much is currently active addition
     * @throws \DrdPlus\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsNotation
     * @throws \DrdPlus\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfRealmsIncrement
     * @throws \DrdPlus\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    public function __construct(string $difficultyAdditionByRealmsNotation, int $currentAddition = null)
    {
        $parts = $this->parseParts($difficultyAdditionByRealmsNotation);
        if (count($parts) === 1 && array_keys($parts) === [0]) {
            $this->realmsChangePerAdditionStep = 1;
            $this->difficultyAdditionPerRealm = $this->sanitizeAddition($parts[0]);
        } else if (count($parts) === 2 && array_keys($parts) === [0, 1]) {
            $this->realmsChangePerAdditionStep = $this->sanitizeRealms($parts[0]);
            $this->difficultyAdditionPerRealm = $this->sanitizeAddition($parts[1]);
        } else {
            throw new Exceptions\InvalidFormatOfAdditionByRealmsNotation(
                "Expected addition by realms in format 'number' or 'number=number', got "
                . ValueDescriber::describe($difficultyAdditionByRealmsNotation)
            );
        }
        $this->currentAddition = $currentAddition ?? 0;/* no addition, no realm increment */
    }

    /**
     * @param string $additionByRealmNotation
     * @return array|string[]
     */
    private function parseParts(string $additionByRealmNotation): array
    {
        $parts = array_map(
            function (string $part) {
                return trim($part);
            },
            explode('=', $additionByRealmNotation)
        );

        foreach ($parts as $part) {
            if ($part === '') {
                return [];
            }
        }

        return $parts;
    }

    /**
     * @param $realmIncrement
     * @return int
     * @throws \DrdPlus\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfRealmsIncrement
     */
    private function sanitizeRealms($realmIncrement): int
    {
        try {
            return ToInteger::toPositiveInteger($realmIncrement);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidFormatOfRealmsIncrement(
                'Expected positive integer for realms increment , got ' . ValueDescriber::describe($realmIncrement)
            );
        }
    }

    /**
     * @param $addition
     * @return int
     * @throws \DrdPlus\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    private function sanitizeAddition($addition): int
    {
        try {
            return ToInteger::toInteger($addition);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidFormatOfAdditionByRealmsValue(
                'Expected integer for addition by realm, got ' . ValueDescriber::describe($addition)
            );
        }
    }

    /**
     * How is realms increased on addition step, @see getDifficultyAdditionPerRealm.
     *
     * @return int
     */
    public function getRealmsChangePerAdditionStep(): int
    {
        return $this->realmsChangePerAdditionStep;
    }

    /**
     * Bonus given by increasing realms, @see getRealmsChangePerAdditionStep
     *
     * @return int
     */
    public function getDifficultyAdditionPerRealm(): int
    {
        return $this->difficultyAdditionPerRealm;
    }

    /**
     * Current value of a difficulty "paid" by realms
     *
     * @return int
     */
    public function getCurrentAddition(): int
    {
        return $this->currentAddition;
    }

    /**
     * Same as @see getCurrentAddition (representing current value of an Integer object)
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->getCurrentAddition();
    }

    /**
     * @param int|float|NumberInterface $value
     * @return FormulaDifficultyAddition
     * @throws \DrdPlus\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    public function add($value): FormulaDifficultyAddition
    {
        $value = $this->sanitizeAddition($value);
        if ($value === 0) {
            return $this;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            $this->getNotation(),
            $this->getValue() + ToInteger::toInteger($value) // current addition is injected as second parameter
        );
    }

    /**
     * @param int|float|NumberInterface $value
     * @return FormulaDifficultyAddition
     * @throws \DrdPlus\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    public function sub($value): FormulaDifficultyAddition
    {
        $value = $this->sanitizeAddition($value);
        if ($value === 0) {
            return $this;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            $this->getNotation(),
            $this->getValue() - ToInteger::toInteger($value) // current addition is injected as second parameter
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->getValue()} {{$this->getRealmsChangePerAdditionStep()}=>{$this->getDifficultyAdditionPerRealm()}}";
    }

    /**
     * @return string
     */
    public function getNotation(): string
    {
        return "{$this->getRealmsChangePerAdditionStep()}={$this->getDifficultyAdditionPerRealm()}";
    }
}