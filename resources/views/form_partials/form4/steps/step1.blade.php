<h5>Списък на използваните съкращения</h5>
<table width="100%" class="table">
<thead>
    <td width="40%">Съкращение</td>
    <td>Пълно наименование</td>
</thead>
<tbody>
    @include('form_partials.shared.abbreviations', ['name' => 'abbreviations[]', 'buttonLabel' => 'forms.abbreviation'])
</tbody>
</table>

<h5>Списък на фигурите</h5>
<table width="100%" class="table">
<thead>
    <td width="40%">Фигура №</td>
    <td>Заглавие/описание на фигурата</td>
</thead>
<tbody>
    @include('form_partials.shared.array_texts', ['name' => 'figures[]', 'buttonLabel' => 'forms.figure', 'keys' => ['number', 'text']])
</tbody>
</table>

<h5>Списък на таблиците</h5>
<table width="100%" class="table">
<thead>
    <td width="40%">Таблица №</td>
    <td>Заглавие/описание на таблицата</td>
</thead>
<tbody>
    @include('form_partials.shared.array_texts', ['name' => 'tables[]', 'buttonLabel' => 'forms.table', 'keys' => ['number', 'text']])
</tbody>
</table>

<p>
    <span class="text-danger">*</span>  Този образец на доклад за последваща оценка на въздействието е изготвен с цел определяне на структурата на докладите от извършените последващи оценки и на необходимите им реквизити.
</p>
<p>
    <span class="text-danger">**</span>  С образеца се цели и унифициране на подходите по оформяне на докладите, като екипът, извършващ последващата оценката на въздействието, свободно може да видоизменя и допълва включените реквизити съобразно спецификата на извършваната оценка, като се съобрази с изискването на минималното съдържание на доклада съгласно чл. 41 от НОМИОВ.
</p>
<p>
    <span class="text-danger">***</span>  Образецът детайлизира предвиденото в Закона за нормативните актове, Наредбата за обхвата и методологията за извършване на оценка на въздействието и Ръководството за извършване на последваща оценка на въздействието, прието с Решение № 885 на Министерския съвет от 3 декември 2020 г.
</p>
