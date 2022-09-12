<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
            <label for="type">{{__('commons.type')}}</label>
            <select class="form-control  select-admin-lte" id="type" name="type" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Income' => 'Income', 'Expense' => 'Expense'] as $key => $text)
                    <option value="{{ $key }}" {{ old('type', optional($ledger)->type) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
            <label for="date">{{__('commons.date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="date" id="date"
                       value="{{ old('date', optional($ledger)->date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
            <label for="amount">{{__('ledgers.amount')}}</label>
            <input class="form-control" name="amount" type="number" id="amount"
                   value="{{ old('amount', optional($ledger)->amount) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('tag_id') ? 'has-error' : '' }}">
            <label for="tag_id">{{__('commons.tag')}}</label>
            <select class="form-control  select-admin-lte" id="tag_id" name="tag_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($tags as $key => $tag)
                    <option value="{{ $key }}" {{ old('tag_id', optional($ledger)->tag_id) == $key ? 'selected' : '' }}>
                        {{ $tag }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('tag_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
            <label for="details">{{__('commons.details')}}</label>
            <textarea class="form-control" name="details" cols="50" rows="3" id="details"
                      required>{{ old('details', optional($ledger)->details) }}</textarea>

            {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

