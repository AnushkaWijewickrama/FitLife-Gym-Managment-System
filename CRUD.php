<?php 

// Insert Data
function insertData($query, $conn) {
    return $conn->query($query);
}

// Get Data returns dataset
function getData($query, $conn){
    return $conn->query($query);
}

// Delete Data
function deleteData($query, $conn) {
    return $conn->query($query);
}

//Update Data
function updateData($query, $conn) {
    return $conn->query($query);
}
?>