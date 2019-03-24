<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/24/19
 * Time: 7:57 AM
 */

namespace App\Http\Controllers\Pub;


use App\Http\Controllers\Controller;
use App\Models\Base\BaseModel;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function view($id, User $authUser)
    {
        $user = null;
        if ($id === 'me' && $authUser->exists) {
            $user = $authUser;
        } elseif (is_numeric($id)) {
            $user = User::find($id);
        }
        if ($user === null) {
            return redirect(route('home'));
        }
        return view('pub.user.view', [
            'user' => $user,
            'isCurentAuthenticatedUser' => $user->id == $authUser->id,
        ]);
    }

    public function edit(User $user)
    {
        return view('pub.user.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $user->fill(array_except($data, 'avatar'));
        $languages = $user->createLanguages($data['lang']);
        \DB::transaction(function () use (
            $user,
            $languages,
            $request,
            $data
        ) {
            $user->languages()->delete();
            if ($data['is_update_avatar']) {
                if ($request->file('avatar') !== null) {
                    $user->uploadImage($request->file('avatar'), 'avatar');
                } else {
                    $user->deleteImage($user->getOriginal('avatar'));
                    $user->avatar = null;
                }
            }
            $user->save();
            BaseModel::massInsert($languages);
        });
        return redirect(route('user.view', [$user->id]));
    }

}