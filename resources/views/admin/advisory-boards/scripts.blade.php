@push('scripts')
    <script type="application/javascript">
        function loadMemberData(url) {
            const form = document.querySelector('form[name=CHAIRMAN_FORM_EDIT]');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    form.querySelector('input[name=advisory_board_member_id]').value = data.id;
                    form.querySelector('#name_bg').value = data.translations[0].name;
                    form.querySelector('#name_en').value = data.translations[1].name;
                    form.querySelector('#advisory_type_id_change').value = data.advisory_type_id;
                    $('#advisory_type_id_change').trigger('change');
                    form.querySelector('#advisory_chairman_type_id_change').value = data.advisory_chairman_type_id;
                    $('#advisory_chairman_type_id_change').trigger('change');
                    form.querySelector('#job_bg').value = data.translations[0].job;
                    form.querySelector('#job_en').value = data.translations[1].job;
                    form.querySelector('#consultation_level_id_change').value = data.consultation_level_id;
                    $('#consultation_level_id_change').trigger('change');
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function updateMemberAjax(element) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = document.querySelector('form[name=CHAIRMAN_FORM_EDIT]');
            const formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.advisory-boards.members.update') }}",
                data: formData,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (result) {
                    // Get a reference to the modal
                    const modal = new bootstrap.Modal(document.getElementById('modal-edit-chairman'));

                    // Hide the modal
                    modal.hide();

                    window.location.reload();
                },
                error: function (xhr) {
                    changeButtonState(element, 'finished');

                    // Handle error response
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        for (let i in errors) {
                            const search_class = '.error_' + i;
                            form.querySelector(search_class).textContent = errors[i][0];
                        }
                    }
                }
            });
        }

        /**
         * Change button state for ajax forms.
         * State can be either 'loading' or 'finished'.
         *
         * @param element
         * @param state
         */
        function changeButtonState(element, state = 'loading') {
            const button_text_translation = state === 'loading' ? @json(__('custom.loading')) : @json(__('custom.add'));
            const loader = element.querySelector('.spinner-grow');

            element.querySelector('.text').innerHTML = button_text_translation;
            element.disabled = state === 'loading';

            if (state === 'loading') {
                loader.classList.remove('d-none');
                return;
            }

            loader.classList.add('d-none');
        }
    </script>
@endpush
