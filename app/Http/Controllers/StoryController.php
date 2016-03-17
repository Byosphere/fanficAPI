<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;
use App\Page;
use App\Http\Requests;
use Response;
use Validator;

class StoryController extends Controller
{


    public function explore()
    {
        $user = \Auth::user();
        $listStory = array();
        foreach (Story::get() as $item) {

            $exist = false;
            foreach ($user->stories as $story) {

                if($story->id == $item->id) {

                    $exist = true;
                }
            }

            if (!$exist) {
                $item->lecteurs;
                $listStory[] = $item;
            }

        }
        return Response::json(array('error' => false, 'stories' => $listStory));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();

        $regles = array(
            'titre' => 'required|min:5|max:40',
            'ref' => 'required|min:5|max:40'
        );

        $validation = Validator::make($request->all(), $regles);

        if ($validation->fails()) {

            return Response::json(array(
                'error' => true,
                'message' => $validation->errors()->all()
            ));

        } else {

            $story = new Story();
            $story->titre = $request->get('titre');
            $story->reference = $request->get('ref');
            $story->author = $user->name;

            $user->stories()->save($story);

            return Response::json(array(
                'error' => false, 'story' => $story));

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
        try {

            $story = Story::findOrfail($id);
            return Response::json(array('error' => false, 'story' => $story->toArray(), 'pages' => $story->pages->toArray()));

        } catch (\Exception $e) {

            return Response::json(array('error' => true, 'message' => $e->getMessage()));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = \Auth::user();
        $regles = array(
            'titre' => 'min:5|max:40',
            'ref' => 'min:5|max:40',
            'nbPages' => 'numeric'
        );

        $validation = Validator::make($request->all(), $regles);

        if ($validation->fails()) {

            return Response::json(array(
                'error' => true,
                'message' => $validation->errors()->all()
            ));

        } else {

            try {

                $story = Story::findOrfail($id);

                if($user->id != $story->user_id)
                    throw new \Exception("Non authorisé", 1);

                if($request->get('titre'))
                    $story->titre = $request->get('titre');

                if($request->get('ref'))
                    $story->reference = $request->get('ref');

                if($request->get('nbPages'))
                    $story->nbPages = $request->get('nbPages');

                $user->stories()->save($story);

                return Response::json(array(
                    'error' => false, 'message' => "Story modifiée !"));

            } catch (\Exception $e) {

                return Response::json(array(
                    'error' => true, 'message' => $e->getMessage()));
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $story = Story::findOrfail($id);

            if(\Auth::user()->id != $story->user_id)
                throw new \Exception("Non authorisé", 1);

            foreach ($story->pages as $page) {

                $page->delete();
            }
            $story->delete();
            return Response::json(array('error' => false, 'message' => "la story a bien été supprimée"));

        } catch (\Exception $e) {

            return Response::json(array(
                'error' => true, 'message' => $e->getMessage()));
        }
    }


}
