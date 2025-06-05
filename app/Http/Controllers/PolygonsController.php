<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonsModel;

class PolygonsController extends Controller
{
    public function __construct()
    {
        $this->polygons = new PolygonsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation request
        $request->validate(
            [
                'name' => 'required|unique:polygons,name',
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',

                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Location is required',
            ]
        );

        // Create image directory if not exists - PHP Create Directory
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get image file - PHP Get Image file & Move
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        // Get data from bootstrap form
        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->user()->id,
        ];

        //dd($data); //ini cuma ngecek dlm bentuk teks data geojson

        // Create data to database
        if (!$this->polygons->create($data)) {
            return redirect()->route('map')->with('error', 'Failed to add polygon');
        }

        // Redirect to map
        return redirect()->route('map')->with('success', 'Polygon has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Polygon',
            'id' => $id,
        ];

        return view('edit-polygon', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validation request
        $request->validate(
            [
                'name' => 'required|unique:polygons,name,' . $id,
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:4048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',

                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Location is required',
            ]
        );

        // Create image directory if not exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get old image file name
        $old_image = $this->polygons->find($id)->image;

        //dd($old_image); // Debug n Die line utk check data

        // Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            // Delete old image file if exists
            if ($old_image != null) {
                if (file_exists('storage/images/' . $old_image)) {
                    unlink('storage/images/' . $old_image);
                }
            }
        } else {
            $name_image = $old_image;
        }

        // Get data from bootstrap form
        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        //dd($data); // ======= ini cuma ngecek dlm bentuk teks data geojson

        // Update data to database
        if (!$this->polygons->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'Failed to update polygon');
        }

        // Redirect to map
        return redirect()->route('map')->with('success', 'Polygon has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->polygons->find($id)->image;

        if (!$this->polygons->destroy($id)) {
            return redirect()->route('map')->with('error', 'Failed to delete polygon');
        }

        // Delete image file if exists
        if ($imagefile != null) {
            if (file_exists('storage/images/' . $imagefile)) {
                unlink('storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Polygon has been deleted');
    }
}
