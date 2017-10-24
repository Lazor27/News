<?php

class User extends Model {

    
    public function registerUser($first_name, $last_name, $login, $email, $password, $date)
    {
        $first_name = $this->db->escape($first_name);
        $last_name  = $this->db->escape($last_name);
        $login      = $this->db->escape($login);
        $email      = $this->db->escape($email);
        $password   = $this->db->escape($password);
        $sql = "
INSERT INTO user (first_name, last_name, login, email, password, date_of_birth) 
  VALUES ('{$first_name}', '{$last_name}', '{$login}', '{$email}', '{$password}', '{$date}')
";
        return $this->db->query($sql);
    }

   
    public function getByLogin($login)
    {
        $login = $this->db->escape($login);
        $sql = "
SELECT * 
  FROM user 
    WHERE login = '{$login}'
";
        $result = $this->db->query($sql);
        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }

   
    public function getByEmail($email)
    {
        $email = $this->db->escape($email);
        $sql = "
SELECT * 
  FROM user 
    WHERE email = '{$email}'
";
        $result = $this->db->query($sql);
        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }
    
    public function getUsersList() 
    {
        $sql= "
SELECT * 
  FROM user";
        return $this->db->query($sql);
    }

   
    public function getUserById($id) 
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM user 
    WHERE user.id = '{$id}'
";
        $result = $this->db->query($sql);
        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }

  
    public function getUserComments($id, $page = null) 
    {
        $id = $this->db->escape($id);
            if ($page == null) {
                $sql =  "SELECT * FROM user
                    JOIN comment on user.id = comment.id_user
                      WHERE comment.text <> '' AND user.id = '{$id}'
                        ORDER BY comment.create_date_time " ;
            }else{

                $count = 10;

                $ofset = ($page - 1) * $count;


                $sql ="SELECT * FROM user
                    JOIN comment on user.id = comment.id_user
                        WHERE comment.text <> '' AND user.id = '{$id}'
                            ORDER BY comment.create_date_time  limit {$ofset} , {$count} ";
            
            }

        return $this->db->query($sql);
}

   
    public function getAdvertising()
    {
        $sql = "SELECT * FROM advertising";
        return $this->db->query($sql);
    }
}