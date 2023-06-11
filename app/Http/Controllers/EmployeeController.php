<?php

namespace App\Http\Controllers;

use App\Models\Emploayee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        return view("index");
    }
    public function store(Request $request)
    {
        $file = $request->file("avatar");
        $fileName = time() . "-" . $file->getClientOriginalExtension();
        $file->storeAs("public/images", $fileName);
        $empData = [
            "first_name" => $request->fname,
            "last_name" => $request->lname,
            "email" => $request->email,
            "phone" => $request->phone,
            "post" => $request->post,
            "avatar" => $fileName
        ];
        Emploayee::create($empData);
        return response()->json(["status" => 200]);
    }
    public function fetchAll()
    {
        $emp = Emploayee::all();
        $output = "";
        $i = 0;
        if ($emp->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Images</td>
                    <td>Name</td>
                    <td>E-mail</td>
                    <td>Post</td>
                    <td>Phone</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>';
            foreach ($emp as $emp) {
                $output .= '<tr>
                <td>' . ++$i . '</td>
                <td><img src="storage/images/' . $emp->avatar . '" alt="avatar" class="img-obc rounded-circle"/></td>
                <td>' . $emp->first_name . ' ' . $emp->last_name . '</td>
                <td>' . $emp->email . '</td>
                <td>' . $emp->phone . '</td>
                <td>' . $emp->post . '</td>
            <td>
                <a href="#" id="' . $emp->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square h4"></i>

                <a href="#" id="' . $emp->id . '" class="text-danger mx-1 deleteIcon" ><i class="bi-trash h4"></i>

            </td>
            </tr>';
            }
            $output .= '</tbody> </table>';
            echo $output;
        } else {
            echo "<h1 class='text-center text-secondary my-5'>No Record Found</h1>";
        }
    }
    public function editEmployee(Request $request)
    {
        $id = $request->id;
        $emp = Emploayee::find($id);
        return response()->json($emp);
    }
    public function update(Request $request)
    {
        $fileName = "";
        $emp = Emploayee::find($request->id);
        if ($request->hasFile('avatar')) {
            $file = $request->file("avatar");
            $fileName = time() . "-" . $file->getClientOriginalExtension();
            $file->storeAs("public/images", $fileName);
            if ($emp->avatar) {
                Storage::delete("public/images/" . $emp->avatar);
            }
        } else {
            $fileName = $request->avatar;
        }
        $empData = [
            "first_name" => $request->fname,
            "last_name" => $request->lname,
            "email" => $request->email,
            "phone" => $request->phone,
            "post" => $request->post,
            "avatar" => $fileName
        ];
        Emploayee::where('id', $request->id)->update($empData);
        return response()->json(["status" => 200]);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        $emp = Emploayee::find($id);
        if (Storage::delete('public/images/' . $emp->avatar)) {
            Emploayee::destroy($id);
            return response()->json(["status" => 200]);
        }
    }
}