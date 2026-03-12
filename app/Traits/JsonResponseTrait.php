<?php 
namespace App\Traits;

/**
 * 
 */
trait JsonResponseTrait
{
    protected $status_code;

    public function successResponseMessage($message='Success', $status_code=200){
        return response()->json([
            'message' => $message,
            'status' =>True
        ],$status_code);
    }


    public function createSuccessMessage($message='Success'){
        return [
            'message' => $message,
            'status' =>True
        ];
    }

    
    public function errorResponseMessage($message='Connection Error', $status_code=500){
        return response()->json([
            'message' => $message,
            'status' =>false
        ],$status_code);
    }


    public function createErrorMessage($message){
        return [
            'message' => $message,
            'status' =>false
        ];
    }


    public function responseDataMessage($data,$message='Success', $status_code=200){
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' =>True
        ],$status_code);
    }

    public function responseErrorDataMessage($data,$message='Failed', $status_code=400){
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' =>false
        ],$status_code);
    }

    public function responseCollection($data)
    {
        return response()->json($data);
    }

    
}