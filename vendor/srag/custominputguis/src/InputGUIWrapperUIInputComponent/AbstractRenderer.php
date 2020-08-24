<?php

namespace srag\CustomInputGUIs\AttendanceList\InputGUIWrapperUIInputComponent;

use ILIAS\UI\Implementation\Component\Input\Field\Renderer;
use ILIAS\UI\Implementation\Render\ResourceRegistry;
use ILIAS\UI\Implementation\Render\Template;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class AbstractRenderer
 *
 * @package srag\CustomInputGUIs\AttendanceList\InputGUIWrapperUIInputComponent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractRenderer extends Renderer
{

    use DICTrait;

    /**
     * @inheritDoc
     */
    public function registerResources(ResourceRegistry $registry)/*: void*/
    {
        parent::registerResources($registry);

        $dir = __DIR__;
        $dir = "./" . substr($dir, strpos($dir, "/Customizing/") + 1);

        $registry->register($dir . "/css/InputGUIWrapperUIInputComponent.css");
    }


    /**
     * @inheritDoc
     */
    protected function getComponentInterfaceName()
    {
        return [
            InputGUIWrapperUIInputComponent::class
        ];
    }


    /**
     * @inheritDoc
     */
    protected function getTemplatePath($name)
    {
        if ($name === "input.html") {
            return __DIR__ . "/templates/" . $name;
        } else {
            // return parent::getTemplatePath($name);
            return "src/UI/templates/default/Input/" . $name;
        }
    }


    /**
     * @param Template                        $tpl
     * @param InputGUIWrapperUIInputComponent $input
     *
     * @return string
     */
    protected function renderInput(Template $tpl, InputGUIWrapperUIInputComponent $input)
    {
        $tpl->setVariable("INPUT", self::output()->getHTML($input->getInput()));

        return self::output()->getHTML($tpl);
    }
}
