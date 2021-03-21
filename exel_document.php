<?php
require_once 'Classes/PHPExcel.php';

class ExelDocument
{
    public function generatEexel($result)
    {
        $document = new \PHPExcel();
        $sheet = $document->setActiveSheetIndex(0);
        $columnPosition = 0; // x
        $startLine = 2; // y
        $sheet->setCellValueByColumnAndRow($columnPosition, $startLine, 'Результат:');

        $sheet->getStyleByColumnAndRow($columnPosition, $startLine)->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $document->getActiveSheet()->mergeCellsByColumnAndRow($columnPosition, $startLine, $columnPosition+2, $startLine);

        $startLine++;

        $columns = ['Фото', 'Id', 'Время', 'Текст'];

        $currentColumn = $columnPosition;

        foreach ($columns as $column) {
            $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $column);
            $currentColumn++;
        }


        $sheet->setCellValue('A4', $result['photoName']);
        $sheet->setCellValue('B4', $result['userid']);
        $sheet->setCellValue('C4', $result['date']);
        $sheet->setCellValue('D4', $result['decryption']);


        $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel5');
        $objWriter->save('Result.xls');
    }
}

?>