<?php
try{

	include('../src/DatabaseHandler.php');
include('../src/FtpIndexer.php');

$dbHandler = new DatabaseHandler('mysql:host=localhost;dbname=carola', 'root', 'Cobra3000-');

$ftp_indexer = new FtpIndexer($dbHandler);

$id = $_POST['id'];

echo(var_dump($ftp_indexer->scan($id))); 


}catch(\Exception $e)
{
	echo $e->getMessage();
}

