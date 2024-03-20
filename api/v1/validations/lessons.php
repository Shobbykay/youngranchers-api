<?php

class LessonsValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->topic_id) || empty($obj->topic_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->duration) || empty($obj->duration)){
            return 'One of more fields are missing';

        } else if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->type) || empty($obj->type)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->media_length) || empty($obj->media_length)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->url) || empty($obj->url)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->description) || empty($obj->description)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

    public function validateLessonEnrol($obj){
        //receive data

        if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->lesson_id) || empty($obj->lesson_id)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}