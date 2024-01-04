<?php
// Include the SongBook model
include_once('../model/SongBook.php');

// Create a class named SongBookController
class SongBookController {

    // Property to hold the SongBook model instance
    protected $model;

    // Constructor method to initialize the model
    public function __construct(){
        $this->model = new SongBook();
    }

    // Method to handle incoming requests
    public function handleRequest() {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                // If the action is 'table', call the table method
                case 'table':
                    $this->table();
                    break;
                // If the action is 'save', call the save method with POST data
                case 'save':
                    $this->save($_POST);
                    break;
                // If the action is 'fetch', call the fetch method with the specified ID
                case 'fetch':
                    $this->fetch($_POST['id']);
                    break;
                // If the action is 'remove', call the remove method with the specified ID
                case 'remove':
                    $this->remove($_POST['id']);
                    break;
                // Default case for invalid actions
                default:
                    echo "Invalid action";
            }
        } else {
            echo "No action specified";
        }
    }

    // Method to retrieve songs and generate an HTML table
    public function table() {
        $result = $this->model->retrieve();

        $rows = array();
        while($row = $result->fetch_assoc()){
            $rows[] = $row;
        }

        echo json_encode($rows);
    }

    // Method to save a song based on POST data
    public function save()
    {
        $data = [
            'id'    =>  $_POST['id'],
            'title' =>  $_POST['title'],
            'artist' =>  $_POST['artist'],
            'lyrics' =>  $_POST['lyrics'],
        ];
        $result = $this->model->save($data);
        
        // Check if the save operation was successful
        if($result === true) {
            echo "Save Successfully!";
        } else {
            echo "Save Failed! " . $result;
        }
    }

    // Method to fetch a song based on the specified ID
    public function fetch()
    {
        $id = $_POST['id'];
        $result = $this->model->fetch($id);
        echo $result;
    }

    // Method to remove a song based on the specified ID
    public function remove()
    {
        $id = $_POST['id'];
        $result = $this->model->remove($id);

        // Check if the removal operation was successful
        if($result === true) {
            echo "Delete Successfully!";
        } else {
            echo "Save Failed! " . $result;
        }
    }

}

// Instantiate the class and handle the request
$songBookController = new SongBookController();
$songBookController->handleRequest();

?>
