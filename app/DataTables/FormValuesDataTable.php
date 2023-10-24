<?php

namespace App\DataTables;

use App\Facades\UtilityFacades;
use App\Models\Form;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use App\Models\FormValue;
use App\Models\User;
use Carbon\Carbon;

class FormValuesDataTable extends DataTable
{
    public function dataTable($query)
    {
        $data = datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('user', function (FormValue $formValue) {
                $tu = '';
                if ($formValue->User) {
                    $tu = $formValue->User->name;
                }
                return $tu;
            })
            ->editColumn('amount', function (FormValue $formValue) {
                return $formValue->json;
            })
            ->editColumn('status', function (FormValue $formValue) {
                if ($formValue->status == "free") {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-primary">' . __('Free') . '</span>';
                    return $out;
                } else if ($formValue->status == "pending") {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-warning">' . __('Pending') . '</span>';
                    return $out;
                } else if ($formValue->status == "successfull") {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-success">' . __('Successfull') . '</span>';
                    return $out;
                } else {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-danger">' . __('Failed') . '</span>';
                    return $out;
                }
            })
            ->editColumn('created_at', function (FormValue $formValue) {
                return UtilityFacades::date_time_format($formValue->created_at);
            })
            ->editColumn('user', function (FormValue $formValue) {
                $username =  User::where('id', $formValue->user_id)->first();
                $user = ($formValue->user_id) ? $username->name : 'Guest';
                return $user;
            })
            ->addColumn('action', function (FormValue $formValue) {
                return view('form_value.action', compact('formValue'));
            });
            

        $labels = $this->labels();
        if ($labels != null) {
            foreach ($labels as $key => $label) {
                $data->editColumn($key, function (FormValue $formValue) use ($key) {
                    $jsonData = $formValue->json;
                    $jsonArray = json_decode($jsonData, true);
                    $value = "-";
                    foreach ($jsonArray as $items) {
                        foreach ($items as $item) {
                            if (isset($item['show_datatable']) && $item['show_datatable']) {
                                if ($item['name'] === $key) {
                                    if ($item['type'] === 'starRating') {
                                        $value = '';
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($item['value'] < $i) {
                                                if (is_float($item['value']) && (round($item['value']) == $i)) {
                                                    $value .= '<i class="text-warning fas fa-star-half-alt"></i>';
                                                } else {
                                                    $value .= '<i class="fas fa-star"></i>';
                                                }
                                            } else {
                                                $value .= '<i class="text-warning fas fa-star"></i>';
                                            }
                                        }
                                    } elseif ($item['type'] === 'radio-group' || $item['type'] === 'select' || $item['type'] === 'checkbox-group') {
                                        $selectedValues = [];
                                        foreach ($item['values'] as $option) {
                                            if (isset($option['selected']) && $option['selected'] == 1) {
                                                $selectedValues[] = $option['label'];
                                            }
                                        }
                                        $value = implode(', ', $selectedValues);
                                    } elseif ($item['type'] === 'date') {
                                        $value = '';
                                        if ($item['value']) {
                                            $date = Carbon::createFromFormat('Y-m-d', $item['value']);
                                            $formattedDate = $date->format('jS M Y');
                                            $value = $formattedDate;
                                        }
                                    } else {
                                        $value = $item['value'];
                                    }
                                }
                            }
                        }
                    }
                    return $value;
                });
            }
            $arr = array_merge(['status', 'action', 'user', 'type', 'created_at'], array_keys($labels));
        } else {
            $arr = array_merge(['status', 'action', 'user', 'type', 'created_at']);
        }
        $data->rawColumns($arr);
        return $data;
    }

    public function query(FormValue $model, Request $request)
    {
        $usr = \Auth::user();
        $role_id = $usr->roles->first()->id;
        $user_id = $usr->id;
 
        $form_values = FormValue::select(['form_values.*', 'forms.title'])
            ->join('forms', 'forms.id', '=', 'form_values.form_id')
            ->leftJoin('users', 'users.id', 'form_values.user_id');
      /*   } */
        if ($request->start_date && $request->end_date) {
            $form_values->whereBetween('form_values.created_at', [$request->start_date, $request->end_date]);
        }
        if ($request->form) {
            $form_values->where('form_values.form_id', '=', $request->form);
        }
        if ($request->user_name) {
            $form_values = FormValue::select(['form_values.*', 'users.name as usr_name'])
                ->join('users', 'users.id', '=', 'form_values.user_id');
            $form_values->where('users.name', 'LIKE', '%' . $request->user_name . '%')->Where('form_values.form_id', '=', $request->form);
        }
      
        return $form_values;

    }

    public function labels()
    {
        $recordId = $this->form_id;
        $formValue = Form::find($recordId);
        if ($formValue->json != '') {
            $jsonData = $formValue->json;
            $jsonArray = json_decode($jsonData, true);
            $filteredData = [];
            foreach ($jsonArray as $j_array) {
                foreach ($j_array as $item) {
                    if (isset($item['show_datatable']) && $item['show_datatable'] == true) {
                        $filteredData[$item['name']] =  $item['label'];
                    }
                }
            }
            $label = $filteredData;
            return $label;
        }
    }

    public function html()
    {
        $dataTable = $this->builder()
        ->setTableId('forms-table')
        ->addIndex()
        ->columns($this->getColumns($this->labels()))
        ->ajax([
            'data' => 'function(d) {
                var filter = $(".created_at").val();
                var spilit = filter.split("to");
                d.form = $("#form_id").val();
                d.start_date = spilit[0];
                d.end_date = spilit[1];
                var user_filter = $("input[name=user]").val();
                d.user_name = user_filter;
            }'
        ])
        ->orderBy(1)
        ->language([
            "responsive" => true,
            "paginate" => [
                "next" => '<i class="ti ti-chevron-right"></i>',
                "previous" => '<i class="ti ti-chevron-left"></i>'
            ],
            'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
            "searchPlaceholder" => __('Search...'),
            "search" => "",
            "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
        ])
        ->initComplete('function() {
            var table = this;
            var template = Handlebars.compile($("#details-template").html());
            
            function deserializeJson(jsonString) {
                try {
                    return JSON.parse(jsonString);
                } catch (error) {
                    console.error("Error al deserializar el JSON:", error);
                    return {};
                }
            }


            $("#forms-table tbody").on("click", "td.details-control", function () {
                var tr = $(this).closest("tr");
                var table = $("#forms-table").DataTable();
                var row = table.row(tr);
                
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass("shown");
                } else {

                    console.log(row.data());
                    var jsonData = deserializeJson(row.data().json);
                    row.child(template(jsonData)).show();
                    tr.addClass("shown");
                }
            });
    
            $("body").on("click", ".add_filter", function () {
                $("#forms-table").DataTable().draw();
            });
    
            $("body").on("click", ".clear_filter", function () {
                $(".created_at").val("");
                $("input[name=user]").val("");
                $("#forms-table").DataTable().draw();
            });
    
            var searchInput = $("#" + table.api().table().container().id + " label input[type=\"search\"]");
            searchInput.removeClass("form-control form-control-sm");
            searchInput.addClass("dataTable-input");
            var select = $(table.api().table().container()).find(".dataTables_length select").removeClass("custom-select custom-select-sm form-control form-control-sm").addClass("dataTable-selector");
        }');
    

            

            $exportButtonConfig = [];
                $exportButtonConfig = [
                    'extend' => 'collection',
                    'className' => 'w-inherit btn btn-light-secondary me-1 dropdown-toggle',
                    'text' => '<i class="ti ti-download"></i> ' . __('Export'),
                    "buttons" => [
                        ["extend" => "print", "text" => '<i class="fas fa-print"></i> ' . __('Print'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                        ["extend" => "csv", "text" => '<i class="fas fa-file-csv"></i> ' . __('CSV'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                        ["extend" => "excel", "text" => '<i class="fas fa-file-excel"></i> ' . __('Excel'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                        //["extend" => "pdf", "text" => '<i class="fas fa-file-pdf"></i> ' . __('PDF'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                        ["extend" => "copy", "text" => '<i class="fas fa-copy"></i> ' . __('Copy'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ],
                ];


            $buttonsConfig = [
                $exportButtonConfig,
                ['extend' => 'reset', 'className' => 'w-inherit btn btn-light-danger me-1'],
                ['extend' => 'reload', 'className' => 'w-inherit btn btn-light-warning'],
            ];


            $dataTable->parameters([
                "dom" =>  "
            <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B>>
            <'dataTable-container'<'col-sm-12'tr>>
            <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
            ",
                'buttons' => $buttonsConfig,
            ]);


            $dataTable->language([
                'buttons' => [
                    'create' => __('Create'),
                    'export' => __('Export'),
                    'print' => __('Print'),
                    'reset' => __('Reset'),
                    'reload' => __('Reload'),
                    'excel' => __('Excel'),
                    'csv' => __('CSV'),
                ]
            ]);

            
            return $dataTable;
    }

 

    protected function getColumns($customFields)
    {
        $columns = [
            Column::make('details-control')
                ->title('')
                ->orderable(false)
                ->searchable(false)
                ->addClass('details-control')
                ->defaultContent('')
                ->width(10),
            Column::make('No')
                ->title(__('No'))
                ->data('DT_RowIndex')
                ->name('DT_RowIndex')
                ->searchable(false)
                ->orderable(false),
            Column::make('user')->title(__('User')),
            Column::make('user_id')->title(__('test')),
            Column::make('status')->title(__('test')),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')
                ->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->addClass('text-end'),
        ];
    
        // Agrega columnas dinámicas para los campos personalizados
        foreach ($customFields as $fieldName => $fieldLabel) {
            $columns[] = Column::make($fieldName)
                ->title($fieldLabel)
                ->searchable(false)
                ->orderable(false)
                ->render($fieldName);
        }
    
        return $columns;
    }
    
    
    
    protected function filename(): string
    {
        return 'FormValues_' . date('YmdHis');
    }
}
