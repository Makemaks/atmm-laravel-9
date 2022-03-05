<?php

namespace App\Http\Controllers\Api;

use App\Models\Podcast;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      //return $request;
      $request['task'] = 'viewing podcast';
      $this->save_api_activity_log($request);

      $podcast = new Podcast();

      if($request->has('type') && in_array($request->input('type'), ['podcast', 'audiobook'])) {
          $podcast = $podcast->where('type', $request->input('type'));
      }

      $podcast = $podcast->where('is_public', 1);

      return $this->paginate($request, [
          'items' => $podcast,
          's_fields' => ['title'],
          'sortBy' => 'date',
          'sortOrder' => 'DESC'
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $podcast = Podcast::findOrFail($id);

      if(!$podcast->is_public) {
          return response()->json(['error' => 'Access Forbidden.'],403);
      }

      return $podcast;
    }
}
