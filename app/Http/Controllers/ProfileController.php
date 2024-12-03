<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function updateAvatar(UpdateAvatarRequest $request)
    {
        $path = Storage::disk('public')->put('avatars',$request->file('avatar'));
        // dd($path);
        // $request->validate([
        //     'avatar' =>'required|image',
        // ]); 
        // dd($request->all());
        // $request->file('avatar')->store('avatars');

        
        // $path = $request->file('avatar')->store('avatars','public');
        if($oldAvatar = $request->user()->avatar){
            Storage::disk('public')->delete($oldAvatar) ;
         }
        // dd($path);
        auth()->user()->update([
            'avatar' => $path
        ]);
        // dd(auth()->user());
        // return back()->with(['message'=>'Avatar is changed']);
        return redirect(route('profile.edit'))->with('message','Avatar is updated');
    }
}
