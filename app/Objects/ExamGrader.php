<?php


//class to deal with scores and pass rates of exams
class ExamGrader{

    private $minPassRate=0.60; //60% 

    //array of points for each student
    //total number of possible points on exam
    //total number of students who took exam
    /* in future, change so that we can use arr.size instead of passing the number of respondents */
    public function getClassPassRate($studentPoints, $numOfExamPoints, $numOfRespondents){

        $passed=0;

        for ($i=0;$i<count($studentPoints); $i++){
            
            $percentage=$this->getStudentExamScore($studentPoints[$i],$numOfExamPoints);

            if($this->isSatisfactory($percentage)){
                $passed++;
            }


        }

        return  round($passed/$numOfRespondents, 2); //round to nearest hundredth

       

        

    }

    //points earned by a single student
    //total number of potential exam points
    public function getStudentExamScore($studentPoints, $numOfExamPoints){

        return round($studentPoints/$numOfExamPoints, 2); //round to nearest hundredth

    }

    //takes an exam percentage (ex: .72) and determines if satisfactory (>.60)
    public function isSatisfactory($grade){
        
        if($grade>=$this->minPassRate){
            return true;
        }
        return false;
    }

}

?>