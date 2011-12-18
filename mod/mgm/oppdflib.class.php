<?php
//HTML2PDF by ClÈment Lavoillotte
//ac.lavoillotte@noos.fr
//webmaster@streetpc.tk
//http://www.streetpc.tk
// Rev:
//    Remote Learner Custom Edit By John T. Macklin 8/13/2008 11:33:58 PM
//    +180 Added Customized function function Output($name='',$dest='') to
//    include the correct customized headers for MSIE Browser Type when ($dest='I')

//define('FPDF_FONTPATH','font/');
require($CFG->libdir.'/fpdf/fpdfprotection.php');

//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['G']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter in 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}
////////////////////////////////////

class OPPDF extends FPDF_Protection
{
//variables of html parser
var $B;
var $I;
var $U;
var $HREF;
var $fontList;
var $issetfont;
var $issetcolor;

function OPPDF($orientation='P',$unit='mm',$format='A4')
{
    //Call parent constructor
    $this->FPDF_Protection($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
    $this->fontlist=array("arial","times","courier","helvetica","symbol");
    $this->issetfont=false;
    $this->issetcolor=false;
}
public function Header()
  {

    global $CFG;
    $this->Image($CFG->dirroot. '/mod/mgm/pix/logo_report_002.jpg',6,6,0, 8);
    $this->SetFont('Arial','B',8);
    $this->setXY(10,20);
    $this->Cell(50,0,$this->getHeader(),0, 0, 'L');
    $this->SetLineWidth(.3);
    $this->Line(9,22,200,22);
    $this->Image($CFG->dirroot. '/mod/mgm/pix/logo_report_003.jpg',140,15,0,6);

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
    $this->Cell(0,10,iconv('UTF-8', 'windows-1252', $str),0,0,'C');
  }

//Establece el texto de la cabecera
public function setHeader($str)
  {
    $this->header=$str;
  }

//Obtiene el texto de la cabecera
public function getHeader()
  {
    return $this->header;
  }

public function opCabecera($str)
  {
    $this->setHeader(iconv('UTF-8', 'windows-1252', $str) );
  }

public function addTable($matrix, $colwidth=0)
  {
    $this->SetFillColor(200);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    //Cabecera
    $columns=count($matrix[0]);
    $column_width=190/$columns;
    if ((!$colwidth) || count($colwidth) != $columns){
    	$colwidth=array();
    	for($i=0;$i<$columns;$i++){
    		$colwidth[$i]=$column_width;
    	}
    }
    $rows=count($matrix);
    for($i=0;$i<$columns;$i++)
        $this->Cell($colwidth[$i],7,iconv('UTF-8', 'windows-1252', $matrix[0][$i]),1,0,'C',1);
    $this->Ln();
    //Restauración de colores y fuentes
    $this->SetFillColor(224,235,255);
    //$this->SetTextColor(0);
    $this->SetFont('');
    //Datos
    $fill=false;
    for($i=1;$i < $rows; $i++)
    {
    	for($j=0;$j < $columns; $j++) {
        $this->Cell($colwidth[$j],6,ucwords(iconv('UTF-8', 'windows-1252',$matrix[$i][$j])),1,0,'L',$fill);
    	}
    	$this->Ln();
      $fill=!$fill;
    }
  }

public function getFillColor()
{
    return $this->fillColor;
}

public function SetFillColor($r,$g=null,$b=null)
{
   parent::SetFillColor($r);
   $this->fillColor=$r;
}

function Output($name='',$dest='')
{
     //Output PDF to some destination
    //Finish document if necessary
    if($this->state<3)
        $this->Close();
    //Normalize parameters
    if(is_bool($dest))
        $dest=$dest ? 'D' : 'F';
    $dest=strtoupper($dest);
    if($dest=='')
    {
        if($name=='')
        {
            $name='doc.pdf';
            $dest='I';
        }
        else
            $dest='F';
    }
    switch($dest)
    {
        case 'I':
            //Send to standard output
            if(ob_get_contents())
                $this->Error('Some data has already been output, can\'t send PDF file');

           if(php_sapi_name()!='cli')
            {
              if(headers_sent())
                   $this->Error('Some data has already been output to browser, can\'t send PDF file');

                //We send to a browser diffrently using IE than FireFox due to Mime Types
                if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
                  //mtrace("MSIE <br />"); //Remote Learner Rev's begin by John T. Macklin (C) 2008
                   header('Expires: 0');
                   header('Cache-Control: private, pre-check=0, post-check=0, max-age=0, must-revalidate');
                   header('Connection: Keep-Alive');
                   header('Content-Language: ' . current_language());
                   header('Keep-Alive: timeout=5, max=100');
                   header('Content-Type: application/pdf');
                   header('Content-Length: '.strlen($this->buffer));
                   header('Content-Disposition: inline; filename="'.$name.'"');
                   header('Content-Transfer-Encoding: binary');
                   header('Pragma: no-cache');
                   header('Pragma: expires');
                   header('Expires: Mon, 20 Aug 1969 09:23:00 GMT');
                   header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                   echo $this->buffer;

                }else{  // Must not Be MSIE
                   header('Content-Type: application/pdf');
                   header('Content-Length: '.strlen($this->buffer));
                   header('Content-Disposition: inline; filename="'.$name.'"');
                   echo $this->buffer;
                }


            }

            break;
        case 'D':
            //Download file
            if(ob_get_contents())
                $this->Error('Some data has already been output, can\'t send PDF file');

            if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE'))
                header('Content-Type: application/force-download');
            else
                header('Content-Type: application/octet-stream');

               if(headers_sent())
                  $this->Error('Some data has already been output to browser, can\'t send PDF file');

                  header('Content-Length: '.strlen($this->buffer));
                  header('Content-disposition: attachment; filename="'.$name.'"');

            echo $this->buffer;
            break;
        case 'F':
            //Save to local file
            $f=fopen($name,'wb');
            if(!$f)
                $this->Error('Unable to create output file: '.$name);
            fwrite($f,$this->buffer,strlen($this->buffer));
            fclose($f);
            break;
        case 'S':
            //Return as a string
            return $this->buffer;
        default:
            $this->Error('Incorrect output destination: '.$dest);
    }
    return 0;
}

}//end of class
?>