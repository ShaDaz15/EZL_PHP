<?php

///Simplifies database calls and operations typically associated with data flow
class BD_Exchange {

    private $ready;
    private $dbc;
    private $allowance;

    //Custom mysql lexer strings?
    const EXT = array(
    'v' => 'NOT ',
    'n' => 'NULL ',
    //'i' => 'INT NULL',
    'z' => 'INT ',
    'x' => 'AUTO_INCREMENT ',
    'c' => 'TEXT ',
    //'tn' => 'TEXT NULL',
    'i' => 'INSERT INTO ',
    'd' => 'DATE ',
    //'dn' => 'DATE NULL',
    //'dnn' => 'DATE NOT NULL',
    's' => 'SELECT ',
    'a' => 'ALTER ',
    'c' => 'CREATE ',
    't' => 'TABLE ',
    '*' => '* '
    //'dnn' => 'DATE NOT NULL'
    );

    //function __construct(){
    //    //require 'db_connection.php';
    //    //if($dbc){
    //    //    $this->ready = true;
    //    //    $this->dbc = $dbc;
    //    //} else {
    //    //    $this->ready = false;
    //    //}
    //    //$dbc = $this->dbc;
    //}

    function is_ready(){ return $this->ready; }

    //Creates a new user, assuming args: 0-username, 1-password, 2-password confirmation
    function create_new_user($args){
        if($args[1] != $args[2]){
            echo "Passwords do not match!";
            return;
        } else {

            $dbc = $this->dbc;

            $response = mysqli_query($dbc, "SELECT * FROM `users` WHERE `username` LIKE '" . $args[0] ."';");
            if(!$response){
                echo "Username taken!";
                return;
            } else {
				//$this->create_table([$args[0], 'id', 'inn', 'post_title', 'tnn', 'post_body', 'tnn', 'submitted']) "INSERT INTO `users` (`username`, `password`) VALUES ('$user', '$pass');"
                mysqli_query($dbc, "INSERT INTO `users` (`username`, `password`) VALUES ('" . $args[0] . "', '" . $args[1] . "');");

            }
        }
    }

    //Handles blog posts, assuming args: 0-user, 1-post_title, 2-post_body
    function post_to_blog($args){
        $dbc = $this->dbc;

        if(isset($_SESSION['user'])){
            if($_SESSION['user'] === $args[0]){
                mysqli_query($dbc, "INSERT INTO `phpsite`.`" . $args[0] . "_blog" . "` (`id`, `post_title`, `post_body`, `submitted`) VALUES (NULL, '" . $args[1] . "', '" . $args[2] . "', CURRENT_TIMESTAMP);") or die (mysqli_error($dbc));
            }
        }
    }

    function login_attempt($args){
        $dbc = $this->dbc;

        if(mysqli_query($dbc, "SELECT `password` FROM `users` WHERE `username`='" . $args[0] . "';")->fetch_object()->password === $args[1]){
            session_start();
            $_SESSION['user']=$args[0];
            echo true;
        } else
            echo "Failed!";
    }

    function create_table($args){}

    function insert_to_table(){}

    function post_interp($arg){
        switch($arg){
            case 'create_user':
                if($_POST){
                    if(isset($_POST["createUser"]) && isset($_POST["createPass"]) && isset($_POST["createPassC"])){
                        $pass = array();
                        $pass[] = $_POST["createUser"];
                        $pass[] = $_POST["createPass"];
                        $pass[] = $_POST["createPassC"];

                        $this->create_new_user($pass);
                    } else {
                        echo "Invalid arguments";
                    }
                }
                //$this->create_new_user($pass);
                break;
            case 'login':
                if($_POST){
                    if(isset($_POST["loginUser"]) && isset($_POST["loginPass"])){
                        $pass = array();
                        //$pass[] = $_SESSION["user"];
                        $pass[] = $_POST["loginUser"];
                        $pass[] = $_POST["loginPass"];

                        $this->login_attempt($pass);
                    } else {
                        echo "Invalid arguments";
                    }
                }
                //$this->login_attempt($pass);
                break;
            case 'blog_post':
                if($_POST){
                    if(isset($_POST["postTitle"]) && isset($_POST["postContent"]) && isset($_SESSION["user"])){
                        $pass = array();
                        $pass[] = $_SESSION["user"];
                        $pass[] = $_POST["postTitle"];
                        $pass[] = $_POST["postContent"];

                        $this->post_to_blog($pass);
                    } else {
                        echo "Invalid arguments";
                    }
                }
                //$this->post_to_blog($pass);
                break;
            case 'edit_profile':
                //$this->login_attempt($pass);
                break;

        }
    }

    //Lexes SQL commands - Doesn't work right now
    function quick_string($str){
        //echo $str;
        $arr = str_split($str);
        $sql = '';
        $swing = '';
        $append = false;
        foreach($arr as $c){
            switch($c){
                case '+':
                    if($append)
                        $append=false;
                    else
                        $append=true;
                    break;
                //case '+':
                default:
                    break;
            }
            //if($append)
            //    $append=false;
            //else
            //    $append=true;
            if($append){
                if($c != '+'){
                    $swing = $swing . $c;
                }
            } else {
                if($swing){
                    $sql = $sql . $swing . ' ';
                    $swing = '';
                }
                if($c != '+')
                    $sql = $sql . $this::EXT[$c];
            }
            //echo $c;
        }
        //echo $sql;
        return $sql;
    }

}


//if($_POST)
//{
//    $user = $_POST['loginUser'];
//    $pass = $_POST['loginPass'];

//    $loginQ = "SELECT `password` FROM `users` WHERE `username`='$user';";
//    $response = mysqli_query($dbc, $loginQ)->fetch_object()->password;
//    //$data = $response->fetch_array(MYSQL_ASSOC)->fetch_object()->password;

//    if(!$response){
//        echo "Failed! No such user!";
//    } else if($response != $pass){
//        echo "Failed! Wrong password!";
//        echo $pass;
//        echo $response;
//    } else {
//        $_SESSION['user'] = $user;
//        $_SESSION['view_user'] = $_SESSION['user'];
//        echo json_encode(array("Good!", $user));
//        //echo $user;
//        //echo $response;
//    }
//}

//if(isset($_SESSION['user']))
//{
//    $user = $_SESSION['user'];
//    //$stamp = date(DATE_RFC2822);

//    $query = "INSERT INTO `phpsite`.`" . $user . "_blog" . "` (`id`, `post_title`, `post_body`, `submitted`) VALUES (NULL, '$title', '$content', CURRENT_TIMESTAMP);";

//    $response = mysqli_query($dbc, $query) or die (mysqli_error($dbc));

//    echo $_SESSION['user'] . "\n" . $response . "\n" . $query . "\n";
//    echo $user . " " . $title . " " . $content;

//    //if($response){
//    //    echo "Responded!";
//    //} else {
//    //    echo "Failed to push!";
//    //}

//    //if($response)
//    //{
//    //    echo 'Responded!';
//    //}
//} else {
//    echo "failed!";
//}



//if($_POST){
//  $user = $_POST["createUser"];
//  $pass = $_POST["createPass"];
//  $passC = $_POST["createPassC"];

//  if($pass == $passC){
//      $query = "INSERT INTO `users` (`username`, `password`) VALUES ('$user', '$pass');";
//      $response = mysqli_query($dbc, $query);
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_blog" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;") or die (mysqli_error($dbc));
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_profile" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `first_name` TEXT NULL , `last_name` TEXT NULL , `age` INT NULL , `birthdate` DATE NULL , `occupation` TEXT NULL , `about_me` JSON NULL , `profile_pic_url` TEXT NOT NULL , `wallpaper_pic_url` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;") or die (mysqli_error($dbc));
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_preferences" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;") or die (mysqli_error($dbc));
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_forums" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;") or die (mysqli_error($dbc));
//      //mysqli_query($dbc, "CREATE TABLE `phpsite` . '$user' ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;");
//  }
//  if(!$response){
//      echo "Failed to Create!";
//  } else {
//      //$query = "CREATE DATABASE '$user' (`username`, `password`) VALUES ('$user', '$pass');";
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_blog" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;");
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_profile" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `first_name` TEXT NULL , `last_name` TEXT NULL , `age` INT NULL , `birthdate` DATE NULL , `occupation` TEXT NULL , `about_me` JSON NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;") or die (mysqli_error($dbc));
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_preferences" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;") or die (mysqli_error($dbc));
//      mysqli_query($dbc, "CREATE TABLE `phpsite` . `" . $user . "_forums" . "` ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;") or die (mysqli_error($dbc));
//      //mysqli_query($dbc, "CREATE TABLE `phpsite` . '$user' ( `id` INT NOT NULL AUTO_INCREMENT , `post_title` TEXT NOT NULL , `post_body` TEXT NOT NULL , `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;");
//      //mysqli_query($dbc, "CREATE DATABASE '$user';");
//      echo "Added user!";
//  }


//}