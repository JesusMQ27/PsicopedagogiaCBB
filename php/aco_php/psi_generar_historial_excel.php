<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
require_once './PHPExcel/PHPExcel.php';
$con = new DB(1111);
$conexion = $con->connect();
$s_menu = strip_tags(trim($_GET["codi"]));
$sm_matricula = strip_tags(trim($_GET["matricula"]));
$s_historial = $_GET["historial"];
$s_campos = $_GET["campos"];
$array = explode("*", $sm_matricula);
$matricula = $array[0];
$alumno = $array[1];
$nombre_excel = "Historial_alumno";
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("Codedrinks") //Autor
        ->setLastModifiedBy("Codedrinks") //Ultimo usuario que lo modifico
        ->setTitle("Reporte Historial de Alumno")
        ->setSubject("Reporte Historial")
        ->setDescription("Reporte Historial de Alumno")
        ->setKeywords("Reporte Historial de Alumno")
        ->setCategory("Reporte excel");
$estiloTituloReporte = array(
    'font' => array(
        'name' => 'Verdana',
        'bold' => true,
        'color' => array(
            'rgb' => '000000'
        ),
        'size' => 15,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap' => TRUE
    )
);

$estiloCabeceraReporte = array(
    'font' => array(
        'name' => 'Verdana',
        'bold' => true,
        'color' => array(
            'rgb' => 'FFFFFF'
        ),
        'size' => 10,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => '428bca'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap' => TRUE
    )
);

$estiloCamposReporte = array(
    'font' => array(
        'name' => 'Calibri',
        'bold' => true,
        'color' => array(
            'rgb' => '000000'
        ),
        'size' => 11,
    )
);


$cadena_historial = "";
$cadena_h = "'" . str_replace(",", "','", $s_historial) . "'";
$s_campos = '1,2,' . $s_campos;
$lista_historial = fnc_historial_solicitudes_alumno($conexion, $cadena_h, $alumno, $s_campos);
$array_campos = explode(",", $s_campos);

if (count($lista_historial) > 0) {
    $li_alumno = fnc_datos_alumno($conexion, $alumno);

    //Generando el reporte
    $tituloPersonal = array('Reporte de Historial de Alumno');
    $objPHPExcel->getActiveSheet()->setTitle('Historial Alumno');

    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', $tituloPersonal[0]);

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'ALUMNO')
            ->setCellValue('B4', $li_alumno[0]["alumno"])
            ->setCellValue('A5', 'DNI')
            ->setCellValue('B5', $li_alumno[0]["dni"])
            ->setCellValue('C2', '');
    $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('B4:D4');

    $aumentar = 1;
    $posi = 8;
    $ultima_columna = "";
    $cabeceras_excel = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ'];
    foreach ($lista_historial as $lista) {
        if ($aumentar == 1) {
            for ($i = 0; $i < count($array_campos); $i++) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabeceras_excel[$i] . '7', $lista["" . $array_campos[$i]]);
                $ultima_columna = $cabeceras_excel[$i];
            }
        } else {
            for ($i = 0; $i < count($array_campos); $i++) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabeceras_excel[$i] . $posi, $lista["" . $array_campos[$i]]);
            }
            $posi++;
        }
        $aumentar++;
    }

    //$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A2:" . $ultima_columna . "2");
    $objPHPExcel->getActiveSheet()->setAutoFilter("A7:" . $ultima_columna . "7");
    $objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($estiloCamposReporte);
    $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($estiloCamposReporte);
    $objPHPExcel->getActiveSheet()->getStyle("A7:" . $ultima_columna . "7")->applyFromArray($estiloCabeceraReporte);
    $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('B2:' . $ultima_columna . '2');

    //$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getStyle("A2:" . $ultima_columna . "2")->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(13)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(14)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(15)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(16)->setWidth(32);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(17)->setWidth(30);

    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('cbb_img');
    $objDrawing->setDescription('cbb_img');
    $objDrawing->setPath('../../php/aco_img/logo_2.png');
    $objDrawing->setCoordinates('A1');
    //setOffsetX works properly
    $objDrawing->setOffsetX(5);
    $objDrawing->setOffsetY(5);
//set width, height
    $objDrawing->setWidth(160);
    $objDrawing->setHeight(55);
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
}
$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$desordenada = str_shuffle($caracteres);
$randon = substr($desordenada, 1, 6);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombre_excel . "_" . $randon . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
