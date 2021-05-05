<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use App\Models\InspectionQuestionAnswers;
use App\Models\InspectionQuestions;
use Illuminate\Http\Request;

class InspectionQuestionController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = InspectionQuestions::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = InspectionQuestions::getAllResult($request);
        } else {
            $res = InspectionQuestions::getPaginatedResult($request);
        }

        return $res;
    }
    
    public function updateOrCreate(Request $request)
    {
        if ($request->id) {
            InspectionQuestions::where('id', $request->id)->update([
                'question' => $request->question,
                'type_answer' => $request->type_answer,
                'type_question' => $request->type_question,
            ]);

            if (isset($request->answer)) {
                InspectionQuestionAnswers::where('inspection_question_id', $request->id)->delete();
                foreach ($request->answer as $answer) {
                    InspectionQuestionAnswers::create([
                        'inspection_question_id' => $request->id,
                        'content' => $answer
                    ]);
                }
            }

            $message = 'Sukses Memperbaharui Item';
        } else {
            $inspection_question = InspectionQuestions::create([
                'question' => $request->question,
                'type_answer' => $request->type_answer,
                'type_question' => $request->type_question,
            ]);

            if (isset($request->answer)) {
                foreach ($request->answer as $answer) {
                    InspectionQuestionAnswers::create([
                        'inspection_question_id' => $inspection_question->id,
                        'content' => $answer
                    ]);
                }
            }

            $message = 'Sukses Menambah Item';
        }
        return response()->json([
            'message' => $message,
            'status' => true
        ]);
    }

    public function delete($id)
    {
        InspectionQuestions::destroy($id);
        return response()->json([
            'message' => 'Sukses Menghapus Item',
            'status' => true
        ]);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'inspection_questions.id'
        ];

        $dataOrder = [];

        $limit = $request->length;

        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']];
            $nestedOrder['dir'] = $row['dir'];

            $dataOrder[] = $nestedOrder;
        }

        $order = $dataOrder;

        $dir = $request->order[0]['dir'];

        $search = $request->search['value'];

        $filter = $request->only(['sDate', 'eDate']);

        $res = InspectionQuestions::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['question'] = $row['question'];
                $nestedData['type_answer'] = $row['type_answer'];
                $nestedData['type_question'] = $row['type_question'];
                $nestedData['action'] = '';
                $nestedData['action'] .= '<span class="dropdown">';
                $nestedData['action'] .= '    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>';
                $nestedData['action'] .= '    <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">';
                if ($user->can('inspections.edit')) {
                    $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="edit-data" data-id="'.$row['id'].'">';
                    $nestedData['action'] .= '        Edit';
                    $nestedData['action'] .= '      </a>';
                }
                
                if ($user->can('inspections.delete')) {
                    $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="delete-data" data-id="'.$row['id'].'">';
                    $nestedData['action'] .= '        Delete';
                    $nestedData['action'] .= '      </a>';
                }
                $nestedData['action'] .= '    </div>';
                $nestedData['action'] .= '</span>';
                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw'  => intval($request->draw),
            'recordsTotal'  => intval($res['totalData']),
            'recordsFiltered' => intval($res['totalFiltered']),
            'data'  => $data,
            'order' => $order
        ];

        return json_encode($json_data);
    }
}
