@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', trans_choice('custom.advisory_boards', 2))

@section('content')

<div class="row">
    @include('site.advisory-boards.side_menu_home')
    <div class="col-lg-10 right-side-content py-2" id="listContainer">
        @include('site.advisory-boards.list')
    </div>

</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            ajaxList('#listContainer');
        });
    </script>
@endpush


@includeIf('modals.site.search-other-nomenclature')
@push('scripts')
    <script>
        const other_nomenclatures = @json($other_nomenclatures ?? []);
        const searchModal = $('#search-other-nomenclature-modal');

        function showOtherValues(nomenclature_key) {
            searchModal.find('input[name=select2_id]').val(nomenclature_key);

            const nomenclatures = other_nomenclatures[nomenclature_key];
            const list = searchModal.find('.list-group');

            list.html('');
            searchModal.modal('show');

            if (nomenclatures.length) {
                for (let nomenclature of nomenclatures) {
                    const item = createListGroupItem(nomenclature.translation.name, nomenclature.id);

                    list.append(item);
                }
            }
        }
    </script>
@endpush
