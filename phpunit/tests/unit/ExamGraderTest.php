
<?php
//require_once __DIR__ . '/../../../app/Objects/ExamGrader.php';

include '../app/Objects/ExamGrader.php';


class ExamGraderTest extends \PHPUnit\Framework\TestCase{

    protected $examGrader;

    public function setUp(){

        //$this->examGrader = new \app\Objects\ExamGrader;
        $this->examGrader = new ExamGrader;
    }

    public function testGetClassPassRate(){

        $expected=.5;
        

        $actual=$this->examGrader->getClassPassRate(array(10,30,20,40,31,22),40,6);

        $this->assertEquals($expected, $actual);


        
    }

    public function testGetStudentExamScore(){
        $expected = .65;

        $actual=$this->examGrader->getStudentExamScore(26,40);

        $this->assertEquals($expected,$actual);
    }

    public function testUnsatisfactoryGrade(){

        $expected=false;
        $actual=($this->examGrader->isSatisfactory(.59));

        $this->assertEquals($expected,$actual);


    }

    public function testSatisfactoryGrade(){
        $expected=true;
        $actual=($this->examGrader->isSatisfactory(.999));

        $this->assertEquals($expected,$actual);
        
    }


}



?>