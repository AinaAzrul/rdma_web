<?php
class Asset{
  
    // database connection and table Asset_no
    private $conn;
    private $table_name = "asset_list";
  
    // object properties
    // public $id;
    public $Asset_no;
    public $Asset_desc;
    public $Category;
    public $Location;
    public $Calib_no;
    public $Start_date;
    public $End_date;
    public $Company_name;
  
    public function __construct($db){
        $this->conn = $db;
    }
  
    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT * FROM " . $this->table_name . "
                ORDER BY Asset_no";
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }

    // used by select drop-down list
    public function read(){

        $query = "SELECT  a.Asset_no, Asset_desc,Category, Location,id, Calib_no, Start_date, End_date,Company_name
        FROM calibration_list c
        RIGHT JOIN asset_list a
        ON c.Asset_no = a.Asset_no
        ORDER BY  a.Asset_no DESC";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        return $stmt;
}

 // used by select drop-down list
 public function read_list(){

    $query = "SELECT *
    FROM asset_list
    ORDER BY Asset_no DESC";

    $stmt = $this->conn->prepare( $query );
    $stmt->execute();

    return $stmt;
}

// create asset
function create(){
    
    // query to insert record
    $query = "INSERT INTO " . $this->table_name . " SET  Asset_no=:Asset_no, Asset_desc=:Asset_desc, Category=:Category, Location=:Location";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
    
    // sanitize
    $this->Asset_no=htmlspecialchars(strip_tags($this->Asset_no));
    $this->Asset_desc=htmlspecialchars(strip_tags($this->Asset_desc));
    $this->Category=htmlspecialchars(strip_tags((string)$this->Category));
    $this->Location=htmlspecialchars(strip_tags((string)$this->Location));
  
    // bind values
    $stmt->bindParam(":Asset_no", $this->Asset_no);
    $stmt->bindParam(":Asset_desc", $this->Asset_desc);
    $stmt->bindParam(":Category", $this->Category);
    $stmt->bindParam(":Location", $this->Location);
    $stmt->execute();

    if($this->Calib_no){
       $this->add_calib();
}
    // execute query
    if(true){
        error_log("add_calib");
        return true;
    }
  
        return false;
}
  
//add calibration in asset
public function add_calib(){
     
    $query2 = "INSERT INTO calibration_list (Asset_no, Calib_no, Start_date,  End_date, Company_name )
    VALUES  (:Asset_no, :Calib_no,:Start_date, :End_date, :Company_name)";
			
            $stmt = $this->conn->prepare($query2);
			// sanitize
            $this->Asset_no=htmlspecialchars(strip_tags($this->Asset_no));
            $this->Calib_no=htmlspecialchars(strip_tags((string)$this->Calib_no));
            $this->Start_date=htmlspecialchars($this->Start_date);
            $this->End_date=htmlspecialchars($this->End_date);
            $this->Company_name=htmlspecialchars(strip_tags((string)$this->Company_name));
            
            // bind values
            $stmt->bindParam(":Asset_no", $this->Asset_no);
            $stmt->bindParam(":Calib_no",  $this->Calib_no);
            $stmt->bindParam(":Start_date", $this->Start_date);
            $stmt->bindParam(":End_date", $this->End_date);
            $stmt->bindParam(":Company_name", $this->Company_name); 

    // execute query
    if($stmt->execute()){

        return true;
    }
  
        return false;
}

// used when filling up the update asset form
function readOne(){
  
    // query to read single record
    $query = "SELECT * FROM
                " . $this->table_name . " 
            WHERE Asset_no = ?
            LIMIT 0,1";
    
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind Asset_no of asset to be updated
    $stmt->bindParam(1, $this->Asset_no);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract $row to access its values while true 
        extract($row);

        //explode the values in First_calib array into separate values
        $message = $row['First_calib'];
        $arr = explode(",", $message);
        $CalibDate_start = $arr[0];
        $CalibDate_end = $arr[1];
        $Company_name= $arr[2];

        $row['Asset_no'] ??= '0' ;
   
            //if there is Asset_no , return true
            if($row['Asset_no'] !='0'){

            // set values to object properties
            $this->id = $row['id'];
             $this->Asset_no = $row['Asset_no'];
            $this->Asset_no = $row['Asset_no'];
            $this->Asset_desc = $row['Asset_desc'];
            $this->Category = $row['Category'];
            $this->Location = $row['Location'];
            $this->CalibDate_start = $CalibDate_start;
            $this->CalibDate_end = $CalibDate_end;
            $this->Company_name = $Company_name;

        }

        else{
            return false;
        }
    }
}
// delete the asset
function delete(){
  
    // delete query by asset_no
    $query = "DELETE FROM " . $this->table_name . " WHERE Asset_no = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Asset_no=htmlspecialchars(strip_tags($this->Asset_no));
  
    // bind Asset_no of record to delete
    $stmt->bindParam(1, $this->Asset_no);
  
    // execute query
    $stmt->execute();
    //count the number of row afected by the query
    $count = $stmt->rowCount();

    //check is there any query successfully executed
    if($count != '0'){
        return true;
    }
    else{
        return false
    ;}
}

// delete the asset
function delete_calib(){
  
    // delete query by asset_no
    $query = "DELETE FROM calibration_list WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind Asset_no of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();
    //count the number of row afected by the query
    $count = $stmt->rowCount();

    //check is there any query successfully executed
    if($count != '0'){
        return true;
    }
    else{
        return false
    ;}
}


// search asset
function search($keywords,$id){

  //search by 2 different parameters 
    /*if(!empty($id) && !empty($keywords)){
     
    // select all query
    $query = "SELECT * FROM " . $this->table_name . " 
    WHERE (Asset_no LIKE ? OR Asset_desc LIKE ?) AND id LIKE ?
    ORDER BY id ASC";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $id=htmlspecialchars(strip_tags($id));
    $id = "%{$id}%";
    $keywords = "%{$keywords}%";
  
    // bind
    $stmt->bindParam(1, $keywords, PDO::PARAM_STR);
    $stmt->bindParam(2, $keywords, PDO::PARAM_STR);
    $stmt->bindParam(3, $id, PDO::PARAM_INT);
  
    // execute query
    $stmt->execute();
    } */

    //search by Asset_no/asset_desc only
    if($keywords && !empty($keywords)){
    // select all query
    $query = "SELECT * FROM " . $this->table_name . " 
    WHERE Asset_no LIKE ? OR Asset_desc LIKE ?
    ORDER BY id ASC";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
  
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
  
    // execute query
    $stmt->execute();
    }

    //Search by id no only
    elseif ($id && !empty($id)) {
        // select all query
    $query = "SELECT * FROM " . $this->table_name . " 
    WHERE id LIKE ?
    ORDER BY id ASC";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $id=htmlspecialchars(strip_tags($id));
    $id = "%{$id}%";
  
    // bind
    $stmt->bindParam(1, $id);

    // execute query
    $stmt->execute();
    }

    else{
        return false;
    }
    return $stmt;
}

// update an asset 
function updateAsset(){

    $query = "UPDATE
                " . $this->table_name . "
            SET
                Asset_no = :Asset_no,
                Asset_desc = :Asset_desc,
                Category = :Category,
                Location = :Location
            WHERE
            Asset_no = :Asset_no";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Asset_no = htmlspecialchars(strip_tags($this->Asset_no));
    $this->Asset_desc = htmlspecialchars(strip_tags($this->Asset_desc));
    $this->Category = htmlspecialchars(strip_tags($this->Category));
    $this->Location = htmlspecialchars(strip_tags($this->Location));
    
  
    // bind new values
    $stmt->bindParam(':Asset_no', $this->Asset_no);
    $stmt->bindParam(':Asset_desc', $this->Asset_desc);
    $stmt->bindParam(':Category', $this->Category);
    $stmt->bindParam(':Location', $this->Location);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}

// update an asset calibration
function updateCalib(){

    $query = "UPDATE
                calibration_list
            SET
                id = :id,
                Calib_no = :Calib_no,
                Start_date = :Start_date,
                End_date = :End_date,
                Company_name = :Company_name
            WHERE
            id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->Calib_no = htmlspecialchars(strip_tags($this->Calib_no));
    $this->Start_date = htmlspecialchars(strip_tags($this->Start_date));
    $this->End_date = htmlspecialchars(strip_tags($this->End_date));
    $this->Company_name = htmlspecialchars(strip_tags($this->Company_name));
    
  
    // bind new values
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':Calib_no', $this->Calib_no);
    $stmt->bindParam(':Start_date', $this->Start_date);
    $stmt->bindParam(':End_date', $this->End_date);
    $stmt->bindParam(':Company_name', $this->Company_name);

 
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}



// add/update calibration values
// function add_calib($Column_name,$new_calib){

//     //check if the column exist or not
//     $query="SHOW COLUMNS FROM " . $this->table_name . " LIKE '$Column_name'";
//     $stmt = $this->conn->prepare($query);
//     $stmt->execute();
//     $row1=$stmt->fetchColumn();

//     //if the column name is included
//     if($row1==true){   

//     switch($Column_name){
//         case "Second_calib":
//                  $query = "UPDATE " . $this->table_name . "
//                 SET Second_calib = :new_calib
//                 WHERE id = :id";

//             // prepare query statement
//             $stmt = $this->conn->prepare($query);

//             // sanitize
//             $this->id = htmlspecialchars(strip_tags($this->id));
//             $this->Second_calib = htmlspecialchars(strip_tags($new_calib));
   
//             // bind new values
//             $stmt->bindParam(':new_calib',$this->Second_calib); 
//             $stmt->bindParam(':id', $this->id);
//             break;

//         case "Third_calib":
//                 $query = "UPDATE " . $this->table_name . "
//                 SET Third_calib = :new_calib
//                 WHERE id = :id";

//             // prepare query statement
//             $stmt = $this->conn->prepare($query);

//             // sanitize
//             $this->id = htmlspecialchars(strip_tags($this->id));
//             $this->Third_calib = htmlspecialchars(strip_tags($new_calib));
   
//             // bind new values
//             $stmt->bindParam(':new_calib',$this->Third_calib); 
//             $stmt->bindParam(':id', $this->id);
//             break;

//     default:
//             break;
//     }

//         // execute the query
//         if($stmt->execute()){
//         return true;
//         }

//         else{
//             return false;
//         }
//   }

//   else{
//       return false;
//   }
// }

// read asset with pagination
public function readPaging($from_record_num, $records_per_page){
  
    // select query
    $query = "SELECT * FROM " . $this->table_name. " 
            ORDER BY id ASC
            LIMIT ?, ?";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
  
    // execute query
    $stmt->execute();
  
    // return values from database
    return $stmt;
}

// used for paging asset
public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name. "";
  
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    return $row['total_rows'];
}

}
?>