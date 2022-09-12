<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
            <label for="user_id">{{__('commons.user')}}</label>
            <select class="form-control  select-admin-lte" id="user_id" name="user_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($users as $key => $user)
                <option value="{{ $key }}" {{ old('user_id', optional($dailyWage)->user_id) == $key ? 'selected' : '' }}>
                    {{ $user }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('wages') ? 'has-error' : '' }}">
            <label for="wages">{{__('employees.wages')}}</label>
            <input class="form-control" name="wages" type="number" id="wages"
                value="{{ old('wages', optional($dailyWage)->wages) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('wages', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('paying_status') ? 'has-error' : '' }}">
            <label for="paying_status">{{__('employees.paying_status')}}</label>
            <select class="form-control  select-admin-lte" id="paying_status" name="paying_status" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Paid' => 'Paid',
'Unpaid' => 'Unpaid'] as $key => $text)
                <option value="{{ $key }}" {{ old('paying_status', optional($dailyWage)->paying_status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('paying_status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('work_date') ? 'has-error' : '' }}">
            <label for="work_date">{{__('employees.work_date')}}</label>
            
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="work_date" id="work_date"
                    value="{{ old('work_date', optional($dailyWage)->work_date) }}" required placeholder="format: d/m/Y">
            </div>

            {!! $errors->first('work_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
                    <textarea class="form-control" name="comments" cols="50" rows="3" id="comments">{{ old('comments', optional($dailyWage)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

