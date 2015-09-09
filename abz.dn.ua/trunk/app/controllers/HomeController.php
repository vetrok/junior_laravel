<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    /**
     * The layout that should be used for responses.
     */
    //protected $layout = 'layouts.master';

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function showSwitch()
	{
        //return View::make('bootstrap')->nest('child', 'choose', array('data'=>$view));
        return View::make('choose');
	}

	public function showWorkersList()
	{
        $start = time();
        $data = new Workers();
        $users = $data->getAllWorkersAsList();
        echo time() - $start;
        return View::make('list', array('users' => $users));
	}

    //Get users as array
	public function showWorkersTable()
	{
        $data = new Workers();
        $users = $data->getAllWorkersAsArray();
        return View::make('table', array('users' => $users));
	}

}
