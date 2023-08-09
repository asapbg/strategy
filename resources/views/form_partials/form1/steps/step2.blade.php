<table width="100%">
    <tr>
        <td colspan="2">
            <h4>4. Варианти на действие. Анализ на въздействията:</h4>
        </td>
    </tr>
    @include('form_partials.shared.variant', ['point' => 4])
    <tr>
        <td colspan="2">
            <h5>5. Сравняване на вариантите:</h5>
            <p>Степени на изпълнение по критерии: 1) висока; 2) средна; 3) ниска.</p>
            @include('form_partials.shared.comparison')
        </td>
    </tr>
</table>