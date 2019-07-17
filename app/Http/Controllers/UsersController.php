<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use http\Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * @param UserRequest $request
     * @param ImageUploadHandler $upload
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request,ImageUploadHandler $upload, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->except(['_token', '_method']);

        if($request->hasFile('avatar')) {
            $res = $upload->save($request->avatar, 'avatars', $user->id, 500);
            if($res) {
                $data['avatar'] = $res['path'];
            }
        }
        $res = $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功');
    }
}