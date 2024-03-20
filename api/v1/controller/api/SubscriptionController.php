<?php

class SubscriptionController extends BaseController{

    //create new subscription
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

        $subscriptionModel = new SubscriptionModel();//responseData
        $checkExist = $subscriptionModel->checkSubscription($data->name);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Subscription already exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{
            //validations
            $validate = new SubscriptionValidation();//responseData
            $val = $validate->validate($data);

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
            $arrSubs = $subscriptionModel->insertSubscription($data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );
        }
        
    }





    //retrieve subscription list
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

        $subscriptionModel = new SubscriptionModel();//responseData
        $response = $subscriptionModel->listSubscription();

        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }








    //create new user subscription
    public function add_user(){

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

        $subscriptionModel = new SubscriptionModel();//responseData
        $checkExist = $subscriptionModel->checkUserSubscription($data->user_id, $data->subscription_id);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "User is already subscribed to this plan"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{
            //validations
            $validate = new SubscriptionValidation();//responseData
            $val = $validate->validateUserSub($data);

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

            //more validations from db
            $checkUserSubExist = $subscriptionModel->checkUserAndSub($data->user_id, $data->subscription_id);

            if ($checkUserSubExist == 1){
                $response = [
                    "status" => "error",
                    "message" => "User does not exist"
                ];
    
                $responseData = json_encode($response);
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else if ($checkUserSubExist == 2){
                $response = [
                    "status" => "error",
                    "message" => "Subscription Plan does not exist"
                ];
    
                $responseData = json_encode($response);
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }


            //insert data
            $arrSubs = $subscriptionModel->insertUserSubscription($data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );
        }
        
    }

}
