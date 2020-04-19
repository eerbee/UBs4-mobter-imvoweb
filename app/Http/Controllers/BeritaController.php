<?php

namespace App\Http\Controllers;

use App\Berita;
use Auth;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $data = Berita::when($request->search, function($query) use($request){
            $query->where('berita_judul', 'LIKE', '%'.$request->search.'%');})
            ->orderBy('berita_id','asc')
            ->paginate(10);

            if (Auth::user())
        {
            return view('berita.index',compact('data'))
                ->with('i', (request()->input('page', 1) - 1) * 10);
        }
        else
        {
            return response()->json($data);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $form_data = array(
            'berita_judul' => $request->berita_judul,
            'berita_link' => $request->berita_link
            // 'berita_gambar' => $request->berita_gambar

        );

        Berita::create($form_data);

        if (Auth::user())
        {
            return redirect('/berita')->with('i', (request()->input('page', 1) - 1) * 10);
        }
        else
        {
            return response()->json('successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Berita::findOrFail($id);
        return response()->json($data);
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
        $form_data = array(
            'berita_judul' => $request->berita_judul,
            'berita_link' => $request->berita_link
        );
  
        Berita::where('berita_id',$id)->update($form_data);

        if (Auth::user())
        {
            return redirect('/berita')->with('i', (request()->input('page', 1) - 1) * 10);
        }
        else
        {
            return response()->json('successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();
        if (Auth::user())
        {
            return redirect('/berita')->with('i', (request()->input('page', 1) - 1) * 10);
        }
        else
        {
            return response()->json('successfully');
        }
    }
}