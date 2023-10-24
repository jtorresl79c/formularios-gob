@php
    use App\Facades\UtilityFacades;
    use App\Models\Role;
    use App\Models\AssignFormsRoles;
    use App\Models\AssignFormsUsers;
@endphp
@php
    $hashids = new Hashids('', 20);
    $id = $hashids->encodeHex($form->id);
@endphp
<div class="section-body">
    <div class="pt-5 mx-0 row">
        <div class="mx-auto col-md-7">
            @if (!empty($form->logo))
                <div class="mb-2 text-center gallery gallery-md">
                    <img id="app-dark-logo" class="float-none gallery-item"
                        src="{{ Storage::exists($form->logo) ? Storage::url($form->logo) : Storage::url('appLogo/78x78.png') }}">
                </div>
            @endif
            @if (session()->has('success'))
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center w-100">{{ $form->title }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center gallery" id="success_loader">
                            <img src="{{ asset('assets/images/success.gif') }}" />
                            <br>
                            <br>
                            <h2 class="w-100 ">{{ session()->get('success') }}</h2>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center w-100">{{ $form->title }}</h5>
                    </div>
                    <div class="card-body form-card-body">
                        <form action="{{ route('forms.fill.store', $form->id) }}" method="POST"
                            enctype="multipart/form-data" id="fill-form">
                            @method('PUT')
                            @if (isset($array))
                                @foreach ($array as $keys => $rows)
                                    <div class="tab">
                                        <div class="row">
                                            @foreach ($rows as $row_key => $row)
                                                @php
                                                    if (isset($row->column)) {
                                                        if ($row->column == 1) {
                                                            $col = 'col-12 step-' . $keys;
                                                        } elseif ($row->column == 2) {
                                                            $col = 'col-6 step-' . $keys;
                                                        } elseif ($row->column == 3) {
                                                            $col = 'col-4 step-' . $keys;
                                                        }
                                                    } else {
                                                        $col = 'col-12 step-' . $keys;
                                                    }
                                                @endphp
                                                @if ($row->type == 'checkbox-group')
                                                    <div class="form-group {{ $col }} ">
                                                        <label for="{{ $row->name }}"
                                                            class="d-block form-label">{{ $row->label }}
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                        </label>
                                                        @foreach ($row->values as $key => $options)
                                                            @php
                                                                $attr = ['class' => 'form-check-input', 'id' => $row->name . '_' . $key];
                                                                $attr['name'] = $row->name . '[]';
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr['class'] = $attr['class'] . ' required';
                                                                }
                                                                if ($row->inline) {
                                                                    $class = 'form-check form-check-inline col-4 ';
                                                                    if ($row->required) {
                                                                        $attr['class'] = 'form-check-input required';
                                                                    } else {
                                                                        $attr['class'] = 'form-check-input';
                                                                    }
                                                                    $l_class = 'form-check-label mb-0 ml-1';
                                                                } else {
                                                                    $class = 'form-check';
                                                                    if ($row->required) {
                                                                        $attr['class'] = 'form-check-input required';
                                                                    } else {
                                                                        $attr['class'] = 'form-check-input';
                                                                    }
                                                                    $l_class = 'form-check-label';
                                                                }
                                                            @endphp
                                                            <div class="{{ $class }}">
                                                                {{ Form::checkbox($row->name, $options->value, isset($options->selected) && $options->selected == 1 ? true : false, $attr) }}
                                                                <label class="{{ $l_class }}"
                                                                    for="{{ $row->name . '_' . $key }}">{{ $options->label }}</label>
                                                            </div>
                                                        @endforeach
                                                        @if ($row->required)
                                                            <div class=" error-message required-checkbox"></div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'file')
                                                    @php
                                                        $attr = [];
                                                        $attr['class'] = 'form-control upload';
                                                        if ($row->multiple) {
                                                            $maxupload = 10;
                                                            $attr['multiple'] = 'true';
                                                            if ($row->subtype != 'fineuploader') {
                                                                $attr['name'] = $row->name . '[]';
                                                            }
                                                        }
                                                        if ($row->required && (!isset($row->value) || empty($row->value))) {
                                                            $attr['required'] = 'required';
                                                            $attr['class'] = $attr['class'] . ' required';
                                                        }
                                                        if ($row->subtype == 'fineuploader') {
                                                            $attr['class'] = $attr['class'] . ' ' . $row->name;
                                                        }
                                                    @endphp
                                                    <div class="form-group {{ $col }}">
                                                        {{ Form::label($row->name, $row->label, ['class' => 'form-label']) }}
                                                        @if ($row->required)
                                                            <span class="text-danger align-items-center">*</span>
                                                        @endif
                                                        @if (isset($row->description))
                                                            <span type="button" class="tooltip-element"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $row->description }}">
                                                                ?
                                                            </span>
                                                        @endif
                                                        @if ($row->subtype == 'fineuploader')
                                                            <div class="dropzone" id="{{ $row->name }}"
                                                                data-extention="{{ $row->file_extention }}">
                                                            </div>
                                                            {!! Form::hidden($row->name, null, $attr) !!}
                                                        @else
                                                            {{ Form::file($row->name, $attr) }}
                                                        @endif
                                                        @if ($row->required)
                                                            <div class="error-message required-file"></div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'header')
                                                    @php
                                                        $class = '';
                                                        if (isset($row->className)) {
                                                            $class = $class . ' ' . $row->className;
                                                        }
                                                    @endphp
                                                    <div class="{{ $col }}">
                                                        <{{ $row->subtype }} class="{{ $class }}">
                                                            {{ html_entity_decode($row->label) }}
                                                            </{{ $row->subtype }}>
                                                    </div>
                                                @elseif($row->type == 'paragraph')
                                                    @php
                                                        $class = '';
                                                        if (isset($row->className)) {
                                                            $class = $class . ' ' . $row->className;
                                                        }
                                                    @endphp
                                                    <div class="{{ $col }}">
                                                        <{{ $row->subtype }} class="{{ $class }}">
                                                            {{ html_entity_decode($row->label) }}
                                                            </{{ $row->subtype }}>
                                                    </div>
                                                @elseif($row->type == 'radio-group')
                                                    <div class="form-group {{ $col }}">
                                                        <label for="{{ $row->name }}"
                                                            class="d-block form-label">{{ $row->label }}
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                        </label>
                                                        @foreach ($row->values as $key => $options)
                                                            @php
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr = ['class' => 'form-check-input required', 'required' => 'required', 'id' => $row->name . '_' . $key];
                                                                } else {
                                                                    $attr = ['class' => 'form-check-input', 'id' => $row->name . '_' . $key];
                                                                }
                                                                if ($row->inline) {
                                                                    $class = 'form-check form-check-inline ';
                                                                    if ($row->required) {
                                                                        $attr['class'] = 'form-check-input required';
                                                                    } else {
                                                                        $attr['class'] = 'form-check-input';
                                                                    }
                                                                    $l_class = 'form-check-label mb-0 ml-1';
                                                                } else {
                                                                    $class = 'form-check';
                                                                    if ($row->required) {
                                                                        $attr['class'] = 'form-check-input required';
                                                                    } else {
                                                                        $attr['class'] = 'form-check-input';
                                                                    }
                                                                    $l_class = 'form-check-label';
                                                                }
                                                            @endphp
                                                            <div class=" {{ $class }}">
                                                                {{ Form::radio($row->name, $options->value, isset($options->selected) && $options->selected ? true : false, $attr) }}
                                                                <label class="{{ $l_class }}"
                                                                    for="{{ $row->name . '_' . $key }}">{{ $options->label }}</label>
                                                            </div>
                                                        @endforeach
                                                        @if ($row->required)
                                                            <div class="error-message required-radio "></div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'select')
                                                    <div class="form-group {{ $col }}">
                                                        @php
                                                            $attr = ['class' => 'form-select w-100', 'id' => 'sschoices-multiple-remove-button', 'data-trigger'];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            if (isset($row->multiple) && !empty($row->multiple)) {
                                                                $attr['multiple'] = 'true';
                                                                $attr['name'] = $row->name . '[]';
                                                            }
                                                            if (isset($row->className) && $row->className == 'calculate') {
                                                                $attr['class'] = $attr['class'] . ' ' . $row->className;
                                                            }
                                                            if ($row->label == 'Registration') {
                                                                $attr['class'] = $attr['class'] . ' registration';
                                                            }
                                                            if (isset($row->is_parent) && $row->is_parent == 'true') {
                                                                $attr['class'] = $attr['class'] . ' parent';
                                                                $attr['data-number-of-control'] = isset($row->number_of_control) ? $row->number_of_control : 1;
                                                            }
                                                            $values = [];
                                                            $selected = [];
                                                            foreach ($row->values as $options) {
                                                                $values[$options->value] = $options->label;
                                                                if (isset($options->selected) && $options->selected) {
                                                                    $selected[] = $options->value;
                                                                }
                                                            }
                                                        @endphp
                                                        @if (isset($row->is_parent) && $row->is_parent == 'true')
                                                            {{ Form::label($row->name, $row->label) }}@if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            <div class="input-group">
                                                                {{ Form::select($row->name, $values, $selected, $attr) }}
                                                            </div>
                                                        @else
                                                            {{ Form::label($row->name, $row->label, ['class' => 'form-label']) }}
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">?</span>
                                                            @endif
                                                            {{ Form::select($row->name, $values, $selected, $attr) }}
                                                        @endif
                                                        @if ($row->label == 'Registration')
                                                            <span class="text-warning registration-message"></span>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'autocomplete')
                                                    <div class="form-group {{ $col }}">
                                                        @php
                                                            $attr = ['class' => 'form-select w-100', 'id' => 'sschoices-multiple-remove-button', 'data-trigger'];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            if (isset($row->multiple) && !empty($row->multiple)) {
                                                                $attr['multiple'] = 'true';
                                                                $attr['name'] = $row->name . '[]';
                                                            }
                                                            if (isset($row->className) && $row->className == 'calculate') {
                                                                $attr['class'] = $attr['class'] . ' ' . $row->className;
                                                            }
                                                            if ($row->label == 'Registration') {
                                                                $attr['class'] = $attr['class'] . ' registration';
                                                            }
                                                            if (isset($row->is_parent) && $row->is_parent == 'true') {
                                                                $attr['class'] = $attr['class'] . ' parent';
                                                                $attr['data-number-of-control'] = isset($row->number_of_control) ? $row->number_of_control : 1;
                                                            }
                                                            $values = [];
                                                            $selected = [];
                                                        @endphp
                                                        <div class="form-group">
                                                            <label for="autocompleteInputZero"
                                                                class="form-label">{{ $row->label }}</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="{{ $row->label }}" list="list-timezone"
                                                                name="autocomplete" id="input-datalist">
                                                            <datalist id="list-timezone">
                                                                @foreach ($row->values as $options)
                                                                    <option value="{{ $options->value }}"></option>
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                @elseif($row->type == 'date')
                                                    <div class="form-group {{ $col }}">
                                                        @php
                                                            $attr = ['class' => 'form-control'];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                        @endphp
                                                        {{ Form::label($row->name, $row->label, ['class' => 'form-label']) }}
                                                        @if ($row->required)
                                                            <span class="text-danger align-items-center">*</span>
                                                        @endif
                                                        @if (isset($row->description))
                                                            <span type="button" class="tooltip-element"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $row->description }}">
                                                                ?
                                                            </span>
                                                        @endif
                                                        {{ Form::date($row->name, isset($row->value) ? $row->value : null, $attr) }}
                                                        @if ($row->required)
                                                            <div class="error-message required-date"></div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'hidden')
                                                    <div class="form-group {{ $col }}">
                                                        {{ Form::hidden($row->name, isset($row->value) ? $row->value : null) }}
                                                    </div>
                                                @elseif($row->type == 'number')
                                                    <div class="form-group {{ $col }}">
                                                        @php
                                                            $row_class = isset($row->className) ? $row->className : '';
                                                            $attr = ['class' => 'number ' . $row_class];
                                                            if (isset($row->min)) {
                                                                $attr['min'] = $row->min;
                                                            }
                                                            if (isset($row->max)) {
                                                                $attr['max'] = $row->max;
                                                            }
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required ';
                                                            }
                                                        @endphp
                                                        {{ Form::label($row->name, $row->label, ['class' => 'form-label']) }}
                                                        @if ($row->required)
                                                            <span class="text-danger align-items-center">*</span>
                                                        @endif
                                                        @if (isset($row->description))
                                                            <span type="button" class="tooltip-element"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $row->description }}">
                                                                ?
                                                            </span>
                                                        @endif
                                                        {{ Form::number($row->name, isset($row->value) ? $row->value : null, $attr) }}
                                                        @if ($row->required)
                                                            <div class="error-message required-number"></div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'textarea')
                                                    <div class="form-group {{ $col }}">
                                                        @php
                                                            $attr = ['class' => 'form-control text-area-height'];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            if (isset($row->rows)) {
                                                                $attr['rows'] = $row->rows;
                                                            } else {
                                                                $attr['rows'] = '3';
                                                            }
                                                            if (isset($row->placeholder)) {
                                                                $attr['placeholder'] = $row->placeholder;
                                                            }
                                                            if ($row->subtype == 'ckeditor') {
                                                                $attr['class'] = $attr['class'] . ' ck_editor';
                                                            }
                                                        @endphp
                                                        {{ Form::label($row->name, $row->label, ['class' => 'form-label']) }}
                                                        @if ($row->required)
                                                            <span class="text-danger align-items-center">*</span>
                                                        @endif
                                                        @if (isset($row->description))
                                                            <span type="button" class="tooltip-element"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $row->description }}">
                                                                ?
                                                            </span>
                                                        @endif
                                                        {{ Form::textarea($row->name, isset($row->value) ? $row->value : null, $attr) }}
                                                        @if ($row->required)
                                                            <div class="error-message required-textarea"></div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'button')
                                                    <div class="form-group {{ $col }}">
                                                        @if (isset($row->value) && !empty($row->value))
                                                            <a href="{{ $row->value }}" target="_new"
                                                                class="{{ $row->className }}">{{ __($row->label) }}</a>
                                                        @else
                                                            {{ Form::button(__($row->label), ['name' => $row->name, 'type' => $row->subtype, 'class' => $row->className, 'id' => $row->name]) }}
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'text')
                                                    @php
                                                        $class = '';
                                                        if ($row->subtype == 'text' || $row->subtype == 'email') {
                                                            $class = 'form-group-text';
                                                        }
                                                    @endphp
                                                    <div class="form-group {{ $class }} {{ $col }}">
                                                        @php
                                                            $attr = ['class' => 'form-control ' . $row->subtype];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            if (isset($row->maxlength)) {
                                                                $attr['max'] = $row->maxlength;
                                                            }
                                                            if (isset($row->placeholder)) {
                                                                $attr['placeholder'] = $row->placeholder;
                                                            }
                                                            $value = isset($row->value) ? $row->value : '';
                                                            if ($row->subtype == 'datetime-local') {
                                                                $row->subtype = 'datetime-local';
                                                                $attr['class'] = $attr['class'] . ' date_time';
                                                            }
                                                        @endphp
                                                        <label for="{{ $row->name }}"
                                                            class="form-label">{{ $row->label }}
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                        </label>
                                                        @if (isset($row->description))
                                                            <span type="button" class="tooltip-element"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $row->description }}">
                                                                ?
                                                            </span>
                                                        @endif
                                                        {{ Form::input($row->subtype, $row->name, $value, $attr) }}
                                                        @if ($row->required)
                                                            <div class="error-message required-text"></div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'starRating')
                                                    <div class="form-group {{ $col }}">
                                                        @php
                                                            $value = isset($row->value) ? $row->value : 0;
                                                            $num_of_star = isset($row->number_of_star) ? $row->number_of_star : 5;
                                                        @endphp
                                                        {{ Form::label($row->name, $row->label, ['class' => 'form-label']) }}
                                                        @if ($row->required)
                                                            <span class="text-danger align-items-center">*</span>
                                                        @endif
                                                        @if (isset($row->description))
                                                            <span type="button" class="tooltip-element"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $row->description }}">
                                                                ?
                                                            </span>
                                                        @endif
                                                        <div id="{{ $row->name }}" class="starRating"
                                                            data-value="{{ $value }}"
                                                            data-num_of_star="{{ $num_of_star }}">
                                                        </div>
                                                        <input type="hidden" name="{{ $row->name }}"
                                                            value="{{ $value }}" class="calculate"
                                                            data-star="{{ $num_of_star }}">
                                                    </div>
                                                @elseif($row->type == 'SignaturePad')
                                                    @php
                                                        $attr = ['class' => $row->name];
                                                        if ($row->required) {
                                                            $attr['required'] = 'required';
                                                            $attr['class'] = $attr['class'] . ' required';
                                                        }
                                                        $value = isset($row->value) ? $row->value : null;
                                                    @endphp
                                                    <div class="row form-group {{ $col }}">
                                                        <div class="col-12">
                                                            <label for="{{ $row->name }}"
                                                                class="form-label">{{ $row->label }}</label>
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-12">
                                                            <div class="signature-pad-body">
                                                                <canvas class="signaturePad form-control"
                                                                    id="{{ $row->name }}"></canvas>
                                                                {!! Form::hidden($row->name, $value, $attr) !!}
                                                                <div class="buttons signature_buttons">
                                                                    <button id="save{{ $row->name }}"
                                                                        type="button" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom"
                                                                        data-bs-original-title="{{ __('Save') }}"
                                                                        class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                                                                    <button id="clear{{ $row->name }}"
                                                                        type="button" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom"
                                                                        data-bs-original-title="{{ __('Clear') }}"
                                                                        class="btn btn-danger btn-sm">{{ __('Clear') }}</button>
                                                                    <button id="showPointsToggle{{ $row->name }}"
                                                                        type="button" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom"
                                                                        data-bs-original-title="{{ __('Show Points?') }}"
                                                                        class="btn btn-info btn-sm show-point toggle">{{ __('Show Points?') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if (@$row->value != '')
                                                            <div class="col-lg-6 col-md-12 col-12">
                                                                <img src="{{ Storage::url($row->value) }}"
                                                                    width="80%" class="border" alt="">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($row->type == 'break')
                                                    <hr class="hr_border">
                                                @elseif($row->type == 'location')
                                                    <input id="pac-input" class="controls" type="text"
                                                        name="location" placeholder="Search Box" />
                                                    <div id="map"></div>
                                                @elseif($row->type == 'video')
                                                    @php
                                                        $attr = ['class' => $row->name];
                                                        if ($row->required) {
                                                            $attr['required'] = 'required';
                                                            $attr['class'] = $attr['class'] . ' required';
                                                        }
                                                        $value = isset($row->value) ? $row->value : null;
                                                    @endphp
                                                    <div class="form-group {{ $col }}">
                                                        <label for="{{ $row->name }}"
                                                            class="form-label">{{ $row->label }}</label>
                                                        @if ($row->required)
                                                            <span class="text-danger align-items-center">*</span>
                                                        @endif
                                                        <div class="d-flex justify-content-start">
                                                            <button type="button" class="btn btn-primary"
                                                                id="videostream">
                                                                <span class="svg-icon svg-icon-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24">
                                                                        <path
                                                                            d="M5 5h-3v-1h3v1zm8 5c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3zm11-4v15h-24v-15h5.93c.669 0 1.293-.334 1.664-.891l1.406-2.109h8l1.406 2.109c.371.557.995.891 1.664.891h3.93zm-19 4c0-.552-.447-1-1-1-.553 0-1 .448-1 1s.447 1 1 1c.553 0 1-.448 1-1zm13 3c0-2.761-2.239-5-5-5s-5 2.239-5 5 2.239 5 5 5 5-2.239 5-5z"
                                                                            fill="black" />
                                                                    </svg>
                                                                </span>
                                                                {{ __('Record Video') }}
                                                            </button>
                                                        </div>
                                                        @if ($row->required)
                                                            <div class="error-message required-text"></div>
                                                        @endif
                                                        <div class="cam-buttons d-none">
                                                            <video autoplay controls id="web-cam-container"
                                                                class="p-2" style="width:100%; height:80%;">
                                                                {{ __("Your browser doesn't support the video tag") }}
                                                            </video>
                                                            <div class="py-4">
                                                                <div class="field-required">
                                                                    <div class="mb-2 btn btn-lg btn-primary float-end">
                                                                        <div id="timer">
                                                                            <span id="hours">00:</span>
                                                                            <span id="mins">00:</span>
                                                                            <span id="seconds">00</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id='gUMArea' class="video_cam">
                                                                    <div class="web_cam_video">
                                                                        {{ Form::hidden('media', $value, $attr) }}
                                                                        {{-- <input type="hidden" class="{{ $attr }}" name="media"
                                                                            value="" @if ($row->required) required="required" @endif id='mediaVideo'> --}}
                                                                    </div>
                                                                </div>
                                                                <div id='btns'>
                                                                    <div id="controls">
                                                                        <button class="btn btn-light-primary"
                                                                            id='start' type="button">
                                                                            <span class="svg-icon svg-icon-2">
                                                                                <span class="svg-icon svg-icon-2">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="24" height="24"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path
                                                                                            d="M16 18c0 1.104-.896 2-2 2h-12c-1.105 0-2-.896-2-2v-12c0-1.104.895-2 2-2h12c1.104 0 2 .896 2 2v12zm8-14l-6 6.223v3.554l6 6.223v-16z"
                                                                                            fill="black" />
                                                                                    </svg>
                                                                                </span>
                                                                            </span>
                                                                            {{ __('Start') }}
                                                                        </button>
                                                                        <button class="btn btn-light-danger"
                                                                            id='stop' type="button">
                                                                            <span class="svg-icon svg-icon-2">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        d="M2 2h20v20h-20z"fill="black" />
                                                                                </svg>
                                                                            </span>
                                                                            <span
                                                                                class="indicator-label">{{ __('Stop') }}</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($row->type == 'selfie')
                                                    @php
                                                        $attr = ['class' => $row->name];
                                                        if ($row->required) {
                                                            $attr['required'] = 'required';
                                                            $attr['class'] = $attr['class'] . ' required';
                                                        }
                                                        $value = isset($row->value) ? $row->value : null;
                                                    @endphp
                                                    <div class="row {{ $col }} selfie_screen">
                                                        <div class="col-md-6 selfie_photo">
                                                            <div class="form-group">
                                                                <label for="{{ $row->name }}"
                                                                    class="form-label">{{ $row->label }}</label>
                                                                @if ($row->required)
                                                                    <span
                                                                        class="text-danger align-items-center">*</span>
                                                                @endif
                                                                <div id="my_camera" class="camera_screen"></div>
                                                                <br />
                                                                <button type="button"
                                                                    class="btn btn-default btn-light-primary"
                                                                    onClick="take_snapshot()">
                                                                    <i class="ti ti-camera"></i>
                                                                    {{ __('Take Selfie') }}
                                                                </button>
                                                                <input type="hidden" name="image"
                                                                    value="{{ $value }}"
                                                                    class="image-tag  {{ implode(' ', $attr) }}">
                                                            </div>
                                                        </div>
                                                        <div class="mt-4 col-md-6">
                                                            <div id="results" class="selfie_result">
                                                                {{ __('Your captured image will appear here...') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!isset($form_value) && $form->payment_status == 1)
                                @if (!isset($form_value) && $form->payment_type == 'stripe')
                                    <div class="strip">
                                        <strong class="d-block">{{ __('Payment') }}
                                            ({{ $form->currency_symbol }}{{ $form->amount }})</strong>
                                        <div id="card-element" class="form-control">
                                        </div>
                                        <span id="card-errors" class="payment-errors"></span>
                                        <br>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'razorpay')
                                    <div class="razorpay">
                                        <p>{{ __('Make Payment') }}</p>
                                        <input type="hidden" name="payment_id" id="payment_id">
                                        <h5>{{ __('Payable Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'paypal')
                                    <div class="paypal">
                                        <p>{{ __('Make Payment') }}</p>
                                        <input type="hidden" name="payment_id" id="payment_id">
                                        <h5>{{ __('Payable Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                        <div id="paypal-button-container"></div>
                                        <span id="paypal-errors" class="payment-errors"></span>
                                        <br>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'paytm')
                                    <div class="paytm">
                                        <p>{{ __('Make Payment') }}</p>
                                        {!! Form::hidden('payment_id', null, ['id' => 'payment_id']) !!}
                                        <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'flutterwave')
                                    <div class="flutterwave">
                                        <p>{{ __('Make Payment') }}</p>
                                        {!! Form::hidden('payment_id', null, ['id' => 'payment_id']) !!}
                                        <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'paystack')
                                    <div class="paystack">
                                        <p>{{ __('Make Payment') }}</p>
                                        {!! Form::hidden('payment_id', null, ['id' => 'payment_id']) !!}
                                        <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'coingate')
                                    <div class="coingate">
                                        <p>{{ __('Make Payment') }}</p>
                                        {!! Form::hidden('payment_id', null, ['id' => 'payment_id']) !!}
                                        <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'mercado')
                                    <div class="mercado">
                                        <p>{{ __('Make Payment') }}</p>
                                        {!! Form::hidden('payment_id', null, ['id' => 'payment_id']) !!}
                                        <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                    </div>
                                @elseif(!isset($form_value) && $form->payment_type == 'payumoney')
                                    <div class="payumoney">
                                        <p>{{ __('Make Payment') }}</p>
                                        {!! Form::hidden('payment_id', null, ['id' => 'payment_id']) !!}
                                        <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                            {{ $form->amount }}</h5>
                                    </div>
                                @endif
                            @endif
                            <div class="row">
                                <div class="col cap">
                                    @if (UtilityFacades::getsettings('captcha_enable') == 'on')
                                        @if (UtilityFacades::getsettings('captcha') == 'hcaptcha')
                                            {!! HCaptcha::renderJs() !!}
                                            <div class="g-hcaptcha"
                                                data-sitekey="{{ UtilityFacades::getsettings('hcaptcha_key') }}">
                                            </div>
                                            {!! HCaptcha::display() !!}
                                        @endif
                                        @if (UtilityFacades::getsettings('captcha') == 'recaptcha')
                                            {!! NoCaptcha::renderJs() !!}
                                            <div class="g-recaptcha"
                                                data-sitekey="{{ UtilityFacades::getsettings('recaptcha_key') }}">
                                            </div>
                                            {!! NoCaptcha::display() !!}
                                        @endif
                                    @endif
                                    <div class="pb-0 mt-3 form-actions">
                                        <input type="hidden" name="form_value_id"
                                            value="{{ isset($form_value) ? $form_value->id : '' }}"
                                            id="form_value_id">
                                    </div>
                                </div>
                            </div>
                            <div class="over-auto">
                                <div class="float-right">
                                    {!! Form::button(__('Previous'), ['class' => 'btn btn-default', 'id' => 'prevBtn', 'onclick' => 'nextPrev(-1)']) !!}
                                    {!! Form::button(__('Next'), ['class' => 'btn btn-primary', 'id' => 'nextBtn', 'onclick' => 'nextPrev(1)']) !!}
                                </div>
                            </div>
                            <div class="extra_style">
                                @if (isset($array))
                                    @foreach ($array as $keys => $rows)
                                        <span class="step"></span>
                                    @endforeach
                                @endif
                            </div>
                        </form>
                        {!! Form::open(['route' => ['coingateprepare'], 'method' => 'post', 'id' => 'coingate_payment_frms']) !!}
                        {{ Form::hidden('cg_currency', null, ['id' => 'cg_currency']) }}
                        {{ Form::hidden('cg_amount', null, ['id' => 'cg_amount']) }}
                        {{ Form::hidden('cg_form_id', null, ['id' => 'cg_form_id']) }}
                        {!! Form::hidden('cg_submit_type', null, ['id' => 'cg_submit_type']) !!}
                        {!! Form::close() !!}
                        {!! Form::open([
                            'route' => ['payumoneyfillprepare'],
                            'method' => 'post',
                            'id' => 'payumoney_payment_frms',
                            'name' => 'payuForm',
                        ]) !!}
                        {{ Form::hidden('payumoney_currency', null, ['id' => 'payumoney_currency']) }}
                        {{ Form::hidden('payumoney_amount', null, ['id' => 'payumoney_amount']) }}
                        {{ Form::hidden('payumoney_form_id', null, ['id' => 'payumoney_form_id']) }}
                        {{ Form::hidden('payumoney_created_by', null, ['id' => 'payumoney_created_by']) }}
                        {!! Form::hidden('payumoney_submit_type', null, ['id' => 'payumoney_submit_type']) !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['mercadofillprepare'], 'method' => 'post', 'id' => 'mercado_payment_frms']) !!}
                        {{ Form::hidden('mercado_amount', null, ['id' => 'mercado_amount']) }}
                        {{ Form::hidden('mercado_form_id', null, ['id' => 'mercado_form_id']) }}
                        {{ Form::hidden('mercado_created_by', null, ['id' => 'mercado_created_by']) }}
                        {!! Form::hidden('mercado_submit_type', null, ['id' => 'mercado_submit_type']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if ($form->allow_share_section == 1)
        <div class="row">
            <div class="mx-auto col-xl-7 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <h5> <i class="me-2" data-feather="share-2"></i>{{ __('Share') }}</h5>
                    </div>
                    <div class="card-body ">
                        <div class="m-auto form-group col-6">
                            <p>{{ __('Use this link to share the poll with your participants.') }}</p>
                            <div class="input-group">
                                <input type="text" value="{{ route('forms.survey', $id) }}"
                                    class="form-control js-content" id="pc-clipboard-1"
                                    placeholder="Type some value to copy">
                                <a href="#" class="btn btn-primary js-copy" data-clipboard="true"
                                    data-clipboard-target="#pc-clipboard-1"> {{ __('Copy') }}
                                </a>
                            </div>
                            <div class="mt-3 social-links-share">
                                <a href="https://api.whatsapp.com/send?text={{ route('forms.survey', $id) }}"
                                    title="Whatsapp" class="social-links-share-main">
                                    <i class="ti ti-brand-whatsapp"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text={{ route('forms.survey', $id) }}"
                                    title="Twitter" class="social-links-share-main">
                                    <i class="ti ti-brand-twitter"></i>
                                </a>
                                <a href="https://www.facebook.com/share.php?u={{ route('forms.survey', $id) }}"
                                    title="Facebook" class="social-links-share-main">
                                    <i class="ti ti-brand-facebook"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ route('forms.survey', $id) }}"
                                    title="Linkedin" class="social-links-share-main">
                                    <i class="ti ti-brand-linkedin"></i>
                                </a>
                                <a href="javascript:void(1);" class="social-links-share-main" title="Show QR Code"
                                    data-action="{{ route('forms.survey.qr', $id) }}" id="share-qr-image">
                                    <i class="ti ti-qrcode"></i>
                                </a>
                                <a href="javascript:void(0)" title="Embed" class="social-links-share-main"
                                    onclick="copyToClipboard('#embed-form-{{ $form->id }}')"
                                    id="embed-form-{{ $form->id }}"
                                    data-url='<iframe src="{{ route('forms.survey', $id) }}" scrolling="auto" align="bottom" style="height:100vh;" width="100%"></iframe>'>
                                    <i class="ti ti-code"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($form->allow_comments == 1)
        <div class="row">
            <div class="mx-auto col-xl-7 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <h5> <i class="me-2" data-feather="message-circle"></i>{{ __('Comments') }}</h5>
                    </div>
                    {!! Form::open([
                        'route' => 'form_comment.store',
                        'method' => 'Post',
                    ]) !!}
                    <div class="card-body">
                        <div class="form-group">
                            {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('Enter your name')]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::textarea('comment', null, [
                                'class' => 'form-control',
                                'rows' => '3',
                                'required',
                                'placeholder' => __('Add a comment'),
                            ]) !!}
                        </div>
                    </div>
                    <input type="hidden" id="form_id" name="form_id" value="{{ $form->id }}">
                    <div class="card-footer">
                        <div class="text-end">
                            {!! Form::submit(__('Add a comment'), ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                        @foreach ($form->commmant as $value)
                            <div class="comments-item">
                                <div class="comment-user-icon">
                                    <img src="{{ asset('assets/images/comment.png') }}">
                                </div>
                                <span class="text-left comment-info">
                                    <h6>{{ $value->name }}</h6>
                                    <span class="d-block"><small>{{ $value->comment }}</small></span>
                                    <h6 class="d-block"><small>({{ $value->created_at->diffForHumans() }})</small>
                                        <a href="#reply-comment" class="text-dark reply-comment-{{ $value->id }}"
                                            id="comment-reply" data-bs-toggle="collapse"
                                            data-id="{{ $value->id }}" title="{{ __('Reply') }}">
                                            {{ __('Reply') }}</i></a>
                                        @if (Auth::user())
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['form_comment.destroy', $value->id],
                                                'id' => 'delete-form-' . $value->id,
                                                'class' => 'd-inline',
                                            ]) !!}
                                            <a href="#" class="text-dark show_confirm" title="Delete"
                                                id="delete-form-{{ $value->id }}">{{ __('Delete') }}</a>
                                            {!! Form::close() !!}
                                        @endif
                                    </h6>
                                    <li class="list-inline-item"> </li>
                                    @foreach ($value->replyby as $reply_value)
                                        <div class="comment-replies">
                                            <div class="comment-user-icon">
                                                <img src="{{ asset('assets/images/comment.png') }}">
                                            </div>
                                            <div class="comment-replies-content">
                                                <h6>{{ $reply_value->name }}</h6>
                                                <span class="d-block"><small>{{ $reply_value->reply }}</small></span>
                                                <h6 class="d-block">
                                                    <small>({{ $reply_value->created_at->diffForHumans() }})</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </span>
                            </div>
                            {!! Form::open([
                                'route' => 'form_comment_reply.store',
                                'method' => 'Post',
                                'data-validate',
                                'enctype' => 'multipart/form-data',
                            ]) !!}
                            <div class="row commant" id="reply-comment-{{ $value->id }}">
                                <div class="form-group">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('Enter your name')]) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::textarea('reply', null, [
                                        'class' => 'form-control',
                                        'rows' => '2',
                                        'required',
                                        'placeholder' => __('Add a comment'),
                                    ]) !!}
                                </div>
                                <input type="hidden" id="form_id" name="form_id" value="{{ $form->id }}">
                                <input type="hidden" id="comment_id" name="comment_id"
                                    value="{{ $value->id }}">
                                <div class="card-footer">
                                    <div class="text-end">
                                        {!! Form::submit(__('Add a comment'), ['class' => 'btn btn-primary']) !!}
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    @if ($row->type === 'selfie')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    @endif
    <script>
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
        });
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ Utility::getsettings('google_map_api') }}&callback=initAutocomplete&libraries=places&v=weekly"
        defer></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).attr('data-url')).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Great!', '{{ __('Copy Link Successfully..') }}', 'success',
                '{{ asset('assets/images/notification/ok-48.png') }}', 4000);
        }
        $(document).ready(function() {
            $('body').on('click', '#share-qr-image', function() {
                var action = $(this).data('action');
                var modal = $('#common_modal1');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('QR Code') }}');
                    modal.find('.modal-body').html(response.html);
                    feather.replace();
                    modal.modal('show');
                })
            });
            var totaldropzone = $('.dropzone').map((_, el) => el.id).get();
            totaldropzone.forEach(function(val) {
                var myDropzone = new Dropzone("#" + val, {
                    url: "{{ route('dropzone.upload', $form->id) }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    params: {
                        file_extention: $("#" + val).data('extention'),
                    },
                    removedfile: function(file) {
                        console.log(file.xhr.response);
                    },
                    acceptedFiles: ".pdf, .pdfa, .fdf, .xdp, .xfa, .pdx, .pdp, .pdfxml, .pdxox, .jpeg, .jpg, .png, .xlsx, .csv, .xlsm, .xltx, .xlsb, .xltm, .xlw",
                    maxFiles: '{{ isset($maxupload) ? $maxupload : 1 }}',
                    parallelUploads: '{{ isset($maxupload) ? $maxupload : 1 }}',
                    addRemoveLinks: true,
                    uploadMultiple: false,
                    autoProcessQueue: true,
                    init: function() {
                        this.on('success', function(files, response) {
                            if ($('.' + val).val()) {
                                var oldDropzone = $('.' + val).val();
                                $('.' + val).val(oldDropzone + ',' + response.filename);
                            } else {
                                $('.' + val).val(response.filename);
                            }
                            if (response.success) {
                                show_toastr('Done!', response.success, 'success',
                                    '{{ asset('assets/images/notification/ok-48.png') }}',
                                    4000);
                            } else {
                                show_toastr('Error!', response.errors, 'danger',
                                    '{{ asset('assets/images/notification/high_priority-48.png') }}',
                                    4000);
                            }
                        });
                    }
                });
            });



            let area = document.createElement('textarea');
            document.body.appendChild(area);
            area.style.display = "none";
            let content = document.querySelectorAll('.js-content');
            let copy = document.querySelectorAll('.js-copy');
            for (let i = 0; i < copy.length; i++) {
                copy[i].addEventListener('click', function() {
                    area.style.display = "block";
                    area.value = content[i].innerText;
                    area.select();
                    document.execCommand('copy');
                    area.style.display = "none";
                    this.innerHTML = 'Copied ';
                    setTimeout(() => this.innerHTML = "Copy", 2000);
                });
            }


            var signaturePad = $('.signaturePad').map((_, el) => el.id).get();

            signaturePad.forEach(function(val) {
                var signaturePad = new SignaturePad(document.getElementById(val), {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)',
                    velocityFilterWeight: .7,
                    minWidth: 0.5,
                    maxWidth: 2.5,
                    throttle: 16,
                    minPointDistance: 3,

                });
                var saveButton = document.getElementById('save' + val),
                    clearButton = document.getElementById('clear' + val),
                    undoButton = document.getElementById('undo' + val),
                    showPointsToggle = document.getElementById('showPointsToggle' + val);

                saveButton.addEventListener('click', function(event) {
                    var data = signaturePad.toDataURL('image/png');
                    $(this).parents('.signature-pad-body').find('.' + val).val(data);
                });
                clearButton.addEventListener('click', function(event) {
                    signaturePad.clear();
                });
                showPointsToggle.addEventListener('click', function(event) {
                    signaturePad.showPointsToggle();
                    showPointsToggle.classList.toggle('toggle');
                });
            });
        });



        function initAutocomplete() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: -33.8688,
                    lng: 151.2195
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            let markers = [];


            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                const bounds = new google.maps.LatLngBounds();

                const lat = $(this).parents('.controls').find('#lat');
                const lng = $(this).parents('.controls').find('#lng');

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };

                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );
                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
        window.initAutocomplete = initAutocomplete;
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', e => {
            $('#input-datalist').autocomplete()
        }, false);
    </script>


    <script>
        $(document).on('click', "#videostream", function() {
            $(".cam-buttons").fadeIn(500);
            $('.cam-buttons').removeClass('d-none');
        });
    </script>
    <script>
        let log = console.log.bind(console),
            id = val => document.getElementById(val),
            ul = id('ul'),
            gUMbtn = id('gUMbtn'),
            start = id('start'),
            stop = id('stop'),
            stream,
            recorder,
            counter = 1,
            chunks,
            media;

        const webCamContainer = document.getElementById('web-cam-container');

        videostream.onclick = e => {
            let mv = id('mediaVideo'),
                mediaOptions = {
                    video: {
                        tag: 'video',
                        type: 'video/webm',
                        ext: '.mp4',
                        gUM: {
                            video: true,
                            audio: true
                        }
                    }
                };
            media = mv.checked ? mediaOptions.video : mediaOptions.audio;
            try {
                navigator.mediaDevices.getUserMedia(media.gUM).then(_stream => {
                    stream = _stream;
                    webCamContainer.srcObject = stream;
                    id('btns').style.display = 'inherit';
                    start.removeAttribute('disabled');
                    recorder = new MediaRecorder(stream);
                    recorder.ondataavailable = e => {
                        chunks.push(e.data);
                        if (recorder.state == 'inactive') makeLink();
                    };
                    $('.web-supported').addClass('d-none');
                }).catch(error => {
                    show_toastr('Error!', 'Camera device not found. ', 'danger',
                        '{{ asset('assets/images/notification/high_priority-48.png') }}',
                        4000);
                });
            } catch (err) {
                show_toastr('Error!', 'Camera device not found.', 'danger',
                    '{{ asset('assets/images/notification/high_priority-48.png') }}',
                    4000);
            }
        }

        start.onclick = e => {
            stop.removeAttribute('disabled');
            chunks = [];
            recorder.start();
            start.disabled = true;

        }

        stop.onclick = e => {
            stop.disabled = true;
            recorder.stop();
            $("#web-cam-container").hide();
        }

        stop.removeAttribute('disabled');

        function makeLink() {
            let blob = new Blob(chunks, {
                type: media.type
            });
            const formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('media', blob);
            fetch('{{ route('videostore') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(res =>
                    res.json()).then(d => {
                    console.log(d);
                    if (d.success) {
                        $('input[name="media"]').val(d.filename);
                    } else {
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toastr-top-right",
                            "preventDuplicates": false,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };
                        show_toastr('Error!', d.message, 'danger',
                            '{{ asset('assets/images/notification/high_priority-48.png') }}',
                            4000);
                    }
                });
        }
    </script>
    <script>
        var hours = 0;
        var mins = 0;
        var seconds = 0;

        $('#start').click(function() {
            startTimer();
        });

        $('#stop').click(function() {
            clearTimeout(timex);
        });

        function startTimer() {
            if (!stream) {
                show_toastr('Error!', 'Camera device not connected. Please check your camera settings.', 'danger',
                    '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
                return;
            }

            timex = setTimeout(function() {
                seconds++;
                if (seconds > 59) {
                    seconds = 0;
                    mins++;
                    if (mins > 59) {
                        mins = 0;
                        hours++;
                        if (hours < 10) {
                            $("#hours").text('0' + hours + ':')
                        } else {
                            $("#hours").text(hours + ':');
                        }
                    }

                    if (mins < 10) {
                        $("#mins").text('0' + mins + ':');
                    } else {
                        $("#mins").text(mins + ':');
                    }
                }

                if (seconds < 10) {
                    $("#seconds").text('0' + seconds);
                } else {
                    $("#seconds").text(seconds);
                }

                startTimer();
            }, 1000);
        }
    </script>
    @if ($row->type == 'selfie')
        <script language="JavaScript">
            Webcam.set({
                width: 300,
                height: 250,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            Webcam.attach('#my_camera');

            function take_snapshot() {
                Webcam.snap(function(data_uri) {
                    $(".image-tag").val(data_uri);
                    document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
                });
            }
        </script>
    @endif
@endpush
