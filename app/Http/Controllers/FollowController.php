<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        $follower = Auth::user();

        abort_if($follower->id === $user->id, 422, '自分自身はフォローできません。');

        $follower->following()->syncWithoutDetaching([$user->id]);

        return back()->with('success', $user->name.'さんをフォローしました！');
    }

    public function destroy(User $user): RedirectResponse
    {
        Auth::user()->following()->detach($user->id);

        return back()->with('success', $user->name.'さんのフォローを解除しました。');
    }

    public function followers(User $user): View
    {
        $followers = $user->followers()->paginate(20);

        return view('users.followers', compact('user', 'followers'));
    }

    public function following(User $user): View
    {
        $following = $user->following()->paginate(20);

        return view('users.following', compact('user', 'following'));
    }
}
