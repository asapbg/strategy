<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TEXT SEARCH CONFIGURATION bulgarian (COPY = simple);');
        DB::statement('CREATE TEXT SEARCH DICTIONARY bulgarian_ispell (
                                TEMPLATE = ispell,
                                DictFile = bulgarian,
                                AffFile = bulgarian,
                                StopWords = bulgarian
                            );');
        DB::statement('CREATE TEXT SEARCH DICTIONARY bulgarian_simple (
                                TEMPLATE = pg_catalog.simple,
                                STOPWORDS = bulgarian
                            );');
        DB::statement('ALTER TEXT SEARCH CONFIGURATION bulgarian ALTER MAPPING FOR asciiword, asciihword, hword, hword_part, word WITH bulgarian_ispell, bulgarian_simple;');

        DB::statement("ALTER TABLE strategic_document_file ADD COLUMN sd_file_text_ts_bg tsvector GENERATED ALWAYS AS (to_tsvector('bulgarian', regexp_replace(regexp_replace(file_text, E'<[^>]+>', '', 'gi'), E'&nbsp;', '', 'g'))) STORED;");
        DB::statement("CREATE INDEX sd_file_text_ts_bg ON strategic_document_file USING GIN (sd_file_text_ts_bg);");

        DB::statement("ALTER TABLE files ADD COLUMN file_text_ts_bg tsvector GENERATED ALWAYS AS (to_tsvector('simple', regexp_replace(regexp_replace(file_text, E'<[^>]+>', '', 'gi'), E'&nbsp;', '', 'g'))) STORED;");
        DB::statement("CREATE INDEX file_text_ts_bg ON files USING GIN (file_text_ts_bg);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
