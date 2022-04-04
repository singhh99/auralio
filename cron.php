<?php
$conn = new PDO("mysql:host=localhost;dbname=combycut", "root", "");  //conn.. through PDO//
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
$qry="UPDATE `customer_booking` SET `booking_status_id` = '100' WHERE `customer_booking`.`customer_schedule_date`->diff('2021-04-29')";
$stmt=$conn->query($qry);
echo "boom";

    