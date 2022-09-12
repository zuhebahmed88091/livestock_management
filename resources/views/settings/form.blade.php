<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">{{__('commons.title')}}</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($setting)->title) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('constant') ? 'has-error' : '' }}">
            <label for="constant">{{__('setings.constant')}}</label>
            <input class="form-control" name="constant" type="text" id="constant"
                value="{{ old('constant', optional($setting)->constant) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('constant', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('value') ? 'has-error' : '' }}">
            <label for="value">{{__('setings.value')}}</label>
            <input class="form-control" name="value" type="text" id="value"
                value="{{ old('value', optional($setting)->value) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('value', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('field_type') ? 'has-error' : '' }}">
            <label for="field_type">{{__('setings.field_type')}}</label>
            <select class="form-control  select-admin-lte" id="field_type" name="field_type" required>
                <option value="" class="d-none" {{ old('field_type', optional($setting)->field_type ?: '') == '' ? 'selected' : '' }} disabled selected>Enter field type here...</option>
                @foreach (['Text' => 'Text',
'Options' => 'Options'] as $key => $text)
                <option value="{{ $key }}" {{ old('field_type', optional($setting)->field_type) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('field_type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('options') ? 'has-error' : '' }}">
            <label for="options">Options</label>
            <input class="form-control" name="options" type="text" id="options"
                value="{{ old('options', optional($setting)->options) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('options', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="" class="d-none" {{ old('status', optional($setting)->status ?: '') == '' ? 'selected' : '' }} disabled selected>Enter status here...</option>
                @foreach (['0' => '0',
'1' => '1'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($setting)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

