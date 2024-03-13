<?php
/* include    = include the file everytime you refresh the page
   include_once= include the file once only
   require    = same with include but will display error and stop the script
   require_once = same with include_once but will display error and stop the script

   */

require_once "Database.php";

class User extends Database
{
  # store() - save the record of a user
  public function store($request)
 {
   $first_name= $request['first_name'];
   $last_name = $request['last_name'];
   $username = $request['username'];
   $password = $request['password'];
 
   $password = password_hash($password,PASSWORD_DEFAULT);






   $sql = "INSERT INTO users (first_name,last_name, username,password) VALUES('$first_name','$last_name', '$username','$password')";

   if($this->conn->query($sql)){
     header('location: ../views'); #go to index.php or the login page
     exit;
   }else{
    die('Error creating the user: '. $this->conn->error);
   }

   }

  //  login() - login the account and redirect to dashboad
  public function login($request){

    $username = $request['username'];
    $password = $request['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";

    $result = $this->conn->query($sql);
  

    #check the username
    if($result->num_rows ==1){
      $user = $result->fetch_assoc();
        // ['id' =>1, 'first_name' =>'Goku' ,'last_name' =>'Son','username'=>'SonGoku','password'=>'$2y$10$Z2hK4dD3Y','photo'=>NULL ];
      // $user['id']-get the value1
      // $user['first_name']-get the value'Goku'
      // $user['last_name']-get the value'Son'
      // $user['username']-get the value'SonGoku'
      // $user['password']-get the value'$2y$10$Z2hK4dD3Y'

      


    #check the password if correct
    if(password_verify($password, $user['password'])){
    #Create session variables for future use._
    session_start();
    $_SESSION['id']       = $user['id'];//1
    $_SESSION['username'] = $user['username'];//'songoku'
    $_SESSION['full_name'] = $user['first_name']." ". $user['last_name'];

    header('location: ../views/dashboard.php');
    exit;
    } else{
       die('Password is incorrect');
    }
    } else{
      die('Username not found.');
    }
  }
 

// logout() -destroy or delete all sessions and redirect to login page

  public function logout(){
    session_start();
    session_unset();
    //remove the session
    session_destroy();

    header('location: ../views');
    exit;
  }

// getAllusers()-retrieves all users
public function getAllUsers()
{
  $sql = "SELECT id, first_name, last_name,username,photo FROM users";

  if($result =$this->conn->query($sql)){
    return $result;
  }else{
    die('Error retrieving all users:'.$this->conn->error);
  }
  }
  // getUser()-retrieves the record of the user
  public function getUser()
  {
    $id = $_SESSION['id'];

    $sql = "SELECT first_name, last_name, username,photo From users WHERE id =$id";

    if($result = $this->conn->query($sql)){
      return $result->fetch_assoc();
      // ['first_name' => 'Goku','Son => 'Goto','username'=>'SonGoku','photo'=>NULL]
    }else{
      die('Error retrieving the user: '. $this->conn->error);
    }
    }

    // update()-save the changes of the user
    public function update($request,$files)
    {
      session_start();
      $id    =$_SESSION['id'];
      $first_name =$request['first_name'];
      $last_name  =$request['last_name'];
      $username   =$request['username'];
      $photo      =$files['photo']['name'];
      $tmp_photo  =$files['photo']['tmp_name'];
      //  holds the actual image from temporary storage
      // ['photo']is the name of the form input file
      // ['name']is the actual name of the image
      
      $sql="UPDATE users SET first_name ='$first_name',last_name = '$last_name',username = '$username' WHERE id = $id";

      if($this->conn->query($sql)){
        $_SESSION['username'] = $username;
        $_SESSION['full_name'] = "first_name $last_name";

        #If there is an uploaded photo , save it to the db and the file to images folder.

      if($photo){
        $sql = "UPDATE users SET photo = '$photo' WHERE id =$id";
        $destination = "../assets/images/$photo";
         #Save the image name to do
        if($this->conn->query($sql)){
          # Save the file to images folder
          if(move_uploaded_file($tmp_photo,$destination)){
            header('location:../views/dashboard.php');
            exit;
          }else{
            die('Error moving the photo.');
          }
        } else {
            die('Error uploading photo: ' .$this->conn->error);
        }
      }

      header('location: ../views/dashboard.php');
      exit;
    }else{
      die('Error updating your account:'. $this->conn->error);
    }

   }
  
   public function delete()
   {
    
    session_start();
    $id = $_SESSION['id'];

    $sql = "DELETE FROM users WHERE id = $id";

    if($this->conn->query($sql)){
      $this->logout();
    }else{
      die('Error deleting your account:' . $this->conn->error);
    }
   }




}
?>





