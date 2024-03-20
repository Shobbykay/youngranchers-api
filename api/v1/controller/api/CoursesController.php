<?php

class CoursesController extends BaseController{

    //create new courses
    public function create(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'POST') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        //create the data
        $json = file_get_contents('php://input');

        // Converts it into a PHP object
        $data = json_decode($json);

        $courseModel = new CoursesModel();//responseData
        $checkExist = $courseModel->checkCourse($data->name);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Course already exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{
            //check course category
            $checkCategoryExist = $courseModel->checkCourseCategory($data->category_id);

            if ($checkCategoryExist < 1){
                //data already exist
                $response = [
                    "status" => "error",
                    "message" => "Course category does not exist"
                ];
    
                $responseData = json_encode($response);
    
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
    
            } else{

                //validations
                $vali = new CoursesValidation();//responseData
                $val = $vali->validate($data);

                if ($val !== ''){
                    $response = [
                        "status" => "error",
                        "message" => $val
                    ];

                    $responseData = json_encode($response);

                    $this->sendOutput(
                        $responseData,
                        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                    );
                }

                //insert data
                $coursesModel = new CoursesModel();//responseData
                $arrSubs = $coursesModel->insertCourses($data);

                $responseData = json_encode($arrSubs);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 Created')
                );

            }

        }
        
    }





    //retrieve courses list
    public function list(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $coursesModel = new CoursesModel();//responseData
        $response = $coursesModel->listCourses();

        $response['message'] = "All courses";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }





    //retrieve popular courses list
    public function popular(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $coursesModel = new CoursesModel();
        $response = $coursesModel->listPopularCourses();

        $response['message'] = "4 top popular courses";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }






    //retrieve user courses list
    public function user_courses($user_id){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $coursesModel = new CoursesModel();//responseData
        $response = $coursesModel->listUserCourses($user_id);

        $response['message'] = "All user enrolled courses";
        if (is_string($response['data'])){
            $response['data']=[];
        }
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }


    



    //view single course
    public function view_course($course_id){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $coursesModel = new CoursesModel();//responseData
        $response = $coursesModel->listSingleCourse($course_id);

        $response['message'] = "View single course";
        if (is_string($response['data'])){
            $response['data']=[];
        }
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }






    //enroll user for course
    public function enroll_user_course(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'POST') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        //create the data
        $json = file_get_contents('php://input');

        // Converts it into a PHP object
        $data = json_decode($json);

        $courseModel = new CoursesModel();
        $checkExist = $courseModel->checkUserCourse($data->course_id, $data->user_id);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "User has already enrolled for this Course"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{
            //check course category
            $checkCourseExist = $courseModel->checkCourseId($data->course_id);

            if ($checkCourseExist < 1){
                //data already exist
                $response = [
                    "status" => "error",
                    "message" => "Course does not exist"
                ];
    
                $responseData = json_encode($response);
    
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
    
            } else{

                //validations
                $vali = new CoursesValidation();//responseData
                $val = $vali->validateUserCourse($data);

                if ($val !== ''){
                    $response = [
                        "status" => "error",
                        "message" => $val
                    ];

                    $responseData = json_encode($response);

                    $this->sendOutput(
                        $responseData,
                        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                    );
                }

                //insert data
                $coursesModel = new CoursesModel();//responseData
                $arrSubs = $coursesModel->enrolUserCourses($data);

                $responseData = json_encode($arrSubs);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 Created')
                );

            }

        }
        
    }





    //retrieve courses list
    public function user_course_summary($user_id){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $coursesModel = new CoursesModel();//responseData
        $response = $coursesModel->summaryUserCourses($user_id);

        $response['message'] = "User course summary";
        $response['data'] = $response['data'][0];
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }
}
