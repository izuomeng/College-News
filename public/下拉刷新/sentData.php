
<?php
$con = mysqli_connect("120.24.43.150", "myuser","321427");
$day = (int)date("j");
if (count($_GET)) {
	$text = $_GET["curser"];
	$text %= 200;
}else{
	$text = 0;
}
if (!$con)
{
    echo "Could not connect server";
}
mysqli_select_db($con,"myNews");

$result = mysqli_query($con,"SELECT * FROM news LIMIT $text,10");
$row = mysqli_fetch_array($result);
$ary = array();
$i = 0;
do {
    $title = $row['title'];
    $ary[$i] = $title;
    $i++;
}while($row = mysqli_fetch_array($result));
echo json_encode($ary);
$i = 0;
?>
