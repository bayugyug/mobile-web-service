<?php
/*************************** 
	GET PARAMETERS         
 	messageCrewAssist.php  
****************************/
$employeeId = $_GET['employeeid'];
$employeeEmail = $_GET['employeeemail'];
$employeeName = $_GET['name'];
$schedulerEmail = $_GET['scheduleremail'];
$subject = $_GET['subject'];
$message = $_GET['message'];









/***********************
	GET PARAMETERS
	messages.php
************************/
$user = $_GET['user'];
$lastId = $_GET['lastMsgId'];

/*************************
	SQL
	messages.php
**************************/
$query = "select id,
				sender,
				recipient,
				message,
				status 
				from messages 
			where recipient = :user and id > :lastId order by id desc";












/***********************
	GET PARAMETERS
	message2.php
************************/
$user = $_GET['user'];
$lastId = $_GET['lastMsgId'];

/***********************
	SQL
	message2.php
************************/
$query = "select toread,
				 tbl_uddeim.id as id,
				 fromid,
				 toid,
				 message,
				 datum,
				 short_message,
				 tbl_users.name as name,
				 systemmessage,
				 toread
				 from tbl_uddeim 
			join tbl_users on tbl_uddeim.fromid = tbl_users.id 
		where toid = :user and totrash = 0 
		order by id desc";












/***********************
	GET PARAMETERS
	setMessageRead.php
************************/
$id = $_GET['id'];

/***********************
	SQL
	setMessageRead.php
************************/
$query = "update tbl_uddeim
			set toread=1 
			where id = :id";
?>