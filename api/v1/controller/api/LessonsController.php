<?php

class LessonsController extends BaseController{

    //create new lessons
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

        $lessonsModel = new LessonsModel();
        $checkExist = $lessonsModel->checkTopicLesson($data->name, $data->topic_id);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Lesson already exist on this topic"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{
            //check course
            $checkTopicExist = $lessonsModel->checkTopic($data->topic_id);

            if ($checkTopicExist < 1){
                //data does not exist
                $response = [
                    "status" => "error",
                    "message" => "Topic does not exist"
                ];
    
                $responseData = json_encode($response);
    
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
    
            } else{

                //validations
                $vali = new LessonsValidation();
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
                $lessonsModel = new LessonsModel();//responseData
                $arrSubs = $lessonsModel->insertTopics($data);

                $responseData = json_encode($arrSubs);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 Created')
                );

            }

        }
        
    }







    //retrieve course topics list
    public function viewTopicLessons($topic_id){

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
            "message" => "Topic lessons"

        ];
        $lessonsModel = new LessonsModel();
        $response_course = $lessonsModel->topicInfo($topic_id);

        if (is_string($response_course['data'])){
            $response['data'] = [];
        } else{
            $response_topic = $lessonsModel->listTopicLessons($topic_id);

            $response['data'] = [
                "topic_info" => $response_course['data'][0],
                "lessons" => $response_topic['data'],
                "total" => count($response_topic['data'])
            ];

        }

        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }








    //retrieve course topics list
    public function viewUserTopicLessons($topic_id, $user_id){

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
            "message" => "User Topic lessons"

        ];
        $lessonsModel = new LessonsModel();
        $response_course = $lessonsModel->topicInfo($topic_id);

        if (is_string($response_course['data'])){
            $response['data'] = [];
        } else{
            $response_topic = $lessonsModel->listUserTopicLessons($topic_id, $user_id);

            $response['data'] = [
                "topic_info" => $response_course['data'][0],
                "lessons" => $response_topic['data'],
                "total" => count($response_topic['data'])
            ];

        }

        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }








    //complete lesson
    public function completeLesson(){

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

        $lessonsModel = new LessonsModel();
        $checkLessonExist = $lessonsModel->checkLesson($data->lesson_id);

        if ($checkLessonExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Lesson does not exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{
            //check
            $checkUserExist = $lessonsModel->checkLessonUserEnrolled($data->user_id, $data->lesson_id);

            if ($checkUserExist < 1){
                //data does not exist
                $response = [
                    "status" => "error",
                    "message" => "User never enrolled for the course that has this lesson"
                ];
    
                $responseData = json_encode($response);
    
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
    
            } else{

                //validations
                $vali = new LessonsValidation();
                $val = $vali->validateLessonEnrol($data);

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
                $lessonsModel = new LessonsModel();
                $arrSubs = $lessonsModel->insertLessonCompletion($data);

                $responseData = json_encode($arrSubs);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 Created')
                );

            }

        }
        
    }
}
