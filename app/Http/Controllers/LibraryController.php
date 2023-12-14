<?php

namespace App\Http\Controllers;

use App\Enums\PublicationTypesEnum;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibraryController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function publications(Request $request)
    {
        $type = PublicationTypesEnum::TYPE_LIBRARY;
        $is_search = $request->has('search');

        $publications = $this->getPublications($request, $type);

        if ($is_search) {
            return $this->view('site.publications.publications', compact('publications'));
        }

        return $this->view('site.publications.index', compact('publications','type'));

    }

    /**
     * @param Request $request
     * @return View
     */
    public function news(Request $request)
    {
        $type = PublicationTypesEnum::TYPE_NEWS;
        $is_search = $request->has('search');

        $news = $this->getPublications($request, $type);

        if ($is_search) {
            return $this->view('site.publications.publications', compact('news'));
        }

        return $this->view('site.publications.index', compact('news','type'));
    }

    /**
     * Display publication details page
     *
     * @param $id
     * @return View
     */
    public function details($type, $id)
    {
        $publication = Publication::with(['translation','mainImg','category.translation'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            ->find($id);

        return $this->view('site.publications.details', compact('publication','type'));
    }

    /**
     * @param Request $request
     * @param PublicationTypesEnum $type
     * @return mixed
     */
    private function getPublications(Request $request, PublicationTypesEnum $type)
    {
        $sort = ($request->offsetGet('sort'))
            ? $request->offsetGet('sort')
            : "DESC";
        $order_by = ($request->offsetGet('order_by'))
            ? $request->offsetGet('order_by')
            : "id";
        $sort_table = (in_array($order_by, Publication::TRANSLATABLE_FIELDS))
            ? "publication_translations"
            : "publication";
        $paginate = $request->filled('paginate') ? $request->get('paginate') : 5;
        $published_from = $request->get('published_from');
        $published_till = $request->get('published_till');
        $title = $request->get('title');

        $publications = Publication::with(['translation', 'mainImg', 'category.translation'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            ->whereType($type)
            ->when($title, function ($query, $title) {
                return $query->where('title', 'ILIKE', "%$title%");
            })
            ->when($published_from, function ($query, $published_from) {
                return $query->where('published_at', '>=', databaseDate($published_from));
            })
            ->when($published_till, function ($query, $published_till) {
                return $query->where('published_at', '<=', databaseDate($published_till));
            })
            ->whereActive(true)
            ->orderBy("$sort_table.$order_by", $sort)
            ->paginate($paginate);
        return $publications;
    }
}
