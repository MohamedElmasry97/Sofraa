<?php

namespace App\Http\Controllers;

use App\Models\Resturant;
use Illuminate\Http\Request;
use Response;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $resturants = Resturant::where(function ($q) use ($request) {
            if ($request->name) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'LIKE', '%' . $request->name . '%');
                });
            }

            if ($request->city_id) {
                $q->whereHas('neighborhood', function ($q2) use ($request) {
                    // search in resturant neighborhood "neighborhood" Model
                    $q2->whereCityId($request->city_id);
                });
            }

            if ($request->status) {
                $q->where('status', $request->status);
            }
        })->with('neighborhood.city')->latest()->paginate(20);
        return view('admin.restaurants.index', compact('resturants'));
    }

    public function create(Resturant $model)
    {
        return view('admin.restaurants.create', compact('model'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'neighborhood_id' => 'required'
        ]);
        $resturant = Resturant::create($request->all());
        if ($request->hasFile('logo')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/resturants/'; // upload path
            $logo = $request->file('logo');
            $extension = $logo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $logo->move($destinationPath, $name); // uploading file to given path
            $resturant->logo = 'uploads/resturants/' . $name;
            $resturant->save();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = public_path();
                $destinationPath = $path . '/uploads/resturants/'; // upload path
                $extension = $photo->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $photo->move($destinationPath, $name); // uploading file to given path
                $resturant->photos()->create(['url' => 'uploads/resturants/' . $name]);
            }
        }
        if ($request->has('neighborhoods_list')) {
            $resturant->delivery_neighborhoods()->attach($request->neighborhoods_list);
        }

        if ($request->has('categories_list')) {
            $resturant->categories()->sync($request->categories_list);
        }

        if ($request->has('weekdays')) {
            foreach ($request->weekdays as $key => $value) {
                $resturant->working_times()->create([
                    'weekday' => $request->weekdays[$key],
                    'opening' => str_replace(' ', '', $request->from[$key]),
                    'closing' => str_replace(' ', '', $request->to[$key])
                ]);
            }
        }

        flash()->success('تم إضافة المطعم بنجاح');
        return redirect('admin/restaurant');
    }

    public function edit($model)
    {
        return view('admin.resturants.edit', compact('model'));
    }

    public function update(Request $request, $resturant)
    {
        $this->validate($request, [
            'name' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'neighborhood_id' => 'required'
        ]);

        $resturant->update($request->all());

        if ($request->hasFile('logo')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/resturants/'; // upload path
            $logo = $request->file('logo');
            $extension = $logo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $logo->move($destinationPath, $name); // uploading file to given path
            $resturant->update(['logo' => 'uploads/resturants/' . $name]);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = public_path();
                $destinationPath = $path . '/uploads/resturants/'; // upload path
                $extension = $photo->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $photo->move($destinationPath, $name); // uploading file to given path
                $resturant->photos()->create(['url' => 'uploads/resturants/' . $name]);
            }
        }

        if ($request->has('neighborhoods_list')) {
            $resturant->delivery_neighborhoods()->attach($request->neighborhoods_list);
        }

        if ($request->has('categories_list')) {
            $resturant->categories()->sync($request->categories_list);
        }
        if ($request->has('weekdays')) {
            $resturant->working_times()->delete();
            foreach ($request->weekdays as $key => $value) {
                $resturant->working_times()->create([
                    'weekday' => $request->weekdays[$key],
                    'opening' => str_replace(' ', '', $request->from[$key]),
                    'closing' => str_replace(' ', '', $request->to[$key])
                ]);
            }
        }

        flash()->success('تم تعديل بيانات المطعم بنجاح.');
        return redirect('admin/restaurant/' . $resturant->id . '/edit');
    }

    public function destroy(Resturant $resturant)
    {
        if (count($resturant->orders) > 0) {
            $data = [
                'status' => 0,
                'msg' => 'لا يمكن حذف المطعم ، لان به طلبات مسجلة',
                'id' => $resturant->id
            ];
            return Response::json($data, 200);
        }

        $resturant->delete();
        $data = [
            'status' => 1,
            'msg' => 'تم الحذف بنجاح',
            'id' => $resturant->id
        ];
        return Response::json($data, 200);
    }

    public function activate($id)
    {
        $resturant = Resturant::findOrFail($id);
        $resturant->activated = 1;
        $resturant->save();
        flash()->success('تم التفعيل');
        return back();
    }

    public function deActivate($id)
    {
        $resturant = Resturant::findOrFail($id);
        $resturant->activated = 0;
        $resturant->save();
        flash()->success('تم الإيقاف');
        return back();
    }
}
