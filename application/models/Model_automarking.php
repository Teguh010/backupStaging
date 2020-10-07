<?php

/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

/**
 * Created by PhpStorm.
 * User: WeiMiNG
 * Date: 6/4/2017
 * Time: 1:42 PM
 */
ini_set('xdebug.var_display_max_depth', '10');
ini_set('xdebug.var_display_max_children', '256');
ini_set('xdebug.var_display_max_data', '1024');

class Model_subjectiveanswer
{
    //1 Primary English 
    //2 Primary Maths 
    //3 Primary Science
    //4 Secondary English
    //5 Secondary Maths
    //6 Secondary Science

    public $subject = 0;
    public $answerText = "";
    public $answerType = 1; // 1 is non-subjective
    public $hasNumbers = FALSE;
    public $hasUnits = FALSE;
    public $hasWords = FALSE;
    public $questionType = 1;
    public $answerId = "";

    public function configure($answerText = "", $answerType = 1, $subject = 1, $questionType = 1, $answerId = 1)
    {
        $this->answerText = $answerText;
        $this->answerType = $answerType;
        $this->subject = $subject;
        $this->questionType = $questionType;
        $this->answerId = $answerId;

        switch ($answerType) {

            case 2: // numbers
                $this->hasNumbers = TRUE;
                $this->hasUnits = $this->hasWords = FALSE;
                break;
            case 3: // numbers with units
                $this->hasNumbers = $this->hasUnits = TRUE;
                $this->hasWords = FALSE;
                break;
            case 4: // words
                $this->hasWords = TRUE;
                $this->hasNumbers = $this->hasUnits = FALSE;
                break;
            case 5: // numbers with words
                $this->hasNumbers = $this->hasWords = TRUE;
                $this->hasUnits = FALSE;
                break;
            case 6: // drawing
            case 7: // long sentences
            case 8: // algebra
            default:
                $this->hasNumbers = $this->hasUnits = $this->hasWords = FALSE;
                return;
        }
    }
}

class Model_automarking extends CI_Model
{
    protected $NUMBER_TYPES;
    protected $EXCLUDED_SPECIAL_CHARS;

    public function __construct()
    {
        parent::__construct();

        $this->UNIT_TYPES = array(
            array("unit", "units"),
            // Length, Distances
            array("m", "metre", "metres"),
            array("cm", "centimetre", "centimetres"),
            array("km", "kilometre", "kilometres"),
            array("mm", "millimetre", "millimetres"),
            // Area, Areas
            array("m2"),
            array("cm2", "cm^2"),
            array("square"),
            // Volume
            array("l", "ℓ", "litre", "litres"),
            array("ml", "mℓ", "millilitre", "millilitres"),
            array("m3", "m^3"),
            array("cm3", "cm^3"),
            array("cube", "cubic"),
            // Mass
            array("kg", "kilogram", "kilograms"),
            array("g", "gram", "grams"),
            array("mg", "milligram", "milligrams"),
            // Time
            array("s", "second", "seconds"),
            array("min", "mins", "minute", "minutes"),
            array("h", "hr", "hrs", "hour", "hours"),
            array("m/s", "metre per sec", "metres per sec", "metre per second", "metres per second"),
            array("km/h", "kilometre per hour", "kilometres per hour"),
            array("cm/s", "centimetre per sec", "centimetres per sec"),
            array("m/min", "metre per minute", "metres per minute", "metre per min", "metres per min"),
            // Angle
            array("°", "degree", "degrees"),
            // Currency
            array("$", "dollar", "dollars"),
            array("₵", "cent", "cents")
        );

        $this->EXCLUDED_SPECIAL_CHARS = array(" ", ",", "\\", PHP_EOL, "{", "}", ":", "and"); //, "/");
    }

    public function Mark($correct_answer, $user_answer, $fullMark)
    {

        $correctAnswer = $correct_answer->answerText;
        $userAnswer = $user_answer->answerText;

        if ($correctAnswer == $userAnswer || $correct_answer->answerId == $userAnswer) {
            return $fullMark;
        }

        //Question type == 4, MCQ, user answer wrongly
        if ((empty($userAnswer)  && $userAnswer !== '0') || $correct_answer->questionType == 4) {
            return 0.0;
        }

        $score = $fullMark;

        //for algebra marking algorithm
        if ($correct_answer->answerType == 8) {
            $correct_algebra = $this->analyze_algebra_pattern($correct_answer->answerText);
            $user_algebra = $this->analyze_algebra_pattern($user_answer->answerText);
          
            if ($this->is_algebra_identical($correct_algebra, $user_algebra)) {
                return $fullMark;
            } else {
                return 0.0;
            }
        }

        //Ignore answerType == 6, 7
        if ($correct_answer->answerType == 6 || $correct_answer->answerType == 7) {
            return 0.0;
        }

        $correctAnswerTextSub = $this->process_and_split_answer_string($correctAnswer, $correct_answer->subject);
        $userAnswerTextSub = $this->process_and_split_answer_string($userAnswer, $user_answer->subject);

        $correctAnswerNumerals = $this->get_numerals($correctAnswerTextSub);
        $userAnswerNumerals = $this->get_numerals($userAnswerTextSub);
        /**
         * REMOVE MATH SYMBOL, E.G "frac"
         */
        $correctAnswerTextSub = $this->filter_number_array($correctAnswerTextSub);
        $userAnswerTextSub = $this->filter_number_array($userAnswerTextSub);

        $correctAnswerLiterals = $this->get_words_not_units($correctAnswerTextSub);
        $userAnswerLiterals = $this->get_words_not_units($userAnswerTextSub);

        $correctAnswerUnits = $this->get_units($correctAnswerTextSub);
        $userAnswerUnits = $this->get_units($userAnswerTextSub);

        $numeralArrayDifference = array_diff($correctAnswerNumerals, $userAnswerNumerals);
        $literalArrayDifference = array_diff($correctAnswerLiterals, $userAnswerLiterals);
        $unitArrayDifference = array_diff($correctAnswerUnits, $userAnswerUnits);

        /** 
         * IF PRIMARY MATH, DEDUCE ANSWER TYPE
         */
        if ($correct_answer->subject == 2) {
            $this->deduce_answer_type($correct_answer, $correct_answer->subject);
        }

        switch ($correct_answer->answerType) {
            case 2: // NUMBER ONLY
                if ($this->is_number_same($correctAnswerNumerals, $userAnswerNumerals)) {
                } else {
                    $score = 0.0;
                }
                //IGNORE OTHER STRING TEXT
                break;

            case 3: // NUMBER WITH UNIT
                if ($this->is_number_same($correctAnswerNumerals, $userAnswerNumerals)) {
                } else {
                    $score = 0.0;
                }
                //DEDUCT 0.5 IF IN CORRECT UNIT
                if (count($unitArrayDifference) != 0) {
                    $score = max(0.0, $score - 0.5);
                }
                break;
            case 4: // WORDS ONLY
                if ($correctAnswerLiterals == $userAnswerLiterals) {
                } else if ($correct_answer->subject == 1) { //ENGLISH SUBJECT
                    $score = 0.0;
                } else if ( //ALLOW TEXT SWAP, E.g: "AB", "BA"
                    count($correctAnswerLiterals) == 1 &&
                    count($userAnswerLiterals) == 1 &&
                    $this->isWordSwap(strtolower($correctAnswerLiterals[0]), strtolower($userAnswerLiterals[0])) === true
                ) {
                } else { //PARTIALLY CORRECT ONLY
                    $deductScore = ((float)count(array_intersect($literalArrayDifference, $correctAnswerLiterals))) / ((float)sizeof($correctAnswerLiterals));
                    $deductScore = $deductScore > 0.5 ? 1 : 0.5;
                    $score = $fullMark * (1.0 - $deductScore);
                }
                break;
            case 5: // NUMBER WITH WORDS
                if ($this->is_number_same($correctAnswerNumerals, $userAnswerNumerals)) {
                } else {
                    $score = 0.0;
                }
                //IGNORE WORDS 
                /*
                if(sizeof($literalArrayDifference) != 0) {
                    $score = max(0, $score - 0.5);
                }
                */
                break;
            default:
                $score = 0.0;
                break;
        }

        return $score;
    }
    private function is_number_same($ans1, $ans2)
    {
        $allowable_decimal = 0.0001;
        if (count($ans1) != count($ans2)) return false;
        for ($i = 0; $i < count($ans1); $i++) {
            if (abs(floatval($ans1[$i]) - floatval($ans2[$i])) > $allowable_decimal) {
                return false;
            }
        }
        return true;
    }
    private function isWordSwap($correctAns, $userAns)
    {
        if (strlen($correctAns) > 3) return false;

        for ($i = 0; $i < strlen($correctAns); $i++) {
            if (stripos($userAns, $correctAns[$i]) === false) {
                return false;
            }
        }

        return true;
    }


    public function remove_beginning_bracket($answerText)
    {
        /**
         * REMOVE CORRECT STARTING AND END BRACKET (,)
         * **** */
        if (substr($answerText, 0, 2) == "\(") {
            $answerText = substr($answerText, 2);
        }

        if (substr($answerText, strlen($answerText) - 2, 2) == "\)") {
            $answerText = substr($answerText, 0, strlen($answerText) - 2);
        }

        return $answerText;
    }

    private function process_and_split_answer_string($answerText, $subjectType)
    {
        $answerText = $this->remove_beginning_bracket($answerText);
        /***
         * REMOVE ANY SPACE
         */
        if ($subjectType == 1) {
            $answerText = preg_replace("/\s+/", " ", $answerText);
            $answerText = preg_replace("/\\\\/", "", $answerText);
        } else {
            $answerText = preg_replace('/\\\\\s+/', "", $answerText);
            $answerText = preg_replace("/\s+/", "", $answerText);
            $answerText = preg_replace("/\\\\/", "", $answerText);
            $answerText = strtolower($answerText);
        }
        /**
         * CHANGE FRACTION TO "/", 
         * REMOVE REDUNDANT CURLY BRACKET "{,}"
         */

        if ($subjectType != 1) {
            $answerText = preg_replace("/\}\{/", "/", $answerText);
            $answerText = preg_replace("/}/", "", $answerText);
            $answerText = preg_replace("/{/", "", $answerText);
        }

        $answerText = preg_replace('/\(|\)|\\\\\(|\\\\\)/', "", $answerText);

        /**
         * CHECK RATIO SYMBOL, REMOVE EXTRA SPACE
         */
        if (strpos($answerText, ':') !== false) {
            $answerText = str_replace(" :", ":", $answerText);
            $answerText = str_replace(": ", ":", $answerText);
        }


        /**
         * STANDARDIZE " °" SYMBOL
         */
        if (strpos($answerText, "^{\circ}") !== false) {
            $answerText = str_replace("^{\circ}", " °", $answerText);
        } else if (strpos($answerText, "^\circ") !== false) {
            $answerText = str_replace("^\circ", " °", $answerText);
        }

        /**
         * REMOVE EXTRA SPACE BETWEEN TWO DIGITS
         */
        $answerText = preg_replace('/ +(?=\d)/', '', $answerText, -1, $count); // combine multiple spaces
        $answerTextSub = preg_replace('/(?<=\d) +(?=\d)/', '', $answerText, -1, $count);

        /**
         * REMOVE REMAININF SPACE
         */
        $answerTextSub = trim($answerTextSub);


        /**
         * SPLIT ANSWER TEXT BY SI UNIT
         */
        $answerTextSub = $this->split_SI_Unit($this->UNIT_TYPES, $answerTextSub);

        foreach ($answerTextSub as $key => $subString) {
            if (!$this->is_SI_unit($subString)) {
                $answerTextSub[$key] = preg_replace('/(?<=[a-z])(?=\d)|(?<=\d)(?=[a-z])/i', ',', $subString);
            }
        }
        $answerTextSub = implode(",", $answerTextSub);

        /**
         * EXPLODE ANSWER TEXT BY SEPARATOR
         */
        $answerTextSub = $this->multiexplode($this->EXCLUDED_SPECIAL_CHARS, $answerTextSub);

        /**
         * SPLIT DOLLAR SIGN
         */
        $answerTextSub = $this->split_dollarsign_prefix($answerTextSub);


        foreach ($answerTextSub as $key => $answerTextItem) {
            if (trim($answerTextItem) == "(" || trim($answerTextItem) == ")") {
                unset($answerTextSub[$key]);
            } else {
                $answerTextItem = trim($answerTextItem, "(");
                $answerTextItem = trim($answerTextItem, ")");
                $answerTextSub[$key] = $answerTextItem;
            }
        }



        return $answerTextSub;
    }

    public function is_SI_unit($string)
    {
        $foundUnits = FALSE;
        foreach ($this->UNIT_TYPES as $unitindex => $unitarray) {
            if (in_array($string, $unitarray)) {
                $foundUnits = TRUE;
                break;
            }
        }
        return $foundUnits;
    }

    public function split_SI_Unit($UNIT, $string)
    {
        $output = array();

        //possible chracter
        foreach ($UNIT as $unitindex => $unitarray) {
            foreach ($unitarray as $unit2index => $singleUnit) {
                $strLen = strlen($string);
                $unitLen = strlen($singleUnit);
                $pos = strpos($string, $singleUnit);

                if (
                    $strLen > $unitLen &&
                    $pos != false &&
                    !preg_match('/[a-z|A-Z]/', substr($string, $pos - 1, 1)) &&
                    (substr($string, $pos + $unitLen, 1) == false ||
                        preg_match('/[\\s|0-9]/', substr($string, $pos + $unitLen, 1)))
                ) {
                    //unit match
                    //split string & unit
                    $string = trim(preg_replace("/" . $singleUnit . "/", "", $string, 1));
                    $output[] = $unitarray[0];
                }
            }
        }
        //no SI UNIT
        $output[] = $string;
        return $output;
    }



    public function deduce_answer_type($model_answer_object, $subjectType)
    {
        if (is_null($model_answer_object->answerText) || empty($model_answer_object->answerText)) {
            return;
        }

        // check if ratio type first, if there's ratio symbol and no words, then deduce as number type
        if (strpos($model_answer_object->answerText, ':') !== false) {
            $tempAnswerText = $model_answer_object->answerText;
            $tempAnswerText = str_replace(":", ",", $tempAnswerText);
            $tempAnswerTextSub = $this->process_and_split_answer_string($tempAnswerText, $subjectType);
            $tempHasWords = false;
            foreach ($tempAnswerTextSub as $tempAnswer) {
                if (is_numeric($tempAnswer) == false) {
                    $tempHasWords = true;
                }
            }
            if ($tempHasWords == false) {
                $model_answer_object->answerType = 2;
                return;
            }
        }

        $answerTextSub = $this->process_and_split_answer_string($model_answer_object->answerText, $subjectType);

        $answerNumerals = $this->get_numerals($answerTextSub);
        $answerUnits = $this->get_units($answerTextSub);
        $answerWords = $this->get_words_not_units($answerTextSub);

        $model_answer_object->hasNumbers = sizeof($answerNumerals) > 0 ? TRUE : FALSE;
        $model_answer_object->hasUnits = sizeof($answerUnits) > 0 ? TRUE : FALSE;
        $model_answer_object->hasWords = sizeof($answerWords) > 0 ? TRUE : FALSE;

        if ($model_answer_object->hasNumbers == TRUE) {
            if ($model_answer_object->hasUnits == TRUE) {
                $model_answer_object->answerType = 3; // numbers with units
            } else {
                if ($model_answer_object->hasWords == TRUE) {
                    $model_answer_object->answerType = 5; // numbers with words
                } else {
                    $model_answer_object->answerType = 2; // numbers only
                }
            }
        } else {
            if ($model_answer_object->hasWords == TRUE) {
                $model_answer_object->answerType = 4; // words only
            } else {
                $model_answer_object->answerType = 1;
            }
        }

        return;
    }

    private function multiexplode($delimiters, $string)
    {
        $strtemp = str_replace($delimiters, $delimiters[0], $string);
        return explode($delimiters[0], $strtemp);
    }

    private function split_dollarsign_prefix($stringArray)
    {
        foreach ($stringArray as $key => $string) {
            $index = strpos($string, '$');
            if ($index === 0) { // only accept case where it is $xxxx. if dollar sign is not in front then take it as not true
                array_splice($stringArray, $key, 0, '$');
                $stringArray[$key + 1] = ltrim($string, '$');
            }
        }
        return $stringArray;
    }

    private function filter_number_array($stringArray)
    {
        $stringText  = array();

        foreach ($stringArray as $index => $str) {
            if ($dividerPos = strpos($str, '/') !== false) {
                $num = explode("/", $str);
                if (isset($num[1]) && !is_numeric($num[1])) {
                    if (is_numeric($num[0])) {
                        $stringText[] = substr($str, $dividerPos);
                    }
                    continue;
                } else if (isset($num[1]) && $num[1] != "0") {
                } else {
                }
            } else if (isset($str) && is_numeric($str)) {
            } else if (isset($str) && !is_numeric($str) && strpos($str, "frac") !== false) {
                continue;
            } else {
                $stringText[] = $str;
            }
        }

        return $stringText;
    }

    private function get_numerals($stringArray)
    {
        $output = array();
        $index = -1;
        $hasFraction = false;
        foreach ($stringArray as $str) {
            if (strpos($str, '/') !== false) {
                $num = explode("/", $str);
                if (isset($num[1]) && !is_numeric($num[1])) {
                    if (is_numeric($num[0])) {
                        $output[] = $num[0];
                        $index++;
                    }
                    continue;
                } else if (isset($num[1]) && $num[1] != "0") {
                    $output[] = floatval($num[0]) / floatval($num[1]);
                    $index++;
                } else {
                    $output[] = floatval($num[0]);
                    $index++;
                }
            } else if (isset($str) && is_numeric($str)) {
                $output[] = strpos($str, '.') === false ? intval($str) : floatval($str);
                $index++;
            } else if (isset($str) && !is_numeric($str) && strpos($str, "frac") !== false) {
                $hasFraction = true;
                continue;
            }

            if ($hasFraction) {
                if ($index > 0) {
                    if ($output[($index - 1)] > 0) {
                        $output[$index - 1] = $output[$index - 1] + $output[$index];
                    } else {
                        $output[$index - 1] = $output[$index - 1] - $output[$index];
                    }
                    array_pop($output);
                    $index--;
                }
                $hasFraction = false;
            }
        }

        return $output;
    }


    private function get_units($stringArray)
    {
        $output = array();
        foreach ($stringArray as $str) {
            foreach ($this->UNIT_TYPES as $unitindex => $unitarray) {
                if (in_array($str, $unitarray)) {
                    $output[] = $unitindex;
                    break;
                }
            }
        }
        return $output;
    }

    private function get_words_not_units($stringArray)
    {
        $output = array();
        foreach ($stringArray as $str) {
            if (!is_numeric($str)) {
                $foundUnits = FALSE;
                foreach ($this->UNIT_TYPES as $unitindex => $unitarray) {
                    if (in_array($str, $unitarray)) {
                        $foundUnits = TRUE;
                        break;
                    }
                }

                if (!$foundUnits) {
                    $output[] = $str;
                }
            }
        }
        return $output;
    }

    public function is_algebra_identical($ans1, $ans2)
    {

        for ($i = 0; $i < count($ans1); $i++) {
            $isSame = false;
            for ($j = 0; $j < count($ans2); $j++) {
                if (
                    $ans1[$i] instanceof Algebra_object &&
                    $ans2[$j] instanceof Algebra_object
                ) {
                    if ($this->is_algebraSame($ans1[$i], $ans2[$j], $ans1, $ans2, $i, $j)) {
                        $isSame = true;
                        break;
                    }
                } else if (
                    is_array($ans1[$i]) &&
                    is_array($ans2[$j])
                ) {
                    if ($this->is_algebra_identical($ans1[$i], $ans2[$j])) {
                        $isSame = true;
                        break;
                    }
                }
            }

            if (!$isSame) {
                return $isSame;
            }
        }

        return true;
    }

    public function is_algebraSame($ans1, $ans2, $overallAns1, $overallAns2, $firstIndex, $secondIndex)
    {
        if ($ans1->number != $ans2->number) {
            return false;
        }
        if (!$this->is_algebra_identical($ans1->power, $ans2->power)) {
            return false;
        }
        if (is_array($ans1) && is_array($ans2) && count($ans1) != count($ans2)) {
            return false;
        }

        if ($ans1->plusMinus != $ans2->plusMinus) {
            return false;
        }
        if (
            $ans1->plusMinus == $ans2->plusMinus &&
            $ans1->plusMinus == 2 &&
            !$this->is_algebra_identical($overallAns1[$firstIndex - 1], $overallAns2[$secondIndex - 1])
        ) {
            return false;
        }
        if (count($ans1->variable) != count($ans2->variable)) return false;

        for ($i = 0; $i < count($ans1->variable); $i++) {
            $isSame = false;
            for ($j = 0; $j < count($ans2->variable); $j++) {
                if (
                    $ans1->variable[$i] instanceof Algebra_object &&
                    $ans2->variable[$j] instanceof Algebra_object
                ) {
                    if ($this->is_algebraSame($ans1->variable[$i], $ans2->variable[$j], $ans1->variable, $ans2->variable, $i, $j)) {
                        $isSame = true;
                        break;
                    }
                } else if (is_string($ans1->variable[$i]) && is_string($ans2->variable[$j])) {
                    if ($ans1->variable[$i] == $ans2->variable[$j]) {
                        $isSame = true;
                        break;
                    }
                } else if (
                    is_array($ans1->variable[$i]) &&
                    is_array($ans2->variable[$j])

                ) {
                    $isSame = $this->is_algebra_identical($ans1->variable[$i], $ans2->variable[$j]);
                    if ($isSame) {
                        break;
                    }
                }
            }

            if (!$isSame) {
                return $isSame;
            }
        }
        return true;
    }


    public function analyze_algebra_pattern($answerText)
    {


        $answerText = $this->remove_beginning_bracket($answerText);

        $answerText = strtolower($answerText);
        /**
         * REMOVE SPACING
         */
        $answerText = str_replace("\\ ", "", $answerText);
        $answerText = str_replace(" ", "", $answerText);
        /**
         * REPLACE FRACTION SYMBOL
         */
        $answerText = preg_replace("/}{/", ")/(", $answerText);
        $answerText = preg_replace("/\\\\frac{/", "(", $answerText);
        $answerText = preg_replace("/}/", ")", $answerText);
        $answerText = preg_replace("/{/", "(", $answerText);
        /**
         * REPLACE BRACKET
         */
        $answerText = preg_replace("/\\\\left\(/", "(", $answerText);
        $answerText = preg_replace("/\\\\right\)/", ")", $answerText);
        /**
         * REMOVE DOT, DOT = MULTIPLY
         */
        $answerText = preg_replace("/\\\\cdot/", "", $answerText);

        /**
         * SPLIT BY SEPARATOR
         */
        $answerText = $this->multiexplode([",", "=", "and"], $answerText);

        $algebraFinalAnswer = array();

        for ($i = 0; $i < count($answerText); $i++) {
            $bracketHierarchy = array();
            /**
             * FIND ALL BRACKET POSITION
             */
            if ($this->check_has_bracket($answerText[$i])) {
                preg_match_all('/\(/', $answerText[$i], $match1, PREG_OFFSET_CAPTURE);
                preg_match_all('/\)/', $answerText[$i], $match2, PREG_OFFSET_CAPTURE);

                $bracketLeft = $this->getBracketPosition($match1);
                $bracketRight = $this->getBracketPosition($match2);

                $bracketMapping = $this->mapping_bracket($answerText[$i], $bracketLeft, $bracketRight);
                usort($bracketMapping, function ($a, $b) {
                    return $a[0] - $b[0];
                });

                $bracketHierarchy = $this->convertHierarchy($bracketMapping, $bracketHierarchy);
            }



            $algebraAnswer = $this->deciperAlgebra(array(), $answerText[$i], $bracketHierarchy);
            $algebraAnswer = $this->tidyUpAlgebra($algebraAnswer, true);
            $algebraAnswer = $this->tidyNumber($algebraAnswer);
            $algebraFinalAnswer[] = $algebraAnswer;
        }

        return $algebraFinalAnswer;
    }

    public function tidyNumber($algebraAnswer)
    {
        $i = 0;
        $number = array();
        $hasMultiply = array();
        $power = array();
        while ($i < count($algebraAnswer)) {
            if ($algebraAnswer[$i] instanceof Algebra_object) {
                $algebraAnswer[$i] = $this->calculatePullOutNumber($algebraAnswer[$i]);
                $number[$i] = $algebraAnswer[$i]->number;
                $plusMinus[$i] = $algebraAnswer[$i]->plusMinus;
                $power[$i] = $algebraAnswer[$i]->power;
            }
            $i++;
        }

        $newAlgebra = $algebraAnswer;

        $hasPower = false;
        for ($j = 0; $j < count($number); $j++) {
            if (count($power[$j]) > 0) {
                $hasPower = true;
                break;
            }
        }

        if ($algebraAnswer instanceof Algebra_object && !$hasPower) {
            $canPull = true;
            $minNumber = 1;
            $finalNumber = 1;
            for ($j = 0; $j < count($number); $j++) {
                if ($number[$j] == 0) break;

                if ($plusMinus[$j] == 3) { // has divider

                } else if ($plusMinus[$j] != 3 && $number[$j]  == 1) {
                    $canPull = false;
                    break;
                } else if ($number[$j] > 1 && $minNumber == 1) {
                    $minNumber = $number[$j];
                } else if ($number[$j] < $minNumber) {
                    if ($minNumber % $number[$j] != 0) {
                        $canPull = false;
                        break;
                    }
                    $minNumber = $number[$j];
                } else {
                    if ($number[$j] % $minNumber != 0) {
                        $canPull = false;
                        break;
                    }
                }
            }
            if ($canPull && count($number) > 1 && $minNumber > 1) {
                $finalNumber = $finalNumber * $minNumber;
                for ($j = 0; $j < count($number); $j++) {
                    if ($plusMinus[$j] != 3) {
                        $algebraAnswer[$j]->number = $algebraAnswer[$j]->number / $finalNumber;
                    }
                }
                $newal = new Algebra_object();
                $newal->number = $finalNumber;
                $newal->variable = $algebraAnswer;
                $newAlgebra = [$newal];
            }
        }


        return $newAlgebra;
    }

    public function calculatePullOutNumber($algebra)
    {
        $number = 1;
        $i = 0;
        $number = array();
        $plusMinus = array();
        $power = array();
        while ($algebra instanceof Algebra_object && $i < count($algebra->variable)) {
            $variable = $algebra->variable[$i];

            //3 possibility, 1 is algebra object, 2nd array, 3rd string
            if ($variable instanceof Algebra_object) {
                $algebra->variable[$i] = $this->calculatePullOutNumber($variable);
                //if it is variable
                $number[$i] = $algebra->variable[$i]->number;
                $plusMinus[$i] = $algebra->variable[$i]->plusMinus;
                $power[$i] = $algebra->variable[$i]->power;
                $i++;
            } else if (is_array($variable)) { //multiply variables
                $number[$i] = 1;
                $plusMinus[$i] = 2;
                $power[$i] = array();
                $i++;
            } else { // string,  do nothing
                break;
            }
        }

        //check number
        $hasMultiply = false;
        $hasPower = false;
        for ($j = 0; $j < count($number); $j++) {
            if ($plusMinus[$j] == 2) { // has devide, or power not empty
                $hasMultiply = true;
                break;
            }
            if (count($power[$j]) > 0) {
                $hasPower = true;
                break;
            }
        }

        if ($algebra instanceof Algebra_object && !$hasMultiply && !$hasPower) {
            $canPull = true;
            $minNumber = 1;
            for ($j = 0; $j < count($number); $j++) {
                if ($plusMinus[$j] == 3) { // has devide
                    $algebra->number = $algebra->number / $number[$j];
                    $algebra->variable[$j]->number = 1;
                } else if ($number[$j]  == 1) {
                    $canPull = false;
                    break;
                } else if ($minNumber == 1) {
                    $minNumber = $number[$j];
                } else if ($number[$j] < $minNumber) {
                    if ($minNumber % $number[$j] != 0) {
                        $canPull = false;
                        break;
                    }
                    $minNumber = $number[$j];
                } else {
                    if ($number[$j] % $minNumber != 0) {
                        $canPull = false;
                        break;
                    }
                }
            }
            if ($canPull) {
                $algebra->number = $algebra->number * $minNumber;
                for ($j = 0; $j < count($number); $j++) {
                    if ($plusMinus[$j] != 3) {
                        $algebra->variable[$j]->number = $algebra->variable[$j]->number / $minNumber;
                    }
                }
            }
        }

        return $algebra;
    }

    public function tidyUpAlgebra($algebraAnswer, $isFirst)
    {
        $i = 0;
        while ($i < count($algebraAnswer)) {
            if ($algebraAnswer[$i] instanceof Algebra_object) {
                if (count($algebraAnswer[$i]->variable) == 1) {
                    if (count($algebraAnswer[$i]->power) == 0) {  // have a change to pull out
                        if (is_array($algebraAnswer[$i]->variable[0]) && count($algebraAnswer[$i]->variable[0]) > 1) { // need to enhance this step
                            $algebraAnswer[$i]->variable = $algebraAnswer[$i]->variable[0];
                        } else if (
                            $algebraAnswer[$i]->variable[0] instanceof Algebra_object &&
                            $algebraAnswer[$i]->variable[0]->number == 1
                        ) {
                            $plusMinus = $algebraAnswer[$i]->plusMinus;
                            $number = $algebraAnswer[$i]->number;
                            $algebraAnswer[$i] = $algebraAnswer[$i]->variable[0];
                            $algebraAnswer[$i]->number = $number;
                            if ($plusMinus == 3) {
                                $algebraAnswer[$i]->plusMinus = $plusMinus;
                            } else if ($plusMinus == 1 && $algebraAnswer[$i]->plusMinus == 1) {
                                $algebraAnswer[$i]->plusMinus = 0;
                            } else if ($plusMinus == 1) {
                                $algebraAnswer[$i]->plusMinus = $plusMinus;
                            }
                        } else if (is_array($algebraAnswer[$i]->variable[0])) {
                            $algebraAnswer[$i]->variable = $algebraAnswer[$i]->variable[0];
                        } else {
                            $i++;
                        }
                    } else {
                        $i++;
                    }
                } else if ( //level up one layer
                    count($algebraAnswer[$i]->variable) > 1 &&
                    count($algebraAnswer[$i]->power) == 0 &&
                    $algebraAnswer[$i]->plusMinus == 0 &&
                    $algebraAnswer[$i]->number == 1 &&
                    ($i == count($algebraAnswer) - 1 || ($algebraAnswer[$i + 1] instanceof Algebra_object && $algebraAnswer[$i + 1]->plusMinus != 3)) &&
                    (!$this->hasNestedVariable($algebraAnswer[$i]->variable))
                ) {
                    if ($isFirst) {
                        if ($algebraAnswer[$i]->variable[0] instanceof Algebra_object) {
                            $newAlgebra = $this->getNewAlgebra($algebraAnswer[$i]->variable);
                            array_splice($algebraAnswer, $i, 1, $newAlgebra);
                        } else {
                            $i++;
                        }
                    } else {
                        $newAlgebra = $this->getNewAlgebra($algebraAnswer[$i]->variable);
                        array_splice($algebraAnswer, $i, 1, $newAlgebra);
                    }
                } else if ( //is a divider
                    count($algebraAnswer[$i]->variable) == 0 &&
                    count($algebraAnswer[$i]->power) == 0 &&
                    $i != 0 &&
                    count($algebraAnswer[$i - 1]->power) == 0 &&
                    $algebraAnswer[$i]->plusMinus == 3
                ) {
                    //combine with other
                    $algebraAnswer[$i - 1]->number = $algebraAnswer[$i - 1]->number /  $algebraAnswer[$i]->number;
                    array_splice($algebraAnswer, $i, 1);
                } else {
                    $algebraAnswer[$i]->variable = $this->tidyUpAlgebra($algebraAnswer[$i]->variable, false);
                    $i++;
                }
            } else if (is_array($algebraAnswer[$i]) && count($algebraAnswer[$i]) == 1) {
                $algebraAnswer[$i] = $algebraAnswer[$i][0];
            } else {
                $i++;
            }
        }

        return $algebraAnswer;
    }

    public function hasNestedVariable($variable)
    {
        for ($i = 0; $i < count($variable); $i++) {
            if (is_array($variable[$i]) && count($variable[$i] > 1)) {
                return true;
            }
        }
        return false;
    }

    public function getNewAlgebra($algebraAnswer)
    {
        $new = array();

        for ($i = 0; $i < count($algebraAnswer); $i++) {
            $newAlgebra = $algebraAnswer[$i];

            $new[] = $newAlgebra;
        }

        return $new;
    }

    public function deciperAlgebra($algebraAnswer = array(), $answerText, $bracketHeirarchy)
    {

        $plusMinus = array("+", "-", "*", "/");

        $index = count($algebraAnswer);
        $numberTxt = "";
        $isPower = false;


        $algebraAnswer[$index] = new Algebra_object();
        $index++;

        if (!isset($answerText)) return $algebraAnswer;

        for ($i = 0; $i < strlen($answerText); $i++) {
            /**
             * DETECTED +,-,*,/
             */
            if ((false !== $key = array_search($answerText[$i], $plusMinus))) {
                if ($i == 0) {
                    $algebraAnswer[$index - 1]->plusMinus = $key;
                    continue;
                }
                //IF BEFORE AND AFTER "/" IS A NUMBER NOT VARIABLE
                if ($key == 3 && is_numeric($answerText[$i + 1]) && is_numeric($answerText[$i - 1])) {
                    $numberTxt = $numberTxt . $answerText[$i];
                    continue;
                }

                $algebraAnswer = $this->assign_number_power($algebraAnswer, $index, $numberTxt, $isPower);
                $numberTxt = "";
                $algebraAnswer[$index] = new Algebra_object();
                $algebraAnswer[$index]->plusMinus = $key;
                $index++;

                /**
                 * DETECTED POWER SIGN/
                 */
            } else if ($answerText[$i] == "^") {
                $algebraAnswer = $this->assign_number_power($algebraAnswer, $index, $numberTxt, $isPower);
                $numberTxt = "";
                $algebraAnswer[$index - 1]->power[] = new Algebra_object();
                $isPower = true;
                /**
                 * DETECTED BRACKET/
                 */
            } else if ($this->isWithinBracket($i, $bracketHeirarchy)) {
                $algebraAnswer = $this->assign_number_power($algebraAnswer, $index, $numberTxt, $isPower);
                $numberTxt = "";

                $bracketRange = $this->getBracketRange($i, $bracketHeirarchy);
                $newAnswerText = substr($answerText, $bracketRange[0] + 1, $bracketRange[1] - $bracketRange[0] - 1);
                $newBracketHeirarchy = $this->offsetBracketPos($bracketHeirarchy[$bracketRange[2]][2], $bracketRange[0] + 1);
                if (count($newBracketHeirarchy) == 0 && $this->isAllNumber($newAnswerText)) {
                    $numberTxt = $newAnswerText;
                    continue;
                } else if ($isPower) {
                    $algebraAnswer[$index - 1]->power[] = $this->deciperAlgebra(
                        array(),
                        $newAnswerText,
                        $newBracketHeirarchy
                    );
                } else {
                    $algebraAnswer[$index - 1]->variable[] = $this->deciperAlgebra(
                        array(),
                        $newAnswerText,
                        $newBracketHeirarchy
                    );
                }
                $isPower = false;
                $i = $bracketRange[1];
                /**
                 * DETECTED NUMBER
                 */
            } else if (is_numeric($answerText[$i]) || $answerText[$i] == ".") {
                $numberTxt .= $answerText[$i];

                /**
                 * DETECTED VARIABLE
                 */
            } else if (!is_numeric($answerText[$i])) {
                $algebraAnswer = $this->assign_number_power($algebraAnswer, $index, $numberTxt, $isPower);
                $nextCharHasPower = false;
                //IF NEXT CHARACTER HAS POWER SIGN
                if ($i + 1 < strlen($answerText)) {
                    if ($answerText[$i + 1] == "^") {
                        $nextCharHasPower = true;
                        //NEXT CHARACTER HAS POWER SIGN, 
                        //INCLUDE NEXT FEW CHARACTER UNDER VARIABLE
                        if ($i + 2 < strlen($answerText)) {
                            if ($this->isWithinBracket($i + 2, $bracketHeirarchy)) {
                                $bracketRange = $this->getBracketRange($i + 2, $bracketHeirarchy);
                                $newBracketHeirarchy = $this->offsetBracketPos($bracketHeirarchy[$bracketRange[2]][2], $bracketRange[0] + 1);
                                $newAnswerText = substr($answerText, $i,  2);
                                $newAnswerText += substr($answerText, $bracketRange[0] + 1, $bracketRange[1] - $bracketRange[0] - 1);
                                $i = $bracketRange[1];

                                if ($isPower) {
                                    $algebraAnswer[$index - 1]->power[count($algebraAnswer[$index - 1]->power) - 1]->variable[] = $this->deciperAlgebra(
                                        array(),
                                        $newAnswerText,
                                        $newBracketHeirarchy
                                    );
                                } else {
                                    $algebraAnswer[$index - 1]->variable[] = $this->deciperAlgebra(
                                        array(),
                                        $newAnswerText,
                                        $newBracketHeirarchy
                                    );
                                }
                            } else {
                                $newAlgebra = new Algebra_object();
                                $newAlgebra->variable[] = $answerText[$i];
                                $newAlgebra->number = 1;
                                $newAlgebra->power = $this->deciperAlgebra($newAlgebra->power, $answerText[$i + 2], array());
                                if ($isPower) {
                                    $algebraAnswer[$index - 1]->power[count($algebraAnswer[$index - 1]->power) - 1]->variable[] = $newAlgebra;
                                } else {
                                    $algebraAnswer[$index - 1]->variable[] = $newAlgebra;
                                }

                                $i = $i + 2;
                            }
                        }
                    }
                }
                if (!$nextCharHasPower) {
                    if ($isPower) {
                        $algebraAnswer[$index - 1]->power[count($algebraAnswer[$index - 1]->power) - 1]->variable[] = $answerText[$i];
                    } else {
                        $algebraAnswer[$index - 1]->variable[] = $answerText[$i];
                    }
                }

                $numberTxt = "";
                $isPower = false;
            }

            if ($i == strlen($answerText) - 1) {
                $algebraAnswer = $this->assign_number_power($algebraAnswer, $index, $numberTxt, $isPower);
                $numberTxt = "";
                $isPower = false;
            }
        }

        return $algebraAnswer;
    }

    public function isAllNumber($answerText)
    {
        for ($i = 0; $i < strlen($answerText); $i++) {
            if (!is_numeric($answerText[$i]) && strpos($answerText[$i], "/") === false && strpos($answerText[$i], ".") === false) {
                return false;
            }
        }
        return true;
    }

    public function offsetBracketPos($bracket, $offset)
    {
        for ($i = 0; $i < count($bracket); $i++) {
            $bracket[$i][0] -= $offset;
            $bracket[$i][1] -= $offset;
            $bracket[$i][2] = $this->offsetBracketPos($bracket[$i][2], $offset);
        }

        return $bracket;
    }

    public function assign_number_power($algebraAnswer, $index, $numberTxt, $isPower)
    {

        if (!$isPower && $algebraAnswer[$index - 1]->number == 0 || $numberTxt != "") {
            if ($numberTxt != "") {
                if (strpos($numberTxt, '.') === false && strpos($numberTxt, '/') === false) {
                    $algebraAnswer[$index - 1]->number = intval($numberTxt);
                } else {
                    if (strpos($numberTxt, '/') !== false) {
                        $num = explode("/", $numberTxt);
                        if (isset($num[1]) && $num[1] != "0") {
                            $numberTxt = floatval($num[0]) / floatval($num[1]);
                        } else {
                            $numberTxt = floatval($num[0]);
                        }
                    }
                    $algebraAnswer[$index - 1]->number = floatval($numberTxt);
                }
            } else {
                $algebraAnswer[$index - 1]->number = 1;
            }
        }

        if ($isPower && $numberTxt != "") {
            if (count($algebraAnswer[$index - 1]->power) == 0) {
                $algebraAnswer[$index - 1]->power[0] = new Algebra_object();
            }
            if ($numberTxt != "") {
                if (strpos($numberTxt, '.') === false && strpos($numberTxt, '/') === false) {
                    $algebraAnswer[$index - 1]->power[count($algebraAnswer[$index - 1]->power) - 1]->number = intval($numberTxt);
                } else {
                    $algebraAnswer[$index - 1]->power[count($algebraAnswer[$index - 1]->power) - 1]->number = floatval($numberTxt);
                }
            } else {
                $algebraAnswer[$index - 1]->power[count($algebraAnswer[$index - 1]->power) - 1]->number = 1;
            }
        }
        return $algebraAnswer;
    }

    public function convertHierarchy($bracketMapping, $bracketHierarchy)
    {
        //bracketHierarchy format 
        //(left bracket pos, right bracket pos, [[left bracket pos, right bracket pos, []],.....])
        $index = count($bracketHierarchy);
        while (count($bracketMapping) > 0) {
            if (count($bracketHierarchy) == 0) {
                $bracketHierarchy[$index][0] = $bracketMapping[0][0];
                $bracketHierarchy[$index][1] = $bracketMapping[0][1];
                $bracketHierarchy[$index][2] = array();
                $index++;
                unset($bracketMapping[0]);
                $bracketMapping = array_values($bracketMapping);
                continue;
            }
            $i = 0;

            $isRepeat = false;
            while ($i < count($bracketHierarchy)) {
                if (
                    $bracketMapping[0][0] < $bracketHierarchy[$i][0] ||
                    $bracketMapping[0][0] > $bracketHierarchy[$i][1]
                ) {
                    $i++;
                    continue;
                }

                $isRepeat = true;
                $bracketHierarchy[$i][2] = $this->convertHierarchy([$bracketMapping[0]], $bracketHierarchy[$i][2]);
                unset($bracketMapping[0]);
                $bracketMapping = array_values($bracketMapping);
                break;
            }
            if (!$isRepeat) {
                $bracketHierarchy[$index][0] = $bracketMapping[0][0];
                $bracketHierarchy[$index][1] = $bracketMapping[0][1];
                $bracketHierarchy[$index][2] = array();
                $index++;
                unset($bracketMapping[0]);
                $bracketMapping = array_values($bracketMapping);
            }
        }

        return $bracketHierarchy;
    }

    public function mapping_bracket($answerText, $bracketLeft, $bracketRight)
    {
        $bracketMapping = array();

        $index = 0;
        $i = 0;
        while (count($bracketLeft) > 0) {
            if ($i < count($bracketLeft) && $bracketRight[0] > $bracketLeft[$i]) {
                $bracketMapping[$index][0] = $bracketLeft[$i];
                $bracketMapping[$index][1] = $bracketRight[0];
                $i++;
            } else {
                unset($bracketLeft[$i - 1]);
                $bracketLeft = array_values($bracketLeft);

                unset($bracketRight[0]);
                $bracketRight = array_values($bracketRight);
                $i = 0;
                $index++;
            }
        }

        return $bracketMapping;
    }

    public function bracketPairing($bracketLeft, $bracketRight)
    {
        $pair = array();
        for ($i = 0; $i < count($bracketLeft); $i++) {
        }
    }

    public function getBracketPosition($match)
    {
        $output = array();
        for ($i = 0; $i < count($match[0]); $i++) {
            $output[] = $match[0][$i][1];
        }

        return $output;
    }

    public function getBracketRange($pos, $hierarchyBracket)
    {
        $range = array();
        // -1 if divider is outside of the box
        for ($j = 0; $j < count($hierarchyBracket); $j++) {
            if (
                $pos >= $hierarchyBracket[$j][0] &&
                $pos <= $hierarchyBracket[$j][1]
            ) {

                $range[0] = $hierarchyBracket[$j][0];
                $range[1] = $hierarchyBracket[$j][1];
                $range[2] = $j;
                break;
            }
        }


        return $range;
    }

    public function check_has_bracket($answerText)
    {
        $regex  = "/\(/";
        if (preg_match($regex, $answerText)) {
            return true;
        }
        return false;
    }

    public function isWithinBracket($pos, $hierarchyBracket)
    {
        // -1 if divider is outside of the box
        $isWithin = false;
        for ($j = 0; $j < count($hierarchyBracket); $j++) {
            if (
                $pos >= $hierarchyBracket[$j][0] &&
                $pos <= $hierarchyBracket[$j][1]
            ) {
                $isWithin = true;
                break;
            }
        }


        return $isWithin;
    }
}

class Algebra_object
{
    public $variable = array(); //can be string can be Algebra_object
    public $number = 0;
    public $power = array();
    public $plusMinus = 0; //0 is plus , 1 is minus, 2 is * , 3 is /

}
