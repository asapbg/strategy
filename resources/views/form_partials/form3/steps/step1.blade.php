<p>
    <b>(Образецът е одобрен от Съвета за административната реформа на 03 септември 2020 г.)</b>
</p>
<h3>{{ __('forms.form3') }}</h3>

<h3>1. {{ __('forms.problem_to_solve') }}</h3>
<div class="row">
    <div class="col-sm-12">
        <h4>1. {{ __('forms.problem_to_solve') }}</h4>
        @include('form_partials.shared.problems')
    </div>
</div>

<h3>2. {{ __('forms.interested_parties') }}</h3>
<p>
    <i>
        Посочете всички потенциални засегнати и заинтересовани страни, върху които предложението ще окаже пряко или косвено въздействие (бизнес в дадена  област/всички предприемачи, неправителствени организации, граждани/техни представители, държавни органи, др.). 
        <br>
        Когато заинтересованите страни се посочват по групи е необходимо да се посочи техният брой, за да се определи мащаба на въздействията.
    </i>
</p>
@include('form_partials.array_textarea_number', ['name' => 'interested_parties[]', 'buttonLabel' => 'forms.interested_party'])

<h3>3. {{ __('forms.goal_goals') }}</h3>
@include('form_partials.shared.goals')
