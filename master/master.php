<?php
class Master
{
    // database connection and table name
    private $conn;
    private $table_name = "master_list";

    // object properties
    public $Entry_id;
    public $Asset_no;
    public $Asset_desc;
    public $Taken_by;
    public $Date_taken;
    public $Return_by;
    public $Date_return;
    public $Remarks;
    public $Category;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read masters
    function read()
    {
        // select all query
        //     $query = "SELECT
        //     m.Entry_id, a.Asset_no as Asset_no, m.Asset_desc, m.Taken_by, m.Date_taken, m.Return_by, m.Date_return, m.Remarks, a.Category as Category
        //      FROM   " . $this->table_name . " m
        //  LEFT JOIN asset_list a ON m.Asset_no = a.Asset_no
        // ORDER BY
        //     m.Entry_id ASC";

        $query =
            "SELECT
    Entry_id, Asset_no, Asset_desc, Taken_by, Date_taken, Return_by, Date_return, Remarks, Category
     FROM   " .
            $this->table_name .
            " ORDER BY Entry_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create master
    function create()
    {
        // query to insert record
        $query =
            "INSERT INTO
                " .
            $this->table_name .
            "
            SET
                Asset_no=:Asset_no, 
                Asset_desc=:Asset_desc, 
                Taken_by=:Taken_by, 
                Date_taken=:Date_taken, 
                Return_by=:Return_by,
                Date_return=:Date_return,
                Remarks=:Remarks,
                Category=:Category ";

        // prepare query
        $stmt = $this->conn->prepare($query);
        error_log($this->Date_taken);

        // sanitize
        $this->Asset_no = htmlspecialchars(strip_tags($this->Asset_no));
        $this->Asset_desc = htmlspecialchars(strip_tags($this->Asset_desc));
        $this->Taken_by = htmlspecialchars(strip_tags($this->Taken_by));
        $this->Date_taken = htmlspecialchars(strip_tags($this->Date_taken));
        $this->Return_by = htmlspecialchars(
            strip_tags((string) $this->Return_by)
        );
        $this->Date_return = htmlspecialchars(strip_tags($this->Date_return));
        $this->Remarks = htmlspecialchars(strip_tags((string) $this->Remarks));
        $this->Category = htmlspecialchars(
            strip_tags((string) $this->Category)
        );

        // bind values
        $stmt->bindParam(":Asset_no", $this->Asset_no);
        $stmt->bindParam(":Asset_desc", $this->Asset_desc);
        $stmt->bindParam(":Taken_by", $this->Taken_by);
        $stmt->bindParam(":Date_taken", $this->Date_taken);
        $stmt->bindParam(":Return_by", $this->Return_by);
        $stmt->bindParam(":Date_return", $this->Date_return);
        $stmt->bindParam(":Remarks", $this->Remarks);
        $stmt->bindParam(":Category", $this->Category);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // used when filling up the update master form
    function readOne()
    {
        // query to read single record
        $query =
            "SELECT *
            FROM
                " .
            $this->table_name .
            " 
            WHERE
                Asset_no = ?
            LIMIT
                0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of master to be updated
        $stmt->bindParam(1, $this->Asset_no);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $row["Entry_id"] ??= "0";

        //if there is id Entry_id, return true
        if ($row["Entry_id"] != "0") {
            // set values to object properties
            $this->Entry_id = $row["Entry_id"];
            $this->Asset_desc = $row["Asset_desc"];
            $this->Taken_by = $row["Taken_by"];
            $this->Date_taken = $row["Date_taken"];
            $this->Return_by = $row["Return_by"];
            $this->Date_return = $row["Date_return"];
            $this->Remarks = $row["Remarks"];
            $this->Category = $row["Category"];
        } else {
            return false;
        }
    }

    // update the product
    function update()
    {
        // update query
        $query =
            "UPDATE
                " .
            $this->table_name .
            "
            SET
                Asset_no = :Asset_no,
                Asset_desc = :Asset_desc,
                Taken_by = :Taken_by,
                Date_taken = :Date_taken,
                Return_by = :Return_by,
                Date_return = :Date_return,
                Remarks = :Remarks,
                Category = :Category

            WHERE
                Entry_id = :Entry_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->Asset_no = htmlspecialchars(strip_tags($this->Asset_no));
        $this->Asset_desc = htmlspecialchars(strip_tags($this->Asset_desc));
        $this->Taken_by = htmlspecialchars(strip_tags($this->Taken_by));
        $this->Date_taken = htmlspecialchars(strip_tags($this->Date_taken));
        $this->Return_by = htmlspecialchars(strip_tags($this->Return_by));
        $this->Date_return = htmlspecialchars(strip_tags($this->Date_return));
        $this->Remarks = htmlspecialchars(strip_tags($this->Remarks));
        $this->Category = htmlspecialchars(strip_tags($this->Category));
        $this->Entry_id = htmlspecialchars(strip_tags($this->Entry_id));

        // bind new values
        $stmt->bindParam(":Asset_no", $this->Asset_no);
        $stmt->bindParam(":Asset_desc", $this->Asset_desc);
        $stmt->bindParam(":Taken_by", $this->Taken_by);
        $stmt->bindParam(":Date_taken", $this->Date_taken);
        $stmt->bindParam(":Return_by", $this->Return_by);
        $stmt->bindParam(":Date_return", $this->Date_return);
        $stmt->bindParam(":Remarks", $this->Remarks);
        $stmt->bindParam(":Category", $this->Category);
        $stmt->bindParam(":Entry_id", $this->Entry_id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // delete the product
    function delete()
    {
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE Entry_id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->Entry_id = htmlspecialchars(strip_tags($this->Entry_id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->Entry_id);

        // execute query
        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count != "0") {
            return true;
        } else {
            return false;
        }
    }

    // search products
    function search($keywords)
    {
        // select all query
        $query =
            "SELECT
                 Entry_id , Asset_no, Asset_desc, Taken_by, Date_taken, Return_by, Date_return, Remarks, Category
            FROM
                " .
            $this->table_name .
            " 
           WHERE
                Asset_no LIKE ? OR Asset_desc LIKE ? OR Category LIKE ?
            ORDER BY
                Entry_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page)
    {
        // select query
        $query =
            "SELECT
                Entry_id , Asset_no, Asset_desc, Taken_by, Date_taken, Return_by, Date_return, Remarks, Category
            FROM
                " .
            $this->table_name .
            " 
            ORDER BY Entry_id DESC
            LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    // used for paging products
    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["total_rows"];
    }
}
?>
