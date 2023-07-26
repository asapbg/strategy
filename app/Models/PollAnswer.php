<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class PollAnswer extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'content'];
    const MODULE_NAME = 'custom.poll_answers';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'poll_answer';

    //activity
    protected string $logName = "poll_answer";

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

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'id', 'poll_id');
    }

    public static function optionsList()
    {
        return DB::table('poll_answer')
            ->select(['poll_answer.id', 'poll_answer_translations.name'])
            ->join('poll_answer_translations', 'poll_answer_translations.poll_answer_id', '=', 'poll_answer.id')
            ->where('poll_answer_translations.locale', '=', app()->getLocale())
            ->orderBy('poll_answer_translations.name', 'asc')
            ->get();
    }
}
