<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Internal library of functions for module mgm
 *
 * All the mgm specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package   mod_mgm
 * @copyright 2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/group/lib.php');

defined('MOODLE_INTERNAL') || die();

define('EDICIONES', $CFG->prefix.'ediciones');
define('EDICIONES_COURSE', $CFG->prefix.'ediciones_course');

define('MGM_CERTIFICATE_NONE', 0);
define('MGM_CERTIFICATE_DRAFT', 1);
define('MGM_CERTIFICATE_VALIDATED', 2);

define('MGM_CRITERIA_PLAZAS', 0);
define('MGM_CRITERIA_OPCION1', 1);
define('MGM_CRITERIA_OPCION2', 2);
define('MGM_CRITERIA_ESPECIALIDAD', 3);
define('MGM_CRITERIA_CC', 4);
define('MGM_CRITERIA_MINGROUP', 5);
define('MGM_CRITERIA_MAXGROUP', 6);
define('MGM_CRITERIA_DEPEND', 7);
define('MGM_CRITERIA_NUMGROUPS', 8);

define('MGM_ITE_ESPECIALIDADES', 1);
define('MGM_ITE_CENTROS', 2);
define('MGM_ITE_SCALA', 3);
define('MGM_ITE_ROLE', 4);

define('MGM_DATA_NO_ERROR', 0);
define('MGM_DATA_CC_ERROR', 1);
define('MGM_DATA_CC_ERROR_PRIVATE', 2);
define('MGM_DATA_DNI_ERROR', 3);
define('MGM_DATA_DNI_INVALID', 4);

define('MGM_PUBLIC_CENTER', 0);
define('MGM_MIXIN_CENTER', 1);
define('MGM_PRIVATE_CENTER', 2);

$CODIGOS_AGRUPACION = array(
    "0" => "0 Agrupación General",
    "1" => "1 Agrupación de Cursos",
    "2" => "2 Investigación Nacional",
    "3" => "3 Proyectos de Formación",
    "4" => "4 Comenius",
    "5" => "5 Lingua",
    "6" => "6 Arion",
    "7" => "7 Petra I y II",
    "8" => "8 Leonardo Da Vinci",
    "9" => "9 Música y Danza",
    "10" => "10 Enseñanza de Idiomas",
    "11" => "11 Titulaciones de Primer Ciclo",
    "12" => "12 Titulaciones de Segundo Ciclo",
    "13" => "13 Titulaciones de Tercer Ciclo",
    "14" => "14 Investigación Provincial",
    "15" => "15 Investigación Carácter Distinto",
    "16" => "16 Proyecto Innovación",
    "17" => "17 Minerva",
    "18" => "18 Medidas de acompañamiento",
    "19" => "19 Grundtvig",
    "20" => "20 Acciones conjuntas"
);

$MODALIDADES = array(
    "10" => "10 Curso",
    "20" => "20 Grupos de Trabajo",
    "30" => "30 Seminarios",
    "A0" => "A0 Curso a distancia"
);

$PROVINCIAS = array(
    10 => "10 ALAVA",
    20 => "20 ALBACETE",
    30 => "30 ALICANTE",
    40 => "40 ALMERIA",
    50 => "50 AVILA",
    60 => "60 BADAJOZ",
    70 => "70 ILLES BALEARS",
    80 => "80 BARCELONA",
    90 => "90 BURGOS",
    100 => "100 CACERES",
    110 => "110 CADIZ",
    120 => "120 CASTELLON",
    130 => "130 CIUDAD REAL",
    140 => "140 CORDOBA",
    150 => "150 A CORUÑA",
    160 => "160 CUENCA",
    170 => "170 GIRONA",
    180 => "180 GRANADA",
    190 => "190 GUADALAJARA",
    200 => "200 GUIPUZCOA",
    210 => "210 HUELVA",
    220 => "220 HUESCA",
    230 => "230 JAEN",
    240 => "240 LEON",
    250 => "250 LLEIDA",
    260 => "260 LA RIOJA",
    270 => "270 LUGO",
    280 => "280 MADRID",
    281 => "281 MADRID-NORTE",
    282 => "282 MADRID-SUR",
    283 => "283 MADRID-ESTE",
    284 => "284 MADRID-OESTE",
    285 => "285 MADRID-CENTRO",
    290 => "290 MALAGA",
    300 => "300 MURCIA",
    310 => "310 NAVARRA",
    320 => "320 OURENSE",
    330 => "330 ASTURIAS",
    340 => "340 PALENCIA",
    350 => "350 LAS PALMAS DE GRAN CANARIA",
    360 => "360 PONTEVEDRA",
    370 => "370 SALAMANCA",
    380 => "380 SANTA CRUZ DE TENERIFE",
    390 => "390 CANTABRIA",
    400 => "400 SEGOVIA",
    410 => "410 SEVILLA",
    420 => "420 SORIA",
    430 => "430 TARRAGONA",
    440 => "440 TERUEL",
    450 => "450 TOLEDO",
    460 => "460 VALENCIA",
    470 => "470 VALLADOLID",
    480 => "480 VIZCAYA",
    490 => "490 ZAMORA",
    500 => "500 ZARAGOZA",
    510 => "510 CEUTA",
    520 => "520 MELILLA",
    600 => "600 EXTRANJERO"
);

$PAISES = array(
    "004" => "004 AFGANISTAN",
    "008" => "008 ALBANIA",
    "010" => "010 ANTARTIDA",
    "012" => "012 ARGELIA",
    "016" => "016 SAMOA NORTEAMERICANA",
    "020" => "020 ANDORRA",
    "024" => "024 ANGOLA",
    "028" => "028 ANTIGUA Y BARBUDA",
    "031" => "031 AZERBAIYAN",
    "032" => "032 ARGENTINA",
    "036" => "036 AUSTRALIA",
    "040" => "040 AUSTRIA",
    "044" => "044 BAHAMAS",
    "048" => "048 BAHREIN",
    "050" => "050 BANGLADESH",
    "051" => "051 ARMENIA",
    "052" => "052 BARBADOS",
    "056" => "056 BELGICA",
    "060" => "060 BERMUDAS",
    "064" => "064 BHUTAN",
    "068" => "068 BOLIVIA",
    "070" => "070 BOSNIA HERZEGOVINA",
    "072" => "072 BOTSWANA",
    "074" => "074 BOUVET",
    "076" => "076 BRASIL",
    "084" => "084 BELICE",
    "086" => "086 INDICO",
    "090" => "090 ISLAS SALOMON",
    "092" => "092 VIRGENES",
    "096" => "096 BRUNEI DARUSSALAM",
    "100" => "100 BULGARIA",
    "104" => "104 MYANMAR",
    "108" => "108 BURUNDI",
    "112" => "112 BIELORRUSIA",
    "116" => "116 CAMBOYA",
    "120" => "120 CAMERUN",
    "124" => "124 CANADA",
    "132" => "132 CABO VERDE",
    "136" => "136 CAIMAN",
    "140" => "140 REPUBLICA CENTROAFRICANA",
    "144" => "144 SRI LANKA",
    "148" => "148 CHAD",
    "152" => "152 CHILE",
    "156" => "156 CHINA",
    "158" => "158 TAIWAN",
    "162" => "162 CHRISTMAS",
    "166" => "166 COCOS",
    "170" => "170 COLOMBIA",
    "174" => "174 COMORES",
    "175" => "175 MAYOTTE",
    "178" => "178 CONGO",
    "180" => "180 REPUBLICA DEMOCRATICA DEL CONGO",
    "184" => "184 COOK",
    "188" => "188 COSTA RICA",
    "191" => "191 CROACIA",
    "192" => "192 CUBA",
    "196" => "196 CHIPRE",
    "202" => "202 REPUBLICA CHECA (RPT)",
    "203" => "203 REPUBLICA CHECA",
    "204" => "204 BENIN",
    "208" => "208 DINAMARCA",
    "212" => "212 DOMINICA",
    "214" => "214 REPUBLICA DOMINICANA",
    "218" => "218 ECUADOR",
    "222" => "222 EL SALVADOR",
    "226" => "226 GUINEA ECUATORIAL",
    "228" => "228 ESLOVAQUIA (RPT)",
    "231" => "231 ETIOPIA",
    "232" => "232 ERITREA",
    "233" => "233 ESTONIA",
    "234" => "234 FEROE",
    "238" => "238 MALVINAS",
    "242" => "242 FIJI",
    "246" => "246 FINLANDIA",
    "249" => "249 FRANCIA METROPOLITANA",
    "250" => "250 FRANCIA",
    "254" => "254 GUAYANA FRANCESA",
    "258" => "258 POLINESIA FRANCESA",
    "260" => "260 FRANCIA",
    "262" => "262 DJIBOUTI",
    "266" => "266 GABON",
    "268" => "268 GEORGIA",
    "270" => "270 GAMBIA",
    "276" => "276 ALEMANIA",
    "280" => "280 ALEMANIA (RPT)",
    "288" => "288 GHANA",
    "292" => "292 GIBRALTAR",
    "296" => "296 KIRIBATI",
    "300" => "300 GRECIA",
    "304" => "304 GROENLANDIA",
    "308" => "308 GRANADA",
    "312" => "312 GUADALUPE",
    "316" => "316 GUAM",
    "320" => "320 GUATEMALA",
    "324" => "324 GUINEA",
    "328" => "328 GUYANA",
    "332" => "332 HAITI",
    "334" => "334 HEARD AND MCDONALD",
    "336" => "336 VATICANO",
    "340" => "340 HONDURAS",
    "344" => "344 HONG KONG",
    "348" => "348 HUNGRIA",
    "352" => "352 ISLANDIA",
    "356" => "356 INDIA",
    "360" => "360 INDONESIA",
    "364" => "364 IRAN",
    "368" => "368 IRAk",
    "372" => "372 IRLANDA",
    "376" => "376 ISRAEL",
    "380" => "380 ITALIA",
    "384" => "384 COSTA DE MARFIL",
    "388" => "388 JAMAICA",
    "392" => "392 JAPON",
    "398" => "398 KAZAJSTAN",
    "400" => "400 JORDANIA",
    "404" => "404 KENIA",
    "408" => "408 REPUBLICA POPULAR DEMOCRATICA DE COREA",
    "410" => "410 REPUBLICA DE COREA",
    "414" => "414 KUWAIT",
    "417" => "417 KIRGUISTAN",
    "418" => "418 LAOS",
    "422" => "422 LIBANO",
    "426" => "426 LESOTHO",
    "428" => "428 LETONIA",
    "430" => "430 LIBERIA",
    "434" => "434 LIBIA",
    "438" => "438 LIECHTENSTEIN",
    "440" => "440 LITUANIA",
    "442" => "442 LUXEMBURGO",
    "446" => "446 MACAO",
    "450" => "450 MADAGASCAR",
    "454" => "454 MALAWI",
    "458" => "458 MALASIA",
    "462" => "462 MALDIVAS",
    "466" => "466 MALI",
    "470" => "470 MALTA",
    "474" => "474 MARTINICA",
    "478" => "478 MAURITANIA",
    "480" => "480 MAURICIO",
    "484" => "484 MEXICO",
    "492" => "492 MONACO",
    "496" => "496 MONGOLIA",
    "498" => "498 MOLDAVIA",
    "500" => "500 MONTSERRAT",
    "504" => "504 MARRUECOS",
    "508" => "508 MOZAMBIQUE",
    "512" => "512 OMAN",
    "516" => "516 NAMIBIA",
    "520" => "520 NAURU",
    "524" => "524 NEPAL",
    "528" => "528 HOLANDA",
    "530" => "530 ANTILLAS HOLANDESAS",
    "533" => "533 ARUBA",
    "540" => "540 NUEVA CALEDONIA",
    "548" => "548 VANUATU",
    "554" => "554 NUEVA ZELANDA",
    "558" => "558 NICARAGUA",
    "562" => "562 NIGER",
    "566" => "566 NIGERIA",
    "570" => "570 NIUE",
    "574" => "574 NORFOLK",
    "578" => "578 NORUEGA",
    "580" => "580 ISLAS MARIANA",
    "583" => "583 MICRONESIA",
    "584" => "584 ISLAS MARSHALL",
    "585" => "585 PALAU",
    "586" => "586 PAKISTAN",
    "591" => "591 PANAMA",
    "598" => "598 PAPUA NUEVA GUINEA",
    "600" => "600 PARAGUAY",
    "604" => "604 PERU",
    "608" => "608 FILIPINAS",
    "612" => "612 PITCAIRN",
    "616" => "616 POLONIA",
    "620" => "620 PORTUGAL",
    "624" => "624 GUINEA-BISSAU",
    "626" => "626 TIMOR",
    "630" => "630 PUERTO RICO",
    "634" => "634 QATAR",
    "638" => "638 REUNION",
    "642" => "642 RUMANIA",
    "643" => "643 RUSIA",
    "646" => "646 RUANDA",
    "654" => "654 SANTA ELENA",
    "659" => "659 SAN CRISTOBAL Y NIEVES",
    "660" => "660 ANGUILLA",
    "662" => "662 SANTA LUCIA",
    "666" => "666 SAN PEDRO Y MIQUELON",
    "670" => "670 SAN VICENTE Y LAS GRANADINAS",
    "674" => "674 SAN MARINO",
    "678" => "678 SANTO TOME Y PRINCIPE",
    "682" => "682 ARABIA SAUDITA",
    "686" => "686 SENEGAL",
    "690" => "690 SEYCHELLES",
    "694" => "694 SIERRA LEONA",
    "702" => "702 SINGAPUR",
    "703" => "703 ESLOVAQUIA",
    "704" => "704 VIETNAN",
    "705" => "705 ESLOVENIA",
    "706" => "706 SOMALIA",
    "710" => "710 REPUBLICA SUDAFRICANA",
    "716" => "716 ZIMBABWE",
    "724" => "724 ESPAÑA",
    "732" => "732 SAHARA OCCIDENTAL",
    "736" => "736 SUDAN",
    "740" => "740 SURINAM",
    "744" => "744 SVALBARD Y JAN MAYEN",
    "748" => "748 SWAZILANDIA",
    "752" => "752 SUECIA",
    "756" => "756 SUIZA",
    "760" => "760 SIRIA",
    "762" => "762 TAYIKISTAN",
    "764" => "764 TAILANDIA",
    "768" => "768 TOGO",
    "772" => "772 TOKELAU",
    "776" => "776 TONGA",
    "780" => "780 TRINIDAD Y TOBAGO",
    "784" => "784 EMIRATOS ARABES UNIDOS",
    "788" => "788 TUNEZ",
    "792" => "792 TURQUIA",
    "795" => "795 TURKMENISTAN",
    "796" => "796 TURKS AND CAICOS",
    "798" => "798 TUVALU",
    "800" => "800 UGANDA",
    "804" => "804 UCRANIA",
    "807" => "807 MACEDONIA",
    "818" => "818 EGIPTO",
    "826" => "826 REINO UNIDO",
    "834" => "834 TANZANIA",
    "840" => "840 ESTADOS UNIDOS DE AMERICA",
    "850" => "850 VIRGENES",
    "854" => "854 BURKINA FASSO",
    "858" => "858 URUGUAY",
    "860" => "860 UZBEKISTAN",
    "862" => "862 VENEZUELA",
    "876" => "876 WALLIS AND FUTUNA",
    "882" => "882 SAMOA OCCIDENTAL",
    "887" => "887 YEMEN",
    "891" => "891 YUGOSLAVIA",
    "894" => "894 ZAMBIA",
    "900" => "900 CHECOSLOVAQUIA",
    "901" => "901 REPUBLICA DEMOCRATICA ALEMANA",
    "902" => "902 REPUBLICA FEDERAL ALEMANA",
    "903" => "903 U.R.S.S.",
    "904" => "904 VIETNAN DEL NORTE",
    "905" => "905 VIETNAN DEL SUR",
    "906" => "906 YEMEN DEL SUR",
    "907" => "907 YEMEN DEMOCRATICO",
    "908" => "908 ZAIRE",
    "909" => "909 ZONA NEUTRAL",
    "992" => "992 PALESTINA",
    "993" => "993 JERUSALEN",
    "994" => "994 FRANJA DE GAZA",
    "995" => "995 CISJORDANIA",
    "996" => "996 ALTOS DEL GOLAN",
    "997" => "997 VARIOS",
    "998" => "998 APATRIDA",
    "999" => "999 DESCONOCIDO"
);

$MATERIAS = array(
    "0001" => "0001 MATERIA DE MIGRACIÓN",
    "0101" => "0101 TEOR. Y ORG. CURRICULAR ASP. GENER.",
    "0102" => "0102 TEORIA DEL CURRICULO",
    "0103" => "0103 DISEÑO GRAL. DEL CURRICULO",
    "0104" => "0104 PROYECTO EDUCATIVO DE CENTRO/ZONA",
    "0105" => "0105 PROYECTO CURRICULAR",
    "0106" => "0106 PROGRAMACION DE AULA",
    "0201" => "0201 ID. Y AUT. PERSONAL. ASP. GENERALES",
    "0202" => "0202 EL CUERPO Y LA PROPIA IMAGEN",
    "0203" => "0203 JUEGO",
    "0204" => "0204 LA ACTIVIDAD Y LA VIDA COTIDIANA",
    "0205" => "0205 EL CUIDADO DE UNO MISMO",
    "0301" => "0301 MEDIO FISICO Y SOCIAL. ASP. GENER.",
    "0302" => "0302 LOS PRIMEROS GRUPOS SOCIALES",
    "0303" => "0303 LOS ANIMALES Y LAS PLANTAS",
    "0401" => "0401 COMUN. Y REPRES. ASPECTOS GENERALES",
    "0402" => "0402 LENGUAJE ORAL",
    "0403" => "0403 APROXIMACION AL LENGUAJE ESCRITO",
    "0404" => "0404 EXPRESION PLASTICA",
    "0405" => "0405 EXPRESION MUSICAL",
    "0406" => "0406 RELACIONES MEDIDA Y REPRESENTACION",
    "0501" => "0501 CONOCIM DEL MEDIO. ASPECTOS GENER.",
    "0502" => "0502 CONOCIMIENTO DEL CUERPO Y SALUD",
    "0503" => "0503 MEDIO SOCIAL",
    "0504" => "0504 EL PASO DEL TIEMPO",
    "0505" => "0505 CULTURA Y TECNOLOGIA",
    "0601" => "0601 ED. ARTISTICA. ASPECTOS GENERALES",
    "0602" => "0602 EXPRESION PLASTICA Y VISUAL",
    "0603" => "0603 MUSICA",
    "0604" => "0604 DRAMATIZACION",
    "0605" => "0605 ARTES APLICADAS",
    "0701" => "0701 ED. FISICA. ASPECTOS GENERALES",
    "0702" => "0702 CONDICION FISICA",
    "0703" => "0703 JUEGOS",
    "0704" => "0704 DEPORTES",
    "0801" => "0801 LENG. Y LIT. ASPECTOS GENERALES",
    "0802" => "0802 LENGUA ESPAÑOLA",
    "0803" => "0803 LITERATURA ESPAÑOLA",
    "0804" => "0804 OTRAS LENGUAS DEL ESTADO",
    "0805" => "0805 LITERATURA UNIVERSAL",
    "0901" => "0901 LENG. Y CULT. CLASICAS. ASP. GENER.",
    "0902" => "0902 LATIN",
    "0903" => "0903 GRIEGO",
    "1001" => "1001 LENGUAS EXTR. ASPECTOS GENERALES",
    "1002" => "1002 INGLES",
    "1003" => "1003 FRANCES",
    "1004" => "1004 ALEMAN",
    "1005" => "1005 ITALIANO",
    "1006" => "1006 OTROS IDIOMAS",
    "1101" => "1101 MATEMATICAS. ASPECTOS GENERALES",
    "1102" => "1102 NUMEROS",
    "1103" => "1103 ALGEBRA",
    "1104" => "1104 GEOMETRIA",
    "1105" => "1105 ANALISIS",
    "1106" => "1106 ESTADISTICA Y PROBABILIDAD",
    "1107" => "1107 ASTRONOMIA",
    "1201" => "1201 CIENC. DE LA NAT. ASPECTOS GENER.",
    "1202" => "1202 FISICA",
    "1203" => "1203 QUIMICA",
    "1204" => "1204 BIOLOGIA",
    "1205" => "1205 GEOLOGIA",
    "1206" => "1206 CIENCIAS MEDIOAMBIENTALES",
    "1301" => "1301 CIENC. SOCIALES. ASPECTOS GENERALES",
    "1302" => "1302 GEOGRAFIA",
    "1303" => "1303 HISTORIA",
    "1304" => "1304 HISTORIA DEL ARTE",
    "1305" => "1305 FILOSOFIA",
    "1306" => "1306 ECONOMIA",
    "1307" => "1307 PSICOLOGIA",
    "1308" => "1308 SOCIOLOGIA",
    "1401" => "1401 ED. RELIGIOSA. ASPECTOS GENERALES",
    "1501" => "1501 TECNOLOGIA. ASPECTOS GENERALES",
    "1502" => "1502 PROCESOS CREACION Y DESARR. PRODUC.",
    "1503" => "1503 REPR. Y COMUNICACION PROCESOS TECN.",
    "1504" => "1504 MATERIALES Y TECNICAS CONSTRUCTIVAS",
    "1505" => "1505 CONOCIMIENTOS TECNOLOGICOS ESPECIA.",
    "1506" => "1506 TECNOLOGIA",
    "1601" => "1601 FORM. PROFESIONAL. ASPECTOS GENER.",
    "1602" => "1602 ORIENTACION PROFESIONAL",
    "1603" => "1603 TRANSICION A LA VIDA ACTIVA",
    "1604" => "1604 FORMACION EN CENTROS DE TRABAJO",
    "1605" => "1605 GARANTIA SOCIAL",
    "1606" => "1606 DESARROLLO LOCAL Y REGIONAL",
    "1607" => "1607 FORMACION CONCERTADA",
    "1701" => "1701 ACTIVIDADES SOCIOCULTURALES",
    "1702" => "1702 ADMINISTRACION DE EMPRESAS",
    "1703" => "1703 ASESORIA DE CONSUMO",
    "1704" => "1704 AUXILIAR ADMINISTRACION Y GESTION",
    "1705" => "1705 AUXILIAR DE COMERCIO INTERIOR",
    "1706" => "1706 BIBLIOTECONOMIA",
    "1707" => "1707 COMERCIO EXTERIOR",
    "1708" => "1708 PROGRAMADOR DE GESTION",
    "1709" => "1709 SECRETARIO EJECUTIVO MULTILINGUE",
    "1801" => "1801 A. FORESTALES Y CONSERV. NATURALEZA",
    "1802" => "1802 EXPLOTACIONES AGROPECUARIAS",
    "1803" => "1803 FITOPATOLOGIA",
    "1804" => "1804 FRUTICULTURA",
    "1805" => "1805 HORTICULTURA",
    "1806" => "1806 HORTOFRUTICULTURA",
    "1807" => "1807 INSTALACION Y MTO. DE JARDINES",
    "1808" => "1808 JARDINERO PRODUCTOR DE PLANTAS",
    "1901" => "1901 FOTOCOMPOSICION",
    "1902" => "1902 REPRODUCCION FOTOMECANICA",
    "2001" => "2001 AUTOMOCION",
    "2002" => "2002 CARROCERIA",
    "2101" => "2101 ACABADOS DE CONSTRUCCION",
    "2102" => "2102 ALBAÑILERIA",
    "2103" => "2103 CUBRICION DE EDIFICIOS",
    "2104" => "2104 HORMIGON",
    "2201" => "2201 DELINEACION INDUSTRIAL",
    "2301" => "2301 INST. Y MTO. EQUIPOS FRIO Y CALOR",
    "2302" => "2302 INST./MANTENEDOR ELECTRICO",
    "2303" => "2303 INST. TERMICAS AUX. DE PROCESO",
    "2304" => "2304 MTO. DE INST. SERVICIO AUXILIARES",
    "2305" => "2305 SISTEMAS AUTOMATICOS Y PROGRAMABLES",
    "2401" => "2401 COCINA",
    "2402" => "2402 RECEPCION",
    "2501" => "2501 MTO. Y OPER. TEC. EQUIPOS DE R.T.V.",
    "2502" => "2502 OPERACIONES DE IMAGEN Y SONIDO",
    "2503" => "2503 REALI.",
    "2601" => "2601 CARPINTERIA Y MUEBLE",
    "2602" => "2602 MECANIZADO DE LA MADERA",
    "2701" => "2701 CULTIVOS MARINOS",
    "2801" => "2801 FABRICACION MECANICA",
    "2802" => "2802 FABRICACION SOLDADA",
    "2803" => "2803 MTO. DE MAQUINAS Y SIST. AUTOMATIC.",
    "2804" => "2804 MANTENIMIENTO EN LINEA",
    "2805" => "2805 OPERADOR DE MAQUINAS HERRAMIENTAS",
    "2901" => "2901 CONFECCION INDUSTRIAL",
    "3001" => "3001 ESTETICA FACIAL",
    "3002" => "3002 PELUQUERIA",
    "3101" => "3101 AUXILIAR DE LABORATORIO",
    "3102" => "3102 INDUSTRIAS ALIMENTARIAS",
    "3201" => "3201 ACT. FISICAS Y ANIMACION DEPORTIVA",
    "3202" => "3202 ANATOMIA PATOLOGIA-CITOLOGIA",
    "3203" => "3203 AUXILIAR DE ENFERMERIA",
    "3204" => "3204 SALUD AMBIENTAL",
    "3301" => "3301 EDUCADOR INFANTIL",
    "3401" => "3401 HILATURA Y TEJEDURIA",
    "3402" => "3402 PROCESOS QUIMICOS TEXTILES",
    "3501" => "3501 ALFARERIA Y CERAMICA",
    "3601" => "3601 ACT. INTERAREAS. ASPECTOS GENERALES",
    "3701" => "3701 TEMAS TRANSVERSALES. ASP. GENERALES",
    "3702" => "3702 EDUCACION AMBIENTAL",
    "3703" => "3703 EDUCACION DEL CONSUMIDOR",
    "3704" => "3704 EDUCACION MORAL Y CIVICA",
    "3705" => "3705 EDUCACION PARA LA IGUALDAD OPORTUN.",
    "3706" => "3706 EDUCACION PARA LA PAZ",
    "3707" => "3707 EDUCACION PARA LA SALUD",
    "3708" => "3708 EDUCACION VIAL",
    "3709" => "3709 PRENSA-ESCUELA",
    "3710" => "3710 OTROS TEMAS TRANSVERSALES",
    "3801" => "3801 NUEVAS TECNOLOGIAS. ASP. GENERALES",
    "3802" => "3802 MEDIOS INFORMATICOS",
    "3803" => "3803 MEDIOS AUDIOVISUALES",
    "3901" => "3901 ATENCION A LA DIVERSIDAD. ASP. GEN.",
    "3902" => "3902 OPTATIVIDAD",
    "3903" => "3903 DIVERSIFICACION Y ADAPT. CURRICULAR",
    "3904" => "3904 NECESIDADES EDUCATIVAS ESPECIALES",
    "3905" => "3905 ATENCION A MINORIAS ETNICAS",
    "4001" => "4001 ASPECTOS GENERALES",
    "4002" => "4002 INTERVENCION INSTITUCIONAL",
    "4003" => "4003 APOYO PSICOPEDAGOGICO",
    "4004" => "4004 ORIENTACION",
    "4005" => "4005 TUTORIA",
    "4101" => "4101 DIREC. Y GESTION. ASP. GENERALES",
    "4102" => "4102 PLANIF. Y EJECUCION PROY. Y PROGR.",
    "4103" => "4103 ESTRUC. ORGANIZ. TRABAJO Y GESTION",
    "4104" => "4104 LAS RELACIONES Y LA PARTICIPACION",
    "4105" => "4105 RECOGIDA",
    "4201" => "4201 ADMINIST. EDUCATIVA. ASP. GENERALES",
    "4202" => "4202 PLANIFICACION EDUCATIVA",
    "4203" => "4203 FUNCIONAMIENTO Y GESTION ADM. EDUC.",
    "4204" => "4204 HABILIDADES SOCIALES PARA INTERVEN.",
    "4301" => "4301 FORMACION PERMANENTE. ASP. GENER.",
    "4302" => "4302 MODELOS DE FORMACION",
    "4303" => "4303 MODALIDADES Y ESTRATEGIAS DE FORM.",
    "4304" => "4304 RECOGIDA",
    "4305" => "4305 HABILIDADES SOCIALES PARA INTERVEN.",
    "9901" => "9901 MATERIA DE MIGRACION"
);

$NIVELES_EDUCATIVOS = array(
    '19' => '19 COMPENSATORIA',
    '17' => '17 EDUCACION DE PERSONAS ADULTAS',
    '18' => '18 EDUCACION ESPECIAL O INTEGRACION',
    '10' => '10 EDUCACION INFANTIL',
    '12' => '12 EDUCACION INFANTIL Y PRIMARIA',
    '15' => '15 ENSEÑANZAS ARTISTICAS',
    '21' => '21 EQUIPOS DE ORIENTACION',
    '20' => '20 EQUIPOS DIRECTIVOS',
    '22' => '22 EQUIPOS INTERDISCIPLINARES',
    '16' => '16 ESCUELAS OFICIALES DE IDIOMAS',
    '14' => '14 FORMACION PROFESIONAL',
    '27' => '27 FORMACION PROFESIONAL GRADO ESPECIFICA',
    '26' => '26 FORMACION PROFESIONAL GRADO MEDIO',
    '24' => '24 OTROS NIVELES / OTROS COLECTIVOS',
    '23' => '23 PERSONAL RED DE FORM. PERMANENTE',
    '11' => '11 PRIMARIA',
    '13' => '13 SECUNDARIA',
    '25' => '25 TODOS LOS PROFESORES CUALQUIER NIV.'
);

$CUERPOS_DOCENTES = array(
    "0000" => "0000 NO FUNCIONARIO",
    "0590" => "0590 PROFESORES DE ENSEÑANZA SECUNDARIA",
    "0591" => "0591 PROF. TECNICOS FORMACION PROFESIONAL",
    "0592" => "0592 PROFESORES DE ESC. OFICIALES DE IDIOMAS",
    "0593" => "0593 CATEDRATICOS DE MUSICA Y ARTES ESCENICAS",
    "0594" => "0594 PROFESORES DE MUSICA Y ARTES ESCENICAS",
    "0595" => "0595 PROFESORES DE ARTES PLASTICAS Y DISEÑO",
    "0596" => "0596 MAESTROS DE TALLER ARTES PLAST. Y DISEÑO",
    "0597" => "0597 MAESTROS",
    "5407" => "5407 ESCALA DOCENTE GRUPO A DE LA AISS",
    "5423" => "5423 ESCALA DOCENTE GRUPO B DE LA AISS",
    "6470" => "6470 PROF.NUMER. Y PSICOL. ENS.INTEGR.",
    "6471" => "6471 PROF.MATER.TECN.-PROF.Y EDUC.E.I.",
    "6472" => "6472 PROF.PRACTICAS Y ACTIVIDADES E.I.",
    "7100" => "7100 INSPECTORES DE EDUCACION",
    "7110" => "7110 INSPECTORES AL SERVICIO ADMON. EDUCATIVA",
    "8100" => "8100 PROFESORES UNIVERSITARIOS",
    "8110" => "8110 ESCALA DE TEC.DE GESTIÓN DE U.SEVILLA",
    "8120" => "8120 ESCALA TEC. DE GESTIÓN DE U.DE CADIZ",
    "8121" => "8121 OTROS PROFESORES/OTROS PROFESIONALES",
    "8122" => "8122 OTROS FUNCIONARIOS DE LA ADMINISTRACION",
    "8123" => "8123 CATEDRÁTICOS DE UNIVERSIDAD"
);

/**
 * Checks if an user can perform the view action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_view($cm=0) {
    if ($cm) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:viewedicion', $context));
}

/**
 * Checks if an user can perform the create action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_create($cm=0) {
    if ($cm) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:createedicion', $context));
}

/**
 * Checks if an user can perform the edit action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_edit($cm=0) {
    if ($cm) {
         $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:editedicion', $context));
}

/**
 * Checks if an user can perform the assigncriteria action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_assigncriteria($cm=0) {
    if ($cm) {
         $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:assigncriteria', $context));
}

/**
 * Checks if an user can perform the aprobe action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_aprobe($cm=0) {
    if ($cm) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:aprobe', $context));
}

/**
 * Prints the turn editing on/off button on mod/mgm/index.php
 *
 * @param integer $editionid The id of the edition we are showing or 0 for system context
 * @return string HTML of the editing button, or empty string if the user is not allowed to see it.
 */
function mgm_update_edition_button($editionid = 0) {
    global $CFG, $USER;

    // Check permissions.
    if (!mgm_can_do_create()) {
        return '';
    }

    // Work out the appropiate action.
    if (!empty($USER->editionediting)) {
        $label = get_string('turneditingoff');
        $edit = 'off';
    } else {
        $label = get_string('turneditingon');
        $edit = 'on';
    }

    // Generate the button HTML.
    $options = array('editionedit' => $edit, 'sesskey' => sesskey());
    if ($editionid) {
        $options['id'] = $editionid;
        $page = 'edition.php';
    } else {
        $page = 'index.php';
    }

    return print_single_button($CFG->wwwroot.'/mod/mgm/'.$page, $options, $label, 'get', '', true);
}

function mgm_get_edition_data($edition) {
    if (!is_object($edition)) {
        return NULL;
    }

    $fulledition = array(
        'edition'		 => $edition,
        'courses'	 	 => get_record('edicion_course', 'edicionid', $edition->id),
        'criteria'		 => get_record('edicion_criterios', 'edicion', $edition->id),
        'preinscripcion' => get_record('edicion_preinscripcion', 'edicionid', $edition->id),
        'inscripcion'	 => get_record('edicion_inscripcion', 'edicionid', $edition->id)
    );

    return $fulledition;
}

/**
 * Return the number of courses in an edition.
 *
 * @param object $edition
 * @return string The number of courses in an edition
 */
function mgm_count_courses($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return '';
    }

    $sql = "SELECT COUNT(d.id) FROM ".$CFG->prefix."edicion_course d
    		WHERE d.edicionid = $edition->id";

    return get_field_sql($sql);
}

function mgm_get_edition_course($editionid, $courseid) {
    return get_record('edicion_course', 'edicionid', $editionid, 'courseid', $courseid);
}

/**
 * Return an edition HTML link
 * @param object $edition
 * @return string
 */
function mgm_get_edition_link($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return '';
    }

    return '<a title="'.get_string('edit').'" href="edicionedit.php?id='.$edition->id.'">'.$edition->name.'</a>';
}

/**
 * Return the edition's actions menu HTML code
 *
 * @param object $edition
 * @return string
 */
function mgm_get_edition_menu($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return '';
    }

    $menu  = '<a title="'.get_string('edit').'" href="edicionedit.php?id='.$edition->id.'"><img'.
             ' src="'.$CFG->pixpath.'/t/edit.gif" class="iconsmall" alt="'.get_string('edit').'" /></a>';
    $menu .= ' | ';
    $menu .= '<a title="'.get_string('delete').'" href="delete.php?id='.$edition->id.'">'.
             '<img src="'.$CFG->pixpath.'/t/delete.gif" class="iconsmall" alt="'.get_string('delete').'" /></a>';
    $menu .= ' | ';
    if (mgm_edition_is_active($edition)) {
        $menu .= '<a title="'.get_string('desactivar', 'mgm').'" href="active.php?id='.$edition->id.'">'.
             	 '<img src="'.$CFG->pixpath.'/t/stop.gif" class="iconsmall" alt="'.get_string('desactivar', 'mgm').'" /></a>';
    } else {
        $menu .= '<a title="'.get_string('activar', 'mgm').'" href="active.php?id='.$edition->id.'">'.
             	 '<img src="'.$CFG->pixpath.'/t/go.gif" class="iconsmall" alt="'.get_string('activar', 'mgm').'" /></a>';
    }
    if (!mgm_edition_is_certified($edition)) {
        $menu .= ' | <a title="'.get_string('cert', 'mgm').'" href="certificate.php?id='.$edition->id.'" id="edicion_'.$edition->id.'">'.
         		 '<img src="'.$CFG->pixpath.'/t/grades.gif" class="iconsmall" alt="'.get_string('cert', 'mgm').'" /></a>';
    } else {
        if (mgm_edition_is_on_draft($edition)) {
            $menu .= ' | <a title="'.get_string('certdraft', 'mgm').'" href="certificate.php?id='.$edition->id.'&draft=1" id="edicion_'.$edition->id.'">'.
         			 '<img src="'.$CFG->pixpath.'/c/site.gif" class="iconsmall" alt="'.get_string('certdraft', 'mgm').'" /></a>';
        }

        if (mgm_edition_is_on_validate($edition)) {
            $menu .= ' | <a title="'.get_string('certified', 'mgm').'" href="#">'.
         			 '<img src="'.$CFG->pixpath.'/i/tick_green_small.gif" class="iconsmall" alt="'.get_string('certified', 'mgm').'" /></a>';
        }
    }

    return $menu;
}

function mgm_print_ediciones_list() {
    global $CFG;

    $editions = get_records('edicion');

    $editionimage = '<img src="'.$CFG->pixpath.'/i/db.gif" alt="" />';
    $courseimage = '<img src="'.$CFG->pixpath.'/i/course.gif" alt="" />';
    $table = '<table class="mod-mgm editionlist">';
    foreach($editions as $edition) {
        $table .= '<tr>';
        $table .= '<td valign="top" class="mod-mgm edition image">'.$editionimage.'</td>';
        $table .= '<td valign="top" class="mod-mgm edition name">';
        $table .= format_string($edition->name);
        $table .= '</td>';
        $table .= '<td class="mod-mgm edition info">&nbsp;</td>';
        if (mgm_count_courses($edition) > 0) {
            foreach(mgm_get_edition_courses($edition) as $course) {
                $table .= '<tr>';
                $table .= '<td valign="top" class="mod-mgm edition image course">'.$courseimage.'</td>';
                $table .= '<td valign="top" class="mod-mgm edition name course">';
                $table .= '<a class="mod-mgm edition link course" href="'.$CFG->wwwroot.'/mod/mgm/courses.php?courseid='.
                          $course->id.'&edicionid='.$edition->id.'">'.format_string($course->fullname).'</a>';
                if ($cr = mgm_exists_criteria_for_course($edition, $course)) {
                    $table .= ' <span style="font-size: 9pt;">(Criterios Fijados: '.$cr.' )</span>';
                } else {
                    $table .= ' <span style="font-size: 9pt;">(Criterios no Fijados)</span>';
                }
                $table .= '</td>';
                $table .= '<td class="mod-mgm course info">&nbsp;</td>';
                $table .= '</tr>';
            }
        }
        $table .= '</tr>';
    }
    $table .= '</table>';

    echo $table;
}

function mgm_print_whole_ediciones_list() {
    global $CFG;

    $editions = get_records('edicion');

    $editionimage = '<img src="'.$CFG->pixpath.'/i/db.gif" alt="" />';
    $courseimage = '<img src="'.$CFG->pixpath.'/i/course.gif" alt="" />';
    $table = '<table class="mod-mgm editionlist">';
    foreach($editions as $edition) {
        $table .= '<tr>';
        $table .= '<td valign="top" class="mod-mgm edition image">'.$editionimage.'</td>';
        $table .= '<td valign="top" class="mod-mgm edition name">';
        $table .= '<a class="mod-mgm edition link" href="'.$CFG->wwwroot.'/mod/mgm/view.php?id='.$edition->id.'">'.
                  format_string($edition->name).'</a>';
        $table .= '</td>';
        $table .= '<td class="mod-mgm edition info">&nbsp;</td>';
        if (mgm_count_courses($edition) > 0) {
            foreach(mgm_get_edition_courses($edition) as $course) {
                $table .= '<tr>';
                $table .= '<td valign="top" class="mod-mgm edition image course">'.$courseimage.'</td>';
                $table .= '<td valign="top" class="mod-mgm edition name course">';
                $table .= '<a class="mod-mgm edition link course" href="'.$CFG->wwwroot.'/course/view.php?id='.
                          $course->id.'">'.format_string($course->fullname).'</a>';
                $table .= '</td>';
                $table .= '<td class="mod-mgm course info">&nbsp;</td>';
                $table .= '</tr>';
            }
        }
        $table .= '</tr>';
    }
    $table .= '</table>';

    echo $table;
}

function mgm_get_edition_courses($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return array();
    }

    $sql = "SELECT * FROM ".$CFG->prefix."course
    		WHERE id IN (
    			SELECT courseid FROM ".$CFG->prefix."edicion_course
    			WHERE edicionid=".$edition->id."
    	    )
    	    ORDER BY fullname;";

    if (!$courses = get_records_sql($sql)) {
        return array();
    }

    return $courses;
}

function mgm_get_edition_available_courses($edition) {
    global $CFG;
    require_once("$CFG->dirroot/enrol/enrol.class.php");

    $sql = "SELECT id, fullname, enrol FROM ".$CFG->prefix."course
			WHERE id NOT IN (
				SELECT courseid FROM ".$CFG->prefix."edicion_course
				WHERE edicionid = ".$edition->id."
			) AND id NOT IN (
				SELECT courseid FROM ".$CFG->prefix."edicion_course
			) AND id != '1'
			ORDER BY fullname";
    $courses = get_records_sql($sql);
    $ret = array();
    foreach ($courses as $course) {
        if(enrolment_factory::factory($course->enrol) instanceof enrolment_plugin_mgm) {
            $ret[] = $course;
        }
    }

    return $ret;
}

function mgm_add_course($edition, $courseid) {
    global $DB;

    if (!record_exists('edicion_course', 'edicionid', $edition->id, 'courseid', $courseid)) {
        $row = new stdClass();
        $row->edicionid = $edition->id;
        $row->courseid = $courseid;

        return insert_record('edicion_course', $row);
    }

    return false;
}

function mgm_remove_course($edition, $courseid) {
    if ($row = mgm_get_edition_course($edition->id, $courseid)) {
        delete_records('edicion_course', 'id', $row->id);

        return true;
    }

    return false;
}

function mgm_update_edition($edition) {
    $edition->timemodified = time();

    $result = update_record('edicion', $edition);

    if($result) {
        events_trigger('edition_updated', $edition);
    }

    return $result;
}

function mgm_create_edition($edition) {
    $edition->timecreated = time();

    $id = insert_record('edicion', $edition);

    if ($result) {
        events_trigger('edition_created', get_record('edicion', 'id', $result));
    }

    return $result;
}

function mgm_delete_edition($editionorid) {
    global $CFG;
    $result = true;

    if (is_object($editionorid)) {
        $editionid = $editionorid->id;
        $edition = $editionorid;
    } else {
        $editionid = $editionorid;
        if (!$edition = get_record('edition', 'id', $editionid)) {
            return false;
        }
    }

    if (!mgm_remove_edition_contents($editionid)) {
        $result = false;
    }

    if (!delete_records('edicion', 'id', $editionid)) {
        $result = false;
    }

    if ($result) {
        // trigger events
        events_trigger('edition_deleted', $edition);
    }
}

function mgm_remove_edition_contents($editionid) {
    if (!$editon = get_record('edicion', 'id', $editionid)) {
        error('Edition ID was incorrect (can\'t find it)');
    }

    delete_records('edicion_course', 'edicionid', $editionid);
    delete_records('edicion_criterios', 'edicion', $editionid);
    delete_records('edicion_inscripcion', 'edicionid', $editionid);
    delete_records('edicion_preinscripcion', 'edicionid', $editionid);

    return true;
}

function mgm_translate_especialidad($id) {
    global $CFG;

    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
    		WHERE type = ".MGM_ITE_ESPECIALIDADES."";
    $especialidades = explode("\n", get_record_sql($sql)->value);

    return ($id !== false && $id != '') ? $especialidades[$id] : '';
}

function mgm_get_edition_course_criteria($editionid, $courseid) {
    global $CFG;

    $criteria = new stdClass();
    $criteria->plazas = 0;
    $criteria->espec = array();

    //Datos extendidos
    $edata = mgm_get_edition_course($editionid,$courseid);
    $criteria->codmodalidad = $edata->codmodalidad;
    $criteria->codagrupacion = $edata->codagrupacion;
    $criteria->codprovincia = $edata->codprovincia;
    $criteria->codpais = $edata->codpais;
    $criteria->codmateria = $edata->codmateria;
    $criteria->codniveleducativo = $edata->codniveleducativo;
    $criteria->numhoras = $edata->numhoras;
    $criteria->numcreditos = $edata->numcreditos;
    $criteria->fechainicio = $edata->fechainicio;
    $criteria->fechafin = $edata->fechafin;
    $criteria->localidad = $edata->localidad;
    $criteria->fechainimodalidad = $edata->fechainimodalidad;
        
    $sql = 'SELECT * FROM '.$CFG->prefix.'edicion_criterios
    		WHERE edicion = \''.$editionid.'\' AND course = \''.$courseid.'\'';
    if (!$cdata = get_records_sql($sql)) {
        return $criteria;
    }

    foreach($cdata as $c) {
        if ($c->type == MGM_CRITERIA_PLAZAS) {
            $criteria->plazas = $c->value;
        }

        // OBSOLETE v1.0
        /*if ($c->type == MGM_CRITERIA_CC) {
            $criteria->ccaas = $c->value;
        }*/

        if ($c->type == MGM_CRITERIA_OPCION1) {
            $criteria->opcion1 = $c->value;
        }

        if ($c->type == MGM_CRITERIA_OPCION2) {
            $criteria->opcion2 = $c->value;
        }

        if ($c->type == MGM_CRITERIA_ESPECIALIDAD) {
            $criteria->espec[$c->value] = mgm_translate_especialidad($c->value);
        }

        /*if ($c->type == MGM_CRITERIA_MINGROUP) {
            $criteria->mingroup = $c->value;
        }

        if ($c->type == MGM_CRITERIA_MAXGROUP) {
            $criteria->maxgroup = $c->value;
        }*/

        if ($c->type == MGM_CRITERIA_DEPEND) {
            $criteria->depends = true;
            $criteria->dlist = $c->value;
        }

        if ($c->type == MGM_CRITERIA_NUMGROUPS) {
            $criteria->numgroups = $c->value;
        }
    }

    return $criteria;
}

function mgm_set_edition_course_criteria($data) {
    global $CFG;

    $criteria = new stdClass();
    $criteria->edicion = $data->edicionid;
    $criteria->course = $data->courseid;
    
    //Datos extendidos
    $edata = mgm_get_edition_course($data->edicionid,$data->courseid);
    $edata->codmodalidad = $data->codmodalidad;
    $edata->codagrupacion = $data->codagrupacion;
    $edata->codprovincia = $data->codprovincia;
    $edata->codpais = $data->codpais;
    $edata->codmateria = $data->codmateria;
    $edata->codniveleducativo = $data->codniveleducativo;
    $edata->numhoras = $data->numhoras;
    $edata->numcreditos = $data->numcreditos;
    $edata->fechainicio = $data->fechainicio;
    $edata->fechafin = $data->fechafin;
    $edata->localidad = $data->localidad;
    $edata->fechainimodalidad = $data->fechainimodalidad;
    
    update_record('edicion_course', $edata);
    
    // Plazas
    $criteria->type = MGM_CRITERIA_PLAZAS;
    $criteria->value = $data->plazas;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }

    // CC
    // OBSOLETE v1.0
    /*$criteria->type = MGM_CRITERIA_CC;
    $criteria->value = $data->ccaas;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }*/

    // Opcion1
    $criteria->type = MGM_CRITERIA_OPCION1;
    $criteria->value = $data->opcion1;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }

    // Opcion2
    $criteria->type = MGM_CRITERIA_OPCION2;
    $criteria->value = $data->opcion2;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }
/*
    // Mingroup
    $criteria->type = MGM_CRITERIA_MINGROUP;
    $criteria->value = $data->mingroup;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }

    // Maxgroup
    $criteria->type = MGM_CRITERIA_MAXGROUP;
    $criteria->value = $data->maxgroup;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }
*/
    // Numgroups
    $criteria->type = MGM_CRITERIA_NUMGROUPS;
    $criteria->value = $data->numgroups;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }

    // Dependencies
    if (isset($data->dpendsgroup)) {
        $criteria->type = MGM_CRITERIA_DEPEND;
        $criteria->value = $data->dpendsgroup['dlist'];
        if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
            insert_record('edicion_criterios', $criteria);
        } else {
            $criteria->id = $criteriaid->id;
            update_record('edicion_criterios', $criteria);
            unset($criteria->id);
        }
    }

    // Add especialidad
    if (isset($data->aespecs)) {
        foreach($data->aespecs as $k=>$v) {
            $criteria->type = MGM_CRITERIA_ESPECIALIDAD;
            $criteria->value = $v;
            if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
                insert_record('edicion_criterios', $criteria);
            } else {
                $criteria->id = $criteriaid->id;
                update_record('edicion_criterios', $criteria);
                unset($criteria->id);
            }
        }
    }

    // Remove especialidad
    if (isset($data->sespecs)) {
        foreach($data->sespecs as $k=>$v) {
            $criteria->type = MGM_CRITERIA_ESPECIALIDAD;
            $criteria->value = $v;
            if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
                continue;
            } else {
                delete_records('edicion_criterios', 'id', $criteriaid->id);
            }
        }
    }
}

function mgm_edition_course_criteria_data_exists($criteria) {
    global $CFG;

    if ($criteria->type !== MGM_CRITERIA_ESPECIALIDAD) {
        $sql = 'SELECT id FROM '.$CFG->prefix.'edicion_criterios
    			WHERE edicion = \''.$criteria->edicion.'\' AND course = \''.$criteria->course.'\'
    			AND type = \''.$criteria->type.'\'';
        if (!$value = get_record_sql($sql)) {
            return false;
        }
    } else {
        $sql = 'SELECT id FROM '.$CFG->prefix.'edicion_criterios
    		WHERE edicion = \''.$criteria->edicion.'\' AND course = \''.$criteria->course.'\'
    		AND type = \''.$criteria->type.'\' AND value = \''.$criteria->value.'\'';
        if (!$value = get_record_sql($sql)) {
            return false;
        }
    }

    return $value;
}

function mgm_get_edition_plazas($edition) {
    if (!is_object($edition)) {
        return '';
    }

    $plazas = 0;
    if (get_records('edicion_course', 'edicionid', $edition->id)) {
        foreach(get_records('edicion_course', 'edicionid', $edition->id) as $course) {
            if($criteria = mgm_get_edition_course_criteria($edition->id, $course->courseid)) {
                $plazas += $criteria->plazas;
            }
        }
    }

    return $plazas;
}

function mgm_get_course_especialidades($courseid, $editionid) {
    $criteria = mgm_get_edition_course_criteria($editionid, $courseid);

    return $criteria->espec;
}

function mgm_get_course_available_especialidades($courseid, $editionid) {
    global $CFG;

    $data = mgm_get_course_especialidades($courseid, $editionid);
    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
    		WHERE type = ".MGM_ITE_ESPECIALIDADES."";
    $especialidades = explode("\n", get_record_sql($sql)->value);

    $strdata = implode(',', $data);
    $filterespecialidades = array_filter($especialidades, create_function('$element',
        '$data = explode(",", "'.$strdata.'"); return (!in_array($element, $data));'));

    return $filterespecialidades;
}

/**
 * Returns the active edition if any, elsewhere returns false
 *
 * @return object bool
 */
function mgm_get_active_edition() {
    if (!$edition = get_record('edicion', 'active', 1)) {
        return false;
    }

    return $edition;
}

/**
 * Returns true if edition is the active one otherwise returns false
 * @param object $edition
 * @return bool
 */
function mgm_edition_is_active($edition) {
    return ($edition->active) ? true : false;
}

/**
 * Returns true if edition is certified otherwise returns false
 *
 * @param object $edition
 * @return bool
 */
function mgm_edition_is_certified($edition) {
    return ($edition->certified) ? true : false;
}

/**
 * Returns true if edition certification is on draft state otherwise returns false
 *
 * @param object $edition
 * @return boolean
 */
function mgm_edition_is_on_draft($edition) {
    return ($edition->certified == MGM_CERTIFICATE_DRAFT) ? true : false;
}

/**
* Returns true if edition certification is on validates state otherwise returns false
*
* @param object $edition
* @return boolean
*/
function mgm_edition_is_on_validate($edition) {
    return ($edition->certified == MGM_CERTIFICATE_VALIDATED) ? true : false;
}

/**
 * Sets the edition certification state as draft
 *
 * @param object $edition
 * @return boolean
 */
function mgm_set_edition_certification_on_draft($edition) {
    if(is_object($edition)) {
        if (!mgm_edition_is_certified($edition)) {
            $edition->certified = MGM_CERTIFICATE_DRAFT;
            return true;
        }
    }

    return false;
}

/**
 * Sets the edition certification state as validated
 *
 * @param object $edition
 * @return boolean
 */
function mgm_set_edition_certification_on_validate($edition) {
    if(is_object($edition)) {
        if (mgm_edition_is_on_draft($edition)) {
            $edition->certified = MGM_CERTIFICATE_VALIDATED;
            return true;
        }
    }

    return false;
}

/**
 * Return the course's edition (Edition will be active)
 *
 * @param string $id
 * @return null object The edition object
 */
function mgm_get_course_edition($id) {
    if (!$row = get_record('edicion_course', 'courseid', $id)) {
        return null;
    }

    if (!$edition = get_record('edicion', 'id', $row->edicionid)) {
        return null;
    }

    if (!$edition->active) {
        return null;
    }

    return $edition;
}

function mgm_get_edition_user_options($edition, $user) {
    global $CFG;
    $sql = "SELECT value FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid = '".$edition."' AND userid = '".$user."'";

    if (!$data = get_record_sql($sql)) {
        return false;
    } else {
        $data = $data->value;
    }
    $choices = array();
    $options = explode(',', $data);
    foreach ($options as $option) {
        $choices[] = $option;
    }

    return $choices;
}

function mgm_preinscribe_user_in_edition($edition, $user, $courses, $ret) {
    $rcourses = array();
    foreach ($courses as $course) {
        if ($course) {
            $rcourses[] = $course;
        }
    }

    if (!count($rcourses)) {
        delete_records('edicion_preinscripcion', 'edicionid', $edition, 'userid', $user);
        return;
    }

    $strcourses = implode(',', $rcourses);

    if (!$record = get_record('edicion_preinscripcion', 'edicionid', $edition, 'userid', $user)) {
        // New record
        $record = new stdClass();
        $record->edicionid = $edition;
        $record->userid = $user;
        $record->value = $strcourses;
        $record->timemodified = time();
        insert_record('edicion_preinscripcion', $record);
    } else {
        // Update record
        $record->value = $strcourses;
        $record->timemodified = time();
        update_record('edicion_preinscripcion', $record);
    }
}

/**
 * Inscribe an user into an edition
 * @param string $edition
 * @param string $user
 * @param string $course
 */
function mgm_inscribe_user_in_edition($edition, $user, $course, $released=false) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid='".$edition."' AND userid='".$user."'";
    if (!$record = get_record_sql($sql)) {
        // New record
        $record = new stdClass();
        $record->edicionid = $edition;
        $record->userid = $user;
        $record->value = $course;
        $record->released = $released;
        insert_record('edicion_inscripcion', $record);
    } else {
        // Update record
        $record->value = $course;
        $record->released = $released;
        $record->timemodified = time();
        update_record('edicion_inscripcion', $record);
    }
}

function mgm_enrol_edition_course($editionid, $courseid) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid='".$editionid."' AND value='".$courseid."'";
    if ($data = get_records_sql($sql)) {
        $course = get_record('course', 'id', $courseid);
        foreach($data as $row) {
            $user = get_record('user', 'id', $row->userid);
            if (!enrol_into_course($course, $user, 'mgm')) {
                print_error('couldnotassignrole');
            }
        }
    }
}

function mgm_create_enrolment_groups($editionid, $courseid) {
    global $CFG;

    if(!$inscripcion = mgm_check_already_enroled($editionid, $courseid)) {
        trigger_error('Error, there is no inscription for the edition and course ids given');
        return false;
    }

    if (!$criteria = mgm_get_edition_course_criteria($editionid, $courseid)) {
        trigger_error('Error, there is no criteria for the edition and course ids given');
        return false;
    }

    $max = round($criteria->plazas / $criteria->numgroups);

    // Split data in groups by CC
    $groups = array();
    $ncount = 0;
    $mcount = array();
    foreach ($inscripcion as $row) {
        if (!array_key_exists($ncount, $groups)) {
            $groups[$ncount] = array();
        }
        $user = get_record('user', 'id', $row->userid);
        if (!$user->ite_data = get_record('edicion_user', 'userid', $row->userid)) {
            if (count($groups[$ncount]) < $max) {
                $groups[$ncount][] = $user;
            } else {
                $ncount++;
                $groups[$ncount][] = $user;
            }
        } else {
            if (!$user->ite_data->cc) {
                if (count($groups[$ncount] < $max)) {
                    $groups[$ncount][] = $user;
                } else {
                    $ncount++;
                    $groups[$ncount][] = $user;
                }
            } else {
                if (array_key_exists($user->ite_data->cc, $mcount)) {
                    if (count($groups[$mcount[$user->ite_data->cc]]) < $max) {
                        $groups[$mcount[$user->ite_data->cc]][] = $user;
                    } else {
                        $mcount[$user->ite_data->cc]++;
                        $groups[$mcount[$user->ite_data->cc]][] = $user;
                    }
                } else {
                    $mcount[$user->ite_data->cc] = $user->ite_data->cc + rand(rand(1, 10000), rand(10000, 100000));
                    $groups[$mcount[$user->ite_data->cc]][] = $user;
                }
            }
        }
    }

    $finalgroups = array();
    for ($i = 0; $i < $criteria->numgroups; $i++) {
        $finalgroups[$i] = array();
        $x = 1;
        foreach ($groups as $group) {
            foreach($group as $gr) {
                echo "<br />if (($x <= ".($i+1) * $max.") && ($x > ".($i * $max)."))";
                if (($x <= ($i+1) * $max) && ($x > ($i * $max))) {
                    $finalgroups[$i][] = $gr;
                }
                $x++;
            }
        }
    }

    $x = 65;
    foreach ($finalgroups as $fg) {
        $group = new object();
        $group->courseid = $courseid;
        $group->name = 'Grupo '.chr($x);
        if (!$gid=groups_create_group($group)) {
            error('Error creating the '.$group->name.' group');
        }
        print_object($group);
        foreach ($fg as $user) {
            if (!groups_add_member($gid, $user->id)) {
                error('Error adding user '.$user->username.' to group '.$group->name);
            }
        }
        $x++;
    }

    return true;
}

function mgm_check_already_enroled($editionid, $courseid) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid='".$editionid."' AND value='".$courseid."'";
    return get_records_sql($sql);
}

function mgm_edition_get_solicitudes($edition, $course) {
    global $CFG;

    $ret = 0;
    $sql = "SELECT * FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid='".$edition->id."'";
    if ($records = get_records_sql($sql)) {
        foreach($records as $record) {
            $solicitudes = explode(",", $record->value);

            $ret += count(array_filter($solicitudes, create_function('$element',
                'return ($element == '.$course->id.');')));
        }
    }

    return $ret;
}

/**
 * Return true if the given course of given edition has criteria set
 * @param object $edition
 * @param object $course
 * @return boolean
 */
function mgm_exists_criteria_for_course($edition, $course) {
    // Local variables
    $ret = false;
    $criteria = mgm_get_edition_course_criteria($edition->id, $course->id);

    if (isset($criteria->opcion1)) {
        $ret = $criteria->opcion1.', '.$criteria->opcion2;
    }

    return $ret;
}

/**
 * Return the user preinscription data as array
 * @param object $line
 * @return array
 */
function mgm_get_user_preinscription_data($line, $edition, $data) {
    $site = get_site();
    $user = $data->user;
    $userdata = $data->userdata;
    $especs = ($data->especs) ? $data->especs : array();
    $userespecs = '<select name="especialidades" readonly="">';
    foreach ($especs as $espec) {
        $userespecs .= '<option name="'.$espec.'">'.mgm_translate_especialidad($espec).'</option>';
    }
    $userespecs .= '</select>';
    $courses = '<select name="courses" readonly="">';
    $values = explode(',', $line->value);
    $realcourses = array();
    for ($i = 0; $i < count($values); $i++) {
        if (mgm_check_already_enroled($edition->id, $values[$i])) {
            continue;
        }
        $realcourses[] = $values[$i];
    }

    foreach($values as $courseid) {
        $ncourse = get_record('course', 'id', $courseid);
        $courses .= '<option name="'.$courseid.'">'.$ncourse->fullname.'</option>';
    }
    $courses .= '</select>';
    $check = '<input type="checkbox" name="users['.$line->userid.']" />';
    $tmpdata = array(
        $check,
        '<a href="../../user/view.php?id='.$line->userid.'&amp;course='.$site->id.'">'.$user->firstname.'</a>',
        $user->lastname,
        date("d/m/Y H:i\"s", $line->timemodified),
        ($userdata) ? $userdata->cc : '',
        $userespecs,
        $courses
    );

    return $tmpdata;
}

/**
 * Reorder by course
 * @param array $file
 * @param object $course
 */
function mgm_order_by_course_preinscription_data(&$data, $course) {
    // Local variables
    $_data = array();

    if (!isset($data)) {
        return $_data;
    }

    foreach ($data as $line) {
        foreach ($line->realcourses as $k=>$v) {
            if ($course->id == $v) {
                if (!isset($_data[$k])) {
                    $_data[$k] = array();
                }

                $_data[$k][] = $line;
            }
        }
    }

    $data = $_data;
}

/**
 * Return not already enroled courses
 * @param string $editionid
 * @param string $value
 */
function mgm_get_user_preinscription_realcourses($editionid, $value) {
    $values = explode(',', $value);
    $realcourses = array();
    for ($i = 0; $i < count($values); $i++) {
        if (mgm_check_already_enroled($editionid, $values[$i])) {
            continue;
        }
        $realcourses[] = $values[$i];
    }

    return $realcourses;
}

function mgm_user_preinscription_tmpdata($userid) {
    if (!$user = get_record('user', 'id', $userid)) {
        return null;
    }

    $tmpuser = new stdClass();
    $tmpuser->user = $user;
    $tmpuser->userdata = get_record('edicion_user', 'userid', $user->id);
    $tmpuser->especs = ($tmpuser->userdata) ? explode("\n", $tmpuser->userdata->especialidades) : array();

    return $tmpuser;
}

function mgm_parse_preinscription_data($edition, $course, $data) {
    // Local variables
    $criteria = mgm_get_edition_course_criteria($edition->id, $course->id);
    $retdata = array();

    foreach ($data as $sqline) {
        $lineuser = mgm_user_preinscription_tmpdata($sqline->userid);
        $lineuser->realcourses = mgm_get_user_preinscription_realcourses($edition->id, $sqline->value);
        if (empty($lineuser->realcourses)) {
            $lineuser->realcourses[0] = '';
        }
        $lineuser->tmpdata = mgm_get_user_preinscription_data($sqline, $edition, $lineuser);
        $lineuser->sqline = $sqline;
        $lineuser->data = array(
        	'opcion1' => array(
                'especialidades' => array(
                    'found'	=> false,
                    'data' => array()
                ),
                'centros' => array(
                    'found'	=> false,
                    'data' => array(
                        'found' => array(),
                        'notfound' => array()
                    )
                )
            ),
            'opcion2' => array(
                'especialidades' => array(
                    'found'	=> false,
                    'data' => array()
                ),
                'centros' => array(
                    'found'	=> false,
                    'data' => array(
                        'found' => array(),
                        'notfound' => array()
                    )
                )
            )
        );

        // Store data by course at first option or not
        if ($course->id == $lineuser->realcourses[0]) {
            $retdata['first'][] = $lineuser;
        } else {
            $retdata['last'][] = $lineuser;
        }
    }

    /*
     * Now we have all the data splited into those ones who choosed the course as first option at
     * $first and everyone else at $last. We are going to proccess and order the data on every one
     * of those arrays before return it.
     */
    $rlastdata = array();
    if (isset($criteria->opcion1)) {    // This course is configured with criteria options
        mgm_order_preinscription_first_data($retdata['first'], $criteria, $edition, $course);
        mgm_order_by_course_preinscription_data($retdata['last'], $course);
        if (isset($retdata['last'])) {
            foreach ($retdata['last'] as $rdata) {
                mgm_order_preinscription_last_data($rdata, $edition, $criteria, $course);
                foreach ($rdata as $rd) {
                    $rlastdata[] = $rd;
                }
            }
        }
    } else {    // This course is not configured with criteria options
        foreach ($retdata as $key=>$value) {
            if ($key == 'first') {
                foreach ($value as $k=>$v) {
                    $retdata[$key][$k] = $v->tmpdata;
                }
            } else {
                foreach ($value as $k=>$v) {
                    $rlastdata[$k] = $v->tmpdata;
                }
            }
        }
    }

    $tmpdata = array();
    if (isset($retdata['first'])) {
        foreach ($retdata['first'] as $data) {
            $tmpdata[] = $data;
        }
    }

    foreach ($rlastdata as $data) {
        $tmpdata[] = $data;
    }

    return $tmpdata;
}

function mgm_order_preinscription_first_data_opcion($opcion, &$data, $criteria, $edition, $course) {
    if (!isset($data)) {
        return;
    }
    foreach ($data as $linedata) {
        if ($criteria->$opcion == 'especialidades') {
            // First option is especialidades
            $linedata->data[$opcion]['especialidades']['found'] = true;
            $kets = array_keys($criteria->espec);
            $found = false;
            // Each espec on course criteria
            foreach($kets as $key=>$value) {
                // Each espec on user criteria
                foreach ($linedata->especs as $k=>$v) {
                    // Match
                    if ($v !== '' && $value == $v) {
                        $linedata->data[$opcion]['especialidades']['data'][$key][$k] = $linedata;
                        $found = true;
                    }
                }
            }
            // If criteria does not match just store it in date order
            if (!$found) {
                $linedata->data[$opcion]['especialidades']['data']['notfound'][] = $linedata;
            }
        } else if($criteria->$opcion == 'centros') {
            // First option is centros
            $linedata->data[$opcion]['centros']['found'] = true;

            if (!empty($linedata->userdata) && mgm_is_cc_on_csv($linedata->userdata->cc)) {
                $linedata->data[$opcion]['centros']['data']['found'] = $linedata;
            } else {
                $linedata->data[$opcion]['centros']['data']['notfound'] = $linedata;
            }
        } else if ($criteria->$opcion == 'ninguna') {
            $linedata->data[$opcion]['ninguna']['found'] = true;
            $linedata->data[$opcion]['ninguna']['data'] = $linedata;
        }
    }
}

function mgm_abstract_results_opcion($opcion, $data) {
    // Local variables
    $tmptdata = array();

    if (!isset($data)) {
        return $tmptdata;
    }

    foreach ($data as $linedata) {
        // If especialidades is the first option
        if ($linedata->data[$opcion]['especialidades']['found']) {
            foreach ($linedata->data[$opcion]['especialidades']['data'] as $key=>$value) {
                foreach ($value as $k=>$v) {
                    $vdata = new stdClass();
                    $vdata->sqline = $v->sqline;
                    $vdata->data = $v->tmpdata;
                    if ($key !== 'notfound') {
                        $tmptdata['found'][$k][] = $vdata;
                    } else {
                        $tmptdata['notfound'][] = $v;
                    }
                }
            }
        } else if ($linedata->data[$opcion]['centros']['found']) {
            foreach ($linedata->data[$opcion]['centros']['data'] as $key=>$value) {
                if (empty($value)) continue;
                $vdata = new stdClass();
                $vdata->sqline = $value->sqline;
                $vdata->data = $value->tmpdata;
                if ($key !== 'notfound') {
                    $tmptdata[$key][0][] = $vdata;
                } else {
                    $tmptdata[$key][] = $value;
                }
            }
        } else if ($linedata->data[$opcion]['ninguna']['found']) {
            $vdata = new stdClass();
            $vdata->sqline = $linedata->data[$opcion]['ninguna']['data']->sqline;
            $vdata->data = $linedata->data[$opcion]['ninguna']['data']->tmpdata;
            $tmptdata['nocriteria'][0][] = $vdata;
        }
    }

    return $tmptdata;
}

function mgm_order_preinscription_first_data(&$data, $criteria, $edition, $course) {
    // Local variables
    $firstdata = $tmptdata = $founddata = $nfdata = $nffounddata = $nfnfdata = $ncdata = $finaldata = array();

    // First pass
    mgm_order_preinscription_first_data_opcion('opcion1', $data, $criteria, $edition, $course);

    // Abstract the opcion1 results
    $tmptdata = mgm_abstract_results_opcion('opcion1', $data);

    $founddata = (array_key_exists('found', $tmptdata)) ? $tmptdata['found'] : array();

    // Seccond pass (Only for not found)
    if (isset($tmptdata['notfound'])) {
        mgm_order_preinscription_first_data_opcion('opcion2', $tmptdata['notfound'], $criteria, $edition, $course);
        $nfdata = mgm_abstract_results_opcion('opcion2', $tmptdata['notfound']);
        if (array_key_exists('found', $nfdata)) {
            foreach ($nfdata['found'] as $nff) {
                $nffounddata[] = $nff;
            }

            // Order data by date
            foreach ($nffounddata as $nff) {
                usort($nff, 'mgm_order_by_date');
            }
        }

        if (array_key_exists('notfound', $nfdata)) {
            foreach ($nfdata['notfound'] as $nfnf) {
                $nfnfdata[] = $nfnf;
            }

            // Order data by date
            usort($nfnfdata, 'mgm_order_by_date');
        }

        if (array_key_exists('nocriteria', $nfdata)) {
            foreach ($nfdata['nocriteria'] as $ncd) {
                $ncdata[] = $ncd;
            }

            // Order data by date
            foreach ($ncdata as $ncd) {
                usort($ncd, 'mgm_order_by_date');
            }
        }
    }

    if (array_key_exists('nocriteria', $tmptdata)) {
        $ncdata = $tmptdata['nocriteria'];
    }

    // Order data by date
    foreach ($founddata as $found) {
        usort($found, 'mgm_order_by_date');
    }

    /**
     * Create tha final and valid array
     */
    foreach ($founddata as $found) {
        foreach ($found as $finalfound) {
            $finaldata[] = $finalfound->data;
        }
    }

    if (!empty($nffounddata)) {
        foreach ($nffounddata as $nffound) {
            foreach ($nffound as $nff) {
                $finaldata[] = $nff->data;
            }
        }
    }

    if (!empty($nfnfdata)) {
        foreach ($nfnfdata as $nfnf) {
            $finaldata[] = $nfnf->tmpdata;
        }
    }

    if (!empty($ncdata)) {
        foreach ($ncdata as $ncd) {
            foreach ($ncd as $nc) {
                $finaldata[] = $nc->data;
            }
        }
    }

    $data = $finaldata;
}

function mgm_order_preinscription_last_data(&$data, $edition, $criteria, $course) {
    // Local variables
    $firstdata = $tmptdata = $founddata = $nfdata = $nffounddata = $nfnfdata = $ncdata = $finaldata = array();

    // First pass
    mgm_order_preinscription_first_data_opcion('opcion1', $data, $criteria, $edition, $course);

    // Abstract the opcion1 results
    $tmptdata = mgm_abstract_results_opcion('opcion1', $data);

    $founddata = (array_key_exists('found', $tmptdata)) ? $tmptdata['found'] : array();

    // Seccond pass (Only for not found)
    if (isset($tmptdata['notfound'])) {
        mgm_order_preinscription_first_data_opcion('opcion2', $tmptdata['notfound'], $criteria, $edition, $course);
        $nfdata = mgm_abstract_results_opcion('opcion2', $tmptdata['notfound']);
        if (array_key_exists('found', $nfdata)) {
            foreach ($nfdata['found'] as $nff) {
                $nffounddata[] = $nff;
            }

            // Order data by date
            foreach ($nffounddata as $nff) {
                usort($nff, 'mgm_order_by_date');
            }
        }

        if (array_key_exists('notfound', $nfdata)) {
            foreach ($nfdata['notfound'] as $nfnf) {
                $nfnfdata[] = $nfnf;
            }

            // Order data by date
            usort($nfnfdata, 'mgm_order_by_date');
        }

        if (array_key_exists('nocriteria', $nfdata)) {
            foreach ($nfdata['nocriteria'] as $ncd) {
                $ncdata[] = $ncd;
            }

            // Order data by date
            foreach ($ncdata as $ncd) {
                usort($ncd, 'mgm_order_by_date');
            }
        }
    }

    if (array_key_exists('nocriteria', $tmptdata)) {
        $ncdata = $tmptdata['nocriteria'];
    }

    // Order data by date
    foreach ($founddata as $found) {
        usort($found, 'mgm_order_by_date');
    }

    /**
     * Create tha final and valid array
     */
    foreach ($founddata as $found) {
        foreach ($found as $finalfound) {
            $finaldata[] = $finalfound->data;
        }
    }

    if (!empty($nffounddata)) {
        foreach ($nffounddata as $nffound) {
            foreach ($nffound as $nff) {
                $finaldata[] = $nff->data;
            }
        }
    }

    if (!empty($nfnfdata)) {
        foreach ($nfnfdata as $nfnf) {
            $finaldata[] = $nfnf->tmpdata;
        }
    }

    if (!empty($ncdata)) {
        foreach ($ncdata as $ncd) {
            foreach ($ncd as $nc) {
                $finaldata[] = $nc->data;
            }
        }
    }

    $data = $finaldata;
}

function mgm_order_by_date($x, $y) {
    if ($x->sqline->timemodified == $y->sqline->timemodified) {
        return 0;
    }
    return ($x->sqline->timemodified < $y->sqline->timemodified) ? -1 : 1;
}

/**
 * Get edition inscription data and return it
 *
 * @param object $edition
 * @param object $course
 * @param boolean $docheck
 */
function mgm_get_edition_course_inscription_data($edition, $course, $docheck=true) {
    global $CFG;

    // Inscription data
    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid = '".$edition->id."' AND value='".$course->id."'
    		ORDER BY timemodified ASC";
    if (!$inscripcion = get_records_sql($sql)) {
        return;
    }

    $data = array();
    foreach ($inscripcion as $line) {
        $tmpdata = mgm_user_preinscription_tmpdata($line->userid);
        $data[] = array(
            '<a href="../../user/view.php?id='.$tmpdata->user->id.'&amp;course=1">'.$tmpdata->user->firstname.'</a>',
            $tmpdata->user->lastname.'<input type="hidden" name="users['.$tmpdata->user->id.']" value="1"></input>'
        );
    }

    return $data;
}

/**
 * Get edition preinscrition data and return it
 *
 * @param object $edition
 * @param object $course
 * @param boolean $docheck
 */
function mgm_get_edition_course_preinscripcion_data($edition, $course, $docheck=true) {
    global $CFG;

    // Preinscripcion date first
    $sql = "SELECT * FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid = '".$edition->id."' AND
    		userid NOT IN (select userid FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid = '".$edition->id."') ORDER BY timemodified ASC";
    if (!$preinscripcion = get_records_sql($sql)) {
        return;
    }

    $final = array();
    foreach ($preinscripcion as $data) {
        $courses = explode(",", $data->value);
        if (in_array($course->id, $courses)) {
            $final[] = $data;
        }
    }

    if (!count($final)) {
        return;
    }

    $data = mgm_parse_preinscription_data($edition, $course, $final);

    if ($docheck) {
        $criteria = mgm_get_edition_course_criteria($edition->id, $course->id);
        $asigned = 0;
        foreach ($data as $k=>$row) {
            $arr = explode('"', $row[0]);
            $userid = $arr[3];
            $check = '<input type="checkbox" name="'.$userid.'" checked="true"/>';

            if ($criteria->plazas > $asigned || $criteria->plazas == 0) {
                $data[$k][0] = $check;
                $asigned++;
            }
        }
    }

    $count = 1;
    foreach ($data as $k=>$row) {
      $r = $row;
      array_unshift($r,$count);
      $data[$k] = $r;
      $count++;
    }

    return $data;
}

/**
 * Return the user's extended data
 * @param string $userid
 * @return string
 */
function mgm_get_user_extend($userid) {
    if ($euser = get_record('edicion_user', 'userid', $userid)) {
        return $euser;
    }

    $euser = new stdClass();
    $euser->cc = '';
    $euser->dni = '';
    $euser->tipoid = '';
    $euser->codniveleducativo='';
    $euser->codcuerpodocente='';
    $euser->codpostal='';
    $euser->sexo='';
    return $euser;
}

function mgm_get_user_especialidades($userid) {
    if ($especialidades = get_record('edicion_user', 'userid', $userid)) {
        $especs = array();
        foreach (explode("\n", $especialidades->especialidades) as $espec) {
            $especs[$espec] = mgm_translate_especialidad($espec);
        }

        return $especs;
    }

    return array();
}

function mgm_get_user_available_especialidades($userid) {
    global $CFG;

    $data = mgm_get_user_especialidades($userid);
    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
    		WHERE type = ".MGM_ITE_ESPECIALIDADES."";
    $especialidades = explode("\n", get_record_sql($sql)->value);

    $strdata = implode(',', $data);
    $filterespecialidades = array_filter($especialidades, create_function('$element',
        '$data = explode(",", "'.$strdata.'"); return (!in_array($element, $data));'));

    return $filterespecialidades;
}

/**
 * Check if the user cc is a valid cc
 * @param string $code
 * @param string $ret
 * @return string
 */
function mgm_check_user_cc($code, &$ret) {
    if (!mgm_is_cc_on_csv($code)) {
        $ret = MGM_DATA_CC_ERROR;
        return '';
    }

    return $code;
}

/**
 * Check if the user dni is a valid dni
 * @param string $dni
 * @param string $ret
 * @return string
 */
function mgm_check_user_dni($userid, $dni, &$ret) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_user
    		WHERE dni='".mysql_escape_string($dni)."' AND userid!='".$userid."'";

    if ($odni = get_record_sql($sql)) {
        $ret = MGM_DATA_DNI_ERROR;
        return '';
    }

    if (!mgm_validate_cif($dni)) {
        $ret = MGM_DATA_DNI_INVALID;
        return '';
    }

    return $dni;
}

function mgm_set_userdata($userid, $data) {
    $ret = MGM_DATA_NO_ERROR;
    $newdata = $data;
    $newdata->cc = mgm_check_user_cc($data->cc, $ret);
    $newdata->dni = mgm_check_user_dni($userid, $data->dni, $ret);
    $newdata->userid = $userid;
    if (!record_exists('edicion_user', 'userid', $userid)) {
        if (isset($data->addsel)) {
             $newdata->especialidades = implode("\n", $data->aespecs);
        } else {
            $newdata->espcialidades = "";
        }
        insert_record('edicion_user', $newdata);
    } else {
        $olddata = get_record('edicion_user', 'userid', $userid);
        $newdata->id = $olddata->id;
        if (isset($data->addsel)) {
            $oldespec = explode("\n", $olddata->especialidades);
            $newespec = array_merge($oldespec, $data->aespecs);
            $newdata->especialidades = implode("\n", $newespec);
        } else if (isset($data->removesel)) {
            $oldespec = explode("\n", $olddata->especialidades);
            foreach ($data->sespecs as $k=>$v) {
                if (in_array($v, $oldespec)) {
                    $idx = array_search($v, $oldespec);
                    unset($oldespec[array_search($v, $oldespec)]);
                }
            }
            $newespec = implode("\n", $oldespec);
            $newdata->especialidades = $newespec;
        } else {
            $newdata->especialidades = $olddata->especialidades;
        }

        update_record('edicion_user', $newdata);
    }

    return $ret;
}

/**
 * Return if a given cc is on cc CSV file
 * @param string $cc
 * @return boolean
 */
function mgm_is_cc_on_csv($cc) {
    foreach (mgm_get_cc_data() as $ccdata) {
        if ($ccdata[5] == $cc) {
            return true;
        }
    }

    return false;
}

function mgm_get_cc_type($cc) {
    if (!$cc) {
        return -1;
    }

    foreach(mgm_get_cc_data() as $ccdata) {        
        if ($ccdata[5] == $cc) {
            return $ccdata[6];
        }
    }

    return -1;
}

/**
 * Checks if a gicen cc is valid or not
 * @param string $cc
 * @return boolean
 */
function mgm_is_cc_valid($cc) {
    foreach (mgm_get_cc_data() as $ccdata) {
        if ($ccdata[5] == $cc) {
            if ($ccdata[7] == MGM_PRIVATE_CENTER) {
                return false;
            }
        }
    }

    return true;
}

/**
 * Return an array with the cc data on CSV file
 * @return array
 */
function mgm_get_cc_data() {
    global $CFG;

    $csvdata = array();
    if (($gestor = fopen($CFG->mgm_centros_file, "r")) !== FALSE) {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            $csvdata[] = $datos;
        }
        fclose($gestor);
    }

    return $csvdata;
}

/**
 * Activate an edition
 * @param object $edition
 */
function mgm_active_edition($edition) {
    if ($aedition = mgm_get_active_edition()) {
        mgm_deactive_edition($aedition);
    }

    $edition->active = 1;
    update_record('edicion', $edition);
}

/**
 * Deactivate an edition
 * @param unknown_type $edition
 */
function mgm_deactive_edition($edition) {
    $edition->active = 0;
    update_record('edicion', $edition);
}

/**
 * Get preinscription uer timemodified data
 * @param string $edition
 * @param string $user
 */
function mgm_get_preinscription_timemodified($edition, $user) {
    global $CFG;

    $sql = "SELECT timemodified FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid = '".$edition."' AND userid = '".$user."'";

    if (!$record = get_record_sql($sql)) {
        return null;
    }

    return $record;
}

function mgm_is_borrador($edition, $course) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
            WHERE edicionid='".$edition->id."' AND value='".$course->id."' AND released='0'";
    if ($borrador = get_records_sql($sql)) {
        return true;
    }

    return false;
}

function mgm_is_inscription_active($id, $course) {

}

function mgm_rollback_borrador($editionid, $courseid) {
    global $CFG;

    $sql = "DELETE FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid='".$editionid."' AND value='".$courseid."'
    		AND released='0'";

    execute_sql($sql);
}

function mgm_edition_set_user_address($userid, $address) {
    if (!$euser = get_record('edicion_user', 'userid', $userid)) {
        error('User doesn\'t exists!');
    } else {
        if (!empty($addres)) {
            $euser->alt_address = true;
            $euser->address = $address;
        } else {
            $euser->alt_address = false;
            $euser->address = '';
        }
    }
}

function mgm_get_edition_out($edition) {
    global $CFG;

    $sql = "SELECT COUNT(id) AS count FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid='".$edition->id."' AND userid
    		IN ( SELECT userid FROM ".$CFG->prefix."edicion_preinscripcion
    			 WHERE edicionid='".$edition->id."' ) AND userid
    		NOT IN ( SELECT userid FROM ".$CFG->prefix."edicion_inscripcion
    				 WHERE edicionid='".$edition->id."' )";
    if (!$count = get_record_sql($sql)) {
        return 0;
    }

    return $count->count;
}

function mgm_get_user_inscription_by_edition($user, $edition) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid = '".$edition->id."' AND userid='".$user->id."'";
    if (!$inscripcion = get_record_sql($sql)) {
        return false;
    }

    return $inscripcion;
}

/**
 * Get the certification scala
 *
 * @return mixed
 */
function mgm_get_certification_scala() {
    global $CFG;

    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
    		WHERE type = ".MGM_ITE_SCALA."";
    if($scala = get_record_sql($sql)) {
        return $scala;
    } else {
        return false;
    }
}

/**
 * Get the certification roles
 *
 * @return mixed
 */
function mgm_get_certification_roles() {
    global $CFG;
    
    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
            WHERE type = ".MGM_ITE_ROLE."";
    if($role = get_record_sql($sql)) {
        $roles = array(
            'coordinador' => 0, 'tutor'=> 0, 'estudiante'=> 0
        );
        
        foreach (explode(",", $role->value) as $value) {
            $tmpvalue = explode(":", $value);
            $roles[$tmpvalue[0]] = $tmpvalue[1];                                       
        }
        
        return $roles;         
    } else {
        return false;
    }
}

/**
 * Sets the certification scala
 *
 * @param string $scala
 */
function mgm_set_certification_scala($scala) {
    if (!$nscala = mgm_get_certification_scala()) {
        $nscala = new stdClass();
        $nscala->type = MGM_ITE_SCALA;
        $nscala->name = 'Scala';
        $nscala->value = $scala;
        insert_record('edicion_ite', $nscala);
    } else {
        $nscala->value = $scala;
        update_record('edicion_ite', $nscala);
    }
}

/**
 * Sets the certification roles
 *
 * @param string $roles
 */
function mgm_set_certification_roles($roles) {
    $troles = "";
    $x = 1;
    foreach ($roles as $k=>$v) {
        if ($x < count($roles)) {
            $troles .= $k.":".$v.",";
        } else {
            $troles .= $k.":".$v;
        }
        $x++;
    }
    
    if (!$nroles = mgm_get_certification_roles()) {
        // New Record
        $nroles = new stdClass();
        $nroles->type = MGM_ITE_ROLE;
        $nroles->name = 'Roles';
        $nroles->value = $troles;
        insert_record('edicion_ite', $nroles);
    } else {
        // Update Record
        $nroles = get_record('edicion_ite', 'type', MGM_ITE_ROLE);
        $nroles->value = $troles;
        update_record('edicion_ite', $nroles);
    }
}

function mgm_get_courses($course) {
    global $CFG;

    $sql = "SELECT id, idnumber, fullname FROM ".$CFG->prefix."course
    		WHERE id !='".$course->id."' 
    		AND idnumber != '' GROUP BY idnumber";

    if (!$data = get_records_sql($sql)) {
        return array();
    }

    return $data;
}

/**
 * Returns true if the user has the required course dependencies
 *
 * @param object $edition
 * @param object $course
 * @param object $user
 * @return boolean
 */
function mgm_check_course_dependencies($edition, $course, $user) {    
    global $CFG;
    
    if(!$criteria = mgm_get_edition_course_criteria($edition->id, $course->id)) {                
        return true;
    }       

    if(!isset($criteria->depends) || !$criteria->depends) {
        return true;
    }
    
    if (mgm_is_course_certified($user->id, $criteria->dlist)) {
        return true;
    }

    /*if(!$ctask = mgm_get_certification_task($criteria->dlist)) {
        return false;
    }

    if (!$grade = mgm_get_grade($ctask, $user)) {
        return false;
    }

    return $grade->finalgrade == $grade->rawgrademax;*/
   
    return false;
}

/**
 * Returns the certifiation task for the given course
 *
 * @param string $course
 * @return object
 */
function mgm_get_certification_task($course) {
    global $CFG;

    $scala = mgm_get_certification_scala();

    $sql = "SELECT * FROM ".$CFG->prefix."grade_items
    		WHERE scaleid ='".$scala->value."'
    		AND courseid ='".$course."'";

    return get_record_sql($sql);
}

/**
 * Returns the grade for the given task and user
 *
 * @param object $task
 * @param object $user
 * @return object
 */
function mgm_get_grade($task, $user) {
    return get_record(
        'grade_grades', 'itemid', $task->id, 'userid', $user->id
    );
}

/**
 * Helper class for courses interface
 */
function mgm_get_check_index($criteria) {
    $x = 0;
    foreach($criteria->dependencias as $k => $v) {
        if ($criteria->dlist == $k) {
            return $x;
        }
        $x++;
    }

    return $x;
}

/**
 * Return the user certification history
 *
 * @param string $userid
 * @return object
 */
function mgm_get_cert_history($userid) {
    if (!$userid) {
        return false;
    }

    return get_records('edicion_cert_history', 'userid', $userid);
}

/**
 * Return true if user given by userid has certified the course given by courseid
 *
 * @param string $userid
 * @param string $courseid
 * @return boolean
 */ 
function mgm_is_course_certified($userid, $courseid) {
    if (!$userid || !$courseid) {
        return false;
    }

    if (!$cert = get_record('edicion_cert_history', 'userid', $userid,
    	'courseid', $courseid)) {    	    
        return false;
    } else {        
        return true;
    }
}

/**
 * Certificates a course
 *
 * @param string $userid 
 * @param string $courseid
 * @param object $edition
 * @param string $roleid
 * @return boolean
 */
function mgm_certificate_course($userid, $courseid, $edition, $roleid=0) {
    if (!$userid || !$courseid || !$edition || !$roleid) {
        return false;
    }
    
    if (!mgm_edition_is_on_validate($edition)) {
        return false;
    }

    $data = new stdClass();
    $data->userid = $userid;
    $data->courseid = $courseid;
    $data->edicionid = $edition->id;
    $data->roleid = $roleid;    
    
    return insert_record('edicion_cert_history', $data);
}

/**
 * Certficates an edition
 *
 * @param object $edition
 * @return boolean
 */
function mgm_certificate_edition($edition) {
    if (!$edition) {
        return false;
    }
    
    foreach (mgm_get_edition_courses($edition) as $course) {
        if (!$participants = mgm_get_course_participants($course)) {
            return false;
        }       
        
        $participants = mgm_check_double_role_in_course($participants);
        
        $course_participants = array();
        foreach($participants as $participant) {            
            if (!mgm_certificate_course($participant->userid, $course->id, $edition->id, $participant->roleid)) {
                return false;
            }            
        }
    }
    
    return true;
}

/**
 * Check if there exists dupe ids in a participants array.
 * If exists, just certificate the highest roleid
 *
 * @param array $participants
 * @return array
 */
function mgm_check_double_role_in_course($participants) {    
    $ardy = array();
    foreach ($participants as $k=>$v) {
        if (array_key_exists($v->userid, $ardy)) {
            // Double found
            $ardy[$v->userid] = mgm_get_highest_role($ardy[$v->userid], $v);           
        } else {
            $ardy[$v->userid] = $v;
        }
    }
    
    return $ardy;    
}

/**
 * Return the highest role
 *
 * @param object $rol1
 * @param object $rol2
 * @return object
 */
function mgm_get_highest_role($rol1, $rol2) {
    $roles = mgm_get_certification_roles();
    $role1_level = $role2_level = 0;
    
    switch ($rol1->roleid) {
        case $roles['coordinador']:
            $role1_level = 3;
            break;
        case $roles['tutor']:
            $role1_level = 2;
            break;
        case $roles['alumno']:
            $role1_level = 1;
            break;        
    }
    
    switch ($rol2->roleid) {
        case $roles['coordinador']:
            $role2_level = 3;
            break;
        case $roles['tutor']:
            $role2_level = 2;
            break;
        case $roles['alumno']:
            $role2_level = 1;
            break;        
    }
    
    if ($role1 > $role2) {
        return $role1;
    } else {
        return $role2;
    }
}

function mgm_get_pass_courses($editionid, $userid) {
    if (!$editionid || !$userid) {
        return false;
    }

    if (!$ctask = mgm_get_certification_task($course)) {
        
    }
}

function mgm_get_course_participants($course) {
    global $CFG;
    
    if(!$course) {
        return false;
    }
        
    if (!$context = get_context_instance(CONTEXT_COURSE, $course->id)) {
        error('No context found');
    }
    
    $sitecontext = get_context_instance(CONTEXT_SYSTEM);
    $frontpagectx = get_context_instance(CONTEXT_COURSE, SITEID);
    
    $adminroles = array();
    if ($roles = get_roles_used_in_context($context, true)) {
        $canviewroles    = get_roles_with_capability('moodle/course:view', CAP_ALLOW, $context);
        $doanythingroles = get_roles_with_capability('moodle/site:doanything', CAP_ALLOW, $sitecontext);

        if ($context->id == $frontpagectx->id) {
            //we want admins listed on frontpage too
            foreach ($doanythingroles as $dar) {
                $canviewroles[$dar->id] = $dar;
            }
            $doanythingroles = array();
        }

        foreach ($roles as $role) {
            if (!isset($canviewroles[$role->id])) {   // Avoid this role (eg course creator)
                $adminroles[] = $role->id;
                unset($roles[$role->id]);
                continue;
            }
            if (isset($doanythingroles[$role->id])) {   // Avoid this role (ie admin)
                $adminroles[] = $role->id;
                unset($roles[$role->id]);
                continue;
            }            
        }
    }
    
    $sql = "SELECT DISTINCT ctx.id, u.id as userid, u.username, r.roleid FROM ".$CFG->prefix."user u ".
            "LEFT OUTER JOIN ".$CFG->prefix."context ctx ".
            "ON (u.id=ctx.instanceid AND ctx.contextlevel=".CONTEXT_USER.") ".
            "JOIN ".$CFG->prefix."role_assignments r ".
            "ON u.id=r.userid ".
            "WHERE (r.contextid = ".$context->id.") ".
            "AND u.deleted = 0 ".            
            "AND u.username != 'guest' ".
            "AND r.roleid NOT IN (".implode(',', $adminroles).")";
        
    return get_records_sql($sql);    
}

/**
   * Funcion para validar un CIF NIF o NIE
   *
   * @param string $nif
   * @return string
   */
function mgm_validate_cif($cif) {
    $cif = strtoupper($cif);
    for ($i = 0; $i < 9; $i ++) {
      $num[$i] = substr($cif, $i, 1);
    }

    // Si no tiene un formato valido devuelve error
    if (!ereg('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)', $cif)) {
      return false;
    }

    // Comprobacion de NIFs estandar
    if (ereg('(^[0-9]{8}[A-Z]{1}$)', $cif)) {
      if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)) {
        return true;
      }
      else {
        return false;
      }
    }

    // Algoritmo para comprobacion de codigos tipo CIF
    $suma = $num[2] + $num[4] + $num[6];
    for ($i = 1; $i < 8; $i += 2) {
      $suma += substr((2 * $num[$i]),0,1) + substr((2 * $num[$i]),1,1);
    }

    $n = 10 - substr($suma, strlen($suma) - 1, 1);
    // Comprobacion de NIFs especiales (se calculan como CIFs)
    if (ereg('^[KLM]{1}', $cif)) {
      if ($num[8] == chr(64 + $n)) {
         return true;
      }
      else {
         return false;
      }
    }

    // Comprobacion de CIFs
    if (ereg('^[ABCDEFGHJNPQRSUVW]{1}', $cif)) {
      if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)) {
         return true;
      }
      else {
         return false;
      }
    }

    //comprobacion de NIEs
    //T
    if (ereg('^[T]{1}', $cif)) {
      if ($num[8] == ereg('^[T]{1}[A-Z0-9]{8}$', $cif)) {
         return true;
      }
      else {
         return false;
      }
    }

    //XYZ
    if (ereg('^[XYZ]{1}', $cif)) {
      if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1)) {
         return true;
      }
      else {
         return false;
      }
    }

    // Si todavia no se ha verificado devuelve error
    return false;
}

class Edicion {
  var $data;
  var $anoacademico = null;
  
  function Edicion( $data = null ) {
    if (!$data)
      $this->data = mgm_get_active_edition();
    else
      $this->data = $data;
  }
  
  function getFin() {
    return $this->data->fin;
  }
  
  function getAnoAcademico() {
    if (!$this->anoacademico) {
      $yday = date("z",$this->data->inicio);
      $year = date("Y",$this->data->inicio);
      //Inicio de año academico el 15 de septiembre
      //TODO: Parametrizar?
      if ($yday < date("z",mktime(0,0,0,9,15,2011))) {
        $year--;
      }
      $this->anoacademico = $year.$year+1;
    }
    return $this->anoacademico;
  }
  
  function getCursos() {
    $cursosdata = mgm_get_edition_courses($this->data);
    $cursos = array();
    $numactividad = 1;
    if ($cursosdata)
    foreach ($cursosdata as $cursodata) {
      $cursos[$cursodata->id] = new Curso( $cursodata, $this, $numactividad );
      $numactividad++;
    }
    return $cursos;
  }
}

class Curso {
  var $data;
  var $participantes = null;
  var $dparticipantes = False;
  var $edata = array();
  var $dbedata;
  var $edicion;
  var $incidencias = array();
  var $info;

  function cargarEdata($campo, $ncampo) {
    if (!$campo) {
      $this->info->campo = $ncampo;
      $this->incidencias[] = get_string('incidencia_curso', 'mgm', $this->info);
    }
    $this->edata[$ncampo] = $campo;
  }
  
  function Curso( $data, $edicion, $numactividad ) {
    $this->data = $data;
    $this->edicion = $edicion;
    $this->dbedata = mgm_get_edition_course($edicion->data->id, $data->id);
    
    $this->info->curso = $this->data->fullname;
    $this->info->edicion = $this->edicion->data->name;
    $this->info->cursoid = $this->data->id;
    $this->info->edicionid = $this->edicion->data->id;
    
    $this->edata['anoacademico'] = $this->edicion->getAnoAcademico();#Obligatorio
    
    #TODO: Parametrizar?
    $this->edata['codentidad'] = '28923065';#Obligatorio
    
    #TODO: Parametrizar?
    $this->edata['codentidadvisado'] = '28923016';#Obligatorio
    
    $this->edata['codtipoactividad'] = 'AP';#Obligatorio
    
    #Nos tiene que venir de vuelta
    $this->edata['numactividad'] = $numactividad;
    
    $this->cargarEdata($this->dbedata->codagrupacion, 'codagrupacion');#Obligatorio
    
    #Nos tiene que venir de vuelta
    $this->edata['codactividad'] = $this->edata['codentidad'].sprintf('%04d', $numactividad);#Obligatorio
    
    $this->cargarEdata($this->dbedata->codmodalidad, 'codmodalidad');#Obligatorio
    
    #TODO: Parametrizar?
    $this->edata['codentidadpadre'] = '28923016';#Obligatorio
    
    $this->cargarEdata($this->dbedata->codprovincia, 'codprovincia');#Obligatorio
    
    $this->cargarEdata($this->dbedata->codpais, 'codpais');#Obligatorio
        
    $this->cargarEdata($this->dbedata->codmateria, 'codmateria');#Obligatorio
    
    $this->cargarEdata($this->dbedata->codniveleducativo, 'codniveleducativo');#Obligatorio
        
    #No es necesario rellenarlo, sería necesario rellenar mediante interfaz
    $this->edata['codambito'] = null;
    
    #No es necesario rellenarlo, aunque es calculable
    $this->edata['numsolicitudes'] = null;
    
    #No es necesario rellenarlo, aunque es calculable
    $this->edata['numasistentes'] = null;

    #No es necesario rellenarlo, aunque es calculable
    $this->edata['numfinalizados'] = null;
    
    $this->cargarEdata($this->dbedata->numhoras, 'numhoras');#Obligatorio
    
    $this->cargarEdata($this->dbedata->numcreditos, 'numcreditos');#Obligatorio
    
    #No es necesario rellenarlo
    $this->edata['fechainicioprevista'] = null;

    #No es necesario rellenarlo
    $this->edata['fechafinprevista'] = null;

    $this->cargarEdata(date('d/m/y H:i:s',$this->dbedata->fechainicio), 'fechainicio');#Obligatorio
    
    $this->cargarEdata(date('d/m/y H:i:s',$this->dbedata->fechafin), 'fechafin');#Obligatorio
    
    #No es necesario rellenarlo
    $this->edata['generaacta'] = null;

    #No es necesario rellenarlo
    $this->edata['actagenerada'] = null;

    #No es necesario rellenarlo
    $this->edata['fechagenacta'] = null;

    #No es necesario rellenarlo
    $this->edata['generacertif'] = null;

    #No es necesario rellenarlo
    $this->edata['tema'] = null;

    $this->edata['titulo'] = mb_strtoupper($this->data->fullname, 'utf-8');#Obligatorio

    #No es necesario rellenarlo
    $this->edata['idsexenios'] = null;

    #No es necesario rellenarlo
    $this->edata['numregCCAA'] = null;

    #No es necesario rellenarlo
    $this->edata['textocertif'] = null;

    #No es necesario rellenarlo
    $this->edata['centroeducativo'] = null;

    $this->cargarEdata(mb_strtoupper($this->dbedata->localidad, 'utf-8'), 'localidad');#Obligatorio
    
    #No es necesario rellenarlo
    $this->edata['motivorechazo'] = null;

    #No es necesario rellenarlo
    $this->edata['fase'] = null;

    #No es necesario rellenarlo
    $this->edata['estado'] = null;

    #No es necesario rellenarlo
    $this->edata['idmodificacion'] = null;

    #No es necesario rellenarlo
    $this->edata['fechaactualizacion'] = null;

    #No es necesario rellenarlo
    $this->edata['codentidadmodif'] = null;

    $this->cargarEdata( date('d/m/y H:i:s',$this->dbedata->fechainimodalidad), 'fechainimodalidad');#Obligatorio
    
    #No es necesario rellenarlo
    $this->edata['convresol'] = null;

    #No es necesario rellenarlo
    $this->edata['fechaconvresol'] = null;

    #No es necesario rellenarlo
    $this->edata['codentidadintr'] = null;

    #No es necesario rellenarlo
    $this->edata['idcambioanoacad'] = null;

    #No es necesario rellenarlo
    $this->edata['idpi'] = null;
  }
  
  function getNombre() {
    return $this->data->name;
  } 
  
  function getTutores() {}
  function getCoordinadores() {}
  function getParticipantes() {
    if ( $this->dparticipantes )
      return $this->participantes;
    $this->participantes = array();
    $userlist = mgm_get_course_participants($this->data);
    if ($userlist)
    foreach ($userlist as $userdata) {
      $this->participantes[$userdata->userid] = new Usuario( $userdata, $this );
    }
    $this->dparticipantes = True;
    return $this->participantes;
  }
  
  function getTareas( $usuario ) {}
}

class Usuario {
  var $data;
  var $dbdata;
  var $edata = array();
  var $edatap = array();
  var $curso;
  var $incidencias = array();
  var $info;
  
  function Usuario( $data, $curso ) {
    $this->data = $data;
    $this->curso = $curso;
    $this->info->nombre = $data->username;
    $this->dbdata = get_record('edicion_user', 'userid', $data->userid);
    
    //Datos para participantes.csv
    $this->edata['anoacademico'] = $this->curso->edicion->getAnoAcademico();#Obligatorio
    $this->edata['anoacademico'] = "20102011";
    $this->edata['codactividad'] = $this->curso->edata['codactividad'];#Obligatorio, proviene de la actividad/curso. Nos vendrá de vuelta
    if (!$this->dbdata) {
      $this->edata['tipoid'] = null;#Obligatorio, "N" o "P" o "T"
      $this->edata['DNI'] = null;#Obligatorio
      $this->incidencias[] = get_string('incidencia_usuario', 'mgm', $this->info);
    }
    else {
      if (!$this->dbdata->dni || strlen($this->dbdata->dni) != 9)
        $this->incidencias[] = get_string('incidencia_dni', 'mgm', $this->info);
      $this->edata['tipoid'] = $this->dbdata->tipoid;
      $this->edata['DNI'] = strtoupper($this->dbdata->dni);
    }
    $this->edata['creditos'] = $this->curso->edata['numcreditos'];#Obligatorio, proviene de la actividad/curso
    $this->edata['fechaemision'] = null;
    $this->edata['fechaultduplicado'] = null;
    $this->edata['numduplicados'] = null;
    $this->edata['remitido'] = null;
    $this->edata['codmotivo'] = null;
    $this->edata['numregistro'] = null;
    $this->edata['numregistroCCAA'] = null;
    $this->edata['generacertif'] = null;
    $this->edata['codtipoparticipante'] = $this->getTipo();#Obligatorio
    $this->edata['codmodalidad'] = $this->curso->edata['codmodalidad'];#Obligatorio, proviene de la actividad/curso
    $this->edata['fechainicio'] = $this->curso->edata['fechainicio'];#Obligatorio, proviene de la actividad/curso
    $this->edata['codtipoactividad'] = 'AP';#Obligatorio
    $this->edata['idayuda'] = null;
    $this->edata['organismo'] = null;
    $this->edata['impayuda'] = null;
    $this->edata['codagrupacion'] = $this->curso->edata['codagrupacion'];#Obligatorio, proviene de la actividad/curso
    $this->edata['numhoras'] = $this->curso->edata['numhoras'];#Obligatorio, proviene de la actividad/curso
    
    //Datos para profesores.csv
    $this->edatap['tipoid'] = $this->edata['tipoid'];#Obligatorio
    $this->edatap['DNI'] = $this->edata['DNI'];#Obligatorio
    if (!$this->dbdata) {
      $this->edatap['codniveleducativo'] = null;#Obligatorio
      $this->edatap['codcuerpodocente'] = null;#Obligatorio
    }
    else {
      $this->edatap['codniveleducativo'] = $this->dbdata->codniveleducativo;#Obligatorio
      $this->edatap['codcuerpodocente'] = $this->dbdata->codcuerpodocente;#Obligatorio
    }
    if (!$this->dbdata) {
      $this->edatap['codprovincia'] = null;#Obligatorio
      $this->edatap['codpostal'] = null;#Obligatorio
      $this->edatap['codcentro'] = null;#Obligatorio
    }
    else {
      $this->edatap['codprovincia'] = $this->dbdata->codprovincia;#Obligatorio
      $this->edatap['codpostal'] = $this->dbdata->codpostal;#Obligatorio
      $this->edatap['codcentro'] = $this->dbdata->cc;#Obligatorio
    }
    $userdata = get_record('user', 'id', $data->userid);
    $apellidos = explode(' ', $userdata->lastname, 2);
    if (count($apellidos) > 0)
      $this->edatap['apellido1'] = mb_strtoupper($apellidos[0], 'utf-8');#Obligatorio
    else
      $this->edatap['apellido1'] = null;
    if (count($apellidos) > 1)
      $this->edatap['apellido2'] = mb_strtoupper($apellidos[1], 'utf-8'); #Obligatorio
    else
      $this->edatap['apellido2'] = null;
    $this->edatap['nombre'] = mb_strtoupper($userdata->firstname, 'utf-8');#Obligatorio
    $this->edatap['anosexperiencia'] = null;
    $this->edatap['situacionadmin'] = null;
    $this->edatap['domicilio'] = mb_strtoupper($userdata->address, 'utf-8');#Obligatorio
    $this->edatap['telefono'] = $userdata->phone1;#Obligatorio
    $this->edatap['localidad'] = mb_strtoupper($userdata->city, 'utf-8');#Obligatorio
    if (!$this->dbdata) {
      $this->edatap['codpais'] = null;
      $this->edatap['sexo'] = null;#Obligatorio
    }
    else {
      $this->edatap['codpais'] = $this->dbdata->codpais;
      $this->edatap['sexo'] = $this->dbdata->sexo;#Obligatorio
    }
  }
  
  function getNombre() {
    return $this->data->username;
  }
  
  function getTipo() {
    $roles = mgm_get_certification_roles();
    if ($roles) {
      if ($this->data->roleid == $roles['alumno'])
        return 'A';
      elseif ($this->data->roleid == $roles['tutor'])
        return 'T';
      elseif ($this->data->roleid == $roles['coordinador'])
        return 'C';
    }
    return null;
  }
}

class Tarea {
  function getNombre() {}  
  function completada() {}
}

class EmisionDatos {
  var $edicion;
  var $uexcluidos;
  
  function EmisionDatos( $edicion = null ) {
    if ($edicion)
      $this->edicion = $edicion;
    else
      $this->edicion = new Edicion();
  }
  
  function Validar( $fechaactual=null ) {
    if (!$fechaactual)
      $fechaactual = mktime();
    
    $cursos = $this->edicion->getCursos();
    
    $tareas_sin_f = array();
    if ($cursos)
    foreach ($cursos as $curso) {
      $usuarios = array_merge($curso->getTutores(), $curso->getCoordinadores());
      if ($usuarios)
      foreach ($usuarios as $usuario) {
        $tareas = $curso->getTareas($usuario);
        if ($tareas)
        foreach ($tareas as $tarea) {
          if (!$tarea->completada()) {
            $tarea_sin_f = new stdClass();
            $tarea_sin_f->curso = $curso->getNombre();
            $tarea_sin_f->usuario = $usuario->getNombre();
            $tarea_sin_f->tarea = $tarea->getNombre();
            $tareas_sin_f[] = $tarea_sin_f;
          }
        }
      }
    }
    $ret = new stdClass();
    $ret->ok = False;
    $ret->incidencias = array();
    if ($this->edicion->getFin() < $fechaactual) {
      if ($tareas_sin_f) {
        foreach ($tareas_sin_f as $tarea) {
          $ret->incidencias[] = get_string('user_no_task_ended','mgm',$tarea);
        }
      }
      else {
        $ret->ok = True;
      }
    }
    else {
      $ret->incidencias[] = get_string('edition_not_ended','mgm');
    }
    return $ret;
  }
  
  function Excluir( $usuarios ) {
    $this->uexcluidos = $usuarios;
  }

  function aFichero( $directorio ) {
    $ret = new stdClass();
    $ret->ok = True;
    $ret->incidencias = array();
    $cursos = $this->edicion->getCursos();
    $fparticipantes = fopen( "/tmp/participantes.csv", "w" );
    $factividades = fopen( "/tmp/actividades.csv", "w" );
    $fprofesores = fopen( "/tmp/profesores.csv", "w" );
    $cabecera_participantes = False;
    $cabecera_actividades = False;
    $cabecera_profesores = False;
    $profesores = array();
    if ($cursos)
    foreach ($cursos as $curso) {
      if (!$cabecera_participantes) {
        fwrite($factividades, implode(',', array_keys($curso->edata))."\n");
        $cabecera_actividades = True;
      }
      if ($curso->incidencias)
        $ret->incidencias = array_merge( $ret->incidencias, $curso->incidencias );
      fwrite($factividades, implode(',', $curso->edata)."\n");
      $participantes = $curso->getParticipantes();
      foreach ($participantes as $participante) {
        if (!$cabecera_participantes) {
          fwrite($fparticipantes, implode(',', array_keys($participante->edata))."\n");
          $cabecera_participantes = True;
        }
        if ($participante->dbdata)
          $profesores[$participante->edata['DNI']] = $participante;
        if ($participante->incidencias)
          $ret->incidencias = array_merge( $ret->incidencias, $participante->incidencias );
        fwrite($fparticipantes, implode(',', $participante->edata)."\n");
      }
    }
    if ($profesores)
    foreach ($profesores as $profesor) {
      if (!$cabecera_profesores) {
        fwrite($fprofesores, implode(',', array_keys($profesor->edatap))."\n");
        $cabecera_profesores = True;
      }
      fwrite($fprofesores, implode(',', $profesor->edatap)."\n");
    }
    fclose($factividades);
    fclose($fparticipantes);
    fclose($fprofesores);
    $ret->filename = tempnam($directorio,"export").".zip";
    zip_files(array("/tmp/participantes.csv","/tmp/actividades.csv","/tmp/profesores.csv"), $ret->filename);
    $newname = md5_file($ret->filename);
    rename($ret->filename, $directorio."/".$newname);
    $ret->filename = $newname;
    @unlink("/tmp/participantes.csv");
    @unlink("/tmp/actividades.csv");
    @unlink("/tmp/profesores.csv");
    return $ret;
  }
  
}