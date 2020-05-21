 
    <?php
        include "connection.php";

        $prodID = $_GET["prodID"];
//$prodID = array('a1');
echo json_encode($prodID);
        function getProductDetails($prodId){
            global $conn;
            $tableName = "product_list";
            $colName = "prod_id";
            $toReturn = "";
            $sql = "select * from $tableName where $colName in (";
            $isFirst = true;
            if($conn != null){
                foreach($prodID as $id){
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
    getProductDetails($prodID);
    ?>