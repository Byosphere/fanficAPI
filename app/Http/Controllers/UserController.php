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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //n'existe pas pour l'api
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //n'existe pas pour l'api
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
            return Response::json(array('error' => false, 'user' => $user->toArray()));

        } catch (\Exception $e) {

            return Response::json(array('error' => true, 'message' => $e->getMessage()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //n'existe pas pour l'api
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
            'pass' => 'min:5|max:40'
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

                if($request->get('name'))
                    $user->name = $request->get('name');

                if($request->get('email'))
                    $user->email = $request->get('email');

                if($request->get('pass'))
                    $user->password = Hash::make($request->get('pass'));

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
            return Response::json(array('error' => false, 'message' => $user->name+" a bien été supprimé"));

        } catch (\Exception $e) {

            return Response::json(array('error' => true, 'message' => $e->getMessage()));
        }

    }
}
