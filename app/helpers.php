<?php 
function getName($user){
    $userData = \App\User::where('id',$user)->first();
    return !empty($userData->first_name) ? $userData->first_name.' '.$userData->last_name:'';
}
function getControllerName(){
    $currentAction = \Route::currentRouteAction();
    list($controller, $method) = explode('@', $currentAction);    
    return $controller = preg_replace('/.*\\\/', '', $controller);
}
function flash($message,$level = 'info'){
    session()->flash('flash_message',$message);
    session()->flash('flash_message_level',$level);
}
function shortString($string,$length){    
    return fixbrokenHtml(substr(strip_tags(html_entity_decode($string),'<br><b>'),0,$length).'...');
}

function YMD2MDY($date, $join = '/') {
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];
}
function YMD2MDY1($date, $join = '/') {
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[1] . $join. $dateArr[2] . $join . $dateArr[0];
}
function MDY2YMD($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[0] . $join . $dateArr[1];   
}

function DMY2YMD($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    $dateN =  $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];  
    return empty($dateArr[3]) ? $dateN:$dateN.' '.$dateArr[3];
}

function YMD2DMY($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    $dateN =  $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];  
    return empty($dateArr[3]) ? $dateN:$dateN.' '.$dateArr[3];
}
function DMY2YMDNoTime($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];
}

function getPakistanTime($date){
    $date = new DateTime($date, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Asia/Karachi'));
    return $date->format('Y-m-d H:i:s');
}

function getAllowedRegistrationType($user = array()){
    if(empty($user)){
        $user = \Auth::user();
    }
    $userRoles = $user->getRoleNames();
    if($user->hasAnyRole(1)){
        return array(1,2,3,4,5);
    }else if($user->hasAnyRole(2)){
     return array(1,2,3,4,5);
    
    }else if($user->hasAnyRole(4)){
        return array(5);
    }
    return array(5);
}

function getBuildings($user = array()){
    if(empty($user)){
        $user = \Auth::user();
    }
    $currentBuilding = \Session::get('current_building','');
    $buildingObj = new \App\Building;
    if($user->hasAnyRole(array(2,3,4,5))){
        $UserBuildingShop = \App\UserBuildingShop::where('user_id',$user->id)->get();
        $buidlings = array();
        foreach($UserBuildingShop as $bul){
            $buidlings[$bul->building_id] = $bul->building_id;
        }
        $buildingObj = $buildingObj->whereIn('id',$buidlings);
    }
    $buildings = $buildingObj->with('user')->get();
    return $buildings;
}