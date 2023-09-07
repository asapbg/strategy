<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Searchable;

class File extends Model
{
    use SoftDeletes, Searchable;
    public $timestamps = true;

    const CODE_OBJ_PUBLICATION = 1;
    const CODE_OBJ_LEGISLATIVE_PROGRAM = 2;
    const CODE_OBJ_OPERATIONAL_PROGRAM = 3;

    const PUBLICATION_UPLOAD_DIR = 'publications'.DIRECTORY_SEPARATOR;
    const PUBLIC_CONSULTATIONS_UPLOAD_DIR = 'pc'.DIRECTORY_SEPARATOR;

    const ALLOWED_FILE_EXTENSIONS = ['doc', 'docx', 'xsl', 'xslx', 'pdf', 'jpeg', 'jpg', 'png'];
    protected $guarded = [];

    /**
     * Content
     */
    protected function preview(): Attribute
    {
        return Attribute::make(
            get: fn () => str_contains($this->content_type, 'image') ? '<img src="'.asset($this->path).'" class="img-thumbnail sm-thumbnail">'
                : ( str_contains($this->content_type, 'pdf') ? '<img src="'.asset('img/default_pdf.png').'" class="img-thumbnail sm-thumbnail">' : '' )
        );
    }
}
