<?php

class Request extends Model {

    
    public function newSearchTags($data)
    {
        $role = (Session::get('role')==null) ? " " : "/" . Session::get('role');
        $name = $this->db->escape($data);
        $sql = "
SELECT * 
  FROM tag 
    WHERE tag.name LIKE '%$name%'";

        $result = $this->db->query($sql);

        if (!empty($result)) {
            foreach ($result as $row) {
                echo ' <li class="block-title-price">
                        <a href="'.$role.'/tags/view/'.$row["id"].'">'.$row["name"].'</a>
                       </li>';
            }
        } else {
            echo '<li class="search-noresult"><a href="'.$role.'/tags/index/">Watch all tags</a></li> ';
        }
        exit;
    }

    
    public function allReaders($id_news,$count_readers)
    {
        $id = $this->db->escape($id_news);
        $count = $this->db->escape($count_readers);

        $sql_1 = "
UPDATE news 
  SET news.count_readers = news.count_readers + '$count' 
    WHERE news.id = '$id'
  ";

        $this->db->query($sql_1);

        $sql = "
SELECT * 
  FROM news 
    WHERE news.id = '$id' LIMIT 1
";
        $result = $this->db->query($sql);
        if (!empty($result)) {
            foreach ($result as $row) {
                echo $row['count_readers'];
            }
        } else {
            echo 'Nobody reade this yet' ;
        }
        exit;
    }

    
    public function addPlus($id_comment) 
    {
        $id = $this->db->escape($id_comment);

        $sql_1 = "
UPDATE comment 
  SET comment.plus = comment.plus+1 
    WHERE id = '$id'
 ";
        $this->db->query($sql_1);

        $sql = "
SELECT * 
  FROM comment
    WHERE comment.id = '$id' LIMIT 1
";
        $result = $this->db->query($sql);
        echo $result[0]['plus'];
        exit;
    }
    public function getBackColor()
    {
        $id = Session::get('id');
        if (isset($id)) {
            $sql = "
SELECT * 
  FROM user
    WHERE id = '{$id}' LIMIT 1";

            $user = $this->db->query($sql);

            $user_color= $user[0]['background_color'];
            if($user_color==null){
               $sql = "
SELECT * 
  FROM user
    WHERE id = '1' LIMIT 1";

            $user = $this->db->query($sql);

            $user_color= $user[0]['background_color'];
 
            }
            echo $user_color;
        } else {
            echo 'yellow';
        }
        exit;
    }


    
    public function getColor() {

        $id = Session::get('id');
        if (isset($id)) {
            $sql = "
SELECT * 
  FROM user
    WHERE id = '{$id}' LIMIT 1";

            $user = $this->db->query($sql);

            $user_color= $user[0]['color'];
            if ($user_color==null){
                 $sql = "
SELECT * 
  FROM user
    WHERE id = '1' LIMIT 1";

            $user = $this->db->query($sql);

            $user_color= $user[0]['color'];

            }
            echo $user_color;
        } else {
            echo 'red';
        }
        exit;
    }


    
    public function addMinus($id_comment)
    {
        $id = $this->db->escape($id_comment);

        $sql_1 = "
UPDATE comment 
  SET comment.minus = comment.minus+1 
    WHERE id = '$id'
 ";
        $this->db->query($sql_1);

        $sql = "
SELECT * 
  FROM comment
    WHERE comment.id = '$id' LIMIT 1
";
        $result = $this->db->query($sql);
        echo $result[0]['minus'];
        exit;
    }

    public function allCategories($search)
    {
        if ($search == 1) {
            $sql = "
SELECT * 
  FROM
    category
";
            $result = $this->db->query($sql);
            $role = (Session::get('role')==null) ? " " : "/" . Session::get('role');
            if (!empty($result)) {

                echo'<li><a href="'.$role.'/categories/">All</a></li>';
                echo '<li class="divider"></li>';

                foreach ($result as $row) {
                    if ($row['id_parent'] == 0) {
                        echo '<li class="dropdown-submenu"><a tabindex="-1" href="'.$role.'/categories/view/'.$row['id'].'">'. $row['name'].'</a>
                              <ul class="dropdown-menu">';
                        foreach ($result as $row_level_2) {
                            if ($row_level_2['id_parent'] == $row['id']) {
                                echo '<li ><a href="'.$role.'/categories/view/'.$row_level_2['id'].'">'. $row_level_2['name'].'</a></li>';
                            }
                        }
                        echo '</ul>';
                        echo '</li>';
                        echo '<li class="divider"></li>';
                    }
                }

            }
            exit;
        }
    }
}