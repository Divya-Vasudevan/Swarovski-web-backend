
    <?php
        include("connection.php");
        
        //params from html get
        if(isset($_GET["cat"])){
            $category = $_GET["cat"];
        }
        else {
            $category = null;
        }
        if(isset($_GET["brand"])){
            $brands = $_GET["brand"];
        }
        else {
            $brands = null;
        }
        if(isset($_GET["family"])){
            $families = $_GET["family"];
            //echo json_encode($families);
        }
        else {
            $families = null;
        }
        if(isset($_GET["light_src"])){
            $light_srcs = $_GET["light_src"];
        }
        else {
            $light_srcs = null;
        }
        if(isset($_GET["finish"])){
            $finishes = $_GET["finish"];
        }
        else {
            $finishes = null;
        }
        if(isset($_GET["room"])){
            $rooms = $_GET["room"];
        }
        else {
            $rooms = null;
        }
        
        function addwhereKeyword($type){
            if($type == 0){
                $sql = "where ";
            }
            else{
                $sql = "where prod_id in (select prod_id from prod_filters where ";
            }
            return $sql;
        }
    
        function getWhereClause($filterName,$filterVals,$table){
            $sql = "";
            if($table == null){
                $sql .= "$filterName in (";
                $isFirst = true;
                foreach($filterVals as $val) {
                    if(!$isFirst){
                        $sql .= ",";
                    }
                    $sql .= "'$val'";
                    $isFirst = false;
                }
                $sql .= ") ";
            }
            else{
                $sql .= "prod_id in (select distinct prod_id from $table where $filterName in (";
                $isFirst = true;
                foreach($filterVals as $val) {
                    if(!$isFirst){
                        $sql .= ",";
                    }
                    $sql .= "'$val'";
                    $isFirst = false;
                }
                $sql .= ")) ";
            }
            return $sql;
        }
        
        //location values(int): 0-international, 1-china, 2-north america

        function getAllProducts(){
            global $conn, $category, $brands, $families, $light_srcs, $finishes, $rooms;
            $toReturn = new stdClass();
            if($conn != null){
                $sql = "select * from ";
                /*if($location == 0){     //here check if there is a category filter if yes then                                                avoid using the view and use actual table
                    $sql .= "prod_filters_international ";
                }
                else if($location == 1){
                    $sql .= "prod_filters_china ";
                }
                else{*/
                    $sql .= "prod_filters ";
                //}
                $filters = "";
                $brandCount = "select brand, count(prod_id) as count from prod_filters ";
                $familyCount = "select family, count(prod_id) as count from prod_filters ";
                $lightSrcCount = "select light_src, count(prod_id) as count from prod_filters ";
                $finishCount = "select finish, count(prod_id) as count from finishes ";
                $roomCount = "select room, count(prod_id) as count from rooms ";
                
                if($category != null || $brands != null || $families != null || $light_srcs != null || $finishes != null || $rooms != null){
                    $flag = false;
                    $bflag = false;
                    $famFlag = false;
                    $finFlag = false;
                    $rflag = false;
                    $lsflag = false;
                    $roomCountFilters = false;
                    $finishCountFilters = false;
                    
                    if($category != null){
                        $sql .= addwhereKeyword(0);
                        $lightSrcCount .= addwhereKeyword(0);
                        $roomCount .= addwhereKeyword(1);
                        $finishCount .= addwhereKeyword(1);
                        $familyCount .= addwhereKeyword(0);
                        $brandCount .= addwhereKeyword(0);
                        
                        $sql .= "category = '$category' ";
                        $brandCount .= "category = '$category' ";
                        $familyCount .= "category = '$category' ";
                        $finishCount .= "category = '$category' ";
                        $roomCount .= "category = '$category' ";
                        $lightSrcCount .= "category = '$category' ";
                        $flag = true;
                        $bflag = true;
                        $lsflag = true;
                        $rflag = true;
                        $finFlag = true;
                        $famFlag = true;
                        $roomCountFilters = true;
                        $finishCountFilters = true;
                    }
                    /****************************brands*******************************/
                    $whereClause = "";
                    if($brands != null){
                        if($flag){ $sql .= "AND "; } 
                        else {$sql .= addwhereKeyword(0);}
                        if($lsflag){ $lightSrcCount .= "AND "; } 
                        else {$lightSrcCount .= addwhereKeyword(0);}
                        if($rflag){ $roomCount .= "AND "; } 
                        else {$roomCount .= addwhereKeyword(1);}
                        if($finFlag){ $finishCount .= "AND "; } 
                        else {$finishCount .= addwhereKeyword(1);}
                        if($famFlag){ $familyCount .= "AND "; } 
                        else {$familyCount .= addwhereKeyword(0);}
                        
                        $whereClause .= getWhereClause("brand",$brands,null);
                        $sql .= $whereClause;
                        $familyCount .= $whereClause;
                        $finishCount .= $whereClause;
                        $roomCount .= $whereClause;
                        $lightSrcCount .= $whereClause;
                        $flag = true;
                        $lsflag = true;
                        $rflag = true;
                        $finFlag = true;
                        $famFlag = true;
                        $roomCountFilters = true;
                        $finishCountFilters = true;
                    }
                    /****************************families*******************************/
                    $whereClause = "";
                    if($families != null){
                        if($flag){ $sql .= "AND "; } 
                        else {$sql .= addwhereKeyword(0);}
                        if($lsflag){ $lightSrcCount .= "AND "; } 
                        else {$lightSrcCount .= addwhereKeyword(0);}
                        if($rflag){ $roomCount .= "AND "; } 
                        else {$roomCount .= addwhereKeyword(1);}
                        if($finFlag){ $finishCount .= "AND "; } 
                        else {$finishCount .= addwhereKeyword(1);}
                        if($bflag){ $brandCount .= "AND "; } 
                        else {$brandCount .= addwhereKeyword(0);}
                        
                        $whereClause .= getWhereClause("family",$families,null);
                        $sql .= $whereClause;
                        $brandCount .= $whereClause;
                        $finishCount .= $whereClause;
                        $roomCount .= $whereClause;
                        $lightSrcCount .= $whereClause;
                        $flag = true;
                        $bflag = true;
                        $lsflag = true;
                        $rflag = true;
                        $finFlag = true;
                        $roomCountFilters = true;
                        $finishCountFilters = true;
                    }
                    /****************************light_srcs*******************************/
                    $whereClause = "";
                    if($light_srcs != null){
                        if($flag){ $sql .= "AND "; } 
                        else {$sql .= addwhereKeyword(0);}
                        if($famFlag){ $familyCount .= "AND "; } 
                        else {$familyCount .= addwhereKeyword(0);}
                        if($rflag){ $roomCount .= "AND "; } 
                        else {$roomCount .= addwhereKeyword(1);}
                        if($finFlag){ $finishCount .= "AND "; } 
                        else {$finishCount .= addwhereKeyword(1);}
                        if($bflag){ $brandCount .= "AND "; } 
                        else {$brandCount .= addwhereKeyword(0);}
                        
                        $whereClause .= getWhereClause("light_src",$light_srcs,null);
                        $sql .= $whereClause;
                        $brandCount .= $whereClause;
                        $familyCount .= $whereClause;
                        $finishCount .= $whereClause;
                        $roomCount .= $whereClause;
                        $flag = true;
                        $bflag = true;
                        $rflag = true;
                        $finFlag = true;
                        $famFlag = true;
                        $roomCountFilters = true;
                        $finishCountFilters = true;
                    }
                    /****************************finishes*******************************/
                    $whereClause = "";
                    if($finishes != null){
                        if($flag){ $sql .= "AND "; } 
                        else {$sql .= addwhereKeyword(0);}
                        if($lsflag){ $lightSrcCount .= "AND "; } 
                        else {$lightSrcCount .= addwhereKeyword(0);}
                        if($rflag){ $roomCount .= "AND "; } 
                        else {$roomCount .= addwhereKeyword(1);}
                        if($bflag){ $brandCount .= "AND "; } 
                        else {$brandCount .= addwhereKeyword(0);}
                        if($famFlag){ $familyCount .= "AND "; } 
                        else {$familyCount .= addwhereKeyword(0);}
                        
                        $whereClause .= getWhereClause("finish",$finishes,"finishes");
                        $sql .= $whereClause;
                        $brandCount .= $whereClause;
                        $familyCount .= $whereClause;
                        $roomCount .= $whereClause;
                        $lightSrcCount .= $whereClause;
                        $flag = true;
                        $bflag = true;
                        $lsflag = true;
                        $rflag = true;
                        $famFlag = true;
                        $roomCountFilters = true;
                    }
                    /*****************************rooms******************************/
                    $whereClause = "";
                    if($rooms != null){
                        if($flag){ $sql .= "AND "; } 
                        else {$sql .= addwhereKeyword(0);}
                        if($lsflag){ $lightSrcCount .= "AND "; } 
                        else {$lightSrcCount .= addwhereKeyword(0);}
                        if($bflag){ $brandCount .= "AND "; } 
                        else {$brandCount .= addwhereKeyword(0);}
                        if($finFlag){ $finishCount .= "AND "; } 
                        else {$finishCount .= addwhereKeyword(1);}
                        if($famFlag){ $familyCount .= "AND "; } 
                        else {$familyCount .= addwhereKeyword(0);}
                        
                        $whereClause .= getWhereClause("room",$rooms,"rooms");
                        $sql .= $whereClause;
                        $brandCount .= $whereClause;
                        $familyCount .= $whereClause;
                        $finishCount .= $whereClause;
                        $lightSrcCount .= $whereClause;
                        $finishCountFilters = true;
                    }
                    if($finishCountFilters){
                        $finishCount .= ")";
                    }
                    if($roomCountFilters){
                        $roomCount .= ")";
                    }
                }
                $sql .= ";";
                $brandCount .= "group by brand;";
                $familyCount .= "group by family;";
                $finishCount .= "group by finish;";
                $roomCount .= "group by room;";
                $lightSrcCount .= "group by light_src;";
                $result = $conn->query($sql);
                $products;
                if ($result->num_rows > 0) {
                    $count = 0;
                    while($row = $result->fetch_assoc()){
                        $products[$count] = $row;
                        $count++;
                    }
                    $toReturn->products = $products;
                //executing filter count queries only if there are results for the requested query
                    $resultBC = $conn->query($brandCount);
                    $count = 0;
                    $bc;
                    if ($resultBC->num_rows > 0) {
                        while($row = $resultBC->fetch_assoc()){
                            $bc[$count] = $row;
                            $count++;
                        }
                        $toReturn->brandCount = $bc;
                    }
                    
                    $resultFamC = $conn->query($familyCount);
                    $count = 0;
                    $famc;
                    if ($resultFamC->num_rows > 0) {
                        while($row = $resultFamC->fetch_assoc()){
                            $famc[$count] = $row;
                            $count++;
                        }
                        $toReturn->familyCount = $famc;
                    }
                    
                    $resultFinC = $conn->query($finishCount);
                    $count = 0;
                    $finc;
                    if ($resultFinC->num_rows > 0) {
                        while($row = $resultFinC->fetch_assoc()){
                            $finc[$count] = $row;
                            $count++;
                        }
                        $toReturn->finishCount = $finc;
                    }
                    
                    $resultRC = $conn->query($roomCount);
                    $count = 0;
                    $rc;
                    if ($resultRC->num_rows > 0) {
                        while($row = $resultRC->fetch_assoc()){
                            $rc[$count] = $row;
                            $count++;
                        }
                        $toReturn->roomCount = $rc;
                    }
                    
                    $resultLSC = $conn->query($lightSrcCount);
                    $count = 0;
                    $lsc;
                    if ($resultLSC->num_rows > 0) {
                        while($row = $resultLSC->fetch_assoc()){
                            $lsc[$count] = $row;
                            $count++;
                        }
                        $toReturn->lightSrcCount = $lsc;
                    }
                    $toReturn->categoryFilters = $category;
                    $toReturn->brandsFilters = $brands;
                    $toReturn->familyFilters = $families;
                    $toReturn->light_srcFilters = $light_srcs;
                    $toReturn->finishFilters = $finishes;
                    $toReturn->roomFilters = $rooms;
                }
                else{
                    echo "<br>No Results";
                }
                $conn->close();
                echo json_encode($toReturn);
            }
        } 
    
    //function call
    getAllProducts();
    ?>