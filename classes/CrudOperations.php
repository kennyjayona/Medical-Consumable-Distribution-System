<?php

class CrudOperations
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function addItem($item_name, $item_quantity)
    {
        $stmt = $this->conn->prepare("INSERT INTO table_item (item_name, item_quantity) VALUES (?, ?)");
        $stmt->bindParam(1, $item_name, PDO::PARAM_STR);
        $stmt->bindParam(2, $item_quantity, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function editItem($itemId, $editedName, $editedQuantity)
    {
        $stmt = $this->conn->prepare("UPDATE table_item SET item_name = ?, item_quantity = ? WHERE id = ?");
        $stmt->bindParam(1, $editedName, PDO::PARAM_STR);
        $stmt->bindParam(2, $editedQuantity, PDO::PARAM_INT);
        $stmt->bindParam(3, $itemId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteItem($itemId)
    {
        $stmt = $this->conn->prepare("DELETE FROM table_item WHERE id = ?");
        $stmt->bindParam(1, $itemId, PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function addBarangay($brgy_name)
    {
        $stmt = $this->conn->prepare("INSERT INTO table_brgy (brgy_name) VALUES (?)");
        $stmt->bindParam(1, $brgy_name, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function editBarangay($brgyId, $editedName)
    {
        $stmt = $this->conn->prepare("UPDATE table_brgy SET brgy_name = ? WHERE id = ?");
        $stmt->bindParam(1, $editedName, PDO::PARAM_STR);
        $stmt->bindParam(2, $brgyId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getBarangayById($brgyId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM table_brgy WHERE id = ?");
        $stmt->bindParam(1, $brgyId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchItemsByBrgyId($barangayId)
    {
        $stmt = $this->conn->prepare("SELECT i.item_name, r.requested_quantity, r.status
                                      FROM request_table r
                                      LEFT JOIN table_item i ON r.item_id = i.id
                                      WHERE r.brgy_id = ?");
        
        $stmt->bindParam(1, $barangayId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }
}
?>
