<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NoteController extends BaseController
{

    public function index()
    {
        $user = Auth::user();
        $products  = Note::where('user_id',  $user->id)->get();
        return response()->json([
            "success" => true,
            "message" => "Note List",
            "note" => $products
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $product = new Note();
        $product->title = $request->title;
        $product->body = $request->body;
        $product->user_id = $user->id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('Note\\' . $user->email . '\\'), $image);
            $product->image = '/Note/' . $user->email . '/' . $image;
        } else {
            $product->image = 'empty';
        }
        $product->save();
        return response()->json([
            "success" => true,
            "message" => "Note Added successfully.",
            "data" => $product
        ]);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 403);
        }
        return response()->json([
            "success" => true,
            "message" => "show Note",
            "data" => Note::find($request->id)
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $product = Note::find($request->id);
        $product->title = $request->title;
        $product->body = $request->body;
        if ($request->deleteImage == 1) {
            if ($product->image != 'empty') {
                unlink(public_path() . $product->image);
                $product->image = 'empty';
            }
        }
        if ($request->hasFile('image')) {
            if ($product->image != 'empty') {
                unlink(public_path() . $product->image);
                $product->image = 'empty';
            }
            $image = $request->file('image');
            $image = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('Note\\' . $user->email . '\\'), $image);
            $product->image = '/Note/' . $user->email . '/' . $image;
        } else {
            $product->image = 'empty';
        }
        $product->update();
        return response()->json([
            "success" => true,
            "message" => "Note Updated successfully.",
            "data" => $product
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 403);
        }

        $product = Note::find($request->id);
        if ($product) {
            DB::beginTransaction();
            if ($product->image != 'empty') {
                unlink(public_path() . $product->image);
            }
            $product->delete();
            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "Note deleted successfully.",

            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Note not found.",
            ], 404);
        }
    }
}
