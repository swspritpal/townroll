<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;


use App\Repositories\Frontend\Access\Comment\CommentRepository;

class CommonController extends Controller
{

    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->content = array();
    }

    private function make_key_value_pair($unformatted_array=null)
    {
        $newFormat=[];
        foreach ($unformatted_array as $key => $item) {
            $newArray['code']=$key;
            $newArray['name']=$item;
            $newFormat[]=$newArray;
            
        }
        return $newFormat;
    }

    public function get_countries(){

        $countries = getCountiesList();
        $countries=$this->make_key_value_pair($countries);

        if(!empty($countries)){
            $this->content['error'] = false;
            $this->content['massage'] = "Countries list.";
            $this->content['data'] = $countries;
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "Countries not found.";
            $this->content['data'] ='';
            $status = 200;
        }
        return response()->json($this->content, $status);
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function getStates($country_sortname)
    {   
        $country_id=getCountryId($country_sortname);

        $states = getStateList($country_id);
        $states=$this->make_key_value_pair($states);

        if(!empty($states)){
            $this->content['error'] = false;
            $this->content['massage'] = "states list.";
            $this->content['data'] = $states;
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "Countries not found.";
            $this->content['data'] ='';
            $status = 200;
        }
        return response()->json($this->content, $status);
    }

    /**
     * @return \Illuminate\Response\Json
     */
    public function getCities($state_id)
    {   

        $cities = getCityList($state_id);
        $cities=$this->make_key_value_pair($cities);

        if(!empty($cities)){
            $this->content['error'] = false;
            $this->content['massage'] = "city list.";
            $this->content['data'] = $cities;
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "Countries not found.";
            $this->content['data'] ='';
            $status = 200;
        }
        return response()->json($this->content, $status);
    }


    /**
     * @return \Illuminate\Response\Json
     */
    public function unique_username(Request $request)
    {   
        $username=$request->input('username');
        if(!empty($username)){
            $response = is_username_unique($username);
            if($response == true){
                $this->content['error'] = false;
                $this->content['massage'] = "Username already taken.";
                $this->content['data'] =false;
                $status = 200;
            }else{     
                $this->content['error'] = false;
                $this->content['massage'] = "Username unique.";
                $this->content['data'] = true;
                $status = 200;
            }

        }else{
            $this->content['massage'] = "Invalid params";
            $this->content['error'] = true;
            $status = 500;
        }
            
        return response()->json($this->content, $status);
    }


    /**
     * @params Image Raw string
     * @return \Illuminate\Response\Json
     */
    public function upload_image(Request $request)
    {   
        $image_source=$urlOrRawString=$extension=$save_path=null;
        //inilize
        $urlOrRawString=$request->get('url_or_raw_string');
        $image_source=$request->get('image_source');
        $extension=$request->get('extension');
        $save_path=$request->get('save_path');

        if($urlOrRawString == "url"){
            $path = $image_source;
            $filename = basename($path);

            $is_uploaded=Image::make($path)->save(public_path($save_path. $filename));
            if(!empty($is_uploaded)){

                $file=$save_path. $filename;
                if(env('USE_OPTIMIZER') && (\File::exists($file))){
                    $imageOptimizer=new \Approached\LaravelImageOptimizer\ImageOptimizer;
                   // optimize
                    $imageOptimizer->optimizeImage($file);
                    // override the previous image with optimized once
                    $is_optimized = file_put_contents($file, \File::get($file));
                    if($is_optimized == false || empty($is_optimized)){
                        \Log::warning('Image does not optimized Sent from API. Named : '.$image_name);
                    }
                }
                
                $this->content['error'] = false;
                $this->content['massage'] = "upload_successfull.";
                $this->content['data'] =$filename;
                $status = 200;
            }else{
                $this->content['massage'] = "error_while_uploading_image";
                $this->content['error'] = true;
                $status = 500;
            }
        }

        // save Raw image data into server
        if($urlOrRawString == "raw_string"){

            //$image_data=is_base64_encoded($image_source);

            $upload_dir=public_path().$save_path;

            list($type, $data) = explode(';', $image_source);
            list(, $data)      = explode(',', $data);
            list($type_content, $extension) = explode('/', $type);
            $image_source = str_replace(' ', '+', $data);
            $data = base64_decode($image_source);        

            $image_name=mt_rand().'.'.$extension;      
            $file = $upload_dir.$image_name;

            $is_uploaded = file_put_contents($file, $data);
            if(!empty($is_uploaded)){

                if(env('USE_OPTIMIZER') && (\File::exists($file))){
                    $imageOptimizer=new \Approached\LaravelImageOptimizer\ImageOptimizer;
                   // optimize
                    $imageOptimizer->optimizeImage($file);
                    // override the previous image with optimized once
                    $is_optimized = file_put_contents($file, \File::get($file));
                    if($is_optimized == false || empty($is_optimized)){
                        \Log::warning('Image does not optimized Sent from API. Named : '.$image_name);
                    }
                }
                $this->content['error'] = false;
                $this->content['massage'] = "upload_successfull.";
                $this->content['data'] =$image_name;
                $status = 200;
            }else{
                $this->content['massage'] = "error_while_uploading_image";
                $this->content['error'] = true;
                $status = 500;
            }
        }

        return response()->json($this->content, $status);
    }


    public function commentStore(Request $request)
    {
        if (!$request->get('content')) {
            $this->content['massage'] = "content_must_not_be_empty";
            $this->content['error'] = true;
            $status = 500;

            return response()->json($this->content, $status);
        }

        if ($comment = $this->commentRepository->create($request,$request->get('user_id'))){
            $this->content['error'] = false;
            $this->content['massage'] = "success";
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "error_while_saving";
            $status = 200;
        }
        
        return response()->json($this->content, $status);
    }
    

    public function comments(Request $request,$commentable_id=null,$commentable_type=null)
    {
        $comment=\App\Comment::where('commentable_id', $commentable_id)->where('commentable_type', $commentable_type)->paginate(env('DEFAULT_SINGLE_POST_COMMENTS_LIMIT'))->toArray();

        if (!empty($comment)){
            $this->content['error'] = false;
            $this->content['massage'] = "success";
            $this->content['data'] = $comment;
            $status = 200;
        }else{
            $this->content['error'] = false;
            $this->content['massage'] = "error_while_fatching";
            $this->content['data'] = null;
            $status = 200;
        }
        
        return response()->json($this->content, $status);
    }
}
