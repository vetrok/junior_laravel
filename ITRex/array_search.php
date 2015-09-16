<?php
/**
 * PHP developer test exercise
 *
 * @author Vitaliy Yatsenko <vetrok87n@gmail.com>
 * @version 1.0
*/

/**
 * Takes array, split it in two parts and searching number of elements equal to $needle
 * in left part of array and number of elements not equal $needle in right part
 *
 * @param  array $arr array where searching proceeding
 * @param int $needle what we search for
 * @param int $split pointer to split array
 *
 * @uses mySplit
 * @return array
 */
function inArray(Array $arr, $needle, $split) {
    $arrayYes = array_slice($arr, 0, $split);
    $arrayNo = array_slice($arr, $split, count($arr));
    //I decide to count number of needle by native PHP functions
    $arrayYes = array_count_values($arrayYes);
    $arrayNo = array_count_values($arrayNo);
    //If null i will get 0
    $yes = (int)$arrayYes[$needle];
    //Unset needle, and count other values
    unset($arrayNo[$needle]);
    $no = (int)array_sum($arrayNo);
    return ['yes' => $yes, 'no' => $no];
}

/**
 * Search for Value where $searchNum in $startArray[0 ... Value - 1] == !$searchNum in arr[Value ... count($searchNum) - 1]
 * Detail description in technical assignment
 *
 * @param int $searchNum number what we search for
 * @param array $startArray array where we searching values
 *
 * @return int
 */
function mySplit($searchNum, Array $startArray) {
    if (in_array($searchNum, $startArray)) {
        $arrLength = count($startArray);
        $start = 0;
        $end = $arrLength;
        $middle = (int)(($start + $end) / 2);
        $middleWatcher = 0;
        $result = inArray($startArray, $searchNum, $middle);
        while ($result['yes'] !== $result['no']) {
            if ($result['yes'] > $result['no']) {
                $end = $middle;
            } else if ($result['yes'] < $result['no']) {
                $start = $middle;
            }
            $middle = (int)(($start + $end) / 2);
            //If wathcer == middle - that means that value repeating and no sense to do another loop
            if ($middle === $middleWatcher) {
                return -1;
            }
            $middleWatcher = $middle;
            $result = inArray($startArray, $searchNum, $middle);
        }
        return $middle;
    }
    return -1;
}


//Simple test with 100001 array
$arr = range(0, 100000);
$arr[100] = 1;
$arr[1000] = 1;
$arr[11000] = 1;

//Must return 100001 - 4 (1 already exists in arr[1])

echo mySplit(1, $arr);
?>