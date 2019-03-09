<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sample;
use Validator;
use Session;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $samples = null;

        if (empty($request->keyword)) {
            $samples = Sample::paginate(15);
        } else {
            $samples = Sample::orWhere('name', 'LIKE', '%'.$request->keyword.'%')->paginate(15);
        }

        return view('sample.index')->with('items', $samples);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sample.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), Sample::$validation['store']);

        if ($validator->fails())
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sample = new Sample();
        $sample->name = $request->name;
        $sample->description = $request->description;
        $sample->save();

        Session::flash('success', 'Successfully store data');

        return redirect()->route('samples.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sample = Sample::find($id);

        if (empty($sample)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('sample.show')->with('item', $sample);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sample = Sample::find($id);

        if (empty($sample)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('sample.edit')->with('item', $sample);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), Sample::$validation['update']);

        if ($validator->fails())
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sample = Sample::find($id);

        if (empty($sample)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        $sample->name = $request->name;
        $sample->description = $request->description;
        $sample->save();

        Session::flash('success', 'Successfully update data');

        return redirect()->route('samples.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sample = Sample::find($id);

        if (empty($sample)) {
            Session::flash('failed', 'Failed delete data');
            return redirect()->route('samples.index');
        }

        $sample->delete();

        Session::flash('success', 'Successfully delete data');
        return redirect()->route('samples.index');
    }

    public function search(Request $request)
    {
        if (empty($request->keyword)) {
            return redirect()->route('samples.index');
        }

        $samples = Sample::where('name', 'LIKE', '%'.$request->keyword.'%')->paginate(15);
        return view('sample.index')->with('items', $samples);
    }
}
