<?php

class Category extends Model {

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

    
    public function getCategoryName($id)
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM category 
    WHERE category.id = '{$id}' 
      LIMIT 1";
        return $this->db->query($sql);
    }

    
    public function getNewsByCategoryId($id)
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM news 
    LEFT JOIN news_category ON news.id = news_category.id_news
      WHERE news_category.id_category = '{$id}'
        ORDER BY news.create_date_time DESC
";
        $result = $this->db->query($sql);
        return $result;
    }

    
    public function getNewsByCategory()
    {
        $sql_categories = "SELECT * FROM categories"; // select all categories
        $categories = $this->db->query($sql_categories);
        $return = array();

       
        foreach ($categories as $category) {
            $sql = "
SELECT title,create_date_time,news.id AS id,categories.id AS id_category 
  FROM news
    LEFT JOIN news_category ON news.id = news_category.id_news
    LEFT JOIN categories ON news_category.id_category = categories.id
  WHERE categories.id = " . $category['id'] . "
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
    

    
    public function getParentsCategories()
    {
        $sql = "
SELECT * 
  FROM category
    WHERE id_parent = 0";
        return $this->db->query($sql);
    }
    
    public function createCategory($id_parent,$cat_name)
    {
        $id = $this->db->escape($id_parent);
        $name = $this->db->escape($cat_name);
        $sql = "
INSERT INTO category(id_parent,name) 
  VALUES ('{$id}','{$name}')
";
        $this->db->query($sql);
    }

    public function editComment($data = array())
    {
        $id = $this->db->escape($data['id']);
        $text = $this->db->escape($data['text']);

        $approved = (array_key_exists('approved',$data)) ? 1 : 0;

        if ($approved) {
            $sql = "
UPDATE comment SET text='{$text}',approved={$approved} WHERE id='{$id}'
";
            $this->db->query($sql);
        } else {
            $sql = "
UPDATE comment SET text='{$text}' WHERE id='{$id}'
";
            $this->db->query($sql);
        }
    }
    
    public function getPoliticComments() 
    {
        $sql = "
SELECT * FROM comment WHERE approved IS NULL ORDER BY create_date_time
";
        return $this->db->query($sql);
    }
}