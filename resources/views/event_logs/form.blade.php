<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
            <label for="user_id">{{__('adminPrivilege.user')}}</label>
            <select class="form-control  select-admin-lte" id="user_id" name="user_id" required>
                <option class="d-none" value="" {{ old('user_id', optional($eventLog)->user_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select user</option>
                @foreach ($users as $key => $user)
                <option value="{{ $key }}" {{ old('user_id', optional($eventLog)->user_id) == $key ? 'selected' : '' }}>
                    {{ $user }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('end_point') ? 'has-error' : '' }}">
            <label for="end_point">{{__('adminPrivilege.end_point')}}</label>
            <input class="form-control" name="end_point" type="text" id="end_point"
                value="{{ old('end_point', optional($eventLog)->end_point) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('end_point', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('changes') ? 'has-error' : '' }}">
            <label for="changes">{{__('adminPrivilege.changes')}}</label>
            <input class="form-control" name="changes" type="text" id="changes"
                value="{{ old('changes', optional($eventLog)->changes) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('changes', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

