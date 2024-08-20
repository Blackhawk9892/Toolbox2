<?php

/**
 * Description of person
 *
 * @author Tim Dumouchel
 */
class Person {

    public $first;
    public $last;
    public $email;
    public $phone;
    
    
    

    const ALPHA_1 = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
        "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b",
        "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s",
        "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "'", "/", "-",
        "_", "@", ".", " "];
    const ALPHA_2 = ["q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "a", "s",
        "d", "f", "g", "h", "j", "k", "l", "z", "x", "c", "v", "b", "n", "m", "1", "2",
        "3", "4", "5", "6", "7", "8", "9", "0", "*", "Q", "W", "E", "R", "T", "Y", "U", "I",
        "O", "P", "A", "S", "D", "F", "G", "H", "J", "K", "L", "Z", "X", "C", "V", "B", "N", "M", "^", "_",
        "_", ".", "@", "="];

    public function __construct($first, $last, $email, $phone) {
        $this->first = $first;
        $this->last = $last;
        $this->phone = $phone;
        $this->email = $email;
        
       
    }

    public function encrypt($data) {

        $newWord = '';
        $count = strlen($data);
        $word = str_split($data);
        foreach ($word as $value) {
            $arrayKey = array_search($value, Person::ALPHA_1);
            $newWord .= Person::ALPHA_2[$arrayKey];
        }

        $i = 200 - $count;
        $x = 1;
        while ($x <= $i) {
            $x++;
            $addsize = $count + $x;
            switch ($addsize) {
               
                case "194":
                    if ($count < 10) {
                        $newWord .= 'X';
                    } else {
                        $numArray = str_split($count);
                        $newWord .= $numArray[0];
                    }
                    break;
                case "196":
                    if ($count >= 10) {
                        $numArray = str_split($count);
                        $newWord .= $numArray[1];
                    } else {
                        $numArray = str_split($count);
                        $newWord .= $numArray[0];
                    }
                    break;
                default:
                    $value = rand(1, 50);

                    $newWord .= Person::ALPHA_2[$value];
            }
        }

        // return $arrayKey;
        return $newWord;
    }

    public function unencrypt($data) {

        $word = str_split($data);


        if ($word[192] == 'X') {
            $wordSize = $word[194];
           
        } else {
            $wordSize = $word[192];
            $wordSize .= $word[194];
            
        }
                

        $chunkWork = chunk_split($data, $wordSize, " ");
        $dataArray = explode(" ", $chunkWork);
        $data = $dataArray[0];
        $newWord = '';

        $word = str_split($data);
        foreach ($word as $value) {
            $arrayKey = array_search($value, Person::ALPHA_2);
            $newWord .= Person::ALPHA_1[$arrayKey];
        }
        return $newWord;
       // return $wordSize;
        
    }

    public function enter() {

        $newWord = '';
        $i = 40 ;
        $x = 1;
        while ($x <= $i) {
            $x++;
            $value = rand(1, 50);
            $newWord .= Person::ALPHA_2[$value];
        }
        return $newWord;
    }

}
