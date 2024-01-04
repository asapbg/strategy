<?php

namespace App\Models;

use App\Enums\PublicationTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Publication extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'short_content', 'content', 'meta_keyword', 'meta_title', 'meta_description'];
    const MODULE_NAME = ('custom.publications');

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'publication';

    //activity
    protected string $logName = "publication";

    protected $fillable = ['slug', 'type', 'publication_category_id', 'file_id', 'published_at', 'active', 'advisory_boards_id', 'users_id', 'is_adv_board_user'];

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

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:2000']
            ],
            'short_content' => [
                'type' => 'textarea',
                'rules' => ['nullable', 'string']
            ],
            'content' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
            'meta_title' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ],
            'meta_keyword' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ],
            'meta_description' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ],
            'file' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
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

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo($this->getRelatedClass(), 'publication_category_id');
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
}
