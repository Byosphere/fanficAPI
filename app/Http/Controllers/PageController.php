<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;
use App\Page;
use Validator;
use Response;
use App\Http\Requests;

class PageController extends Controller
{


    public function store(Request $request, $storyId)
    {
        try {

            $user = \Auth::user();
            $story = Story::findOrfail($storyId);
            $page = new Page();

            $regles = array(
                'texte' => 'min:5|required'
            );

            $validation = Validator::make($request->all(), $regles);

            if ($validation->fails()) {

                return Response::json(array(
                    'error' => true,
                    'message' => $validation->errors()->all()
                ));

            } else {

                $page->texte = $request->get('texte');
                $story->nbPages++;
                $story->pages()->save($page);
                $story->save();

                return Response::json(array(
                    'error' => false, 'message' => "Page enregistrée !"));

            }

        } catch (\Exception $e) {

            return Response::json(array(
                'error' => true, 'message' => $e->getMessage()));
        }
    }

    public function show($pageId)
    {

        $this->authentificate($pageId);
        $page = Page::find($pageId);
        return Response::json(array('error' => false, 'page' => $page->toArray()));

    }


    public function update(Request $request, $pageId)
    {

        $regles = array(
            'texte' => 'min:5|required'
        );

        $this->authentificate($pageId);
        $page = Page::find($pageId);

        $validation = Validator::make($request->all(), $regles);

        if ($validation->fails()) {

            return Response::json(array(
                'error' => true,
                'message' => $validation->errors()->all()
            ));

        } else {

            $page->texte = $request->get('texte');
            $page->save();

            return Response::json(array(
                'error' => false, 'message' => "Page modifiée !"));
        }
    }


    public function destroy($pageId)
    {
        $this->authentificate($pageId);
        $page = Page::find($pageId);
        $story = $page->story();
        $story->nbPages--;
        $page->delete();
        $story->save();
    }

    private function authentificate($pageId) {

        try {

            $user = \Auth::user();
            $page = Page::findOrfail($pageId);
            $story = Story::findOrfail($page->story_id);

            if($user->id != $story->user_id)
                throw new \Exception("Bad authetification", 1);

            return $page;

        } catch (\Exception $e) {

            return Response::json(array(
                'error' => true, 'message' => $e->getMessage()));
        }
    }
}
