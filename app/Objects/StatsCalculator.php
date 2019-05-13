<?php

//class to deal with various mathematical calculations on an array of grades
class StatsCalculator{

    //an array containing grades
    private $gradeArr;

    //constructor
    public function __construct($_gradeArr){
        $this->gradeArr = $_gradeArr;
    }

    //setter for grades array
    public function setGradeArr($_gradeArr){
        $this->gradeArr=$_gradeArr;
    }

    public function mean(){        

        return round((array_sum($this->gradeArr)) / count($this->gradeArr),2);


    }


    //calculates the standard deviartion
    public function standardDeviation(){
        $num_of_elements = count($this->gradeArr);

        $variance = 0.0;

        // calculating mean using array_sum() method
        $average = array_sum($this->gradeArr)/$num_of_elements;

        foreach($this->gradeArr as $i){
            // sum of squares of differences between
                        // all numbers and means.
            $variance += pow(($i - $average), 2);
        }

        return round((float)sqrt($variance/$num_of_elements),2);

    }

    //calculate the median
    public function median(){
        sort($this->gradeArr);
        $count = count($this->gradeArr); //total numbers in array
        $midNumber = floor(($count-1)/2); // find the middle value, or the lowest middle value
        if($count % 2) { // odd number, middle is the median
            $median = $this->gradeArr[$midNumber];
        } else { // even number, calculate avg of 2 medians
            $low = $this->gradeArr[$midNumber];
            $high = $this->gradeArr[$midNumber+1];
            $median = (($low+$high)/2);
        }
        return round($median,2);

    }

    //calculate the mode
    public function mode(){
        $values = array();
        foreach ($this->gradeArr as $v) {
          if (isset($values[$v])) {
            $values[$v] ++;
          } else {
            $values[$v] = 1;  // counter of appearance
          }
        }
        arsort($values);  // sort the array by values, in non-ascending order.
        $modes = array();
        $x = $values[key($values)]; // get the most appeared counter
        reset($values);
        foreach ($values as $key => $v) {
          if ($v == $x) {   // if there are multiple 'most'
            $modes[] = $key;  // push to the modes array
          } else {
            break;
          }
        }
        return $modes;

    }




}

?>