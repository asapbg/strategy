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
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->title,
            'summary' => '',
            'updated' => $this->updated_at ?? $this->created_at,
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
        return static::with(['translations'])
            ->ActivePublic()
            ->where('type', '=', PublicationTypesEnum::TYPE_LIBRARY->value)
            ->orderByRaw("(case when updated_at is null then created_at else updated_at end) desc")
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

    /**
     * We use this method for rss feed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItemsNews(): \Illuminate\Database\Eloquent\Collection
    {
        return static::with(['translations'])
            ->ActivePublic()
            ->where('type', '=', PublicationTypesEnum::TYPE_NEWS->value)
            ->orderByRaw("(case when updated_at is null then created_at else updated_at end) desc")
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
