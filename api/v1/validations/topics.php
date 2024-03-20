<?php

class TopicsValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->duration) || empty($obj->duration)){
            return 'One of more fields are missing';

        } else if (!isset($obj->course_id) || empty($obj->course_id)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->cover_image) || empty($obj->cover_image)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->description) || empty($obj->description)){
            return 'One of more fields are missing';

        }  else if (!is_numeric($obj->course_id)){
            return 'Invalid course id supplied';

        } else{
            return '';
            
        }
    }

}