<?php


showImages();

function showImages() {
global $tpl, $config, $mysql; 

$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_zboard WHERE active = \'0\' ');
	

require_once("conexion.php");
$cn=Conectarse();
$id = $_REQUEST['des'];
var_dump($id);
$listar= mysql_query("select * from ngr_zboard_images where zid='$id'",$cn);
if(mysql_num_rows($listar)>0){
	echo"<table>";
	echo"<tr>";
	echo"<th>Estado</th>";
	echo"<th>Miniatura</th>";
	echo"</tr>";
		while($imagen=(mysql_fetch_array($listar))){
			echo"<tr>";
if($imagen['zid']==1){
echo"<td><img src='images/001_18.png' width='20'></td>";
}
else
{
echo"<td><img src='images/001_19.png' width='20'></td>";
}

echo"<td><a href='uploads/".$imagen['filepath']."'><img src='uploads/thumb/".$imagen['filepath']."' width='70' height='50'></a></td>";

echo"</tr>";
}
echo"</table>";
}else
{
echo"<div>no existen imagenes registradas</div>";

}

}
?>