<div class="form_input" wire:ignore>
    <select id="select2-dropdown" wire:model="selectedVals" name="selectedVals[]" multiple="multiple" class="form-control">
        @foreach($datas as $d)
            <option value="{{ $d['id'] }}" @if (in_array($d['id'], $selectedVals)) selected="selected" @endif>{{ $d['name'] }}</option>
        @endforeach
    </select>

    @push('scripts')
        <script type="text/javascript">
            function matchCustom(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                return null;
                }

                // `params.term` should be the term that is used for searching
                // `data.text` is the text that is displayed for the data object
                if (data.text.indexOf(params.term) > -1) {
                var modifiedData = $.extend({}, data, true);
                modifiedData.text += ' (matched)';

                // You can return modified objects from here
                // This includes matching the `children` how you want in nested data sets
                return modifiedData;
                }

                // Return `null` if the term should not be displayed
                return null;
            }
            $(document).ready(function () {
                $('#select2-dropdown').select2({
                    placeholder: "Ajouter des permissions",
                    allowClear: true,
                    multiple: true,
                    matcher: matchCustom
                });
                $('#select2-dropdown').on('change', function (e) {
                    var data = $('#select2-dropdown').select2("val");
                    let closeButton = $('.select2-selection__clear')[0];
                    if(typeof(closeButton)!='undefined'){
                        if(data.length<=0)
                        {
                            $('.select2-selection__clear')[0].children[0].innerHTML = '';
                        } else{
                            $('.select2-selection__clear')[0].children[0].innerHTML = 'x';
                        }
                    }
                    @this.set('selectedVals', data);
                    @this.dispatch('dataSelected', [...data]);
                });
                Livewire.on('resetData', (data) => {
                    $('#select2-dropdown').val(data);
                    $('#select2-dropdown').select2({
                        allowClear: true,
                        multiple: true,
                        matcher: matchCustom
                    }).trigger('change')
                })
            });


        </script>
    @endpush
</div>
