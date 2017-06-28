<?php

class Main extends Model {

   
    public function getCarouselData()
    {
        $sql = "
SELECT id, title
  FROM news 
    ORDER BY create_date_time DESC 
      LIMIT 4
";
        return $this->db->query($sql);
    }

   
    public function saveAdvBlock($data=array()) 
    {
        $text = $this->db->escape($data['text']);
        $firm = $this->db->escape($data['firm']);
        $price = $this->db->escape($data['price']);
        
        
        return $this->db->query("INSERT INTO advertising (text, firm,  price ) VALUES ('{$text}','{$firm}','{$price}')");
    }

    
    public function getAdvBlockId()
    {
        $sql = "
SELECT *
  FROM advertising 
    ORDER BY id DESC 
      LIMIT 1
";
        return $this->db->query($sql);
    }

    public function setColor($text)
    {
        $color = $this->db->escape($text);
        $sql = "
UPDATE user SET color = '{$color}' WHERE id = 1;
";
        $this->db->query($sql);
    }
    
    public function setBackColor($text)
    {
        $background_color = $this->db->escape($text);
        $sql = "
UPDATE user SET background_color = '{$background_color}' WHERE id = 1;
";
        $this->db->query($sql);
    }
   
    public function getUsersLogins()
    {
        $sql = "
SELECT COUNT(comment.id) AS count_comments,comment.id_user,user.login 
  FROM comment
    LEFT JOIN user ON comment.id_user = user.id
      GROUP BY comment.id_user
        ORDER BY count_comments DESC
          LIMIT 5
";
        return $this->db->query($sql);
    }

    
    public function getTopThreeTopics()
    {
        $sql = "
SELECT news.id, news.title, COUNT(comment.id_news) AS count_com
  FROM news 
    LEFT JOIN comment ON news.id=comment.id_news 
      WHERE MONTH(comment.create_date_time) = MONTH(CURRENT_DATE) 
      AND YEAR(comment.create_date_time) = YEAR(CURRENT_DATE) 
        GROUP BY news.id 
          ORDER BY count_com 
            DESC LIMIT 3
";
        return $this->db->query($sql);
    }

   
    public function getCategoryTree() 
    {
        $sql = "
SELECT * 
  FROM category
 ";
        $result = $this->db->query($sql);
        $return = array();
        foreach($result as $val) {
            $return[$val['id_parent']][] = $val;
        }
        return $return;
    }

   
    public function getNewsByCategory() 
    {
        $sql_categories = "SELECT * FROM category"; // select all categories
        $categories = $this->db->query($sql_categories);
        $return = array();
        
       
        foreach ($categories as $category) {
            $sql = "
SELECT title,create_date_time,news.id AS id,category.id AS id_category 
  FROM news
    LEFT JOIN news_category ON news.id = news_category.id_news
    LEFT JOIN category ON news_category.id_category = category.id
  WHERE category.id = " . $category['id'] . "
  ORDER BY news.create_date_time DESC
  LIMIT 5
";
            $return = array_merge($return,$this->db->query($sql));
        }
        return $return;
    }

    
    public function getAdvertising()
    {
        $sql = "SELECT * FROM advertising";
        return $this->db->query($sql);
    }
}