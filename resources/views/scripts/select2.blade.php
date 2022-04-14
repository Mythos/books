@push('scripts')
    <script>
        var select2Options = {
            theme: 'bootstrap-5',
            placeholder: "{{ __('Not set') }}"
        };
        document.addEventListener('livewire:load', function() {
            $('select').select2(select2Options);
            $('select').on('change', function(e) {
                var $element = $(this);
                var field = $element.attr('name');
                var data = $element.select2('val');
                @this.set(field, data);
            });
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });
        document.addEventListener('livewire:update', function() {
            $('select').select2(select2Options);
        });
    </script>
@endpush
