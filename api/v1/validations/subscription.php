<?php

class SubscriptionValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->amount) || ($obj->amount=='')){
            return 'One of more fields are missing';

        } else if (!isset($obj->description) || empty($obj->description)){
            return 'One of more fields are missing';

        } else if (!is_numeric($obj->amount)){
            return 'Price for Subscription must be a number value';

        } else if ($obj->amount < 0){
            return 'Invalid Price for Subscription provided';

        } else{
            return '';
            
        }
    }

    public function validateUserSub($obj){
        //receive data

        if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->subscription_id) || empty($obj->subscription_id)){
            return 'One of more fields are missing';

        } else if (!is_numeric($obj->subscription_id)){
            return 'Invalid subscription id provided';

        } else{
            return '';
            
        }
    }
}