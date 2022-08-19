<?php
// namespace rdma\model;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API Live Test",
 *     version="1.0"
 * )
 */




// 'user' object
class User{
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $role;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    /*
     * @OA\Get(
     *     path="/rdma_web/users/user.php", 
     * tags= {"Users"},
     *     @OA\Response
     *      ( response="200", description="Success" )
     *      ( response="404", description="No Users Found" )
     * 
     */
    


 // read users
  function read(){
  
        // select all query
        $query = "SELECT
        id, firstname, lastname, email, role
        FROM   " . $this->table_name . "
        ORDER BY
        id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
}

// create() method will be here
// create new user record
function create(){
 
    // insert query
    $query = "INSERT INTO " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password,
                role = :role";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->role=htmlspecialchars(strip_tags($this->role));
 
    // bind the values
    $stmt->bindParam(':firstname', $this->firstname);
    $stmt->bindParam(':lastname', $this->lastname);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':role', $this->role);
 
    // hash the password before saving to database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
 
    // execute the query, also check if query was successful
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
 

// emailExists() method will be here
// check if given email exist in the database
function emailExists(){
 
    // query to check if email exists
    $query = "SELECT id, firstname, lastname, password, role
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // bind given email value
    $stmt->bindParam(1, $this->email);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->password = $row['password'];
        $this->role = $row['role'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}
 
// update() method will be here
// update a user record
public function update(){
 
    // if password needs to be updated
    $password_set=!empty($this->password) ? ", password = :password" : "";
 
    // if no posted password, do not update the password
    $query = "UPDATE " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                role = :role
                {$password_set}
            WHERE id = :id";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->role=htmlspecialchars(strip_tags($this->role));
 
    // bind the values from the form
    $stmt->bindParam(':firstname', $this->firstname);
    $stmt->bindParam(':lastname', $this->lastname);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':role', $this->role);
 
    // hash the password before saving to database
    if(!empty($this->password)){
        $this->password=htmlspecialchars(strip_tags($this->password));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    }
 
    // unique ID of record to be edited
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

  

// delete the product
function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();

   $count = $stmt->rowCount();

if($count !='0'){
    return true;
}
else{
    return false;
}
}

// search users
function search($keywords){
  
    // select all query
    $query = "SELECT
                 id , firstname, lastname, email, role, created, modified
            FROM
                " . $this->table_name . " 
           WHERE
                firstname LIKE ? OR lastname LIKE ? 
            ORDER BY
                id DESC";
  
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
  
    return $stmt;
}


} 