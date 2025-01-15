<?php
use Carbon\Carbon;

require '../vendor/autoload.php';


$dt = Carbon::now();
echo $dt->year;
