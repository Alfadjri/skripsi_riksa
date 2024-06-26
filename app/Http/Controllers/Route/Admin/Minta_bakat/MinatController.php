<?php

namespace App\Http\Controllers\Route\Admin\Minta_bakat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Default\Search as R_Search;
use App\Http\Requests\Default\Edit_description as R_E_Description;
use App\Http\Requests\Soal\Jawaban\Create_Jawaban as R_C_Soal;
use App\Http\Requests\Soal\Jawaban\detail_jawaban as R_D_Soal;
use App\Http\Requests\Soal\Jawaban\Edit_Jawaban as R_E_Soal;


// component
use App\Http\Controllers\Component\Minat_bakat\minat_controller as C_Minat;




class MinatController extends C_Minat
{
    private $limit_page = 10;
    private $search_default = null;
    public function user_test(R_Search $request)
    {
        $value = $request->validated();
        $value['search'] = (isset($value['search'])) ? $value['search'] : $this->search_default;
        $value['limit_per_page'] = (isset($value['limit_per_page'])) ? $value['limit_per_page'] : $this->limit_page;
        // controller items
        $users = $this->search_user($value['search'], $value['limit_per_page']);
        $questions = $this->search_question($this->search_default, $this->limit_page);
        return view('Admin.Minat_bakat.dashboard', ['questions' => $questions, 'users' => $users], );
    }
    public function setting_page(R_Search $request)
    {
        $value = $request->validated();
        $value['search'] = (isset($value['search'])) ? $value['search'] : $this->search_default;
        $value['limit_per_page'] = (isset($value['limit_per_page'])) ? $value['limit_per_page'] : 10;
        //  controller items
        $descrition = $this->get_description();
        $users = $this->search_user($this->search_default, $this->limit_page);
        $qustions = $this->search_question($value['search'], $value['limit_per_page']);
        $summarys = $this->get_summary();
        // end controller items
        return view('Admin.Minat_bakat.setting_page', ['description' => $descrition, 'questions' => $qustions, 'summarys' => $summarys, 'users' => $users]);
    }

    public function page_create_soal()
    {
        $summarys = $this->get_summary();
        $qustions = $this->search_question($this->search_default, $this->limit_page);
        $users = $this->search_user($this->search_default, $this->limit_page);
        return view('Admin.Minat_bakat.create_soal', ['summarys' => $summarys, 'questions' => $qustions, 'users' => $users]);
    }
    function edit_description(R_E_Description $request)
    {
        $value = $request->validated();
        $this->set_description($value['id'], $value['desc']);
        return redirect()->route('admin.minat.setting.dashboard');
    }

    public function create_soal(R_C_Soal $request)
    {
        $values = $request->validated();
        foreach ($values as $key => $value) {
            foreach ($value['pertanyaan'] as $key => $summary) {
                $this->create_jawaban($summary, $value['id_summar'][$key]);
            }
        }
        return redirect()->route('admin.minat.setting.dashboard', ['limit_per_page' => 10]);
    }

    public function delete_soal(R_D_Soal $request)
    {
        $value = $request->validated();
        $status_delete = $this->delete_jawaban($value['id']);
        return redirect()->route('admin.minat.setting.dashboard', ['limit_per_page' => 10]);
    }


    public function edit_soal(R_E_Soal $request)
    {
        $value = $request->validated();
        $status_edit = $this->edit_jawaban($value['id_soal'], $value['id_summary'], $value['pertanyaan']);
        return redirect()->route('admin.minat.setting.dashboard', ['limit_per_page' => 10]);
    }
}
