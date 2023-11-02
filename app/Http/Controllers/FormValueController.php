<?php

namespace App\Http\Controllers;

use App\DataTables\FormValuesDataTable;
use App\Exports\FormValuesExport;
use App\Facades\UtilityFacades;
use App\Models\Form;
use App\Models\FormValue;
use App\Models\UserForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;


class FormValueController extends Controller
{
    public function index(FormValuesDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-submitted-form')) {
            $forms = Form::all();
            return $dataTable->render('form_value.index', compact('forms'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    

    public function showSubmitedForms($form_id, FormValuesDataTable $dataTable)
    {
            $forms = Form::all();
            $forms_details = Form::find($form_id);
            return $dataTable->with('form_id', $form_id)->render('form_value.view_submited_form', compact('forms','forms_details'));
 }


    public function show($id)
    {
      /*   if (\Auth::user()->can('show-submitted-form')) { */
            try {
                $form_value = FormValue::find($id);
                $array = json_decode($form_value->json);
            } catch (\Throwable $th) {
                return redirect()->back()->with('errors', $th->getMessage());
            }
            return view('form_value.view', compact('form_value', 'array'));
       /*  } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        } */
    }

    public function edit($id)
    {
        $usr = \Auth::user();
        // $user_role = $usr->roles->first()->id;
        $form_value = FormValue::find($id);
   
        $array = json_decode($form_value->json);
        $form = $form_value->Form;
        $frm = Form::find($form_value->form_id);
        return view('form.fill', compact('form', 'form_value', 'array'));
            /* } else {
                return redirect()->back()->with('failed', __('Permission denied.'));
            } */
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-submitted-form')) {
            FormValue::find($id)->delete();
            return redirect()->back()->with('success',  __('Form successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function download_pdf($id)
    {
        $form_value = FormValue::where('id', $id)->first();
        if ($form_value) {
            $form_value->createPDF();
        } else {
            $form_value = FormValue::where('id', '=', $id)->first();
            if (!$form_value) {
                $id = Crypt::decryptString($id);
                $form_value = FormValue::find($id);
            }
            if ($form_value) {
                $form_value->createPDF();
            } else {
                return redirect()->route('home')->with('error', __('File is not exist.'));
            }
        }
    }

    public function export(Request $request)
    {
        $form = Form::find($request->form_id);
        return Excel::download(new FormValuesExport($request), $form->title . '.csv');
    }

    public function download_csv_2($id)
    {
        $form_value = FormValue::where('id', '=', $id)->first();
        if (!$form_value) {
            $id = Crypt::decryptString($id);
            $form_value = FormValue::find($id);
        }
        if ($form_value) {
            $form_value->createCSV2();
            return response()->download(storage_path('app/public/csv/Survey_' . $form_value->id . '.xlsx'))->deleteFileAfterSend(true);
        } else {
            return redirect()->route('home')->with('error', __('File is not exist.'));
        }
    }

    public function export_xlsx(Request $request)
    {
        $form = Form::find($request->form_id);
        return Excel::download(new FormValuesExport($request), $form->title . '.xlsx');
    }

    public function getGraphData(Request $request, $id)
    {
        $form = Form::find($id);
        $chartData = UtilityFacades::dataChart($id);
        return view('form_value.chart', compact('chartData', 'id', 'form'));
    }

    public function VideoStore(Request $request)
    {
        $file = $request->file('media');
        $extension = $file->getClientOriginalExtension();
        $filename = $file->store('form_video');
        $values = $filename;
        return response()->json(['success' => 'Video uploded successfully.', 'filename' => $values]);
    }


    public function SelfieDownload($id)
    {

        $data = FormValue::find($id);
        $json = $data->json;
        $jsonData = json_decode($json, true);
        $selfieValue = null;
        foreach ($jsonData[0] as $field) {
            if ($field['type'] === 'selfie') {
                $selfieValue = $field['value'];
                break;
            } elseif ($field['type'] === 'video') {
                $selfieValue = $field['value'];
                break;
            }
        }
        if ($selfieValue === null) {
            return redirect()->back()->with('errors', __('Image Value Not Found'));
        }
        $filePath = storage_path('app/' . $selfieValue);
        return response()->download($filePath);
    }
}
