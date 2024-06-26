<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\FieldOfAction;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PollsController extends ApiController
{
    public function list(Request $request){
        if(isset($this->request_inputs['date-after']) && !empty($this->request_inputs['date-after'])){
            if(!$this->checkDate($this->request_inputs['date-after'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-after'])->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-before']) && !empty($this->request_inputs['date-before'])){
            if(!$this->checkDate($this->request_inputs['date-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-before'])->format('Y-m-d');
        }

        $data = DB::select('
            select
                "poll".id,
                "poll"."name",
                "public_consultation"."reg_num" as "consultation_reg_num",
                "poll"."start_date" as "date_start",
                "poll"."end_date" as "date_end",
                "poll"."only_registered",
                (
                select
                        json_agg(json_build_object(\'name\', poll_question.name, \'options\',
                            (
                            select json_agg(json_build_object(\'name\', poll_question_option.name, \'votes\', (select count(upo.user_poll_id) from user_poll_option upo where upo.poll_question_option_id = poll_question_option.id)))
                            from poll_question_option
                            where poll_question_option.poll_question_id = poll_question.id and poll_question_option.deleted_at is null
                        )))
                     from poll_question
                     where true
                        '.(!$this->authanticated ? ' and poll_question.deleted_at is null ' : '').'
                         and poll_question.poll_id = poll.id
                 ) as questions
                from "poll"
                left join "public_consultation" on "public_consultation"."id" = "poll"."consultation_id"
                where true
                    '.(!$this->authanticated ? ' and "poll"."deleted_at" is null ' : '').'
                    '.(isset($from) ? ' and poll.created_at >= \''.$from.' 00:00:00'.'\'' : '').'
                    '.(isset($to) ? ' and poll.created_at <= \''.$to.' 23:59:59'.'\'' : '').'
                order by "poll"."start_date" desc

            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
            ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->questions)){
                    $row->questions = json_decode($row->questions, true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;

        return $this->output($data);
    }

    public function show(Request $request, $id = 0)
    {
        $data = DB::select('
                    select
                "poll"."name",
                "public_consultation"."reg_num" as "consultation_reg_num",
                "poll"."start_date" as "date_start",
                "poll"."end_date" as "date_end",
                "poll"."only_registered",
                (
                select
                        json_agg(json_build_object(\'name\', poll_question.name, \'options\',
                            (
                            select json_agg(json_build_object(\'name\', poll_question_option.name, \'votes\', (select count(upo.user_poll_id) from user_poll_option upo where upo.poll_question_option_id = poll_question_option.id)))
                            from poll_question_option
                            where poll_question_option.poll_question_id = poll_question.id and poll_question_option.deleted_at is null
                        )))
                     from poll_question
                     where true
                        '.(!$this->authanticated ? ' and poll_question.deleted_at is null ' : '').'
                         and poll_question.poll_id = poll.id
                 ) as questions
                from "poll"
                left join "public_consultation" on "public_consultation"."id" = "poll"."consultation_id"
                where true
                     '.(!$this->authanticated ? ' and "poll"."deleted_at" is null ' : '').'
                    and "poll".id = '.$id.'
                order by "poll"."start_date" desc
                ');

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data->questions)){
                $data->questions = json_decode($data->questions, true);
            }
        }
        return $this->output($data);
    }

    public function questions(Request $request, int $id = 0)
    {
        $data = DB::select('
            select
                poll_question.name,
                (
                    select json_agg(json_build_object(\'name\', poll_question_option.name, \'votes\', (select count(upo.user_poll_id) from user_poll_option upo where upo.poll_question_option_id = poll_question_option.id)))
                    from poll_question_option
                    where poll_question_option.poll_question_id = poll_question.id and poll_question_option.deleted_at is null
                ) as options
             from poll_question
             join poll on poll_question.poll_id = poll.id
             where true
                '.(!$this->authanticated ? ' and poll_question.deleted_at is null and poll.deleted_at is null ' : '').'
                and poll.id = '.$id.'
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->options)){
                    $row->options = json_decode($row->options, true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;

        return $this->output($data);
    }
}
