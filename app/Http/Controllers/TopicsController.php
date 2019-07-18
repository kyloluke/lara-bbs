<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\TopicRequest;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;

class TopicsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic, User $user)
    {
        $topics = $topic->withOrder($request->order)->paginate(15);
        $active_users = $user->getActiveUsers();
        return view('topics.index', compact('topics', 'active_users'));
    }

    public function show(Request $request, Topic $topic)
    {
        // slug 不为空 并且 不等于传来的slug的情况下  重定向
        if(!empty($topic->slug) &&  $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
        return view('topics.show', compact('topic'));
    }


    public function create(Topic $topic)
    {
        // 需要获取到分类列表
        $categories = Category::all();
        return view('topics.create_and_edit', compact('categories', 'topic'));
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(TopicRequest $request,Topic $topic)
    {
        $this->authorize('update', $topic);
        $res = $topic->update($request->except(['_token', '_method']));
        if($res) {
            return redirect()->to($topic->link())->with('success', '帖子更新成功 ^_^');
        } else {;
            return false;
        }
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('delete', $topic);
        $res = $topic->delete();
        if($res) {
            return redirect()->route('topics.index')->with('success', '删除成功 ^_^');
        }
    }

    public function store(TopicRequest $request)
    {
        $topic = $request->user()->topics()->create($request->except(['_token', '_method']));
        if($topic) {
            return redirect()->to($topic->link())->with('success', '帖子发布成功 ^_^');
        } else {
            return false;
        }
    }

    // 参考 https://simditor.tower.im/docs/doc-config.html#anchor-upload
    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];

        // 判断是否有文件上传，并赋值给 $file
        if($file = $request->upload_file) {
            $result = $uploader->save($file, 'topics', \Auth::id(), 1024);
            if($result) {
                $data['success'] = true;
                $data['msg'] = '上传成功';
                $data['file_path'] = $result['path'];
            }
        }

        return $data;
    }
}
