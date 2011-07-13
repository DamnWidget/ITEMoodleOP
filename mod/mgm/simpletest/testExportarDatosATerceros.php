<?php

/**
* Test Exportar datos a terceros
*
* @package    mod
* @subpackage mgm
* @copyright  2011 Pedro Peña Pérez <pedro.pena@open-phoenix.com>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

Mock::generate('Edicion');
Mock::generate('Curso');
Mock::generate('Usuario');
Mock::generate('Tarea');
  
class testExportarDatosATerceros extends UnitTestCase 
{
    public function setUp()
    {
      $this->edicion = new MockEdicion();
      $this->edicion->setReturnValue('getFin', mktime(0,0,0,9,15,2010));
      $this->curso1 = new MockCurso();
      $this->curso2 = new MockCurso();
      $this->curso3 = new MockCurso();
      $this->edicion->setReturnValue('getCursos', array($this->curso1, $this->curso2, $this->curso3));
      $this->tutor1 = new MockUsuario();
      $this->tutor1->setReturnValue('getNombre', 'M');
      $this->tutor2 = new MockUsuario();
      $this->tutor2->setReturnValue('getNombre', 'N');
      $this->tutor3 = new MockUsuario();
      $this->tutor3->setReturnValue('getNombre', 'O');
      $this->coordinador1 = new MockUsuario();
      $this->coordinador1->setReturnValue('getNombre', 'P');
      $this->coordinador2 = new MockUsuario();
      $this->coordinador2->setReturnValue('getNombre', 'Q');
      $this->coordinador3 = new MockUsuario();
      $this->coordinador3->setReturnValue('getNombre', 'R');
      $this->curso1->setReturnValue('getTutores', array($this->tutor1));
      $this->curso1->setReturnValue('getCoordinadores', array());
      $this->curso1->setReturnValue('getNombre', 'A');
      $this->curso2->setReturnValue('getTutores', array($this->tutor1,$this->tutor2));
      $this->curso2->setReturnValue('getCoordinadores', array($this->coordinador1));
      $this->curso2->setReturnValue('getNombre', 'B');
      $this->curso3->setReturnValue('getTutores', array($this->tutor3));
      $this->curso3->setReturnValue('getCoordinadores', array($this->coordinador2,$this->coordinador3));
      $this->curso3->setReturnValue('getNombre', 'C');
      $this->tarea1 = new MockTarea();
      $this->tarea2 = new MockTarea();
      $this->tarea3 = new MockTarea();
      $this->curso1->setReturnValue('getTareas', array($this->tarea1, $this->tarea2), array($this->tutor1));
      $this->curso2->setReturnValue('getTareas', array($this->tarea1, $this->tarea3), array($this->tutor2));
      $this->curso2->setReturnValue('getTareas', array($this->tarea1, $this->tarea3), array($this->coordinador1));
      $this->curso3->setReturnValue('getTareas', array($this->tarea1, $this->tarea3), array($this->coordinador2));
      $this->tarea1->setReturnValue('completada', True);
      $this->tarea3->setReturnValue('completada', True);
      $this->tarea2->setReturnValue('getNombre', 'Certificar superación del curso');
      
      $this->edicion->expectOnce('getFin', array());
      $this->edicion->expectOnce('getCursos', array());
      $this->curso1->expectAtLeastOnce('getTutores', array());
      $this->curso1->expectAtLeastOnce('getTareas', array($this->tutor1));
      $this->tarea1->expectAtLeastOnce('completada', array());
      $this->tarea2->expectAtLeastOnce('completada', array());
    }
    
    public function testEmisionDatosValidarEdicionNoFinalizada()
    {
        $this->edicion->setReturnValue('getFin', mktime(0,0,0,6,10,2011));
        $emision = new EmisionDatos( $this->edicion );

        $resultado = $emision->Validar( mktime(0,0,0,9,15,2010) );

        $this->assertFalse($resultado->ok);
        $this->assertEqual($resultado->incidencias, array(get_string('edition_not_ended','mgm')));
    }
    
    public function testEmisionDatosValidarTutoresYCoordinadoresConTareasCursoFinalizadas()
    {
        $this->tarea2->setReturnValue('completada', True);
        $emision = new EmisionDatos( $this->edicion );
        
        $resultado = $emision->Validar();
  
        $this->assertTrue($resultado->ok);
        $this->assertEqual($resultado->incidencias, array());
    }
    
    public function testEmisionDatosValidarTutoresYCoordinadoresConTareasCursoNoFinalizadas()
    {
      $this->tarea2->setReturnValue('completada', False);
      $emision = new EmisionDatos( $this->edicion );
    
      $resultado = $emision->Validar();
  
      $this->assertFalse($resultado->ok);
      $tarea_sin_f = new stdClass();
      $tarea_sin_f->curso = 'A';
      $tarea_sin_f->usuario = 'M';
      $tarea_sin_f->tarea = 'Certificar superación del curso';
      $this->assertEqual($resultado->incidencias, array(get_string('user_no_task_ended','mgm', $tarea_sin_f)));
    }
}

class testExportarDatosATercerosIntegracion extends UnitTestCase
{
  public function setUp() {
    $this->oldedition_active = mgm_get_active_edition();
    $this->edition = new stdClass();
    $this->edition->name = "Edición de prueba";
    $this->edition->inicio = mktime(0,0,0,9,15,2010);
    $this->edition->fin = mktime(0,0,0,2,15,2011);
    $id = insert_record('edicion', $this->edition, true);
    $this->edition = get_record('edicion', 'id', $id);
  }
  
  public function tearDown() {
    delete_records('edicion', 'id', $this->edition->id);
    mgm_active_edition($this->oldedition_active);
  }
  
  public function testEdicionCursosIntegracion()
  {
    $edicion = new Edicion();
    $emision = new EmisionDatos( $edicion );
    $resultado = $emision->aFichero('/tmp');
  }
}
?>