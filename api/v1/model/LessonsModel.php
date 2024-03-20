<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class LessonsModel extends Database{

    public function insertTopics($data){

        $name = $data->name;
        $topic_id = $data->topic_id;
        $type = $data->type;
        $duration = $data->duration;
        $media_length = $data->media_length;
        $url = addslashes($data->url);
        $description = addslashes($data->description);
        $created_by = 'developer';
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO lesson(topic_id, name, type, duration, media_length, url, description, created_by, created_at) VALUES ('$topic_id', '$name', '$type', '$duration', '$media_length', '$url', '$description', '$created_by', '$date')");

    }

    public function checkTopicLesson($name, $topic_id){
        return $this->count("SELECT COUNT(1) count FROM lesson WHERE name='$name' AND topic_id='$topic_id'");
    }

    public function checkTopic($topic_id){
        return $this->count("SELECT COUNT(1) count FROM topics WHERE id='$topic_id'");
    }

    public function checkLesson($lesson_id){
        return $this->count("SELECT COUNT(1) count FROM lesson WHERE id='$lesson_id'");
    }

    public function checkLessonUserEnrolled($user_id, $lesson_id){
        //
        return $this->count("SELECT COUNT(1) count FROM user_courses WHERE user_id='$user_id' AND course_id IN (SELECT a.id FROM courses a LEFT JOIN topics b ON a.id=b.course_id LEFT JOIN lesson c ON b.id = c.topic_id WHERE c.id='$lesson_id')");
    }

    public function listUserTopicLessons($topic_id,$user_id){
        return $this->list("SELECT b.*, (SELECT IF((a.id),'completed','') FROM user_lesson_completion a WHERE a.lesson_id=b.id AND a.user_id='$user_id') status FROM lesson b WHERE b.topic_id='".$topic_id."' ORDER BY b.id ASC");
    }

    public function listTopicLessons($topic_id){
        return $this->list("SELECT * FROM lesson WHERE topic_id='".$topic_id."' ORDER BY id ASC");
    }

    public function topicInfo($topic_id){
        return $this->list("SELECT * FROM topics WHERE id='".$topic_id."'");
    }

    public function insertLessonCompletion($data){

        $user_id = $data->user_id;
        $lesson_id = $data->lesson_id;
        $completion_date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO user_lesson_completion(user_id, lesson_id, completion_date) VALUES ('$user_id', '$lesson_id', '$completion_date')");

    }

}
