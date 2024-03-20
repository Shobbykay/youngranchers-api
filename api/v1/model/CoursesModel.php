<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class CoursesModel extends Database{

    public function insertCourses($data){

        $name = $data->name;
        $duration = $data->duration;
        $course_type = addslashes($data->course_type);
        $cover_image = addslashes($data->cover_image);
        $overview = addslashes($data->overview);
        $category_id = $data->category_id;
        $created_by = 'developer';
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO courses(name, category_id, duration, course_type, cover_image, overview, created_by, created_date) VALUES ('$name', '$category_id', '$duration', '$course_type', '$cover_image', '$overview', '$created_by', '$date')");

    }

    public function enrolUserCourses($data){

        $user_id = $data->user_id;
        $course_id = $data->course_id;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO user_courses(user_id, course_id, created_date) VALUES ('$user_id', '$course_id', '$date')");

    }

    public function checkCourse($name){
        return $this->count("SELECT COUNT(1) count FROM courses WHERE name='$name'");
    }

    public function checkCourseId($course_id){
        return $this->count("SELECT COUNT(1) count FROM courses WHERE id='$course_id'");
    }

    public function checkUserCourse($course_id, $user_id){
        return $this->count("SELECT COUNT(1) count FROM user_courses WHERE user_id='$user_id' AND course_id='$course_id'");
    }

    public function checkCourseCategory($id){
        return $this->count("SELECT COUNT(1) count FROM category WHERE id='$id'");
    }

    public function summaryUserCourses($user_id){
        return $this->list("SELECT count(IF(status='active', 1, NULL)) active, count(IF(completed=1, 1, NULL)) completed FROM `user_courses` WHERE user_id='".$user_id."' GROUP BY user_id");
    }

    public function listCourses(){
        return $this->list("SELECT a.id, a.name, a.category_id, b.name category_name, a.duration, a.course_type, a.cover_image, a.overview, (SELECT COUNT(*) FROM user_courses WHERE course_id=a.id) enrolled_users, a.created_by, a.created_date FROM `courses` a
        LEFT JOIN category b ON a.category_id=b.id
        ORDER BY a.name DESC");
    }

    public function listPopularCourses(){
        return $this->list("SELECT a.id, a.name, a.category_id, b.name category_name, a.duration, a.course_type, a.cover_image, a.overview, (SELECT COUNT(*) FROM user_courses WHERE course_id=a.id) enrolled_users, a.created_by, a.created_date FROM `courses` a
        LEFT JOIN category b ON a.category_id=b.id
        ORDER BY a.name DESC, RAND() LIMIT 4");
    }

    public function listSingleCourse($course_id){
        return $this->list("SELECT a.id, a.name, a.category_id, b.name category_name, a.duration, a.course_type, a.cover_image, a.overview, (SELECT COUNT(*) FROM user_courses WHERE course_id=a.id) enrolled_users, a.created_by, a.created_date FROM `courses` a
        LEFT JOIN category b ON a.category_id=b.id
        WHERE a.id='$course_id'
        ORDER BY a.name DESC");
    }

    public function listUserCourses($user_id){
        return $this->list("SELECT a.id, a.name, a.category_id, b.name category_name, a.duration, a.course_type, a.cover_image, a.overview, a.created_by, a.created_date FROM `courses` a
        LEFT JOIN category b ON a.category_id=b.id
        WHERE a.id IN (SELECT course_id FROM user_courses WHERE user_id='".$user_id."')
        ORDER BY a.name DESC");
    }

}
