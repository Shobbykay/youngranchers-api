<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require __DIR__ . "/inc/bootstrap.php";

//Controllers
require PROJECT_ROOT_PATH . "/controller/api/UserController.php";
require PROJECT_ROOT_PATH . "/controller/api/SubscriptionController.php";
require PROJECT_ROOT_PATH . "/controller/api/CoursesController.php";
require PROJECT_ROOT_PATH . "/controller/api/TopicsController.php";
require PROJECT_ROOT_PATH . "/controller/api/LessonsController.php";


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$resources = [
    "user",
    "lessons",
    "courses",
    "subscriptions",
    "topics",
    "lessons"
];

$actions = [
    "create",
    "list",
    "view",
    "update",
    "delete"
];

if ((isset($uri[2]) && !in_array($uri[5], $resources) ) || !isset($uri[3])) {

    header("HTTP/1.1 404 Not Found");
    $response = [
        "status" => "error",
        "message" => "Resource not found, refer to API doc"
    ];
    echo json_encode($response);
    exit();

}




//ROUTING
if ($uri[5] == 'user'){
    //user route

    array_push($actions, "loan_eligibility");

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'loan_eligibility')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new UserController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    $objFeedController = new UserController();
    $strMethodName = $uri[3] . 'Action';
    $objFeedController->{$strMethodName}();


} else if ($uri[5] == 'subscriptions'){

    array_push($actions, "add_user");

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    $objFeedController = new SubscriptionController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

    // $response = [
    //     "status" => "error".$uri[5],
    //     "message" => "Welcome to subscriptions"
    // ];
    // echo json_encode($response);

}  else if ($uri[5] == 'courses'){

    array_push($actions, "user");
    array_push($actions, "user_summary");
    array_push($actions, "enroll_user");
    array_push($actions, "popular");

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'user' || $uri[6] == 'view' || $uri[6] == 'enroll_user' || $uri[6] == 'user_summary' || $uri[6] == 'popular')){

        if ($uri[6] == 'popular'){

            $objFeedController = new CoursesController();
            $strMethodName = "popular";
            $objFeedController->{$strMethodName}();
            exit();
        }

        if ($uri[6] !== 'enroll_user'){
            if (!isset($uri[7]) || empty($uri[7])){
                header("HTTP/1.1 404 Not Found");
                $response = [
                    "status" => "error",
                    "message" => "Resource not found, refer to API doc"
                ];
                echo json_encode($response);
                exit();
            }
        }

        $objFeedController = new CoursesController();
        $strMethodName = "user_courses";

        if ($uri[6] == 'view'){
            $strMethodName = "view_course";
        }

        if ($uri[6] == 'user_summary'){
            $strMethodName = "user_course_summary";
            //user_id
        }

        if ($uri[6] == 'enroll_user'){
            $strMethodName = "enroll_user_course";

            $objFeedController->{$strMethodName}();
            exit();
            
        }

        $objFeedController->{$strMethodName}($uri[7]);
        exit();

    } else if ($uri[6] !== 'user'){
        if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }
    }

    
    $objFeedController = new CoursesController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

}  else if ($uri[5] == 'topics'){

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new TopicsController();
        $strMethodName = "listCourseTopics";

        $objFeedController->{$strMethodName}($uri[7]);
        exit();

    } else if ($uri[6] !== 'view'){
        if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }
    }

    
    $objFeedController = new TopicsController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

}  else if ($uri[5] == 'lessons'){

    array_push($actions, "complete");

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view') || ($uri[6] == 'complete')){

        if ($uri[6] == 'complete'){
            $objFeedController = new LessonsController();
            $strMethodName = "completeLesson";

            $objFeedController->{$strMethodName}();
            exit();
        }

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc".$uri[6]
            ];
            echo json_encode($response);
            exit();
        }

        if (isset($uri[8]) || !empty($uri[8])){
            echo 'hi kayode';
            //user id
            $objFeedController = new LessonsController();
            $strMethodName = "viewUserTopicLessons";

            $objFeedController->{$strMethodName}($uri[7],$uri[8]);
            exit();
        }

        $objFeedController = new LessonsController();
        $strMethodName = "viewTopicLessons";

        $objFeedController->{$strMethodName}($uri[7]);
        exit();

    } else if ($uri[6] !== 'view'){
        if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }
    }

    
    $objFeedController = new LessonsController();
    $strMethodName = $uri[6];

    $objFeedController->{$strMethodName}();

}

