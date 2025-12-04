<?php

namespace App\Models;

use App\Enums\PublicationTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class Publication extends ModelActivityExtend implements TranslatableContract, Feedable
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'short_content', 'content', 'meta_keyword', 'meta_title', 'meta_description'];
    const MODULE_NAME = ('custom.publications');

    const DEFAULT_IMG_LIBRARY = 'img'.DIRECTORY_SEPARATOR.'library.jpg';
    const DEFAULT_IMG_NEWS = 'img'.DIRECTORY_SEPARATOR.'news-2.jpg';
    const DEFAULT_IMG_ADV = 'images'.DIRECTORY_SEPARATOR.'ms-2023.jpg';
    const DEFAULT_IMG_OGP = 'images'.DIRECTORY_SEPARATOR.'ogp-img.png';

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'publication';

    //activity
    protected string $logName = "publication";

    protected $fillable = ['slug', 'type', 'publication_category_id', 'file_id', 'published_at', 'active', 'advisory_boards_id', 'users_id', 'is_adv_board_user', 'old_id'];

    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        $extraInfo = '';
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $extraInfo.$this->content,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
            'enclosure' => $this->mainImg ? $this->mainImgAsset : $this->defaultImg,
            'link' => route('strategy-document.view', ['id' => $this->id]),
            'authorName' => '',
            'authorEmail' => ''
        ]);
    }
    /**
     * We use this method for rss feed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItemsPublication(): \Illuminate\Database\Eloquent\Collection
    {
        $request = request();
        $sort = ($request->offsetGet('sort'))
            ? $request->offsetGet('sort')
            : "DESC";
        $order_by = ($request->offsetGet('order_by'))
            ? $request->offsetGet('order_by')
            : "created_at";
        $sort_table = (in_array($order_by, Publication::TRANSLATABLE_FIELDS))
            ? "publication_translations"
            : "publication";
        $published_from = $request->get('published_from');
        $published_till = $request->get('published_till');
        $keywords = $request->get('keywords');
        $categories = $request->get('categories');

        return static::with(['translations'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            ->ActivePublic()
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
            ->where('type', '=', PublicationTypesEnum::TYPE_LIBRARY->value)
            ->orderBy("$sort_table.$order_by", $sort)
            ->orderByRaw("created_at desc")
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

    /**
     * We use this method for rss feed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItemsNews(): \Illuminate\Database\Eloquent\Collection
    {
        $request = request();
        $sort = ($request->offsetGet('sort'))
            ? $request->offsetGet('sort')
            : "DESC";
        $order_by = ($request->offsetGet('order_by'))
            ? $request->offsetGet('order_by')
            : "created_at";
        $sort_table = (in_array($order_by, Publication::TRANSLATABLE_FIELDS))
            ? "publication_translations"
            : "publication";
        $published_from = $request->get('published_from');
        $published_till = $request->get('published_till');
        $keywords = $request->get('keywords');
        $categories = $request->get('categories');

        return static::with(['translations'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            ->ActivePublic()
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
            ->where('type', '=', PublicationTypesEnum::TYPE_NEWS->value)
            ->orderBy("$sort_table.$order_by", $sort)
            ->orderByRaw("created_at desc")
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public function scopeActivePublic($query){
        $query->where('publication.active', true)
            ->where('publication.published_at', '<=', Carbon::now()->format('Y-m-d'));
    }

    public function scopeAdvBoard($query){
        $query->where('publication.type', '=', PublicationTypesEnum::TYPE_ADVISORY_BOARD->value);
    }

    public function scopeOgp($query){
        $query->where('publication.type', '=', PublicationTypesEnum::TYPE_OGP_NEWS->value);
    }

    /**
     * @return bool
     */
    public function isPublishedNewsOrLibrary(): bool
    {
        return $this->active && $this->published_at <= Carbon::now()->format('Y-m-d')
            && in_array($this->type, [PublicationTypesEnum::TYPE_NEWS->value, PublicationTypesEnum::TYPE_LIBRARY->value]);
    }

    /**
     * Content
     */
    protected function advCategory(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type == PublicationTypesEnum::TYPE_ADVISORY_BOARD->value ?
                ($this->advisory_boards_id ? $this->advBoard->name : trans_choice('custom.advisory_boards', 1))
                : '',
        );
    }

    protected function advDefaultImg(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('images'.DIRECTORY_SEPARATOR.'ms-2023.jpg'),
        );
    }

    protected function libraryDefaultImg(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('img'.DIRECTORY_SEPARATOR.'library.jpg'),
        );
    }

    protected function newsDefaultImg(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('img'.DIRECTORY_SEPARATOR.'news-2.jpg'),
        );
    }

    protected function defaultImg(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type == PublicationTypesEnum::TYPE_ADVISORY_BOARD->value ? asset(self::DEFAULT_IMG_ADV)
                : ($this->type == PublicationTypesEnum::TYPE_NEWS->value ? asset(self::DEFAULT_IMG_NEWS)
                    : ($this->type == PublicationTypesEnum::TYPE_OGP_NEWS->value ? asset(self::DEFAULT_IMG_OGP) : asset(self::DEFAULT_IMG_LIBRARY)) ),
        );
    }

    protected function mainImgAsset(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('files'.DIRECTORY_SEPARATOR.str_replace('files'.DIRECTORY_SEPARATOR, '', $this->mainImg->path)),
        );
    }

    protected function thumbListAsset(): Attribute
    {
        $type = $this->type == PublicationTypesEnum::TYPE_LIBRARY->value ? 'list_publication' : 'list_news';
        return Attribute::make(
            get: fn () => $this->mainImg && file_exists(public_path('files'.DIRECTORY_SEPARATOR.'thumbnails'.DIRECTORY_SEPARATOR.$this->mainImg->id.'_thumbnail_'.$type.'.jpg')) ? asset('files'.DIRECTORY_SEPARATOR.'thumbnails'.DIRECTORY_SEPARATOR.$this->mainImg->id.'_thumbnail_'.$type.'.jpg') : ($this->mainImg ? asset('files'.DIRECTORY_SEPARATOR.str_replace('files'.DIRECTORY_SEPARATOR, '', $this->mainImg->path)) : $this->defaultImg),
        );
    }

    protected function thumbHomePageAsset(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mainImg && file_exists(public_path('files'.DIRECTORY_SEPARATOR.'thumbnails'.DIRECTORY_SEPARATOR.$this->mainImg->id.'_thumbnail_home.jpg')) ? asset('files'.DIRECTORY_SEPARATOR.'thumbnails'.DIRECTORY_SEPARATOR.$this->mainImg->id.'_thumbnail_home.jpg') : ($this->mainImg ? asset('files'.DIRECTORY_SEPARATOR.str_replace('files'.DIRECTORY_SEPARATOR, '', $this->mainImg->path)) : $this->defaultImg),
        );
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:2000'],
                'required_all_lang' => true
            ],
            'short_content' => [
                'type' => 'textarea',
                'rules' => ['nullable', 'string'],
                'required_all_lang' => false
            ],
            'content' => [
                'type' => 'summernote',
                'rules' => ['required', 'string'],
                'required_all_lang' => false
            ],
            'meta_title' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
                'required_all_lang' => false
            ],
            'meta_keyword' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
                'required_all_lang' => false
            ],
            'meta_description' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
                'required_all_lang' => false
            ],
            'file' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
                'required_all_lang' => false
            ]
        );
    }

    public function getRelatedClass(): string
    {
        return match ($this->type) {
            PublicationTypesEnum::TYPE_ADVISORY_BOARD->value => FieldOfAction::class,
            default => PublicationCategory::class,
        };
    }

//    /**
//     * @return BelongsTo
//     */
//    public function category(): BelongsTo
//    {
//        return $this->belongsTo(PublicationCategory::class, 'publication_category_id');
//    }

    /**
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(PublicationCategory::class, 'id', 'publication_category_id');
    }

    /**
     * @return HasOne
     */
    public function mainImg()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'id_object', 'id')->where('code_object', '=', File::CODE_OBJ_PUBLICATION);
    }

    /**
     * @return HasOne
     */
    public function advBoard(): HasOne
    {
        return $this->hasOne(AdvisoryBoard::class, 'id', 'advisory_boards_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

    public static function list(array $filter, $type){
        $filter['from'] = empty($filter['published_from']) ? null : $filter['published_from'];
        $filter['to'] = empty($filter['published_till']) ? null : $filter['published_till'];
        $filter['category'] = empty($filter['categories']) ? null : $filter['categories'];

        return self::select('publication.*')
            ->ActivePublic()
            ->where('publication.type', '=', (int)$type)
            ->with(['translations', 'category', 'category.translations'])
            ->leftJoin('publication_category', 'publication_category.id', '=', 'publication.publication_category_id')
            ->leftJoin('publication_category_translations', function ($j){
                $j->on('publication_category_translations.publication_category_id', '=', 'publication_category.id')
                    ->where('publication_category_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('publication_translations', function ($j){
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($filter)
            ->get();
    }
}
