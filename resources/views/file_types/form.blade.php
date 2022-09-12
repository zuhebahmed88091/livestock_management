<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">{{__('commons.name')}}</label>
            <input class="form-control" name="name" type="text" id="name"
                value="{{ old('name', optional($fileType)->name) }}" minlength="1" maxlength="10" required>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="" class="d-none" {{ old('status', optional($fileType)->status ?: '') == '' ? 'selected' : '' }} disabled selected>Enter status here...</option>
                @foreach (['Active' => 'Active',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($fileType)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

