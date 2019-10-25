<?php

function checkForPastAndAfterhour($db, $orderDate){
    //check if the order is in the past or afterhours
    $endOfOrderTime = $db->getData("settings", 'endOfOrderTime')[0]['endOfOrderTime'];
    $endOfOrderDateTime = new DateTime($orderDate." ".$endOfOrderTime);
    $endOfOrderDateTime->modify('-1 day');
    $currentTime = new DateTime('now');
    if($endOfOrderDateTime <= $currentTime AND $endOfOrderDateTime->format('Y-m-d') == $currentTime->format('Y-m-d')){
        return [false,"Der Bestellschluss für morgen ist um ".$endOfOrderDateTime->format('H:i')." Uhr. Eine Änderung der Bestellung für morgen ist jetzt leider nicht mehr möglich."];
    }
    elseif($endOfOrderDateTime <= $currentTime AND $endOfOrderDateTime->format('Y-m-d') != $currentTime->format('Y-m-d')){
        return [false, "Der Bestellschluss dieser Bestellung liegt in der Vergangenheit. Somit kann die Bestellung nicht mehr geändert werden."];
    }
    else{
        return [true,""];
    }
}

function makeDict($db, $table, $nameKey, $nameValue, $whereStatement=NULL, $whereValue=NULL){
    $list = $db->getData($table, array($nameKey,$nameValue),$whereStatement,$whereValue);
    $dict = NULL;

    foreach($list as $obj){
        $dict[$obj[$nameKey]] = $obj[$nameValue];
    }
    return $dict;
}

function checkForPermission($db, $productId, $specifiedDate, $preProductCalendarDict){
    //get calendar for product
    $calendarDatesRaw = $db->getData("calendarsDaysRelations",
        array('date'), "idCalendar=?1",$preProductCalendarDict[$productId]);

    $dateNow = new DateTime("now");
    //if now is later than Bestellschluss(endOfOrderTime), add one day to now
    $endOfOrderTimeStr = $db->getData("settings",array('endOfOrderTime'))[0]['endOfOrderTime'];
    $endOfOrderTime = explode(':',$endOfOrderTimeStr);
    $endOfOrderDate = clone $dateNow;
    $endOfOrderDate->setTime($endOfOrderTime[0],$endOfOrderTime[1],$endOfOrderTime[2]);
    if($endOfOrderDate < $dateNow){
        $dateNow->modify('+1 day');
    }

    //hole min und max; rechne die Daten aus und packe sie in array
    $orderDate = new DateTime($specifiedDate);
    $productDetails = $db->getData("products", array('preBakeExp','preBakeMax'), "id=?1",$productId)[0];
    $minDate = clone $orderDate;
    $minDate->modify('-'.$productDetails['preBakeExp'].' day');
    $maxDate = clone $orderDate;
    $maxDate->modify('-'.$productDetails['preBakeMax'].' day');
    //for schleife->ist eines der min-max daten in calendarDates?
    $check = false;
    foreach ($calendarDatesRaw as $cDate){
        $currentDate = new DateTime($cDate['date']);
        if($currentDate <= $minDate && $currentDate >= $maxDate && $currentDate >= $dateNow){
            $check = true;
            continue;
        }
    }
    return $check;
}

?>


















