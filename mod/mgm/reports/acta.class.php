<?php
//HTML2PDF by ClÈment Lavoillotte
//ac.lavoillotte@noos.fr
//webmaster@streetpc.tk
//http://www.streetpc.tk
// Rev:
//    Remote Learner Custom Edit By John T. Macklin 8/13/2008 11:33:58 PM
//    +180 Added Customized function function Output($name='',$dest='') to
//    include the correct customized headers for MSIE Browser Type when ($dest='I')

require_once($CFG->dirroot.'/mod/mgm/oppdflib.class.php');

class ACTAPDF extends OPPDF
{
//variables of html parser
var $B;
var $I;
var $U;
var $HREF;
var $fontList;
var $issetfont;
var $issetcolor;
var $username;
var $cabecera1;
var $cabecera2;


function ACTAPDF($orientation='P',$unit='mm',$format='A4'){
    //Call parent constructor
    $this->FPDF_Protection($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
    $this->SetLeftMargin(20);
    $this->fontlist=array("arial","times","courier","helvetica","symbol");
    $this->issetfont=false;
    $this->isetcolor=false;
    $this->username=false;
    $this->cabecera1=false;
    $this->cabecera2=false;
}
public function Header()
  {
    global $CFG;
    $this->Image($CFG->dirroot. '/mod/mgm/pix/logo_report_002.jpg',6,6,0, 8);
    $this->SetFont('Arial','B',8);
    $this->setXY(10,20);
    $this->Cell(50,0,$this->getCabecera1(),0, 0, 'L');
    $this->SetLineWidth(.3);
    $this->Line(9,22,200,22);
    $this->Image($CFG->dirroot. '/mod/mgm/pix/logo_report_003.jpg',140,15,0,6);
    //$this->Cell(50);
    $this->setXY(6,24);
    $this->MultiCell(200,4,$this->getCabecera2(),0,'C');
    $this->Ln(5);
  }
  //pie de pagina
public function Footer()
  {
    //Posición: a 1,5 cm del final
    $str= "Página " . $this->PageNo() . '/{nb}';
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,10,iconv('UTF-8', 'windows-1252', $str),0,0,'L');
    $this->SetXY(-60,-18);
    $this->Cell(50,15,'',1,0,'C');
    $this->SetXY(-60,-8);
    $this->Cell(50,5,iconv('UTF-8', 'windows-1252', $this->getUsername()),0,0,'C');
    $this->SetXY(120,-15);
    $this->Cell(0,10,'Fecha: '. date("d-m-Y"),0,0,'L');

  }
public function setUsername($str)
  {
    $this->username=$str;
  }

//Obtiene el texto de la cabecera
public function getUsername(){
    return $this->username;
}

public function setCaberera1($str)
  {
    $this->caberera1=$str;
  }

//Obtiene el texto de la cabecera1
public function getCabecera1() {
    return $this->caberera1;
}

public function setCaberera2($str)
  {
    $this->caberera2=$str;
  }

//Obtiene el texto de la cabecera1
public function getCabecera2() {
    return $this->caberera2;
}

public function opCabecera($str1, $str2)
  {
    $this->setCaberera1(iconv('UTF-8', 'windows-1252', $str1));
    $this->setCaberera2(iconv('UTF-8', 'windows-1252', $str2));
  }



}//end of class
?>