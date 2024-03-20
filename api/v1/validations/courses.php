<?php

class CoursesValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->duration) || empty($obj->duration)){
            return 'One of more fields are missing';

        } else if (!isset($obj->course_type) || empty($obj->course_type)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->cover_image) || empty($obj->cover_image)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->overview) || empty($obj->overview)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->category_id) || empty($obj->category_id )){
            return 'One of more fields are missing';

        }  else{
            return '';
            
        }
    }


    public function validateUserCourse($obj){
        //receive data

        if (!isset($obj->course_id) || empty($obj->course_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }
}