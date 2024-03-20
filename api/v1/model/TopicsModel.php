<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class TopicsModel extends Database{

    public function insertTopics($data){

        $name = $data->name;
        $duration = $data->duration;
        $topic_id = $data->topic_id;
        $cover_image = addslashes($data->cover_image);
        $description = addslashes($data->description);
        $created_by = 'developer';
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO topics(name, course_id, duration, cover_image, description, created_by, created_date) VALUES ('$name', '$course_id', '$duration', '$cover_image', '$description', '$created_by', '$date')");

    }

    public function checkTopic($name, $topic_id){
        return $this->count("SELECT COUNT(1) count FROM topics WHERE name='$name' AND course_id='$course_id'");
    }

    public function checkCourse($course_id){
        return $this->count("SELECT COUNT(1) count FROM courses WHERE id='$course_id'");
    }

    public function listCourseTopics($course_id){
        return $this->list("SELECT * FROM topics WHERE course_id='".$course_id."' ORDER BY id ASC");
    }

    public function courseInfo($course_id){
        return $this->list("SELECT * FROM courses WHERE id='".$course_id."'");
    }

    // public function listSingleCourse($course_id){
    //     return $this->list("SELECT a.id, a.name, a.category_id, b.name category_name, a.duration, a.course_type, a.cover_image, a.overview, 0 enrolled_users, a.created_by, a.created_date FROM `courses` a
    //     LEFT JOIN category b ON a.category_id=b.id
    //     WHERE a.id='$course_id'
    //     ORDER BY a.name DESC");
    // }

    // public function listUserCourses($user_id){
    //     return $this->list("SELECT a.id, a.name, a.category_id, b.name category_name, a.duration, a.course_type, a.cover_image, a.overview, a.created_by, a.created_date FROM `courses` a
    //     LEFT JOIN category b ON a.category_id=b.id
    //     WHERE a.id IN (SELECT course_id FROM user_courses WHERE user_id='".$user_id."')
    //     ORDER BY a.name DESC");
    // }

}
