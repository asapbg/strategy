<p>
    <b>Образецът на частична предварителна оценка на въздействието влиза в сила от 01 януари 2021 г.</b>
</p>
<h3>Частична предварителна оценка на въздействието</h3>
<div>
    <div class="row">
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'institution'])</h5>
        </div>
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'regulatory_act'])</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            @include('form_partials.radio', ['name' => 'included_in_program', 'value' => 0, 'label' => 'forms.not_included_in_program'])
        </div>
        <div class="col-sm-6">
            @include('form_partials.radio', ['name' => 'included_in_program', 'value' => 1, 'label' => 'forms.included_in_program'])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h5>@include('form_partials.text', ['name' => 'contact_person'])</h5>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'phone'])</h5>
        </div>
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'email'])</h5>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-sm-12">
            <h4>1. {{ __('forms.problem_to_solve') }}</h4>
            <p>
                <i>1.1. Кратко опишете проблема/проблемите и причините за неговото/тяхното възникване. По възможност посочете числови стойности.<br>
                    1.2. Посочете възможно ли е проблемът да се реши в рамките на съществуващото законодателство чрез промяна в организацията на работа и/или чрез въвеждане на нови технологични възможности (например съвместни инспекции между няколко органа и др.).<br>
                    1.3. Посочете защо действащата нормативна рамка не позволява решаване на проблема/проблемите.<br>
                    1.4. Посочете задължителните действия, произтичащи от нормативни актове от по-висока степен или актове от правото на ЕС.<br>
                    1.5. Посочете дали са извършени последващи оценки на нормативния акт или анализи за изпълнението на политиката и какви са резултатите от тях? </i>
            </p>
            @include('form_partials.shared.problems')
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-sm-12">
            <h4>2. {{ __('forms.goal_goals') }}</h4>
            <p>
                <i>Посочете определените цели за решаване на проблема/проблемите, по възможно най-конкретен и измерим начин, включително индикативен график за тяхното постигане. Целите е необходимо да са насочени към решаването на проблема/проблемите и да съответстват на действащите стратегически документи.</i>
            </p>
            @include('form_partials.shared.goals')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h4>3. {{ __('forms.interested_parties') }}</h4>
            <p>
                <i>Посочете всички потенциални заинтересовани страни/групи заинтересовани страни (в рамките на процеса по извършване на частичната предварителна частична оценка на въздействието и/или при обществените консултации по чл. 26 от Закона за нормативните актове), върху които предложенията ще окажат пряко или косвено въздействие (бизнес в дадена област/всички предприемачи, неправителствени организации, граждани/техни представители, държавни органи/общини и др.).</i>
            </p>
            @include('form_partials.array_textarea_number', ['name' => 'interested_parties[]', 'buttonLabel' => 'forms.interested_party', 'class' => 'summernote'])
        </div>
    </div>
</div>
