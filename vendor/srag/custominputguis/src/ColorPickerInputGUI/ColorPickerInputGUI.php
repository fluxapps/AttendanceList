<?php

namespace srag\CustomInputGUIs\AttendanceList\ColorPickerInputGUI;

use ilColorPickerInputGUI;
use srag\CustomInputGUIs\AttendanceList\Template\Template;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class ColorPickerInputGUI
 *
 * @package srag\CustomInputGUIs\AttendanceList\ColorPickerInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ColorPickerInputGUI extends ilColorPickerInputGUI
{

    use DICTrait;

    /**
     * @inheritDoc
     */
    public function render($a_mode = "")
    {
        $tpl = new Template("Services/Form/templates/default/tpl.property_form.html", true, true);

        $this->insert($tpl);

        $html = self::output()->getHTML($tpl);

        $html = preg_replace("/<\/div>\s*<!--/", "<!--", $html);
        $html = preg_replace("/<\/div>\s*<!--/", "<!--", $html);

        return $html;
    }
}
