<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
            <label for="user_id">{{__('employees.employee')}}</label>
            <select class="form-control  select-admin-lte" id="user_id" name="user_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($users as $key => $user)
                    <option
                        value="{{ $key }}" {{ old('user_id', optional($leave)->user_id) == $key ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
            <label for="start_date">{{__('commons.start_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="start_date" id="start_date"
                       value="{{ old('start_date', optional($leave)->start_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('end_date') ? 'has-error' : '' }}">
            <label for="end_date">{{__('commons.end_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="end_date" id="end_date"
                       value="{{ old('end_date', optional($leave)->end_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3"
                      id="comments">{{ old('comments', optional($leave)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

