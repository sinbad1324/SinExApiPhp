<?php 
namespace Traits;

trait DataFormatTrait {
    public function json($message , $data,$status=false) :string {
        return json_encode(["message"=>$message ??"", "data"=>$data??[] , "status"=>$status]);
    }
}


?>