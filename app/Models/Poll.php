<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;

class Poll extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'content'];
    const MODULE_NAME = 'custom.polls';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'poll';

    //activity
    protected string $logName = "poll";

    protected $fillable = ['begin_date', 'end_date', 'consultation_id', 'active'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ],
            'content' => [
                'type' => 'ckeditor',
                'rules' => ['required', 'string']
            ],
        );
    }

    public function answers()
    {
        return $this->hasMany(PollAnswer::class, 'poll_id', 'id');
    }

    public static function optionsList()
    {
        return DB::table('poll')
            ->select(['poll.id', 'poll_translations.name'])
            ->join('poll_translations', 'poll_translations.poll_id', '=', 'poll.id')
            ->where('poll_translations.locale', '=', app()->getLocale())
            ->orderBy('poll_translations.name', 'asc')
            ->get();
    }
}
