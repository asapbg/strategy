<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use Illuminate\Http\Request;


class CommentsController extends AdminController
{
    const LIST_ROUTE = 'admin.consultations.comments.index';
    const LIST_VIEW = 'admin.consultations.comments.index';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? Comments::PAGINATE;
        $items = Comments::with(['author'])->FilterBy($requestFilter)->paginate($paginate);
        return $this->view('admin.consultations.comments.index', compact('items', 'filter'));
    }

    private function filters($request)
    {
        return array(
            'consultation' => array(
                'type' => 'select',
                'options' => optionsFromModel(PublicConsultation::optionsList(), true),
                'placeholder' => trans_choice('custom.public_consultations', 1),
                'value' => $request->input('consultation'),
                'col' => 'col-md-4'
            ),
        );
    }
}
