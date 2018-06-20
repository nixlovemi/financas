<?php
include APPPATH . 'third_party/fpdf16/fpdf.php';

class customFPDF extends FPDF
{
    private $titulo_pdf;
    private $nr_cadastro;
    private $orientation;
    private $username;
    private $headerLineWidth;
    private $footerLineH;
    private $footerSizeUser;
    private $footerSizeDate;
    private $footerSizePgNumber;

    /**
     * Qdo eh Portrait usar tamanho (202); Landscape (289);
     * @param string $titulo_pdf
     * @param string $orientation (P|L)
     */
    function __construct($titulo_pdf, $nr_cadastro, $orientation = 'P', $username = '')
    {
        $unit   = 'mm';
        $format = 'A4';

        $this->titulo_pdf  = utf8_decode($titulo_pdf);
        $this->nr_cadastro = $nr_cadastro;
        $this->orientation = $orientation;
        $this->username    = $username;

        if ($this->orientation == "P") {
            $this->headerLineWidth    = 206;
            $this->footerLineH        = 288;
            $this->footerSizeUser     = 80;
            $this->footerSizeDate     = 80;
            $this->footerSizePgNumber = 42;
        } else {
            $this->headerLineWidth    = 293;
            $this->footerLineH        = 201;
            $this->footerSizeUser     = 114;
            $this->footerSizeDate     = 114;
            $this->footerSizePgNumber = 62;
        }

        parent::__construct($orientation, $unit, $format);
    }

    function Header()
    {
        $this->SetLeftMargin(4);

        // $this->Image($_SESSION ["PATH_CUT"].'/images/Sigla.jpg', 12, 1, 12);
        $this->SetFont('arial', '', 11);

        $this->SetY(1);
        $this->Cell(0, 10, 'WEBAPP', 0, 1, 'C');
        $this->SetY(5);
        $this->Cell(0, 10, $this->titulo_pdf, 0, 0, 'C');

        $this->SetFont('arial', '', 10);
        $this->SetY(8);
        $this->line(4, 13, $this->headerLineWidth, 13);
        $this->Ln(4);

        $this->SetX(3);
        $this->SetFont('arial', '', 8);
        $this->Cell(135, 7, 'Controller: '.$this->nr_cadastro, 0, 0, 'L');
        $this->Ln(10);
    }

    function Footer()
    {

        $strDate         = date("Y-m-d");
        $arrDaysOfWeek   = array('Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado');
        $arrMonthsOfYear = array(1 => 'Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $intDayOfWeek    = date('w', strtotime($strDate));
        $intDayOfMonth   = date('d', strtotime($strDate));
        $intMonthOfYear  = date('n', strtotime($strDate));
        $intYear         = date('Y', strtotime($strDate));
        $data            = $arrDaysOfWeek [$intDayOfWeek].', '.$intDayOfMonth.' de '.$arrMonthsOfYear [$intMonthOfYear].' de '.$intYear;
        $hora            = date("H:i");

        // $this->AddFont('verdana', '', 'verdana.php');
        $this->SetFont('arial', '', 8);

        $this->SetY(- 10);
        $this->line(4, $this->footerLineH, $this->headerLineWidth, $this->footerLineH);
        $this->SetX(3);

        $username = $this->username;

        $this->Ln(2);
        $this->Cell($this->footerSizeUser, 5, "Emitido por: ".$username, 0, 0, 'L');
        $this->Cell($this->footerSizeDate, 5, "em $data $hora", 0, 0, 'L');
        $this->Cell($this->footerSizePgNumber, 5, $this->PageNo().'/'.count($this->pages), 0, 0, 'R');
    }
}
