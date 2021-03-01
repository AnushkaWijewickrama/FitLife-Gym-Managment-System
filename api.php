<?php 
// Headers
header('Content-Type: application/json');

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

include 'Database.php';
include "CRUD.php";

$conn = getConnection();

//Login API
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // echo json_encode($data);

    $quary = " SELECT * FROM `users` WHERE email ='" . $email . "' && password = '" . $password . "' ";
    $results = getData($quary, $conn);


    if ($results->num_rows > 0) {
        while ($row = $results-> fetch_assoc()) {
        $member = [
            'id' => $row["id"],
            'firstName' => $row["firstName"],
            'lastName' => $row["lastName"],
            'email' =>  $row["email"],
            'address' => $row["address"],
            'teleNo' => $row["teleNo"],
            'age' => $row["age"],
            'bloodType' => $row["bloodType"],
            'auth' => true
        ];
    }
        echo json_encode($member);
        // SHOULD SEND MEMBER OBJECT
    } else {
        $member[] = [
            "auth" => false,
            "username" => $email
        ];
        echo json_encode($member);
    }
}
//Login API
if (isset($_POST['adminlogin'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // echo json_encode($data);

    $quary = " SELECT * FROM `admin` WHERE email ='" . $email . "' && password = '" . $password . "' ";
    $results = getData($quary, $conn);


    if ($results->num_rows > 0) {
        while ($row = $results-> fetch_assoc()) {
            $user = [
                'firstName' => $row["firstName"],
                'lastName' => $row["lastName"],
                'email' =>  $row["email"],
                'address' => $row["address"],
                'TeleNo' => $row["TeleNo"],
       
            ];
           }  
            echo json_encode($user);
        // SEND GYM OBJECT
    } else {
        $data[] = [
            "login" => 'fail',
            "username" => $email
        ];
        echo json_encode($data);
    }
}
//Register API
if (isset($_POST['register'])) {

    //$id = $_GET['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $teleNo = $_POST['teleNo'];
    $age = $_POST['age'];
    $bloodType = $_POST['bloodType'];

    // need to check if username already exists before registering - do it later

    $query = "INSERT INTO users (firstName, lastName, email, password, address, teleNo, age, bloodType) 
    VALUES ('" .$firstName."','".$lastName."','".$email."','".$password."','".$address."','".$teleNo ."','".$age ."','".$bloodType ."')";
    
   
    if (insertData($query, $conn)) {
        $data[] = [
            "repoterid" => $email,
            "success" => "true"
        ];
        echo json_encode($data);
    } else {
        $data[] = [
            "repoterid" => $email,
            "success" => "true"
        ];
        echo json_encode($data);
    }

    $conn->close();
    
}

//viewMemberByID API
if (isset($_GET['user'])) {

    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id=" . $id;
    $results = getData($query, $conn);

    if ($results->num_rows > 0) {

        while ($row = $results->fetch_assoc()) {

            $user = [
                'firstName' => $row["firstName"],
                'lastName' => $row["lastName"],
                'userName' =>  $row["email"],
                'password' => $row["password"],
                'address' => $row["address"],
                'teleNo' => $row["teleNo"],
                'age' => $row["age"],
                'bloodType' => $row["bloodType"]

            ];
            echo json_encode($user);
            
        }


        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
        );
    }
    $conn->close();
}
//deleteMember API
if (isset($_POST['user'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM users WHERE id=" . $id;

    if (deleteData($query, $conn)) {
        $data[] = [
            "userId" => $id,
            "success" => "true"
        ];
        echo json_encode($data);
    } else {
        $data[] = [
            "userId" => $id,
            "success" => "false"
        ];
        echo json_encode($data);
    }

    $conn->close();
}
//UpdateProfile API
if (isset($_POST['profile'])) {

    $userId = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    //$password = $_POST['password'];
    $address = $_POST['address'];
    $teleno = $_POST['teleNo'];
    $age = $_POST['age'];
    $bloodType = $_POST['bloodType'];
   
    $query = "UPDATE users SET firstName = $firstName , lastName= $lastName , email = $email , address= $address , teleNo= $teleno , age= $age , bloodType= $bloodType  WHERE id = $userId ";
  //echo json_encode($query);
  //$query ="UPDATE users ". "SET emp_salary = $emp_salary ". "WHERE emp_id = $emp_id" ;
     
    // ADD REPORTER ID

     if (updateData($query, $conn)) {
       $data[] = [
            "memberId" => $userId,
            "success" => "true"
        ];
         echo json_encode($data);
     } else {
         $data[] = [
             "memberId" => $userId,
             "success" => "false"
         ];
         echo json_encode($data);
     }

     $conn->close();
}
//UpdateProfileByMobile API
if (isset($_POST['mprofile'])) {

    $userId = $_POST['userId'];
    $address = $_POST['address'];
    $teleno = $_POST['teleNo'];
    $age = $_POST['age'];
   
   
    $query = "UPDATE users SET address= '".$address."' , teleNo= '".$teleno."' , age= '".$age."' WHERE id = $userId ";
    //echo json_encode($query);
    //$query ="UPDATE users ". "SET emp_salary = $emp_salary ". "WHERE emp_id = $emp_id" ;
     
    // ADD REPORTER ID

      if (updateData($query, $conn)) {

        $member = getMemberById ($userId, $conn);

        $data = [
             "memberId" => $userId,
             "success" => "true",
             "member" => $member
         ];

         echo json_encode($data);
      } else {
          $data[] = [
              "memberId" => $userId,
              "success" => "false"
          ];
          echo json_encode($data);
      }

     $conn->close();
}
//error

//addSchedule API
if (isset($_POST['schedule'])) {

    $userId = $_POST['userId'];
    $instructorId = $_POST['instructorId'];
    $workoutId = $_POST['workoutId'];
    $date = $_POST['date'];
    $time = $_POST['time'];
  
    $query = "INSERT INTO schedule (userId, instructorId, workoutId, date, time) 
    VALUES ('" . $userId . "','" . $instructorId . "','" .$workoutId. "','" .$date. "','" .$time. "')";
    //var_dump($quary);

    if (insertData($query, $conn)) {
        $data[] = [
            "userId" => $userId,
            "success" => "true"

        ];
        echo json_encode($data);
    } else {
        $data[] = [
            "userId" => $userId,
            "success" => "false"
        ];
        echo json_encode($data);
    }


    $conn->close();
}

//ViewProfileByMemberId API
if(isset($_GET['profile'])) {
  
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id=".$id;
    $results = getData($query,$conn);

  if ($results->num_rows > 0) {

        while ($row = $results-> fetch_assoc()) {

            $profile = [
                'firstName' => $row["firstName"],
                'lastName' => $row["lastName"],
                'email' =>  $row["email"],
                'password' => $row["password"],
                'address' => $row["address"],
                'teleNo' => $row["teleNo"],
                'age' => $row["age"],
                'bloodType' => $row["bloodType"],
                'auth' => true
            ];
        }
        
        
        echo json_encode($profile);
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}

//viewAllProfile API
if(isset($_GET['profiles'])) {
    
    $query = "SELECT * FROM users";
    $result = getData($query,$conn);
    

    if ($result->num_rows > 0) {

        $profile= array();
        while ($row = $result-> fetch_assoc()) {

            $user = [
                'firstName' => $row["firstName"],
                'lastName' => $row["lastName"],
                'email' =>  $row["email"],
                'password' => $row["password"],
                'address' => $row["address"],
                'teleNo' => $row["teleNo"],
                'age' => $row["age"],
                'bloodType' => $row["bloodType"],
            ];

            array_push($profile,$user);
            
        }
        echo json_encode($profile);
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}

//viewScheduleByUserId API
if(isset($_GET['schedule'])) {
    $instructorId = $_GET['instructorId'];
    $workoutId = $_GET['workoutId'];
    $userId = $_GET['userId'];
    $query = "SELECT * FROM schedule WHERE userId=".$userId;
    $results = getData($query,$conn);
    $workout = getWorkoutById($workoutId,$conn);
    $instructor= getInstructorById($instructorId,$conn);
   
  if ($results->num_rows > 0) {
   
       $schedule = array();
        while ($row = $results-> fetch_assoc()) {
            echo 'g';
            $workout = [
                'userId' => $row["userId"],
                'instructorId' => $row["instructorId"],
                'workoutId' => $row["workoutId"],
                'date' => $row["date"],
                'time' => $row["time"],
                'workoutName' => $workout,
                'instructorusername' => $instructor
                
            ];
            array_push($schedule, $workout);
        }
        echo json_encode($schedule);
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    
    $conn->close();
}
//viewAllSchedule API
if(isset($_GET['schedules'])) {
    
    $query = "SELECT * FROM schedule";
    $result = getData($query,$conn);
    

    if ($result->num_rows > 0) {

        $schedule = array();
        while ($row = $result-> fetch_assoc()) {

            $workout = [
                'userId' => $row["userId"],
                'instructorId' => $row["instructorId"],
                'workoutId' =>  $row["workoutId"],
                'date' => $row["date"],
                'time' => $row["time"]
            ];

            array_push($schedule,$workout);
            
        }
        echo json_encode($schedule);
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}

//updateScheduleByMemberId API
if(isset($_POST['schedule'])) {
   
    $userId = $_POST('userId');
    $instructorId = $_POST('instructorId');
    $workoutId = $_POST('workoutId');
    $date = $_POST('date');
    $time = $_POST('time');

    $query = "UPDATE schedule SET(userId ='" .$userId."' , instructorId='".$instructorId."' workoutId='".$workoutId."', date='".$date."', time='".$time."')
     WHERE userId ='" .$userId."'"; 
   

    if ( insertData($query, $conn) ) {
        $data [] = [
            "repoterid" => $userId,
            "success" => "true"
        ];
        echo json_encode($data);
    }else {
        $data [] = [
            "repoterid" => $userId,
            "success" => "true"
        ];
        echo json_encode($data);
    }

    $conn->close();

}

//add workout API
if (isset($_POST['workout'])) {

    $name = $_POST['name'];
   
    $query = "INSERT INTO workout (name) 
    VALUES ('" . $name ."')";

    if (insertData($query, $conn)) {
        $data[] = [
            
            "workoutname" => $name,
            "success" => true

        ];
        echo json_encode($data);
    } else {
        $data[] = [
            "workoutname" => $name,
            "success" => false
        ];
        echo json_encode($data);
    }

    $conn->close();
}
//viewAllWorkout API
if(isset($_GET['workouts'])) {
    
    $query = "SELECT * FROM workout";
    $result = getData($query,$conn);
    $workouts= array();

    if ($result->num_rows > 0) {

        
        while ($row = $result-> fetch_assoc()) {

            $workout = [
                'id' => $row["id"],
                'name' => $row["name"],
            ];

            array_push($workouts,$workout);
            
        }
        echo json_encode($workouts);
        
    } else {
        echo json_encode(
            array('message' => 'no workout found')
          );
    }
    $conn->close();
}
//viewWorkoutById
if(isset($_GET['workout'])) {
  
    $id = $_GET['id'];
    $query = "SELECT * FROM workout WHERE id=".$id;
    $results = getData($query,$conn);

  if ($results->num_rows > 0) {

        while ($row = $results-> fetch_assoc()) {

            $workout = [
                'id' => $row["id"],
                'name' => $row["name"]
            ];
            echo json_encode($workout);
        }
        
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}
//AddMemberMeasurements API
if (isset($_POST['addmeasurement'])) {

    $userId = $_POST['userId'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $waist = $_POST['waist'];
    $chest = $_POST['chest'];
    $shoulder = $_POST['shoulder'];
    $arm = $_POST['arm'];
    $thigh = $_POST['thigh'];
    $calf = $_POST['calf'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
 
    $query = "INSERT INTO measurement (userId,weight,height,waist,chest,shoulder,arm,thigh,calf,startDate,endDate) 
    VALUES ('" . $userId ."','" . $weight ."','" . $height ."','" . $waist ."','" . $chest ."','" . $shoulder ."','" . $arm ."','" . $thigh ."','" . $calf ."','" . $startDate ."','" . $endDate ."')";
    //var_dump($quary);

    if (insertData($query, $conn)) {
        $data = [
            "success" => "true",
            "messurement" =>   [
                'userId' => $userId,
                'weight' => $weight,
                'height' => $height,
                'waist' => $waist,
                'chest' => $chest,
                'shoulder' => $shoulder,
                'arm' => $arm,
                'thigh' => $thigh,
                'calf' => $calf,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]

        ];
        echo json_encode($data);
    } else {
        $data[] = [
            "userId" => $userId,
            "success" => "false"
        ];
        echo json_encode($data);
    }


    $conn->close();
}
//viewMemberMeasurements API
if(isset($_GET['measurement'])) {
  
    $userId = $_GET['userId'];
    $query = "SELECT * FROM measurement WHERE userId=".$userId;
    $results = getData($query,$conn);

  if ($results->num_rows > 0) {
        
    $measurements=array();
        while ($row = $results-> fetch_assoc()) {
            $measurement = [
                'userId' => $row["userId"],
                'weight' => $row["weight"],
                'weight' => $row["weight"],
                'height' => $row["height"],
                'waist' => $row["waist"],
                'chest' => $row["chest"],
                'shoulder' => $row["shoulder"],
                'arm' => $row["arm"],
                'thigh' => $row["thigh"],
                'calf' => $row["calf"],
                'startDate' => $row["startDate"],
                'endDate' => $row["endDate"],
                'auth' => true
                
            ];
            array_push($measurements,$measurement);
            
        }
        echo json_encode($measurements);
        
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
            
        );
    }
    $conn->close();
}
//viewMembersMeasurements API
if(isset($_GET['measurements'])) {
  
    $query = "SELECT * FROM measurement";
    $results = getData($query,$conn);
    

  if ($results->num_rows > 0) {
        $measurements=array();
        while ($row = $results-> fetch_assoc()) {

            $measurement = [
                'userId' => $row["userId"],
                'instructorId' => $row["instructorId"],
                'waist' => $row["waist"],
                'chest' => $row["chest"],
                'shoulder' => $row["shoulder"],
                'arm' => $row["arm"],
                'thigh' => $row["thigh"],
                'calf' => $row["calf"]
            ];
           array_push($measurements,$measurement);
        }
        echo json_encode($measurements);
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}
//addPayment API
if (isset($_POST['payment'])) {

    $memberId = $_POST['memberId'];
    $instructorId = $_POST['instructorId'];
    $price = $_POST['price'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $query = "INSERT INTO payment (memberId, instructorId, price, date, time) 
    VALUES ('" . $memberId ."','" . $instructorId ."','" . $price ."','" . $date ."','" . $time ."')";
    //var_dump($quary);

    if (insertData($query, $conn)) {
        $data[] = [
            "memberId" => $memberId,
            "success" => "true"

        ];
        echo json_encode($data);
    } else {
        $data[] = [
            "memberId" => $memberId,
            "success" => "false"
        ];
        echo json_encode($data);
    }


    $conn->close();
}
//viewPayments API 
if(isset($_GET['payments'])) {
  
    $query = "SELECT * FROM payment";
    $results = getData($query,$conn);
    

  if ($results->num_rows > 0) {
        $payment=array();
        while ($row = $results-> fetch_assoc()) {

            $user = [
                'memberId' => $row["memberId"],
                'instructorId' => $row["instructorId"],
                'price' => $row["price"],
                'date' => $row["date"],
                'time' => $row["time"]
               
            ];
           array_push($payment,$user);
        }
        echo json_encode($payment);
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}
//viewPaymentByUserId API 
if(isset($_GET['payment'])) {
    $memberId = $_GET['memberId'];
    $query = "SELECT * FROM payment WHERE memberId=".$memberId;
   
    $results = getData($query,$conn);
    

  if ($results->num_rows > 0) {
    $payment=array();
        while ($row = $results-> fetch_assoc()) {

            $user = [
                'memberId' => $row["memberId"],
                'instructorId' => $row["instructorId"],
                'price' => $row["price"],
                'date' => $row["date"],
                'time' => $row["time"]
               
            ];
            array_push($payment,$user);
        }
        echo json_encode($payment);
        
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
  
    $conn->close();
}
//addNote API
if (isset($_POST['note'])) {

    $userId = $_POST['userId'];
    $instructorId = $_POST['instructorId'];
    $note = $_POST['note'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    
    $query = "INSERT INTO notes (userId,instructorId,note,date,time) 
    VALUES ('" . $userId ."','" . $instructorId ."','" . $note ."','" . $date ."','" . $time ."')";
    //var_dump($quary);

    if (insertData($query, $conn)) {
        $data[] = [
            "workoutname" => $userId,
            "success" => "true"
        ];
        // send NOTE
        echo json_encode($data);
    } else {
        $data[] = [
            "workoutname" => $userId,
            "success" => "false"
        ];
        echo json_encode($data);
    }


    $conn->close();
}
//viewNotes API
if(isset($_GET['notes'])) {
  
    $query = "SELECT * FROM notes";
    $results = getData($query,$conn);
    

  if ($results->num_rows > 0) {
        $notes=array();
        while ($row = $results-> fetch_assoc()) {

            $note = [
                'memberId' => $row["memberId"],
                'instructorId' => $row["instructorId"],
                'price' => $row["price"],
                'note' => $row["note"],
                'time' => $row["time"]
               
            ];
           array_push($notes,$note);
        }
        echo json_encode($notes);
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}
//viewNoteByUserId API
if(isset($_GET['note'])) {
  
    $userId = $_GET['userId'];
    $query = "SELECT * FROM notes WHERE id=".$userId;
    $results = getData($query,$conn);

  if ($results->num_rows > 0) {

        while ($row = $results-> fetch_assoc()) {

            $note = [
                'userId' => $row["userId"],
                'instructorId' => $row["instructorId"],
                'note' => $row["note"],
                'date' => $row["date"],
                'time' => $row["time"]
               
            ];
            echo json_encode($note);
        }
        
        
    } else {
        echo json_encode(
            array('message' => 'Not Found')
          );
    }
    $conn->close();
}



//helper functions

//get user (member) by id (returns user object)

function getMemberById ($id, $conn) {

    $query = "SELECT * FROM users WHERE id=" . $id;
    $results = getData($query, $conn);

    if ($results->num_rows > 0) {

        while ($row = $results->fetch_assoc()) {

            $user = [
                'firstName' => $row["firstName"],
                'lastName' => $row["lastName"],
                'userName' =>  $row["email"],
                'password' => $row["password"],
                'address' => $row["address"],
                'teleNo' => $row["teleNo"],
                'age' => $row["age"],
                'bloodType' => $row["bloodType"]

            ];
        }
        return $user;

        
    } else {
        return false;
    }
}
function getWorkoutById ($id, $conn) {
  
        $query = "SELECT * FROM workout WHERE id=".$id;
        $results = getData($query,$conn);
    
      if ($results->num_rows > 0) {
    
            while ($row = $results-> fetch_assoc()) {
    
                $workout = [
                    
                    'name' => $row["name"]
                ];
                
            }
            return $workout;
            
            
        } else {
            return false;
        }
        
    

}
function getInstructorById ($id, $conn) {
  
    $query = "SELECT * FROM instructor WHERE id=".$id;
    $results = getData($query,$conn);

  if ($results->num_rows > 0) {

        while ($row = $results-> fetch_assoc()) {

            $instructor = [
                'id' => $row["id"],
                'userName' => $row["userName"]

            ];
            return $instructor;
        }
        
        
    } else {
      return false;
    }
   


}
?>
 