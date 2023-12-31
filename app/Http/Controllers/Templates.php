<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Templates extends Controller
{
    public function index()
    {
            //        8.2.1.1.2 -  Раздел „Правна информация на Министерски съвет“
            //          публично и админ (има чернови)
            //        8.2.1.1.3 -  Раздел „Обществени консултации“
            //          има задач в jira, само отворена страница
            //        8.2.1.1.4 - Оценка на въздействие
            //          да се снима
//        8.2.1.1.5  Законодателна инициатива
//          само списък индекс страница (публично )
//        8.2.1.1.7 - Консултативни съвети
//          публично
//        8.2.1.1.8 -  Партньорство за открито управление
//          има го на стария Партньорство за открито управление (публично)
            //        8.2.1.1.9 - Раздел „Библиотека“ и раздел „Новини“
            //          публично
            //        8.2.1.1.10 - Списък на физическите и юридическите лица, на които е възложено от държавата или общините да изработят проекти на нормативни актове, оценки на въздействието
            //          публично и админ file List-LNA-2023 от чата
            //        Екран настройки в администрация
            // Боби ги е направил ще се снима
        return view('templates.list');
    }

    public function show($slug)
    {
        return view('templates.'.$slug);
    }
}
