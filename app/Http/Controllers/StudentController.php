<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Mark;


class StudentController extends Controller
{

  

  public function getStudentByStandard(Request $request){

      $standard = $request->query('standard');

      $perPage = $request->input('per_page', 10);

    
      $query = Student::query();

      if ($standard !== null) {
          $query->where('standard', $standard);
      }

      $students = $query->with('mark')->paginate($perPage);

      // echo "<pre>";
      // print_r($students);
      

      return response()->json($students);


  }

  public function fetchResults() {

  
    $students = Student::with('mark')->get();

    // echo "<pre>";
    // print_r($students);
    // die();

    $results = [];

    foreach ($students as $student) {

        $totalMarks = $student->mark->sum('marks');

        // echo $totalMarks;
        // die;
        $percentage = ($totalMarks / ($student->marks->count() * 100)) * 100;

        //echo $percentage;
        //die;

        $status = 'Fail';

        if ($percentage >= 35 && $percentage < 60) {
            $status = 'Second Class';
        }elseif ($percentage >= 60 && $percentage < 85) {
            $status = 'First Class';
        } elseif ($percentage >= 85) {
            $status = 'Distinction';
        }

        $results[] = [
            'student_name' => $student->student_name,
            'percentage' => $percentage,
            'status' => $status,
        ];
    }

   // print($results);die;

    return response()->json($results);
  }
 
}

