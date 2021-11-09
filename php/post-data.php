<?php
  // Db connection settings
  $servername = "***********";
  $dbname = "**********";
  $username = "************";
  $password = "************";
  $api_key_value = "************";
  $api_key_value2 = "************";

  $api_key = $value1 = $value2 = $value3 = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value)
    {
      $value1 = test_input($_POST["value1"]);
      $value2 = test_input($_POST["value2"]);
      $value3 = test_input($_POST["value3"]);
        
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error)
      {
        die("Connection failed: " . $conn->connect_error);
      } 
        
      $sql = "INSERT INTO Sensor (value1, value2, value3)
      VALUES ('" . $value1 . "', '" . $value2 . "', '" . $value3 . "')";
    
      if ($conn->query($sql) === TRUE)
      {
        echo "New record created successfully";
      } 
      else
      {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    
      $conn->close();
    }
    elseif ($api_key == $api_key_value2)
    {
        $value1 = test_input($_POST["value1"]);
        $value2 = test_input($_POST["value2"]);
        $value3 = test_input($_POST["value3"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO SensorSzymon (value1, value2, value3)
        VALUES ('" . $value1 . "', '" . $value2 . "', '" . $value3 . "')";
        
        if ($conn->query($sql) === TRUE)
        {
            echo "New record created successfully";
        } 
        else
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();  
    }
    else
    {
      echo "Błędny klucz API";
    }
  }
  else
  {
    echo "Nie przesłano żadnych danych.";
  }

  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>