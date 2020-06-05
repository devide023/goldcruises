<?php


namespace App\Code;


use App\Models\Sql;
use Illuminate\Http\Request;

trait TSql
{
    public function get_tsql(Request $request){
        $action = $request->route()->getActionName();
        $pos = stripos($action,'@');
        $pos2 = strripos($action,'\\');
        $actionname = strtolower(substr($action,$pos+1));
        $control = $request->route()->getAction()['controller'];
        $controlname =strtolower(substr($control,$pos2+1,$pos-($pos2+1)));
        $sql = Sql::where('controller',$controlname)
            ->where('action',$actionname)
            ->first();
        return $sql;
    }
}
