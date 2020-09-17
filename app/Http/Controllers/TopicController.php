<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class TopicController extends Controller
{

    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::latest()->paginate(10);

        return view('topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('topics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'g-recaptcha-response' => 'required|captcha'
        ],
        [
            'title.required' => 'Veuillez renseigner le titre de votre topic',
            'content.required' => 'Veuillez saisir le contenu de votre topic',
            'title.min' => 'Le titre de votre topic doit contenir au moins 5 caractères',
            'content.min' => 'Le sujet de votre topic doit contenir au moins 10 caractères',
            'g-recaptcha-response.required' => 'Merci de vérifier que vous n\'êtes pas un robot',
            'g-recaptcha-response.captcha' => 'Erreur Captcha! veuillez réessayer plus tard ou nous contacter'
        ]);

        $topic = auth()->user()->topics()->create([
            'title' => $request->get('title'),
            'content' => $request->get('content')
        ]);

        return redirect()->route('topics.show', $topic->id)->with('success', 'Votre topic a été crée avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

    public function showFromNotification(Topic $topic, DatabaseNotification $notification)
    {

        $notification->markAsRead();

        return view('topics.show', compact('topic', 'notification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {

        $this->authorize('update', $topic);

        return view('topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {

        $this->authorize('update', $topic);

        $data = $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ],
        [
            'title.required' => 'Veuillez renseigner le titre de votre topic',
            'content.required' => 'Veuillez saisir le contenu de votre topic',
            'title.min' => 'Le titre de votre topic doit contenir au moins 5 caractères',
            'content.min' => 'Le sujet de votre topic doit contenir au moins 10 caractères'
        ]);

        $topic->update($data);

        return redirect()->route('topics.show', $topic->id)->with('success', 'Le topic a été modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {

        $this->authorize('delete', $topic);

        $topic->delete();

        return redirect('/');
    }
}
