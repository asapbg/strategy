<div class="modal fade" id="search-other-nomenclature-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title mb-0">тест</h4>

                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body overflow-scroll" style="max-height: 40vh;">
                <input type="hidden" name="select2_id"/>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="other-nomenclature-search"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="{{ __('custom.search') }}" aria-label="search" aria-describedby="other-nomenclature-search">
                </div>

                <div class="list-group"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('custom.cancel') }}</button>

                <button type="button" class="btn btn-success" onclick="generateOptionsFromListGroup();">{{ __('custom.select') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const current_language = @json(App::getLocale());

        function generateOptionsFromListGroup() {
            const select2_id = $('#search-other-nomenclature-modal input[name=select2_id]').val();
            const list = $('#search-other-nomenclature-modal').find('.list-group');
            const selected_items = [];

            // filter active items
            for (let item of list.find('.active')) {
                selected_items.push(item);
            }

            for (let item of selected_items) {
                const id = item.getAttribute('data-id');
                const name = item.textContent;

                removeOptionFromSelect2('#' + select2_id, current_language == 'bg' ? 'друг' : 'other');
                addOptionToSelect2('#' + select2_id, name, id, true);
            }

            $('#search-other-nomenclature-modal').modal('hide');
        }

        document.querySelector('#other-nomenclature-search + input').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase(); // Get the input value and convert to lowercase
            const listItems = document.querySelectorAll('.list-group .list-group-item'); // Get all list items

            listItems.forEach(item => {
                if (item.textContent.toLowerCase().includes(searchTerm)) {
                    item.classList.remove('d-none'); // Show the item if it matches
                } else {
                    item.classList.add('d-none'); // Hide the item if it doesn't match
                }
            });
        });
    </script>
@endpush
