<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Format;

use ilHtmlToPdfTransformerFactory;
use srag\DataTableUI\AttendanceList\Component\Table;

/**
 * Class PdfFormat
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Format
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PdfFormat extends HtmlFormat
{

    /**
     * @inheritDoc
     */
    public function getFormatId()
    {
        return self::FORMAT_PDF;
    }


    /**
     * @inheritDoc
     */
    protected function getFileExtension()
    {
        return "pdf";
    }


    /**
     * @inheritDoc
     */
    protected function renderTemplate(Table $component)
    {
        $html = parent::renderTemplate($component);

        $pdf = new ilHtmlToPdfTransformerFactory();

        $tmp_file = $pdf->deliverPDFFromHTMLString($html, "", ilHtmlToPdfTransformerFactory::PDF_OUTPUT_FILE, self::class, $component->getTableId());

        $data = file_get_contents($tmp_file);

        unlink($tmp_file);

        return $data;
    }
}
