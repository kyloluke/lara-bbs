<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use Illuminate\Http\Request;
use App\Models\Reply;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Reply $reply)
    {
//        $reply->fill($request->except(['_token', '_method'])); // 这样使用是 ok 的
        $reply->content = $request->input('content');
        $reply->user_id = \Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();

        return redirect()->to($reply->topic->link())->with('success', '留言成功');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();
        return redirect()->to($reply->topic->link())->with('success', '评论删除成功 ^_^');
    }

}
