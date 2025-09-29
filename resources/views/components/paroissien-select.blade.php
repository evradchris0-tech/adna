<div  @class(["form_group","required" => $isRequired]) wire:ignore>
    <label for="paroissiens">{{$label}}</label>
    <div class="form_input">
        <select
            @if ($isRequired) required @endif
            @if ($isLive) wire:model.live='paroissien' @endif
            @if (!$isLive) wire:model='paroissien' @endif
            @if ($hasEvent != "") wire:change='{{$hasEvent}}' @endif
            id="paroissiens"
            name="paroissiens_id"
            {{$id && $id !== 0 ? "disabled" : ""}}
            class="form-control"
        >
            <option value="" selected>paroissiens</option>
            @foreach ($paroissiens as $paroissien)
                <option value="{{$paroissien->id}}">{{$paroissien->firstname}} {{$paroissien->lastname}}</option>
            @endforeach
        </select>
    </div>
    @error('engagementForm.paroissiens_id') <span class="error">{{ $message }}</span> @enderror
    @error('versementForm.paroissiens_id') <span class="error">{{ $message }}</span> @enderror
</div>
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
            $('#paroissiens').select2({
                placeholder: "Choisir un paroissien",
                allowClear: true,
                multiple: false,
                matcher: matchCustom
            });
            $('#paroissiens').on('change', function (e) {
                var id = $('#paroissiens').select2("val");
                @this.dispatch('paroisienSelected', [id]);
                @this.set('paroissien', id);
            });
        });
    </script>
@endpush
