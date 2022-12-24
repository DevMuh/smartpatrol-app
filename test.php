<?php
$time1 = new DateTime('07:00:00');
$time2 = new DateTime('09:30:00');
$t = $time1;
$t->modify("+0 minutes");
$interval = $time1->diff($time2);
//if($time2 > $t){
echo  $interval->hh . ' j, ' . $interval->i . ' m';
//} else {
//  echo "wrong";
//};

$end = clone $start;
$end->add(new DateInterval('P1M6D'));

$diff = $end->diff($start);
echo 'Difference: ' . $diff->format('%m month, %d days (total: %a days)') . "\n";

?>
