<?php

include("connection.php");
//$searchTerm = $_GET["searchTerm"];
$searchTerm = "jasmine";

        if(isset($_GET["brand"])){
            $brands = $_GET["brand"];
        }
        else {
            $brands = null;
        }
        if(isset($_GET["family"])){
            $families = $_GET["family"];
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

$brands = array('schonbek');
$finishes = array('x');

function removeStopWords($input){
 
 	// Stop words
	$commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','just','keep','keeps','kept','know','known','knows','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','que','quite','qv','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','zero');
 
	return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
}

function getListOfTerms($searchTerm){
    $searchTerm = preg_replace('/[^A-Za-z0-9\s]/', ' ', $searchTerm); // Removes special chars.
    $searchTerm = trim(removeStopWords($searchTerm));
    $searchTerm = preg_replace('!\s+!', ' ', $searchTerm); //replacing multiple spaces with single
    $toReturn = explode(' ',$searchTerm);//get array of terms to search - splitting str on space
    return $toReturn;
}

function getsearchClause($term,$filter,$table,&$isFirst){
    global $conn;
    $test = "";
    if($table == NULL){
        $test = "select prod_id from prod_filters where $filter like '%$term%' limit 1;";
    }
    else{
        $test = "select prod_id from $table where $filter like '%$term%' limit 1;";
    }
    $result = $conn->query($test);
    $searchClause = "";
    if($result->num_rows > 0){
        //add where clause to query.
        if(!$isFirst){
            $searchClause .= "AND ";
        }
        if($table == NULL){
            $searchClause .= "$filter like '%$term%' ";
        }
        else{
            $searchClause .= "prod_id in (select prod_id from $table where $filter like '%$term%') ";
        }
        $isFirst = false;
    }
    return $searchClause;
}

function getFilterClause($filterName,$filterVals,$table){
    $filterClauses = "";
    if($filterVals != NULL){
        $filterClauses = "AND ";
        if($filterVals != NULL){
            if($table == null){
                $filterClauses .= "$filterName in (";
                $isFirst = true;
                foreach($filterVals as $val) {
                    if(!$isFirst){
                        $filterClauses .= ",";
                    }
                    $filterClauses .= "'$val'";
                    $isFirst = false;
                }
                $filterClauses .= ") ";
            }
            else{
                $filterClauses .= "prod_id in (select distinct prod_id from $table where $filterName in (";
                $isFirst = true;
                foreach($filterVals as $val) {
                    if(!$isFirst){
                        $filterClauses .= ",";
                    }
                    $filterClauses .= "'$val'";
                    $isFirst = false;
                }
                $filterClauses .= ")) ";
            }   
        }
    }
    return $filterClauses;
}

function getFilterCount($searchClause,$filter,$table){
    global $conn;
    global $brands, $families, $light_srcs, $finishes, $rooms;
    $filterCount = "";
    if($table == NULL){
        $filterCount = "select $filter, count(prod_id) as count from prod_filters where $searchClause ";
    }
    else{
        $filterCount = "select $filter, count(prod_id) as count from $table where prod_id in (select prod_id from prod_filters where $searchClause ";
    }
    if(strcmp($filter,"brand") == 0){
        $filterCount .= getFilterClause("family",$families,NULL);
        $filterCount .= getFilterClause("finish",$finishes,"finishes");
        $filterCount .= getFilterClause("room",$rooms,"rooms");
        $filterCount .= getFilterClause("light_src",$light_srcs,NULL);
    }
    if(strcmp($filter,"family") == 0){
        $filterCount .= getFilterClause("brand",$brands,NULL);
        $filterCount .= getFilterClause("finish",$finishes,"finishes");
        $filterCount .= getFilterClause("room",$rooms,"rooms");
        $filterCount .= getFilterClause("light_src",$light_srcs,NULL);
    }
    if(strcmp($filter,"finish") == 0){
        $filterCount .= getFilterClause("brand",$brands,NULL);
        $filterCount .= getFilterClause("family",$families,NULL);
        $filterCount .= getFilterClause("room",$rooms,"rooms");
        $filterCount .= getFilterClause("light_src",$light_srcs,NULL);
    }
    if(strcmp($filter,"room") == 0){
        $filterCount .= getFilterClause("brand",$brands,NULL);
        $filterCount .= getFilterClause("family",$families,NULL);
        $filterCount .= getFilterClause("finish",$finishes,"finishes");
        $filterCount .= getFilterClause("light_src",$light_srcs,NULL);
    }
    if(strcmp($filter,"light_src") == 0){
        $filterCount .= getFilterClause("brand",$brands,NULL);
        $filterCount .= getFilterClause("family",$families,NULL);
        $filterCount .= getFilterClause("finish",$finishes,"finishes");
        $filterCount .= getFilterClause("room",$rooms,"rooms");
    }
    if($table != NULL){
        $filterCount .= ") ";
    }
    $filterCount .= "group by $filter;";
    $countResult = $conn->query($filterCount);
    $count = 0;
    $rows = NULL;
    while($row = $countResult->fetch_assoc()){
        $rows[$count] = $row;
        $count++;
    }
    return $rows;
}

function search(){
    global $searchTerm,$conn;
    global $brands, $families, $light_srcs, $finishes, $rooms;
    $toReturn = new stdClass();
    $terms = getListOfTerms($searchTerm);
    $result;
    
    //search in db and get filter count for results.
    
    $searchClause = "";
    $isFirst = true;
    foreach($terms as $term){
        //search in category
        $searchClause .= getsearchClause($term,"category",NULL,$isFirst);
        //search in brand
        $searchClause .= getsearchClause($term,"brand",NULL,$isFirst);
        //search in family
        $searchClause .= getsearchClause($term,"family",NULL,$isFirst);
        //search in light source
        $searchClause .= getsearchClause($term,"light_src",NULL,$isFirst);
        //search in finish
        $searchClause .= getsearchClause($term,"finish","finishes",$isFirst);
        //if there is no result for any of the terms return no results
        if(strlen($searchClause) == 0){
            echo "No Results";
            $conn->close();
            return 0;
        }
    }
    if(strlen($searchClause) > 0){
        $sql = "select prod_id, family, img_url from prod_filters where " . $searchClause;
        $sql .= getFilterClause("brand",$brands,NULL);
        $sql .= getFilterClause("family",$families,NULL);
        $sql .= getFilterClause("finish",$finishes,"finishes");
        $sql .= getFilterClause("room",$rooms,"rooms");
        $sql .= getFilterClause("light_src",$light_srcs,NULL);
        $sql .= ";";
        $result = $conn->query($sql);
    }
    if($result->num_rows > 0){
        $count = 0;
        $rows = NULL;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                $rows[$count] = $row;
                $count++;
            }
        }
        $toReturn->products = $rows;
        $toReturn->brandCount = getFilterCount($searchClause,"brand",NULL);
        $toReturn->familyCount = getFilterCount($searchClause,"family",NULL);
        $toReturn->lightSrcCount = getFilterCount($searchClause,"light_src",NULL);
        $toReturn->finishCount = getFilterCount($searchClause,"finish","finishes");
        $toReturn->roomCount = getFilterCount($searchClause,"room","rooms");        
    }
    else{
        echo "No Results";
    }
    
    $conn->close();
    $toReturn->brandsFilters = $brands;
    $toReturn->familyFilters = $families;
    $toReturn->light_srcFilters = $light_srcs;
    $toReturn->finishFilters = $finishes;
    $toReturn->roomFilters = $rooms;
    echo json_encode($toReturn);
}

search();

?>