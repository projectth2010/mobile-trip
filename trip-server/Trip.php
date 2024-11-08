<?php
include_once 'db.php';

class Trip
{
    private $conn;
    private $table_name = "trips";

    private $expenses_table = "trip_expenses"; // Table for trip expenses

    private $events_table = "trip_events"; // Table for trip events

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createTrip($user_id, $name, $destination, $start_date, $end_date, $latitude, $longitude)
    {
        $query = "INSERT INTO " . $this->table_name . " (user_id, name, destination, start_date, end_date,latitude ,longitude) VALUES (:user_id, :name, :destination, :start_date, :end_date, :latitude , :longitude)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':destination', $destination);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getTrips($user_id)
    {

        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);

        if ($user_id != "") {
            $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTrip($trip_id)
    {


        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :trip_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $trip_id);


        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function editTrip($trip_id, $name, $destination, $start_date, $end_date, $latitude, $longitude)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, destination = :destination, start_date = :start_date, end_date = :end_date, latitude = :latitude, longitude = :longitude 
                  WHERE id = :trip_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':trip_id', $trip_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':destination', $destination);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteTrip($trip_id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :trip_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $trip_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function closeTrip($trip_id)
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'closed' WHERE id = :trip_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $trip_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Trip Event Management
    public function createEvent($trip_id, $event_name, $description, $event_date)
    {
        $query = "INSERT INTO " . $this->events_table . " (trip_id, event_name, description, event_date) VALUES (:trip_id, :event_name, :description, :event_date)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':trip_id', $trip_id);
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':event_date', $event_date);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateEvent($event_id, $event_name, $description, $event_date)
    {
        $query = "UPDATE " . $this->events_table . " 
                  SET event_name = :event_name, description = :description, event_date = :event_date 
                  WHERE id = :event_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':event_date', $event_date);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteEvent($event_id)
    {
        $query = "DELETE FROM " . $this->events_table . " WHERE id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $event_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update Expense for a Trip
    public function updateExpense($trip_id, $expense_id, $amount, $description)
    {
        $query = "UPDATE " . $this->expenses_table . " 
                  SET amount = :amount, description = :description 
                  WHERE id = :expense_id AND trip_id = :trip_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':trip_id', $trip_id);
        $stmt->bindParam(':expense_id', $expense_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
