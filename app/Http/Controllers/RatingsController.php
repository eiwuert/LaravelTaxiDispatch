<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ratings;
use App\Http\Requests;
use Validator;
use Session;
use Auth;
use DB;
class RatingsController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 public function __construct(){
		$this->rating=new Ratings();
	}
    public function manage()
    {
        //
		$rating_list = Ratings::where('status',0)->get();
		return view('ratings.manage_rating',['rating_list' => $rating_list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
				return view('ratings.add_rating');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
		if(isset($_POST)){
			
			$rules = [
			'rating_id'  => 'required',
			'rating_reason'  => 'required|min:2|max:30',
			];
			//Define the validtion messgae for the rule
			$messages = [
				'rating_reason.required'        => 'Reason should be required',
			];
			$rating_reason=ltrim($request->input('rating_reason'));
			$rating_reason=rtrim($rating_reason);
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
			}
			 $rating_details = Ratings::where('reason',"=", $rating_reason)
			 ->where('status','=',0)
			 ->get();
			 
			if(count($rating_details)>0){
				return redirect('/rating/add')->withInput()
							->with('error_status', 'Rating reason already exists');
			}
		
			//Store the rating reason
			 	$rating = new Ratings;
        $rating->rating = $request->input('rating_id');
        $rating->reason = $rating_reason;
        $rating->status = 0;
       	$rating-> create_by = Auth::user()->id;
				$rating->updated_by = Auth::user()->id;
        $rating->save();			
		
			Session::flash('message', trans('Rating successfully created'));
			return redirect('/manage_rating');
		}
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
	
	
    }

    /**
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
				  if($request->input('_token')== null){
						$rating_id=$request->input('data_id');
				
						 $rating_details = Ratings::where('id',"=", $rating_id)->get();
						if(count($rating_details)>0){
								//Store the rating reason
								$rating = Ratings::find($rating_id);
								$rating->status = 1;
								$rating->save();
								$response="Ranking reason successfully deleted";
						}else{
								$response="Unable to delete..Try again";
						}
						
						return response()->json([
						'Response' => $response,
						'Status' => 'Success'
					]);
			
				}
    }
	
	
	
}
