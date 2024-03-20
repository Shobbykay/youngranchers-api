<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class SubscriptionModel extends Database{

    public function getUsers($limit){

        return $this->select("SELECT * FROM users ORDER BY user_id ASC LIMIT ?", ["i", $limit]);

    }

    public function insertSubscription($data){

        $name = $data->name;
        $amount = $data->amount;
        $desc = addslashes($data->description);
        $created_by = 'developer';
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO subscriptions(name, amount, description, created_by, created_date) VALUES ('$name', '$amount', '$desc', '$created_by', '$date')");

    }

    public function insertUserSubscription($data){

        $user_id = $data->user_id;
        $subscription_id = $data->subscription_id;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO user_subscription(user_id, subscription_id, created_date) VALUES ('$user_id', '$subscription_id', '$date')");

    }

    public function checkSubscription($name){
        return $this->count("SELECT COUNT(1) count FROM subscriptions WHERE name='$name'");
    }

    public function checkUserSubscription($user_id, $subscription_id){
        return $this->count("SELECT COUNT(1) count FROM user_subscription WHERE user_id='$user_id' AND subscription_id='$subscription_id'");
    }

    public function checkUserAndSub($user_id, $subscription_id){

        $count=0;

        $user_count = $this->count("SELECT COUNT(1) count FROM users WHERE id='$user_id'");
        $subscription_count = $this->count("SELECT COUNT(1) count FROM subscriptions WHERE id='$subscription_id'");

        if ($user_count < 1){
            $count=1;//does not exist
        }

        if ($subscription_count < 1){
            $count=2;//does not exist
        }

        return $count;
    }

    public function listSubscription(){
        return $this->list("SELECT * FROM subscriptions ORDER BY id DESC");
    }

}
