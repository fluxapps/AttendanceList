<?php

namespace srag\CustomInputGUIs\AttendanceList\InputGUIWrapperUIInputComponent;

use ILIAS\Refinery\Constraint;
use ILIAS\Refinery\Custom\Constraint as CustomConstraint;

/**
 * Class InputGUIWrapperConstraint
 *
 * @package srag\CustomInputGUIs\AttendanceList\InputGUIWrapperUIInputComponent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class InputGUIWrapperConstraint extends CustomConstraint implements Constraint
{

    use InputGUIWrapperConstraintTrait;
}
