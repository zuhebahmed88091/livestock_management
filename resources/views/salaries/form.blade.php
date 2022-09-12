<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
            <label for="user_id">{{__('employees.employee')}}</label>
            <select class="form-control  select-admin-lte" id="user_id" name="user_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($users as $key => $user)
                    <option
                        value="{{ $key }}" {{ old('user_id', optional($salary)->user_id) == $key ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('salary_date') ? 'has-error' : '' }}">
            <label for="salary_date">{{__('salaries.salary_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="salary_date" id="salary_date"
                       value="{{ old('salary_date', optional($salary)->salary_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('salary_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
            <label for="amount">{{__('ledgers.amount')}}</label>
            <input class="form-control" name="amount" type="number" id="amount"
                   value="{{ old('amount', optional($salary)->amount) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3"
                      id="comments">{{ old('comments', optional($salary)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

