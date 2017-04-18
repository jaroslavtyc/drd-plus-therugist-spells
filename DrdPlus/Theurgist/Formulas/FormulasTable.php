<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Theurgist\Codes\FormCode;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Codes\ProfileCode;
use DrdPlus\Theurgist\Formulas\CastingParameters\Affection;
use DrdPlus\Theurgist\Formulas\CastingParameters\Attack;
use DrdPlus\Theurgist\Formulas\CastingParameters\Brightness;
use DrdPlus\Theurgist\Formulas\CastingParameters\Casting;
use DrdPlus\Theurgist\Formulas\CastingParameters\DetailLevel;
use DrdPlus\Theurgist\Formulas\CastingParameters\DifficultyLimit;
use DrdPlus\Theurgist\Formulas\CastingParameters\Duration;
use DrdPlus\Theurgist\Formulas\CastingParameters\Power;
use DrdPlus\Theurgist\Formulas\CastingParameters\Radius;
use DrdPlus\Theurgist\Formulas\CastingParameters\Realm;
use DrdPlus\Theurgist\Formulas\CastingParameters\SizeChange;
use DrdPlus\Theurgist\Formulas\CastingParameters\Speed;
use DrdPlus\Theurgist\Formulas\CastingParameters\SpellTrait;
use DrdPlus\Theurgist\Formulas\CastingParameters\Transposition;
use Granam\Integer\IntegerInterface;
use Granam\Integer\IntegerObject;

class FormulasTable extends AbstractFileTable
{
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/formulas.csv';
    }

    const REALM = 'realm';
    const AFFECTION = 'affection';
    const CASTING = 'casting';
    const DIFFICULTY_LIMIT = 'difficulty_limit';
    const RADIUS = 'radius';
    const DURATION = 'duration';
    const POWER = 'power';
    const ATTACK = 'attack';
    const SIZE_CHANGE = 'size_change';
    const DETAIL_LEVEL = 'detail_level';
    const BRIGHTNESS = 'brightness';
    const SPEED = 'speed';
    const TRANSPOSITION = 'transposition';
    const FORMS = 'forms';
    const TRAITS = 'traits';
    const PROFILES = 'profiles';
    const MODIFIERS = 'modifiers';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REALM => self::POSITIVE_INTEGER,
            self::AFFECTION => self::ARRAY,
            self::CASTING => self::POSITIVE_INTEGER,
            self::DIFFICULTY_LIMIT => self::ARRAY,
            self::RADIUS => self::ARRAY,
            self::DURATION => self::ARRAY,
            self::POWER => self::ARRAY,
            self::ATTACK => self::ARRAY,
            self::SIZE_CHANGE => self::ARRAY,
            self::DETAIL_LEVEL => self::ARRAY,
            self::BRIGHTNESS => self::ARRAY,
            self::SPEED => self::ARRAY,
            self::TRANSPOSITION => self::ARRAY,
            self::FORMS => self::ARRAY,
            self::TRAITS => self::ARRAY,
            self::PROFILES => self::ARRAY,
            self::MODIFIERS => self::ARRAY,
        ];
    }

    const FORMULA = 'formula';

    protected function getRowsHeader(): array
    {
        return [
            self::FORMULA,
        ];
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Realm
     */
    public function getRealm(FormulaCode $formulaCode): Realm
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Realm($this->getValue($formulaCode, self::REALM));
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Affection
     */
    public function getAffection(FormulaCode $formulaCode): Affection
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Affection($this->getValue($formulaCode, self::AFFECTION));
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Casting
     */
    public function getCasting(FormulaCode $formulaCode): Casting
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Casting($this->getValue($formulaCode, self::CASTING));
    }

    /**
     * @param FormulaCode $formulaCode
     * @return DifficultyLimit
     */
    public function getDifficultyLimit(FormulaCode $formulaCode): DifficultyLimit
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new DifficultyLimit($this->getValue($formulaCode, self::DIFFICULTY_LIMIT));
    }

    /**
     * @param FormulaCode $formulaCode
     * @param DistanceTable $distanceTable
     * @return Radius|null
     */
    public function getRadius(FormulaCode $formulaCode, DistanceTable $distanceTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $radiusValues = $this->getValue($formulaCode, self::RADIUS);
        if (!$radiusValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Radius($radiusValues, $distanceTable);
    }

    /**
     * @param FormulaCode $formulaCode
     * @param TimeTable $timeTable
     * @return Duration
     */
    public function getDuration(FormulaCode $formulaCode, TimeTable $timeTable): Duration
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Duration($this->getValue($formulaCode, self::DURATION), $timeTable);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Power|null
     */
    public function getPower(FormulaCode $formulaCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $powerValues = $this->getValue($formulaCode, self::POWER);
        if (!$powerValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Power($powerValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Attack|null
     */
    public function getAttack(FormulaCode $formulaCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $attackValues = $this->getValue($formulaCode, self::ATTACK);
        if (!$attackValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Attack($attackValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return SizeChange|null
     */
    public function getSizeChange(FormulaCode $formulaCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $sizeChangeValues = $this->getValue($formulaCode, self::SIZE_CHANGE);
        if (!$sizeChangeValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new SizeChange($sizeChangeValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return DetailLevel|null
     */
    public function getDetailLevel(FormulaCode $formulaCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $detailLevelValues = $this->getValue($formulaCode, self::DETAIL_LEVEL);
        if (!$detailLevelValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new DetailLevel($detailLevelValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Brightness|null
     */
    public function getBrightness(FormulaCode $formulaCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $brightnessValues = $this->getValue($formulaCode, self::BRIGHTNESS);
        if (!$brightnessValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Brightness($brightnessValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Speed|null
     */
    public function getSpeed(FormulaCode $formulaCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $speedValues = $this->getValue($formulaCode, self::SPEED);
        if (!$speedValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Speed($speedValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Transposition|null
     */
    public function getTransposition(FormulaCode $formulaCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $transpositionValues = $this->getValue($formulaCode, self::TRANSPOSITION);
        if (!$transpositionValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Transposition($transpositionValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|FormCode[]
     */
    public function getForms(FormulaCode $formulaCode): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return array_map(
            function (string $formValue) {
                return FormCode::getIt($formValue);
            },
            $this->getValue($formulaCode, self::FORMS)
        );
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|SpellTrait[]
     */
    public function getSpellTraits(FormulaCode $formulaCode): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return array_map(
            function (string $spellTraitAnnotation) {
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return new SpellTrait($spellTraitAnnotation);
            },
            $this->getValue($formulaCode, self::TRAITS)
        );
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|ProfileCode[]
     * @throws \DrdPlus\Theurgist\Formulas\Exceptions\UnknownFormulaToGetProfilesFor
     */
    public function getProfiles(FormulaCode $formulaCode): array
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return array_map(
                function (string $profileValue) {
                    return ProfileCode::getIt($profileValue);
                },
                $this->getValue($formulaCode, self::PROFILES)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownFormulaToGetProfilesFor("Given formula code '{$formulaCode}' is unknown");
        }
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|ModifierCode[]
     * @throws \DrdPlus\Theurgist\Formulas\Exceptions\UnknownFormulaToGetModifiersFor
     */
    public function getModifiers(FormulaCode $formulaCode): array
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return array_map(
                function (string $modifierValue) {
                    return ModifierCode::getIt($modifierValue);
                },
                $this->getValue($formulaCode, self::MODIFIERS)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownFormulaToGetModifiersFor("Given formula code '{$formulaCode}' is unknown");
        }
    }

    /**
     * @param FormulaCode $formulaCode
     * @param array $modifiers
     * @param ModifiersTable $modifiersTable
     * @return IntegerInterface
     * @throws \DrdPlus\Theurgist\Formulas\Exceptions\CanNotBuildFormulaWithRequiredModification
     */
    public function getDifficultyOfModified(
        FormulaCode $formulaCode,
        array $modifiers,
        ModifiersTable $modifiersTable
    ): IntegerInterface
    {
        return new IntegerObject(
            $this->calculateRealmAndDifficulty($formulaCode, $modifiers, $modifiersTable)['difficulty']
        );
    }

    /**
     * @param FormulaCode $formulaCode
     * @param array $modifiers
     * @param ModifiersTable $modifiersTable
     * @return Realm
     * @throws \DrdPlus\Theurgist\Formulas\Exceptions\CanNotBuildFormulaWithRequiredModification
     */
    public function getRequiredRealmOfModified(
        FormulaCode $formulaCode,
        array $modifiers,
        ModifiersTable $modifiersTable
    ): Realm
    {
        return $this->calculateRealmAndDifficulty($formulaCode, $modifiers, $modifiersTable)['realm'];
    }

    /**
     * @param FormulaCode $formulaCode
     * @param array $modifiers
     * @param ModifiersTable $modifiersTable
     * @return array
     * @throws \DrdPlus\Theurgist\Formulas\Exceptions\CanNotBuildFormulaWithRequiredModification
     */
    private function calculateRealmAndDifficulty(
        FormulaCode $formulaCode,
        array $modifiers,
        ModifiersTable $modifiersTable
    ): array
    {
        $basicFormulaDifficultyLimit = $this->getDifficultyLimit($formulaCode);
        $formulaAdditionByRealms = $basicFormulaDifficultyLimit->getAdditionByRealms();
        $formulaAdditionByRealmsValue = $formulaAdditionByRealms->getAddition();
        $formulaMaximalDifficulty = $basicFormulaDifficultyLimit->getMaximal();
        $minimalPossibleRealm = $this->getRealm($formulaCode);
        $realmIncrementToHandleAdditionalDifficulty = $formulaAdditionByRealms->getRealmIncrement();
        $difficultyOfModifiedWithoutRealmChange = $this->getDifficultyOfModifiedWithoutRealmChange(
            $formulaCode,
            $modifiers,
            $modifiersTable
        );
        $highestRequiredRealmByModifiers = $modifiersTable->getHighestRequiredRealm($modifiers);
        $additionsCount = 1;
        while ($formulaMaximalDifficulty < $difficultyOfModifiedWithoutRealmChange
            || $minimalPossibleRealm->getValue() < $highestRequiredRealmByModifiers->getValue()
        ) {
            if ($formulaAdditionByRealmsValue <= 0) {
                // this should never happen, because every formula addition is currently greater than zero
                throw new Exceptions\CanNotBuildFormulaWithRequiredModification(
                    "Formula {$formulaCode} with basic difficulty {$basicFormulaDifficultyLimit}"
                    . " can not be build with difficulty {$difficultyOfModifiedWithoutRealmChange}"
                    . " because of its addition by realms {$formulaAdditionByRealms}"
                );
            }
            $formulaMaximalDifficulty += $formulaAdditionByRealmsValue;
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $minimalPossibleRealm = $minimalPossibleRealm->add($realmIncrementToHandleAdditionalDifficulty);
            $additionsCount++;
        }

        return [
            'realm' => $minimalPossibleRealm,
            'difficulty' => $difficultyOfModifiedWithoutRealmChange + $additionsCount * $formulaAdditionByRealmsValue,
        ];
    }

    /**
     * @param FormulaCode $formulaCode
     * @param array $modifiers
     * @param ModifiersTable $modifiersTable
     * @return int
     */
    private function getDifficultyOfModifiedWithoutRealmChange(
        FormulaCode $formulaCode,
        array $modifiers,
        ModifiersTable $modifiersTable
    ): int
    {
        return $this->getDifficultyLimit($formulaCode)->getMinimal()
            + $modifiersTable->sumDifficultyChange($modifiers)->getValue();
    }
}