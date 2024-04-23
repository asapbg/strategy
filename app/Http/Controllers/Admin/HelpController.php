<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends AdminController
{
    const ADV_BOARD_GUIDE_FILE = 'adv_board_guide.pdf';
    const ADV_BOARD_INNER_GUIDE_FILE = 'adv_board_inner_guide.pdf';

    const SD_GUIDE_FILE = 'sd_guide.pdf';
    const SD_INNER_GUIDE_FILE = 'sd_inner_guide.pdf';
    const PRIS_GUIDE_FILE = 'pris_guide.pdf';
    const OGP_GUIDE_FILE = 'ogp_guide.pdf';
    const PC_GUIDE_FILE = 'pc_guide.pdf';
    const ADMIN_GUIDE_FILE = 'admin_guide.pdf';

    public function index(Request $request){
        return $this->view('admin.help.index');
    }

    public function guide(Request $request, $role = 'advisory_boards'){
        switch ($role){
            case 'advisory_boards':
                $name = 'Ръководство за "Раздел Консултативни съвети"';
                $file = self::ADV_BOARD_GUIDE_FILE;
                break;
            case 'advisory_boards_inner':
                $name = 'Ръководство за "Консултативни съвети"';
                $file = self::ADV_BOARD_INNER_GUIDE_FILE;
                break;
            case 'sd':
                $name = 'Ръководство за "Раздел Стратегически документи"';
                $file = self::SD_GUIDE_FILE;
                break;
            case 'sd_inner':
                $name = 'Ръководство за "Стратегически документи"';
                $file = self::SD_INNER_GUIDE_FILE;
                break;
            case 'pris':
                $name = 'Ръководство за "Правна информация"';
                $file = self::PRIS_GUIDE_FILE;
                break;
            case 'ogp':
                $name = 'Ръководство за "Партньорство за открито управление"';
                $file = self::OGP_GUIDE_FILE;
                break;
            case 'pc':
                $name = 'Ръководство за "Обществени консултации"';
                $file = self::PC_GUIDE_FILE;
                break;
            case 'admin':
                $name = 'Ръководство за "Администратор"';
                $file = self::ADMIN_GUIDE_FILE;
                break;
        }
        $this->setTitleSingular(__('custom.help'));
        return $this->view('admin.help.guide', compact('name', 'file'));
    }
}
