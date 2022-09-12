<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">{{__('commons.title')}}</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($inventoryUnit)->title) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">{{__('commons.status')}}</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Active' => 'Active',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($inventoryUnit)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

