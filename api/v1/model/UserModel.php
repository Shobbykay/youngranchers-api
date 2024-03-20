<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class UserModel extends Database{

    public function getUsers($limit){

        return $this->select("SELECT * FROM users ORDER BY user_id ASC LIMIT ?", ["i", $limit]);

    }


    public function checkEligibility($email){
        //email or user_id
        $check_users_tb = $this->count("SELECT COUNT(1) count FROM users WHERE email='$email' OR id='$email'");

        $eligibility_status = '';
        $response = [
            "status" => "error",
        ];
        
        if ($check_users_tb > 0){
            //user exist
            //check if user performed any operation (bought a course)
            $check_users_courses = $this->count("SELECT COUNT(1) count FROM user_courses WHERE user_id='$email' AND status='active'");

            if ($check_users_courses > 0){
                //user has bought course
                $response['status'] = "success";
                $eligibility_status = "USER_IS_ELIGIBILE";

            } else{
                //no course ever bought
                $eligibility_status = "USER_NEVER_BOUGHT_ANY_COURSE";
            }

        } else{
            //user never attempted FarmTinder
            $eligibility_status = "USER_NEVER_USED_YOUNGRANCHERS";
        }

        $response['message'] = "View loan eligibility";
        $response['code'] = $eligibility_status;

        return $response;
    }

}
