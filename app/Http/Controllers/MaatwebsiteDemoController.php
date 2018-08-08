<?php
namespace wyrades\Http\Controllers;

use Illuminate\Http\Request;
use wyrades\Item;
use Excel;

class MaatwebsiteDemoController extends Controller
{

	/**
     * Return View file
     *
     * @var array
     */
	public function importExport()
	{
		return view('importExport');
	}

	/**
     * File Export Code
     *
     * @var array
     */
	public function downloadExcel(Request $request, $type)
	{
		$data = Item::get()->toArray();
		return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
			$excel->sheet('mySheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}

	/**
     * Import file into database Code
     *
     * @var array
     */
	public function importExcel(Request $request)
	{

		if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();

			$data = Excel::load($path, function($reader) {})->get();

			if(!empty($data) && $data->count()){

				foreach ($data->toArray() as $key => $value) {
					if(!empty($value)){
						foreach ($value as $v) {		
							$insert[] = ['title' => $v['title'], 'description' => $v['description']];
						}
					}
				}

				
				if(!empty($insert)){
					Item::insert($insert);
					return back()->with('success','Insert Record successfully.');
				}

			}

		}

		return back()->with('error','Please Check your file, Something is wrong there.');
	}

}