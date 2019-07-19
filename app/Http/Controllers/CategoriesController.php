<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category, User $user)
    {
        $active_users = $user->getActiveUsers();
        $topics = Topic::where('category_id', $category->id)->withOrder($request->order)->paginate(15);
        return view('topics.index', compact('topics','category', 'active_users'));
    }
}
