<?php

namespace App\Http\Controllers;

use App\Enums\PublicationTypesEnum;
use App\Models\Consultations\PublicConsultation;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\LegislativeInitiative;
use App\Models\Publication;
use App\Models\PublicationCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibraryController extends Controller
{

    /**
     * @var string
     */
    protected $default_img = "files" . DIRECTORY_SEPARATOR . File::PUBLICATION_UPLOAD_DIR . "news-default.jpg";

    /**
     * @var int
     */
    protected $paginate = 50;

    /**
     * @param Request $request
     * @param int|null $type
     *
     * @return View
     */
    public function publications(Request $request, int $type = null)
    {
        $type = PublicationTypesEnum::tryFrom($type) ?? PublicationTypesEnum::TYPE_LIBRARY;
        $rssUrl = config('feed.feeds.publication.url');
        $requestFilter = $request->all();

        $pageTitle = trans_choice(PublicationTypesEnum::getTypeName()[$type->value], 2);
        $is_search = $request->has('search');
        $paginate = $request->filled('paginate')
            ? $request->get('paginate')
            : $this->paginate;

        $publications = $this->getPublications($request, $type->value);

        $default_img = asset(Publication::DEFAULT_IMG_LIBRARY);

        $requestFilter['type'] = str(PublicationTypesEnum::TYPE_LIBRARY->value);
        $hasSubscribeEmail = $this->hasSubscription(null, PublicConsultation::class, $requestFilter);
        $hasSubscribeRss = false;
        unset($requestFilter['type']);

        $closeSearchForm = true;
        if ($is_search) {
            $closeSearchForm = false;
            return $this->view('site.publications.publications', compact('publications', 'default_img', 'type', 'requestFilter', 'hasSubscribeEmail', 'hasSubscribeRss', 'rssUrl', 'closeSearchForm'));
        }

        $publicationCategories = PublicationCategory::optionsList(true, $type);

        if ($type->value === PublicationTypesEnum::TYPE_ADVISORY_BOARD->value) {
            $publicationCategories = FieldOfAction::Active()->advisoryBoard()->with('translations')->select('id')->get();
        }

        return $this->view('site.publications.index',
            compact('publications', 'type', 'publicationCategories', 'pageTitle', 'paginate', 'default_img', 'requestFilter', 'hasSubscribeEmail', 'hasSubscribeRss', 'rssUrl', 'closeSearchForm'));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function news(Request $request)
    {
        $type = PublicationTypesEnum::TYPE_NEWS->value;
        $rssUrl = config('feed.feeds.news.url');
        $requestFilter = $request->all();

        $pageTitle = trans_choice(PublicationTypesEnum::getTypeName()[$type], 2);
        $is_search = $request->has('search');
        $paginate = $request->filled('paginate')
            ? $request->get('paginate')
            : $this->paginate;

        $news = $this->getPublications($request, $type);

        $requestFilter['type'] = str(PublicationTypesEnum::TYPE_NEWS->value);

        $hasSubscribeEmail = $this->hasSubscription(null, Publication::class, $requestFilter);
        $hasSubscribeRss = false;
        unset($requestFilter['type']);

        $default_img = asset(Publication::DEFAULT_IMG_NEWS);
        if ($is_search) {
            return $this->view('site.publications.news', compact('news', 'default_img', 'type', 'requestFilter', 'hasSubscribeEmail', 'hasSubscribeRss', 'rssUrl'));
        }

        $publicationCategories = PublicationCategory::optionsList(true);

        return $this->view('site.publications.index',
            compact('news', 'type', 'publicationCategories', 'pageTitle', 'paginate', 'default_img', 'requestFilter', 'hasSubscribeEmail', 'hasSubscribeRss', 'rssUrl'));
    }

    /**
     * Display publication details page
     *
     * @param $type
     * @param $id
     * @return View
     */
    public function details($type, $id)
    {
        $publication = Publication::select('publication.*')
            ->with(['translation', 'mainImg', 'category.translation'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            ->find($id);
//        $pageTitle = $publication->translation?->title;
        $default_img = $publication->type == PublicationTypesEnum::TYPE_NEWS->value ? $publication->newsDefaultImg : $publication->libraryDefaultImg;

        $pageTitle = trans_choice(PublicationTypesEnum::getTypeName()[$type], 2);
        $this->setBreadcrumbsTitle($publication->title);
        $this->setSeo($publication->meta_title ?? $publication->title, $publication->meta_description ?? $publication->short_content, $publication->meta_keyword, array('title' => $publication->meta_title ?? $publication->title, 'img' => $publication->mainImg ? $publication->mainImg->path : Publication::DEFAULT_IMG_LIBRARY));

        return $this->view('site.publications.details', compact('publication', 'type', 'pageTitle', 'default_img'));
    }


    /**
     * @param Request $request
     * @param $type
     * @return mixed
     */
    private function getPublications(Request $request, $type)
    {
        $sort = ($request->offsetGet('sort'))
            ? $request->offsetGet('sort')
            : "DESC";
        $order_by = ($request->offsetGet('order_by'))
            ? $request->offsetGet('order_by')
            : "published_at";
        $sort_table = (in_array($order_by, Publication::TRANSLATABLE_FIELDS))
            ? "publication_translations"
            : "publication";
        $paginate = $request->filled('paginate')
            ? $request->get('paginate')
            : $this->paginate;
        $published_from = $request->get('published_from');
        $published_till = $request->get('published_till');
        $keywords = $request->get('keywords');
        $categories = $request->get('categories');
        if ($categories) {
            $categories = array_filter($categories, fn($m) => $m > 0);
        }

        $publications = Publication::select('publication.*')
            ->with(['translation', 'mainImg', 'category.translation'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            ->whereType($type)
            ->when($categories, function ($query, $categories) {
                return $query->whereIn('publication_category_id', $categories);
            })
            ->when($keywords, function ($query, $keywords) {
                return $query->whereRaw("(title::text ILIKE '%$keywords%' OR content::text ILIKE '%$keywords%')");
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
