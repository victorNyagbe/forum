<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use App\Topic;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request,Topic $topic)
    {
        $request->validate([
            'content' => 'required|min:3'
        ],
        [
            'content.required' => 'Veuillez saisir votre commentaire. Impossible de soumettre un commentaire vide',
            'content.min' => 'Votre commentaire est trop court'
        ]);

        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = auth()->user()->id;

        $topic->comments()->save($comment);

        return redirect()->route('topics.show', $topic->id);
    }

    public function storeCommentReply(Request $request,Comment $comment)
    {
        $request->validate([
            'replyComment' => 'required|min:3'
        ],
        [
            'replyComment.required' => 'Veuillez saisir votre commentaire. Impossible de soumettre un commentaire vide',
            'replyComment.min' => 'Votre commentaire est trop court'
        ]);

        $commentReplied = new Comment();
        $commentReplied->content = $request->get('replyComment');
        $commentReplied->user_id = auth()->user()->id;

        $comment->comments()->save($commentReplied);

        return redirect()->back();
    }

    public function markedAsSolution(Topic $topic,Comment $comment)
    {
        if (auth()->user()->id == $topic->user_id) {

            $topic->solution = $comment->id;
            $topic->save();

            return response()->json(['success' => ['success' => 'Marqué comme solution']], 200);

        } else {
            return response()->json(['errors' => ['error' => 'Utilisateur non valide']], 401);
        }
        
    }
}
