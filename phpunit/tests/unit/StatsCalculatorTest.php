<?php

include '../app/Objects/StatsCalculator.php';

class StatsCalculatorTest extends \PHPUnit\Framework\TestCase{

    protected $statsCalc;

    public function setUp(){
        $this->statsCalc = new StatsCalculator(array(1,2,3,8,2));
        
    }

    public function testMean(){
        $expected=3.2;
        $actual=$this->statsCalc->mean();
        $this->assertEquals($expected, $actual);

    }

    public function testStandardDeviation(){
        $expected=2.48;
        $actual=$this->statsCalc->standardDeviation();
        $this->assertEquals($expected, $actual);
    }

    public function testMedian(){
        $expected=2;
        $actual=$this->statsCalc->median();
        $this->assertEquals($expected, $actual);
    }

    public function testMode(){
        $expected=(array(2));
        $actual=$this->statsCalc->mode();
        $this->assertEquals($expected, $actual);
    }





}



?>