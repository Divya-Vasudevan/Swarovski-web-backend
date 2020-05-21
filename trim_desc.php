<?php
include "connection.php";
$code = $_GET["trimDesc"];
//$code = array('M','o');

function getTrimDescDetails(){
        global $conn, $code;
        $toReturn = "";
        $sql = "select * from trim_desc where code in (";
        $isFirst = true;
        if($conn != null){
            foreach($code as $id){
                if(!$isFirst){
                    $sql .= ",";
                }
                $sql .= "'$id'";
                $isFirst = false;
            }
            $sql .= ");";
            $rows;
            $count = 0;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    $rows[$count] = $row;
                    $count++;
                }
                $toReturn = json_encode($rows);
            } 
            else {
                echo "No Results";
            }
            $conn->close();
            echo $toReturn;
        }
}
    
//function call
getTrimDescDetails();
?>