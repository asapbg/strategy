<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends AdminController
{

    const ADV_BOARD_GUIDE_FILE = 'adv_board_guide.pdf';

    public function index(Request $request){
        return $this->view('admin.help.index');
    }

    public function guide(Request $request, $role = 'advisory_boards'){
        switch ($role){
            case 'advisory_boards':
                $name = 'Ръководство за "Консултативни съвети"';
                $file = self::ADV_BOARD_GUIDE_FILE;
                break;
        }
        return $this->view('admin.help.guide', compact('name', 'file'));
    }
}
