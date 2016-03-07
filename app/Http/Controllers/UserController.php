<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use Validator;
use Response;
use Hash;

class UserController extends Controller
{

    public function connect(Request $request)
    {
        $regles = array(
            'email' => 'required|email',
            'pass' => 'required|min:5|max:40'
        );
        $validation = Validator::make($request->all(), $regles);

        if ($validation->fails()) {

            return Response::json(array(
                'error' => true,
                'message' => $validation->errors()->all()
            ));

        } else {

            if(\Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('pass')])) {

                return Response::json(array(
                    'error' => false, 'user' => \Auth::user()->toArray()));
            } else {

                return Response::json(array(
                    'error' => true,
                    'message' => "mauvaise combinaison email/mot de passe"
                ));
            }

        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $regles = array(
            'name' => 'required|min:5|max:40',
            'email' => 'required|email',
            'pass' => 'required|min:5|max:40'
        );

        $validation = Validator::make($request->all(), $regles);

        if ($validation->fails()) {

            return Response::json(array(
                'error' => true,
                'message' => $validation->errors()->all()
            ));

        } else {

            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('pass'));
            $user->save();

            return Response::json(array(
                'error' => false, 'message' => "Utilisateur enregistré !"));

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

            $user = User::findOrfail($id);
            $followed = array();
            $user->lectures;
            $user->stories;
            
            return Response::json(array('error' => false, 'user' => $user->toArray()));

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

        $regles = array(
            'name' => 'min:5|max:40',
            'email' => 'email',
            'pass' => 'min:5|max:40',
            'follow' => 'numeric',
            'page' => 'numeric'
        );

        $validation = Validator::make($request->all(), $regles);

        if ($validation->fails()) {

            return Response::json(array(
                'error' => true,
                'message' => $validation->errors()->all()
            ));

        } else {

            try {

                $user = User::findOrfail($id);

                if(\Auth::User()->id != $user->id)
                    throw new Exception("Non authorisé", 1);

                if($request->get('name'))
                    $user->name = $request->get('name');

                if($request->get('email'))
                    $user->email = $request->get('email');

                if($request->get('pass'))
                    $user->password = Hash::make($request->get('pass'));

                if($request->get('follow')) {

                    $storyFollow = \App\Story::findOrfail($request->get('follow'));
                    $existing = false;

                    foreach ($user->lectures()->get() as $story) {

                        if ($story->id == $storyFollow->id)
                            $existing = true;
                    }

                    if($request->get('page') && $existing)
                            $user->lectures()->updateExistingPivot($storyFollow->id, ['pageActuelle' => $request->get('page')]);

                    if($request->get('page') && !$existing)
                            $user->lectures()->attach($storyFollow->id, ['pageActuelle' => $request->get('page')]);

                    if(!$request->get('page') && !$existing)
                            $user->lectures()->attach($storyFollow->id);

                }

                $user->save();

                return Response::json(array(
                    'error' => false, 'message' => "Utilisateur mis à jour"));

            } catch(\Exception $e) {

                return Response::json(array(
                    'error' => true,
                    'message' => $e->getMessage()
                ));
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

            $user = User::findOrfail($id);

            if(\Auth::User()->id != $user->id)
                throw new \Exception("Non authorisé", 1);

            foreach ($user->lectures()->get() as $story ) {

                dd($story->titre);
            }

            $user->delete();
            return Response::json(array('error' => false, 'message' => "l'utilisateur a bien été supprimé"));

        } catch (\Exception $e) {

            return Response::json(array('error' => true, 'message' => $e->getMessage()));
        }

    }
}
