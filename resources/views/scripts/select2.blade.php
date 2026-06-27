@push('scripts')
    <script>
        var select2Options = {
            theme: 'bootstrap-5',
            placeholder: "{{ __('Not set') }}"
        };
        function initializeSelect2() {
            $('select:visible').select2(select2Options);
            $('select:visible').off('change.select2-livewire').on('change.select2-livewire', function(e) {
                var $element = $(this);
                var field = $element.attr('name');
                var data = $element.select2('val');
                @this.set(field, data);
            });
        }

        document.addEventListener('livewire:initialized', function() {
            initializeSelect2();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });
        document.addEventListener('livewire:init', function() {
            Livewire.hook('morphed', function() {
                initializeSelect2();
            });
        });
    </script>
@endpush
