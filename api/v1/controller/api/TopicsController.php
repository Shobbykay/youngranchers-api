<?php

class TopicsController extends BaseController{

    //create new topics
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

        $topicsModel = new TopicsModel();
        $checkExist = $topicsModel->checkTopic($data->name, $data->course_id);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Topic already exist on this course"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{
            //check course
            $checkCourseExist = $topicsModel->checkCourse($data->course_id);

            if ($checkCourseExist < 1){
                //data does not exist
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
                $vali = new TopicsValidation();
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
                $topicsModel = new TopicsModel();//responseData
                $arrSubs = $topicsModel->insertTopics($data);

                $responseData = json_encode($arrSubs);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 Created')
                );

            }

        }
        
    }





    //retrieve course topics list
    public function listCourseTopics($course_id){

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

        $response = [
            "success" => "success",
            "message" => "Course topics"

        ];
        $topicsModel = new TopicsModel();//responseData
        $response_course = $topicsModel->courseInfo($course_id);

        if (is_string($response_course['data'])){
            $response['data'] = [];
        } else{
            $response_topic = $topicsModel->listCourseTopics($course_id);

            $response['data'] = [
                "course_info" => $response_course['data'][0],
                "topics" => $response_topic['data'],
                "total" => count($response_topic['data'])
            ];

        }

        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }



}
