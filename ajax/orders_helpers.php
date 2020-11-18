<?php

function getStandardOrderDate($standardOrderSlot){
    //set dates for standard orders - all standard orders are saved on january in the year 6666
    switch ($standardOrderSlot) {
        case 1:
            $day = '01';
            break;
        case 2:
            $day = '02';
            break;
        case 3:
            $day = '03';
            break;
        case 4:
            $day = '04';
            break;
        case 5:
            $day = '05';
            break;
        case 6:
            $day = '06';
            break;
        case 7:
            $day = '07';
            break;
        case 8:
            $day = '08';
            break;
        case 9:
            $day = '09';
            break;
        default:
            $day = '00';
    }
    $month = '01';
    $year = '6666';
    return $year."-".$month."-".$day;
}
?>

