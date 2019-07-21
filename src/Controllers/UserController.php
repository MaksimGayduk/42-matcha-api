<?php

namespace App\Controllers;

class UserController{

    public function actionGetUser($id = null){

        $data = [];

        $user = 'user';
        for($i = 0; $i < 10; $i++){
            $data[$user . $i]['id'] = $i;
            $data[$user . $i]['age'] = $i + 3;
            $data[$user . $i]['name'] = 'userName' . $i;
        }

        if($id == null)
            return $data;
        else{
            foreach ($data as $user){
                if($user['id'] == $id){
                    return $user;
                }
            }
        }
    }

    public function actionDeleteUser($id = null)
    {

        return $data = '[DELETE]';
    }


    public function actionPostUser()
    {
        
        return $data = '[POST]';
    }

    public function actionPatchUser($id = null)
    {
        
        return $data = '[PATCH]';
    }

    public function actionPutUser()
    {
        
        return $data['PUT'];
    }


}
