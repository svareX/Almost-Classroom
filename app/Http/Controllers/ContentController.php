<?php

namespace App\Http\Controllers;


use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ContentController extends Controller
{
    function sort(Request $request)
    {
        if ($request->ajax()) {
            $paginate_count = 4;
            $sort = $request->get('sort');
            $groups = $request->get('groups');
            $search = $request->get('search');
            $user = User::findOrFail(Auth::user()->id);

            switch ($sort) {
                case 1:
                    $tridit_dle = 'id';
                    $tridit_jak = 'asc';
                    break;
                case 2:
                    $tridit_dle = 'id';
                    $tridit_jak = 'desc';
                    break;
                case 3:
                    $tridit_dle = 'deadline';
                    $tridit_jak = 'asc';
                    break;
                case 4:
                    $tridit_dle = 'deadline';
                    $tridit_jak = 'desc';
                    break;
                case 5:
                    $tridit_dle = 'name';
                    $tridit_jak = 'asc';
                    break;
                case 6:
                    $tridit_dle = 'name';
                    $tridit_jak = 'desc';
                    break;
                default:
                    $tridit_dle = 'deadline';
                    $tridit_jak = 'desc';
                    break;
            }

            if ($search != "") {
                $group_posts = collect([]);
                $groups_final = $user->groups()->get();
                foreach ($groups_final as $group) {
                    $id[] = $group->id;
                }
                $posts = Post::whereIn('group_id', $id)->where('name', 'like', '%' . $search . '%')->orWhere('content', 'like', '%' . $search . '%')->orderBy($tridit_dle, $tridit_jak)->paginate($paginate_count/2);
                /*
                $groups = $user->groups()->where('name', 'like', '%' . $search . '%')->get();
                foreach ($groups as $group) {
                    $group_posts = $group->posts()->get();
                }
                $posts = $posts->merge($group_posts);
                */
                return view('prispevky', ['prispevky' => $posts])->render();
            }

            switch ($groups) {
                case 1:
                    $groups_final = $user->groups;
                    foreach ($groups_final as $group) {
                        $id[] = $group->id;
                    }
                    if ($sort < 5) {
                        $posts = array();
                        $posts = Post::whereIn('group_id', $id)->orderBy($tridit_dle, $tridit_jak)->paginate($paginate_count);
                        return view('prispevky', ['prispevky' => $posts])->render();
                    } else {
                        $groups_final = $user->groups()->orderBy($tridit_dle, $tridit_jak)->get();
                    }
                    break;
                case 2:
                    $groups_final = $user->groups()->where('public', '!=', 1)->get();
                    foreach ($groups_final as $group) {
                        $id[] = $group->id;
                    }
                    if ($sort < 5) {
                        $posts = Post::whereIn('group_id', $id)->orderBy($tridit_dle, $tridit_jak)->paginate($paginate_count);
                        return view('prispevky', ['prispevky' => $posts])->render();
                    } else {
                        $groups_final = $user->groups()->where('public', '!=', 1)->orderBy($tridit_dle, $tridit_jak)->get();
                    }
                    break;
                case 3:
                    $groups_final = Group::where('public', 1)->get();
                    foreach ($groups_final as $group) {
                        $id[] = $group->id;
                    }
                    if ($sort < 5) {
                        $posts = Post::whereIn('group_id', $id)->orderBy($tridit_dle, $tridit_jak)->paginate($paginate_count);
                        return view('prispevky', ['prispevky' => $posts])->render();
                    } else {
                        $groups_final = Group::where('public', 1)->orderBy($tridit_dle, $tridit_jak)->get();
                    }
                    break;
                default:
                    $groups_final = $user->groups;
                    foreach ($groups_final as $group) {
                        $id[] = $group->id;
                    }
                    if ($sort < 5) {
                        $posts = array();
                        $posts = Post::whereIn('group_id', $id)->orderBy($tridit_dle, $tridit_jak)->paginate($paginate_count);
                        return view('prispevky', ['prispevky' => $posts])->render();
                    } else {
                        $groups_final = $user->groups()->orderBy($tridit_dle, $tridit_jak)->get();
                    }
                    break;
            }
            foreach ($groups_final as $group) {
                $id[] = $group->id;
                $posts = array();
                $posts = Post::whereIn('group_id', $id)->orderBy($tridit_dle, $tridit_jak)->paginate($paginate_count);
                return view('prispevky', ['prispevky' => $posts])->render();
            }
        }
        return redirect()->route('dashboard');
    }
}
