<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class testController extends Controller
{

    public function index() {

        // $user = new \App\User();
        // $user->name = "Yohann";
        // $user->email = "dreamlike.swarm@gmail.com";
        // $user->save();
        // $story = new \App\Story();
        // $story->titre = "Un tutre";
        // $user->stories()->save($story);
        // $page1 = new \App\Page(['texte' => 'lksdfhlksdfjlskjflksjflks']);
        // $page2 = new \App\Page(['texte' => 'lksdfhlksdfjlqsdqsdkjqsdqslksjflks']);
        // $story->pages()->save($page1);
        // $story->pages()->save($page2);
        // $story->lecteurs()->save($user, ['pageActuelle' => 12]); //many to many
        // dd($user->stories()->get());
        dd("test");
    }
}
