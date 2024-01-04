<?php
// Include the database configuration file
include_once('../config/db.php');

// Create a class named SongBook, extending the Database class
class SongBook extends Database {

    // Property to hold the database connection
    protected $con;

    // Constructor method to initialize the database connection
    public function __construct(){
        $this->con = $this->connect();
    }

    // Method to retrieve all songs from the database
    public function retrieve() {
        $sql = " SELECT * FROM t_song ";

        $result = $this->con->query($sql);

        // Check if the query was successful
        if ($result === false) {
            return $this->con->error;
        }

        return $result;
    }

    // Method to fetch a specific song based on its ID
    public function fetch($id) {
        $sql = " SELECT * FROM t_song WHERE id = '".mysqli_real_escape_string($this->con, $id)."' LIMIT 1 ";

        $result = $this->con->query($sql);

        // Check if the query was successful
        if ($result === false) {
            return $this->con->error;
        }

        // Return the fetched record as JSON
        return json_encode($result->fetch_assoc());
    }

    // Method to remove a song from the database based on its ID
    public function remove($id) {
        $sql = " DELETE FROM t_song WHERE id = '".mysqli_real_escape_string($this->con, $id)."' ";

        $result = $this->con->query($sql);

        // Check if the query was successful
        if ($result === false) {
            return $this->con->error;
        }

        return $result;
    }

    // Method to save or update a song in the database
    public function save($data) {
        $id = mysqli_real_escape_string($this->con, $data['id']);
        $title = mysqli_real_escape_string($this->con, $data['title']);
        $artist = mysqli_real_escape_string($this->con, $data['artist']);
        $lyrics = mysqli_real_escape_string($this->con, $data['lyrics']);
        $date = date('Y-m-d H:i:s');

        // Check if the song is being inserted or updated
        if($id == '') {
            $sql = " INSERT INTO t_song (title, artist, lyrics, created_at) VALUES ('".$title."','".$artist."','".$lyrics."','".$date."'); ";
        } else {
            $sql = " UPDATE t_song SET title = '".$title."', artist = '".$artist."', lyrics = '".$lyrics."', updated_at = '".$date."' WHERE id = '".$id."' ";
        }

        $result = $this->con->query($sql);

        // Check if the query was successful
        if ($result === false) {
            return $this->con->error;
        }

        return $result;
    }

}

?>
