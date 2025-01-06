<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Controller;
use App\Models\CustomRole;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class AdvisoryBoardContactsController extends Controller
{

    public function index(Request $request, $section = Setting::ADVISORY_BOARDS_SECTION)
    {
        if (!$request->user()->canAny(['manage.*', 'manage.advisory-boards'])) {
            return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
        }

        $moderators = User::role([CustomRole::MODERATOR_ADVISORY_BOARDS, CustomRole::MODERATOR_ADVISORY_BOARD])
            ->whereNotIn('email', User::EXCLUDE_CONTACT_USER_BY_MAIL)
            ->get()
            ->sortByDesc(function ($item, $key) {
                return $item->advisoryBoardNames();
            });

        $this->setTitlePlural(__('site.admin.advisory_boards.contacts.title'));

        return $this->view('admin.advisory-boards.contacts.index', compact('moderators'));
    }
}
