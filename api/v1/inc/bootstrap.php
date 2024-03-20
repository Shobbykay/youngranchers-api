<?php

define("PROJECT_ROOT_PATH", __DIR__ . "/../");

// include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";

// include the base controller file
require_once PROJECT_ROOT_PATH . "/controller/api/BaseController.php";

// include the use model file
require_once PROJECT_ROOT_PATH . "/model/UserModel.php";
require_once PROJECT_ROOT_PATH . "/model/SubscriptionModel.php";
require_once PROJECT_ROOT_PATH . "/model/CoursesModel.php";
require_once PROJECT_ROOT_PATH . "/model/TopicsModel.php";
require_once PROJECT_ROOT_PATH . "/model/LessonsModel.php";


//validations
require_once PROJECT_ROOT_PATH . "/validations/subscription.php";
require_once PROJECT_ROOT_PATH . "/validations/courses.php";
require_once PROJECT_ROOT_PATH . "/validations/topics.php";
require_once PROJECT_ROOT_PATH . "/validations/lessons.php";
